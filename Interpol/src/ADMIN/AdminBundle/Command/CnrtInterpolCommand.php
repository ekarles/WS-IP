<?php

namespace ADMIN\AdminBundle\Command;

ini_set('memory_limit','2048M');

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ADMIN\AdminBundle\Entity\CnrtPersona;
use ADMIN\AdminBundle\Entity\CnrtPersonaRepository;
use ADMIN\AdminBundle\Entity\Proceso;
use GESTION\GestionBundle\Repository\InterpolRepository;
use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;

include_once ('/apache/includes/ambiente.php');

class CnrtInterpolCommand extends ContainerAwareCommand
{
    
    private $resultado;
    private $inicio;
    private $fin;
    private $proceso;
    private $totalCNRT=0;
    private $totalErrorIDGE=0;
    private $totalErrorRenaper=0;
    private $totalErrorInti=0;
    private $totalPositivosInti=0;
    private $totalOk=0;
    
    protected function configure()
    {
        $this
            ->setName('admin:admin:cnrt_interpol')
            ->setDescription('Consulta la API de CNRT para realizar automáticamente consultas de NOMINALSEXACT')
            ->addArgument('funcion', InputArgument::OPTIONAL, 'Usar funcion?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('max_execution_time', 600);
        $this->inicio=microtime(true);
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $this->proceso = new Proceso();
        $this->proceso->setFechaIni(new \Datetime());
        
        $funcion = $input->getArgument('funcion');
        
        if(empty($funcion)){
            $this->proceso->setNombre('admin:admin:cnrt_interpol');
        }else{
            $this->proceso->setNombre('admin:admin:cnrt_'.$funcion);
        }
        
        $em->persist($this->proceso);
        $em->flush();
        
        $this->resultado = "";

        $loteCnrt = $this->procesarCnrt($funcion);
        
    }
    
    private function getResponse($lote){
        
        $delay = 0;
        $respuestas = $this->procesar($lote);
        $promise = Awaitable\resolve($respuestas);
        
        yield $promise->delay($delay);
        
    }
    
    private function procesarCnrt($funcion){
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        switch($funcion){
            case 'reprocesar':
           
                $this->ejecutarReproceso();
                
                break;
            default:
                                
                $this->ejecutarProceso();

           
        }  //switch($funcion){
    }
    
    
    function ejecutarProceso(){
        $em = $this->getContainer()->get('doctrine')->getManager();
        $ultimoId = $em->getRepository('ADMINAdminBundle:Parametro')->findBy(["nombre"=>"LAST_ID_CNRT"]);
        
        if(isset($ultimoId[0])){
            
            $lastId = $ultimoId[0];
            $ambienteIp =  $this->getContainer()->getParameter('ambienteIp');
            $urlWSPFA = $this->getContainer()->getParameter('urlWSPFA');
            $usuario = $this->getContainer()->getParameter('usuarioCnrtInti');
            $pass = $this->getContainer()->getParameter('passCnrtInti');
            $token = $this->getContainer()->getParameter('tokenCnrtInti');
            $curlCNRT = curl_init();
            
            curl_setopt_array($curlCNRT, array(
                CURLOPT_URL => 'http://'.$urlWSPFA.'/PFA_CNRT_CLIENTE_WS/personas',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 300,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                                "sistema":"CNRT",
                                "origen":"PFA",
                                "token":"'.$token.'",
                                "usuario":"'.$usuario.'",
                                "pass":"'.$pass.'",
                                "usuarioApellido":"ADMIN",
                                "usuarioNombre":"ADMIN",
                                "usuarioTipoDoc":"DNI",
                                "usuarioDoc":"0",
                                "usuarioDepen":"INTERPOL",
                                "usuarioIp":"'.$ambienteIp.'",
                                "usuarioJerarquia":"ADMIN",
                                "usuarioDepenId":"0",
                                "latitud":"'.LATITUD_DEFAULT.'",
                                "longitud":"'.LONGITUD_DEFAULT.'",
                                "lastId":"'.$lastId->getValornum().'"
                                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
            
            
            $response = curl_exec($curlCNRT);
            $status = curl_getinfo ( $curlCNRT, CURLINFO_HTTP_CODE);
            
            echo "Status: [" . $status. "]\n"."<br>".PHP_EOL;
            
            $err = curl_error($curlCNRT);
            
            if($err!=''){
                print_r( $err );
                echo "\n"."<br>".PHP_EOL;
            }
            
            curl_close($curlCNRT);
            
            if ($err || $status!=200) {
                $str = date('Y-m-d H:i:s')." Error al intentar consultar a CNRT: Status=".$status." - Desc. Error=".print_r($err,true)." - Resp=.".print_r($response,true)."\n<br>";
                $this->resultado.=$str;
                echo $str.PHP_EOL;
                
                $this->proceso->setResultado($this->resultado);
                $this->proceso->setFechaFin(new \Datetime());
                $em->flush();
                
                exit();
            }else{
                
                $resp = json_decode($response);
                
                $loteCnrt = isset($resp->respuesta)?$resp->respuesta:[];
                
                if(isset(end($loteCnrt)->ID)){
                    
                    $nvoLastId = end($loteCnrt)->ID;
                    $lastId->setValornum($nvoLastId);
                    
                    $em->flush();
                }
                
                $this->totalCNRT = sizeof($loteCnrt);
                
                if(sizeof($loteCnrt)>=20){
                    $cantRoutines = 20;
                }else{
                    $cantRoutines = 10;
                }
                
                if(sizeof($loteCnrt)>=$cantRoutines){
                    $i=0;
                    
                    $sublotes=array();
                    $routines=array();
                    
                    foreach($loteCnrt as $row){
                        $sublotes[($i%$cantRoutines)][]=$row;
                        $i++;
                    }
                    
                    for($i=0;$i<$cantRoutines;$i++){
                        $routine[$i] = new Coroutine($this->getResponse($sublotes[$i]));
                        $respuestas[] = $routine[$i]->wait();
                    }
                    
                    Loop\Run();
                    
                }else{
                    $respuestas = $this->getResponse($loteCnrt);
                }
                
                //Por ultimo el tiempo al culminar
                $this->fin = microtime(true);
                //La resta del final - inicio nos dará el tiempo de ejecución
                $duration= $this->fin - $this->inicio;
                
                $hours = (int)($duration/60/60);
                $minutes = (int)($duration/60)-$hours*60;
                $seconds = (int)$duration-$hours*60*60-$minutes*60;
                
                $str = date('Y-m-d H:i:s')." Proceso finalizado en ".$hours. " horas ".$minutes. " minutos ".$seconds." segundos.\n<br><br>".PHP_EOL;
                
                $str.= "<b>RESUMEN PROCESO:</b><br>".PHP_EOL;
                
                $str.= "Se procesaron en total <b>".$this->totalCNRT."</b> registros CNRT.<br>".PHP_EOL;
                $str.= "Total de errores o inexistentes IDGE: <b>".$this->totalErrorIDGE."</b>.<br>".PHP_EOL;
                $str.= "Total de errores o inexistentes RENAPER: <b>".$this->totalErrorRenaper."</b>.<br>".PHP_EOL;
                $str.= "Total de errores INTI: <b>".$this->totalErrorInti."</b>.<br>".PHP_EOL;
                $str.= "Se lograron consultar correctamente a Interpol: <b>".$this->totalOk."</b>.<br>".PHP_EOL;
                $str.= "Total de positivos de Interpol: <b>".$this->totalPositivosInti."</b>.<br>".PHP_EOL;
                
                echo $str."<br>".PHP_EOL;
                
                $this->resultado.=$str;
                
                $this->proceso->setResultado($this->resultado);
                
                $this->proceso->setFechaFin(new \Datetime());
                
                $em->flush();
                
            }
            
        }else{
            
            $str = date('Y-m-d H:i:s')." Error al intentar obtener el parámetro LastId\n<br>";
            $this->resultado.=$str;
            echo $str.PHP_EOL;
        } //if(isset($ultimoId[0])){
        
    }
        
    
    function procesar($loteCnrt){
        $respuestas = "";
        $em = $this->getContainer()->get('doctrine')->getManager();
        $erroresRenaper=array();
        $erroresInterpol=array();
        
        
        $i=0;
        foreach($loteCnrt as $row){
            $errorRenaper = false;
            $errorIdge = false;
            
            $cnrtPersona[$i] = new CnrtPersona();
            $cnrtPersona[$i]->setIdPasajero($row->ID);
            $cnrtPersona[$i]->setEstado(0);
            $cnrtPersona[$i]->setFechaConsulta(new \Datetime());
            
            $cnrtPersona[$i]->setNumeroDocumento($row->NUMERO_DOCUMENTO);
            $cnrtPersona[$i]->setApellido($row->APELLIDO);
            $cnrtPersona[$i]->setNombre($row->NOMBRE);
            $cnrtPersona[$i]->setSexo($row->SEXO);
            $cnrtPersona[$i]->setFechaInicio(trim($row->FECHA_INICIO));
            $cnrtPersona[$i]->setOrigen(trim($row->ORIGEN));
            $cnrtPersona[$i]->setPciaOrigen(trim($row->PROVINCIA_ORIGEN));
            $cnrtPersona[$i]->setDestino(trim($row->DESTINO));
            $cnrtPersona[$i]->setPciaDestino(trim($row->PROVINCIA_DESTINO));
            $cnrtPersona[$i]->setNroButaca(trim($row->NRO_BUTACA));
            $cnrtPersona[$i]->setDominio(trim($row->DOMINIO));
            $cnrtPersona[$i]->setNroEmpresa(trim($row->NRO_EMPRESA));
            $cnrtPersona[$i]->setDescEmpresa(trim($row->DESC_EMPRESA));
            $cnrtPersona[$i]->setObservaciones(trim($row->OBSERVACIONES));
            
            $em->persist($cnrtPersona[$i]);
            
            $respIdge = $this->consultarIdge($row->NUMERO_DOCUMENTO);
            
            if(isset($respIdge->respuesta[0])){
                $respIdge = $respIdge->respuesta[0];
            }
            
            if(isset($respIdge->error)&&$respIdge->error){
                
                $cnrtPersona[$i]->setEstado(5);
                $resp= date('Y-m-d H:i:s')." - Error al intentar consultar a IDGE - ID:" .$row->ID. ", NRO_DOCUMENTO:" .$row->NUMERO_DOCUMENTO. ", " . $respIdge->message . " \n<br>";
                $respuestas.= $resp;
                $this->resultado.=$resp;
                echo $resp.PHP_EOL;
                $this->totalErrorIDGE++;
                $errorIdge = true;
                
                $row->ID_CNRT_PERSONA=$cnrtPersona[$i]->getId();
                
                $erroresRenaper[] = $row;
            
            }elseif(isset($respIdge->fecha_nacimiento) && $respIdge->fecha_nacimiento!='' && strlen(trim($respIdge->fecha_nacimiento))==10){
                
                $cnrtPersona[$i]->setFechaNacimiento(trim($respIdge->fecha_nacimiento));
                
                $row->FECHANACIMIENTO = trim($respIdge->fecha_nacimiento);
                
                $nominals = $this->consultarInterpol($row);
                
                if($nominals!==false){
                    
                    $cnrtPersona[$i]->setEstado(1);
                    $this->totalOk++;
                    
                    if(is_object($nominals)){
                        //En este caso la persona tiene alguna notificación de Interpol
                        $cnrtPersona[$i]->setResultado(1);
                        $cnrtPersona[$i]->setRespuestaDetails(json_encode($nominals));
                        
                        $resp= date('Y-m-d H:i:s')." ID=".$row->ID." FechaNac=".$respIdge->fecha_nacimiento." Resp.INTI=POSITIVO!!! \n<br>";
                        $respuestas.= $resp;
                        $this->resultado.=$resp;
                        $this->totalPositivosInti++;
                        
                        echo $resp."<br>".PHP_EOL;
                    }elseif(count($nominals)==0){
                        //En este caso la persona se encuentra sin novedad
                        $cnrtPersona[$i]->setResultado(0);
                        //Blanqueo todos los datos innecesarios
                        $cnrtPersona[$i]->setOrigen(null);
                        $cnrtPersona[$i]->setPciaOrigen(null);
                        $cnrtPersona[$i]->setDestino(null);
                        $cnrtPersona[$i]->setPciaDestino(null);
                        $cnrtPersona[$i]->setNroButaca(null);
                        $cnrtPersona[$i]->setDominio(null);
                        $cnrtPersona[$i]->setNroEmpresa(null);
                        $cnrtPersona[$i]->setDescEmpresa(null);
                        $cnrtPersona[$i]->setObservaciones(null);
                        
                        
                        $resp= date('Y-m-d H:i:s')." ID=".$row->ID." FechaNac=".$respIdge->fecha_nacimiento." Resp.INTI=Sin Novedad \n<br>";
                        $respuestas.= $resp;
                        
                        echo $resp.PHP_EOL;
                    }
                }else{
                    $cnrtPersona[$i]->setEstado(4);
                    
                    $row->ID_CNRT_PERSONA=$cnrtPersona[$i]->getId();
                    
                    $erroresInterpol[] = $row;
                    
                    $resp= date('Y-m-d H:i:s')." - Error al intentar consultar a Interpol - ID_CNRT=".$row->ID.", ID_PASAJERO:" .$row->ID_CNRT_PERSONA. ", NRO_DOCUMENTO:" .$row->NUMERO_DOCUMENTO. ", SEXO: " . $row->SEXO." \n<br>";
                    $respuestas.= $resp;
                    $this->resultado.=$resp;
                    $this->totalErrorInti++;
                    
                    echo $resp.PHP_EOL;
                }
                
                
            }elseif(!$errorIdge){
                $cnrtPersona[$i]->setEstado(6);
                
                $row->ID_CNRT_PERSONA=$cnrtPersona[$i]->getId();
                
                $erroresRenaper[] = $row;
                
                $resp= date('Y-m-d H:i:s')." - Inexistente IDGE - ID_CNRT=".$row->ID.", ID_PASAJERO:" .$row->ID_CNRT_PERSONA. ", NRO_DOCUMENTO:" .$row->NUMERO_DOCUMENTO. ", SEXO: " . $row->SEXO . "\n<br>";
                $respuestas.= $resp;
                $this->resultado.=$resp;
                echo $resp.PHP_EOL;
                $this->totalErrorIDGE++;
                
            }
            
            $i++;
        }
        
        $em->flush();
        
        //Para posibles casos de errores temporales de conexión hago un 2do y último intento de obtener la Fec. Nac.
        $j=0;
        foreach($erroresRenaper as $row){
            $cnrtPersona[$j] = $em->getRepository('ADMINAdminBundle:CnrtPersona')->find($row->ID_CNRT_PERSONA);
            
            if($cnrtPersona[$j]){
                
                $respRenaper = $this->consultarRenaper($row->NUMERO_DOCUMENTO, $row->SEXO);
                
                if(isset($respRenaper->respuesta[0])){
                    $respRenaper = $respRenaper->respuesta[0];
                }
                
                if(isset($respRenaper->error)&&$respRenaper->error){
                    
                    $resp= date('Y-m-d H:i:s')." - Error al intentar consultar a RENAPER S/FOTO - ID_CNRT=".$row->ID.", ID_PASAJERO:" .$row->ID_CNRT_PERSONA. ", NRO_DOCUMENTO:" .$row->NUMERO_DOCUMENTO. ", " . $respRenaper->message . " \n<br>";
                    $respuestas.= $resp;
                    $this->resultado.=$resp;
                    echo $resp.PHP_EOL;
                    
                    //   HUBO UN ERROR EN RENAPER SIN FOTO, INTENTO EN EL SERVICIO CON FOTO
                    $respRenaper = $this->consultarRenaper($row->NUMERO_DOCUMENTO, $row->SEXO, 'S');
                    
                    if(isset($respRenaper->respuesta[0])){
                        $respRenaper = $respRenaper->respuesta[0];
                    }
                    
                    if(isset($respRenaper->error)&&$respRenaper->error){
                        
                        $bNoModificarEstado = true;
                        $cnrtPersona[$j]->setEstado(3);
                        $resp= date('Y-m-d H:i:s')." - Error al intentar consultar a RENAPER C/FOTO- ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", " . $respRenaper->message . " \n<br>";
                        $respuestas.= $resp;
                        $this->resultado.=$resp;
                        echo $resp.PHP_EOL;
                        $this->totalErrorRenaper++;
                        $errorRenaper = true;
                        
                    }
                    
                    
                }
                
                if(isset($respuestaRenaper->FECHANACIMIENTO) && $respuestaRenaper->FECHANACIMIENTO!=''){
                    
                    $cnrtPersona[$j]->setFechaNacimiento($respuestaRenaper->FECHANACIMIENTO);
                    
                    
                    // Si logré obtener la fecha nac. en este segundo intento sumo el registro al array de erroresInterpol
                    $row->FECHANACIMIENTO = $respuestaRenaper->FECHANACIMIENTO;
                    $erroresInterpol[] = $row;
                    
                }elseif(!$errorRenaper){
                    $cnrtPersona[$j]->setEstado(2);
                    
                    
                    $resp= date('Y-m-d H:i:s')." - Inexistente RENAPER - ID_CNRT=".$row->ID.", ID_PASAJERO:" .$row->ID_CNRT_PERSONA. ", NRO_DOCUMENTO:" .$row->NUMERO_DOCUMENTO." \n<br>";
                    $respuestas.= $resp;
                    $this->resultado.=$resp;
                    echo $resp.PHP_EOL;
                    $this->totalErrorRenaper++;
                }
                
            }else{
                $resp= date('Y-m-d H:i:s')." ID=".$row->ID." Error al intentar obtener la persona en CNRT_PERSONA \n<br>";
                $respuestas.= $resp;
                $this->resultado.=$resp;
                echo $resp.PHP_EOL;
            }
        }
        
        //Para posibles casos de errores temporales de conexión hago un 2do y último intento de consultar a interpol
        $k=0;
        foreach($erroresInterpol as $row){
            $cnrtPersona[$k] = $em->getRepository('ADMINAdminBundle:CnrtPersona')->find($row->ID_CNRT_PERSONA);
            $nominals = $this->consultarInterpol($row);
            
            if($nominals!==false){
                $cnrtPersona[$k]->setEstado(1);
                $this->totalOk++;
                if(is_object($nominals)){
                    //En este caso la persona tiene alguna notificación de Interpol
                    $cnrtPersona[$k]->setResultado(1);
                    $cnrtPersona[$k]->setRespuestaDetails(json_encode($nominals));
                    
                    $resp= date('Y-m-d H:i:s')." ID=".$row->ID." FechaNac=".$row->FECHANACIMIENTO." Resp.INTI=POSITIVO!!! \n<br>";
                    $respuestas.= $resp;
                    $this->resultado.=$resp;
                    echo $resp.PHP_EOL;
                    $this->totalPositivosInti++;
                }elseif(count($nominals)==0){
                    //En este caso la persona se encuentra sin novedad
                    $cnrtPersona[$k]->setResultado(0);
                    
                    $resp= date('Y-m-d H:i:s')." ID=".$row->ID." FechaNac=".$row->FECHANACIMIENTO." Resp.INTI=Sin Novedad \n<br>";
                    $respuestas.= $resp;
                    $this->resultado.=$resp;
                    echo $resp.PHP_EOL;
                }
            }else{
                $cnrtPersona[$k]->setEstado(4);
                
                $resp= date('Y-m-d H:i:s')." ID=".$row->ID." Error al intentar consultar a Interpol \n<br>";
                $respuestas.= $resp;
                $this->resultado.=$resp;
                echo $resp.PHP_EOL;
                $this->totalErrorInti++;
            }
            
        }
        
        $em->flush();
        
        return $respuestas;
    }
    
    
    function ejecutarReproceso(){
        $oCnrtPersona=[];
        $respuestas = "";
        $bNoModificarEstado = true;
        $em = $this->getContainer()->get('doctrine')->getManager();
        $loteCnrt = $em->getRepository('ADMINAdminBundle:CnrtPersona')->getErrores24();
        
        $this->totalCNRT = sizeof($loteCnrt);
        
        foreach($loteCnrt as $row){
            $bNoModificarEstado = false;
            $errorRenaper = false;
            $errorIdge = false;
            //  Estado de verificacion contra el servicio de Interpol.
            //  (0-PENDIENTE, 1-VERIFICADO, 2-INEXISTENTE RENAPER, 3-ERROR COMUNICACION RENAPER, 4-ERROR COMUNICACION INTERPOL, 5-ERROR COMUNICACION IDGE, 6-INEXISTENTE IDGE).
            if($row->getEstado()=="2" || $row->getEstado()=="3" || $row->getEstado()=="5" || $row->getEstado()=="0"){
                if( $row->getFechaNacimiento() == null || $row->getFechaNacimiento()=='' || strlen(trim($row->getFechaNacimiento()))!=10 ){  //  Si no tiene fecha de nacimiento
                    
                    $respIdge = $this->consultarIdge($row->getNumeroDocumento());
                    if(isset($respIdge->respuesta[0])){
                        $respIdge = $respIdge->respuesta[0];
                    }
                    
                    if(isset($respIdge->error)&&$respIdge->error){
                        
                        $bNoModificarEstado = true;
                        $row->setEstado(5);
                        $resp= date('Y-m-d H:i:s')." - Error al intentar consultar a IDGE - ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", " . $respIdge->message . " \n<br>";
                        $respuestas.= $resp;
                        $this->resultado.=$resp;
                        echo $resp.PHP_EOL;
                        $this->totalErrorIDGE++;
                        $errorIdge = true;
                    
                    }
                    
                    if(isset($respIdge->fecha_nacimiento) && $respIdge->fecha_nacimiento!=null && $respIdge->fecha_nacimiento!='' && strlen(trim($respIdge->fecha_nacimiento))==10){
                        $row->setFechaNacimiento($respIdge->fecha_nacimiento);
                    }else{   //  if(isset($respIdge->fecha_nacimiento) &&
                        
                        if(!$errorIdge) {
                            $bNoModificarEstado = true;
                            $row->setEstado(6);
                            $resp= date('Y-m-d H:i:s')." - Inexistente IDGE - ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", SEXO: " . $row->getSexo() . " \n<br>";
                            $respuestas.= $resp;
                            $this->resultado.=$resp;
                            echo $resp.PHP_EOL;
                            $this->totalErrorIDGE++;
                        }
                        
                        //   SI NO EXISTE EN IDGE VOY A BUSCARLO A RENAPER
                        $respRenaper = $this->consultarRenaper($row->getNumeroDocumento(), $row->getSexo());
                        
                        if(isset($respRenaper->respuesta[0])){
                            $respRenaper = $respRenaper->respuesta[0];
                        }
                        
                        if(isset($respRenaper->error)&&$respRenaper->error){
                            
                            $bNoModificarEstado = true;
                            $row->setEstado(3);
                            $resp= date('Y-m-d H:i:s')." - Error al intentar consultar a RENAPER S/FOTO- ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", " . $respRenaper->message . " \n<br>";
                            $respuestas.= $resp;
                            $this->resultado.=$resp;
                            echo $resp.PHP_EOL;
                            
                            
                            //   HUBO UN ERROR EN RENAPER SIN FOTO, INTENTO EN EL SERVICIO CON FOTO
                            $respRenaper = $this->consultarRenaper($row->getNumeroDocumento(), $row->getSexo(), 'S');
                            
                            if(isset($respRenaper->respuesta[0])){
                                $respRenaper = $respRenaper->respuesta[0];
                            }
                            
                            if(isset($respRenaper->error)&&$respRenaper->error){
                             
                                $bNoModificarEstado = true;
                                $row->setEstado(3);
                                $resp= date('Y-m-d H:i:s')." - Error al intentar consultar a RENAPER C/FOTO- ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", " . $respRenaper->message . " \n<br>";
                                $respuestas.= $resp;
                                $this->resultado.=$resp;
                                echo $resp.PHP_EOL;
                                $this->totalErrorRenaper++;
                                $errorRenaper = true;
                                
                            }
                            
                        }
                            
                        if(isset($respRenaper->FECHANACIMIENTO) && $respRenaper->FECHANACIMIENTO!=''){
                            $row->setFechaNacimiento($respRenaper->FECHANACIMIENTO);
                        }else{
                            if(!$errorRenaper){
                                $bNoModificarEstado = true;
                                $row->setEstado(2);
                                $resp= date('Y-m-d H:i:s')." - Inexistente RENAPER - ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", SEXO: " . $row->getSexo() . " \n<br>";
                                $respuestas.= $resp;
                                $this->resultado.=$resp;
                                echo $resp.PHP_EOL;
                                $this->totalErrorRenaper++;
                            }
                        }
                    
                    }   //  if(isset($respIdge->fecha_nacimiento) &&
                }   //  if( isset($loteCnrt[1]->getFechaNacimiento()) && 
            }
            
            //  SIEMPRE QUE ESTÉ PENDIENTE DE SER PROCESADO SE DEBERÁ CONSULTAR INTERPOL
            if( $row->getEstado() != "1" ){
            
                $cnrtPersona = $em->getRepository('ADMINAdminBundle:CnrtPersona')->find($row->getId());
                $cnrtPersona->setFechaNacimiento($row->getFechaNacimiento());
                
                if(!empty($row->getFechaNacimiento())){
                    $fila = (object)[
                        "NOMBRE"=>$row->getNombre(),
                        "APELLIDO"=>$row->getApellido(),
                        "FECHANACIMIENTO"=>$row->getFechaNacimiento(),
                        "ID"=>$row->getId()
                    ];
                    
                    $nominals = $this->consultarInterpol( $fila );
                    $this->totalOk++;
                    if($nominals!==false){
                        
                        if(is_object($nominals)){
                            //En este caso la persona tiene alguna notificación de Interpol
                            $cnrtPersona->setEstado(1);
                            $cnrtPersona->setResultado(1);
                            $cnrtPersona->setRespuestaDetails(json_encode($nominals));
                            $this->totalPositivosInti++;
                            
                            $resp= date('Y-m-d H:i:s')." ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", SEXO: " . $row->getSexo() . ", FECHA_NAC:" . $row->getFechaNacimiento() . ". -> Resp.INTI=POSITIVO!!! \n<br>";
                            $respuestas.= $resp;
                            $this->resultado.=$resp;
                            echo $resp."<br>".PHP_EOL;
                        }elseif(count($nominals)==0){
                            //En este caso la persona se encuentra sin novedad
                            $cnrtPersona->setResultado(0);
                            //Blanqueo todos los datos innecesarios
                            $cnrtPersona->setOrigen(null);
                            $cnrtPersona->setPciaOrigen(null);
                            $cnrtPersona->setDestino(null);
                            $cnrtPersona->setPciaDestino(null);
                            $cnrtPersona->setNroButaca(null);
                            $cnrtPersona->setDominio(null);
                            $cnrtPersona->setNroEmpresa(null);
                            $cnrtPersona->setDescEmpresa(null);
                            $cnrtPersona->setObservaciones(null);
                            
                            if(!$bNoModificarEstado){
                                $cnrtPersona->setEstado(1);
                            }
                            $resp= date('Y-m-d H:i:s')." ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", SEXO: " . $row->getSexo() . ", FECHA_NAC:" . $row->getFechaNacimiento() . ". -> Resp.INTI=Sin Novedad \n<br>";
                            $respuestas.= $resp;
                            $this->resultado.=$resp;
                            echo $resp."<br>".PHP_EOL;
                        }
                    }else{
                        $cnrtPersona->setEstado(4);
                        
                        $resp= date('Y-m-d H:i:s')." ID_CNRT=".$row->getId().", ID_PASAJERO:" .$row->getIdPasajero(). ", NRO_DOCUMENTO:" .$row->getNumeroDocumento(). ", SEXO: " . $row->getSexo() . ". -> Error al intentar consultar a Interpol \n<br>";
                        $respuestas.= $resp;
                        $this->resultado.=$resp;
                        echo $resp."<br>".PHP_EOL;
                    }
                }   //  if($row->getFechaNacimiento()!=""){
            }
        }
        
        // completar la lógica para que ejecute las consultas a las APIS faltantes y grabar los resultados
        
        //Por ultimo el tiempo al culminar
        $this->fin = microtime(true);
        //La resta del final - inicio nos dará el tiempo de ejecución
        $duration= $this->fin - $this->inicio;
        
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = (int)$duration-$hours*60*60-$minutes*60;
        
        $str = date('Y-m-d H:i:s')." Proceso finalizado en ".$hours. " horas ".$minutes. " minutos ".$seconds." segundos.\n<br><br>".PHP_EOL;
        
        $str.= "<b>RESUMEN REPROCESO:</b><br>".PHP_EOL;
        
        $str.= "Se reprocesaron en total <b>".$this->totalCNRT."</b> registros CNRT.<br>".PHP_EOL;
        $str.= "Total de errores o inexistentes IDGE: <b>".$this->totalErrorIDGE."</b>.<br>".PHP_EOL;
        $str.= "Total de errores o inexistentes RENAPER: <b>".$this->totalErrorRenaper."</b>.<br>".PHP_EOL;
        $str.= "Total de errores INTI: <b>".$this->totalErrorInti."</b>.<br>".PHP_EOL;
        $str.= "Se lograron consultar correctamente a Interpol: <b>".$this->totalOk."</b>.<br>".PHP_EOL;
        $str.= "Total de positivos de Interpol: <b>".$this->totalPositivosInti."</b>.<br>".PHP_EOL;
        
        echo $str."<br>".PHP_EOL;
        
        $this->resultado.=$str;
        
        $this->proceso->setResultado($this->resultado);
        
        $this->proceso->setFechaFin(new \Datetime());
        $em->flush();
    }
    
    function consultarIdge($dato){
        $urlWSBACKEND = AMBIENTE_WS_BACKEND;
        $ambienteIp =  $this->getContainer()->getParameter('ambienteIp');
        $usuario = $this->getContainer()->getParameter('usuarioIdgeInti');
        $pass = $this->getContainer()->getParameter('passIdgeInti');
        $token = $this->getContainer()->getParameter('tokenIdgeInti');
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://'.$urlWSBACKEND.'/sipfaweb/BackEndIdge/API/index.php/fechanacimiento',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{   "sistema": "IDGE",
                                      "origen": "INTI",
                                      "usuario":"'.$usuario.'",
                                      "pass":"'.$pass.'",
                                      "token":"'.$token.'",
                                      "usuarioApellido": "USER_APE",
                                      "usuarioNombre": "USER_NOM",
                                      "usuarioTipoDoc": "DNI",
                                      "usuarioDoc": "'.DNI_DEFAULT.'",
                                      "usuarioDepen": "USER_DEPEN",
                                      "usuarioIp": "'.$ambienteIp.'",
                                      "latitud": "'.LATITUD_DEFAULT.'",
                                      "longitud": "'.LONGITUD_DEFAULT.'",
                                      "nombre": "",
                                      "apellido": "",
                                      "fechaNac": "",
                                      "dni": "'.$dato.'"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $responseIdge = curl_exec($curl);
        
        $status = curl_getinfo ( $curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err || $status != 200) {
            
            $respIdge = new \stdClass();
            $respIdge->error = true;
            $respIdge->message = " Status=" . $status . " - Desc. Error=" . print_r($err, true) . " - Resp= " . print_r($responseIdge, true);
            
        }else{
        
            try {
            
                $respIdge = json_decode($responseIdge);
            
            } catch (Exception $e) {
                
                $respIdge = new \stdClass();
                $respIdge->error = true;
                $respIdge->message = " Status=" . $status . " - Desc. Error=" . print_r($err, true) . " - Resp= " . print_r($responseIdge, true);
            }
        
        }
        
        return $respIdge;
        
    }
    
    
    function consultarRenaper($dato, $sexo, $foto='N'){
        $urlWSPFA = AMBIENTE_WS_PFA;
        $ambienteIp =  $this->getContainer()->getParameter('ambienteIp');
        $usuario = $this->getContainer()->getParameter('usuarioRenaperInti');
        $pass = $this->getContainer()->getParameter('passRenaperInti');
        $token = $this->getContainer()->getParameter('tokenRenaperInti');
        $sexo = $sexo=='O'?'':$sexo;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://'.$urlWSPFA.'/PFA_RENAPER_FRONT_WS/personas',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{ "sistema":"RENAPER",
                                            "usuario":"'.$usuario.'",
                                            "pass":"'.$pass.'",
                                            "token":"'.$token.'",
                                            "origen":"CNRT_INTERPOL",
                                            "usuarioIp":"'.$ambienteIp.'",
                                            "usuarioApellido":"ADMIN",
                                            "usuarioNombre":"ADMIN",
                                            "usuarioDepen":"INTERPOL",
                                            "usuarioDepenId":"0",
                                            "usuarioTipoDoc":"DNI",
                                            "usuarioDoc":"'.DNI_DEFAULT.'",
                                            "latitud":"'.LATITUD_DEFAULT.'",
                                            "longitud":"'.LONGITUD_DEFAULT.'",
                                            "dato":"'.$dato.'",
                                            "sexo":"' . $sexo . '",
                                            "foto":"'.$foto.'"
                                            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $responseRenaper = curl_exec($curl);
        
        $status = curl_getinfo ( $curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err || $status != 200) {
            
            $respRenaper = new \stdClass();
            $respRenaper->error = true;
            $respRenaper->message = " Status=" . $status . " - Desc. Error=" . print_r($err, true) . " - Resp= " . print_r($responseRenaper, true);
            
        }else{
            
            try {
                
                $respRenaper = json_decode($responseRenaper);
                
            } catch (Exception $e) {
                
                $respRenaper = new \stdClass();
                $respRenaper->error = true;
                $respRenaper->message = " Status=" . $status . " - Desc. Error=" . print_r($err, true) . " - Resp= " . print_r($responseRenaper, true);
            }
            
        }
        
        return $respRenaper;
        
    }
    
    
    function consultarInterpol($datos){
        $nombre   = $datos->NOMBRE;
        $apellido = $datos->APELLIDO;
        $fechaNac = $datos->FECHANACIMIENTO;
        $tipoCons = 'AD';  //Por ser un proceso automático todas las consultas son investigativas

        $usuario          = 'ADMIN';
        $usuarioIp        = $this->getContainer()->getParameter('ambienteIp');
        $usuarioApellido  = 'ADMIN';
        $usuarioNombre    = 'ADMIN';
        $usuarioDepen     = 'CNRT INTERPOL';
        $usuarioDepenId   = 0;
        $legajo           = '';
        $usuarioTipoDoc   = 'DNI';
        $usuarioDoc       = DNI_DEFAULT;
        $usuarioJerarquia = 'ADMIN';
        
        $data = (object) array(
            "nombre"           => $nombre,
            "apellido"         => $apellido,
            "fechaNacimiento"  => $fechaNac,
            "tipoCons"         => $tipoCons,
            "usuario"          => $usuario,
            "usuarioIp"        => $usuarioIp,
            "usuarioApellido"  => $usuarioApellido,
            "usuarioNombre"    => $usuarioNombre,
            "usuarioDepen"     => $usuarioDepen,
            "usuarioDepenId"   => $usuarioDepenId,
            "legajo"           => $legajo,
            "usuarioTipoDoc"   => $usuarioTipoDoc,
            "usuarioDoc"       => $usuarioDoc,
            "usuarioJerarquia" => $usuarioJerarquia
        );
        
        $interpolPersona = new InterpolRepository($this->getContainer(), $data, null);
        
        $respuestaNominals = $interpolPersona->getNOMINALSEXACT(['origen'=>'PROCESO_CNRT']);
        
        if(isset($respuestaNominals->datas->search->origin->nominal)){
            $nominals[0] = $respuestaNominals->datas->search->origin->nominal;
            if(is_array($nominals[0])&&count($nominals[0])==0){
                return [];
            }
        }else{
            return [];
        }
        
        
        foreach ($nominals as $n){
            if(isset($n->date_of_birth)){
                $n->date_of_birth = substr($n->date_of_birth,6,2).'/'.substr($n->date_of_birth,4,2).'/'.substr($n->date_of_birth,0,4);
            }
        }
        
        foreach ($nominals as $claveFila => $valorFila){
            foreach ($valorFila as $clave => $valor){
                if($clave=="forename"){
                    if (is_object($valor)){
                        if(!(Array)$valor){
                            $nominals[$claveFila]->forename = "NO DATA";
                        }
                        foreach ((Array)$valor as $valorclave => $valorvalor){
                            $nominals[$claveFila]->forename .= $valorvalor;
                        }
                    }
                }
            }
        }
        
        if(count($nominals)==0 && isset($respuestaNominals->message) && $respuestaNominals->message != 'Sin resultados'){
            $nominals =["ERROR"=>$respuestaNominals->message];
            
            return false;
        }
        
        //Si encuentro un positivo intento obtener el detalle
        if(isset($nominals[0]->entityId) && $nominals[0]->entityId != ''){
            
            $entity = $nominals[0]->entityId;
            
            $data = (object) array(
                "entity"           => $entity,
                "nombre"           => $nombre,
                "apellido"         => $apellido,
                "fechaNacimiento"  => $fechaNac,
                "tipoCons"         => $tipoCons,
                "usuario"          => $usuario,
                "usuarioIp"        => $usuarioIp,
                "usuarioApellido"  => $usuarioApellido,
                "usuarioNombre"    => $usuarioNombre,
                "usuarioDepen"     => $usuarioDepen,
                "usuarioDepenId"   => $usuarioDepenId,
                "legajo"           => $legajo,
                "usuarioTipoDoc"   => $usuarioTipoDoc,
                "usuarioDoc"       => $usuarioDoc,
                "usuarioJerarquia" => $usuarioJerarquia
            );
            
            
            $interpolPersona = new InterpolRepository($this->getContainer(), $data, null);
            
            $respuestaNominals  = $interpolPersona->getNOMINALSDETAILS( $entity );
            
            $nominalDetails = [];
            
            if(isset($respuestaNominals->datas->origin->nominal)){
                $nominalDetails = $respuestaNominals->datas->origin->nominal; 
            }else{
                return false;
            }
        
            return $nominalDetails;
        }else{
            return [];
        }
        
    }
    
}
?>