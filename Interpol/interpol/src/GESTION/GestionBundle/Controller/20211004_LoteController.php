<?php

namespace GESTION\GestionBundle\Controller;

ini_set('memory_limit', '512M');

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use GESTION\GestionBundle\Entity\ConsultaLote;
use GESTION\GestionBundle\Entity\ConsultaLoteRepository;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalle;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalleRepository;
use GESTION\GestionBundle\Repository\InterpolRepository;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;
use GESTION\GestionBundle\Repository\Diccionario;

/**
 * Lote controller.
 *
 */
class LoteController extends Controller {

    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    //Array de Tipos de Búsquedas 

    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        
        return $this->render('GESTIONGestionBundle:Lote:index.html.twig', array(
            'usuario'=>$usuario
        ));
    }
    
    
    public function altaAction(Request $request)
    {
        $loteDetalles = array();
        $tipoCons = $request->get('lstTipoCons');
        $nominals = $request->get('lstNominals');
        $usuario = $this->getUser();
        
        switch($tipoCons){
            case 'NOMINALS':
                $tipoLote = 'P';
                break;
            case 'SLTD':
                $tipoLote = 'D';
                break;
            case 'SMV':
                $tipoLote = 'V';
                break;
            default:
                $tipoLote = '';
        }
        
        
        if($tipoCons=='NOMINALS')
            $tipoCons.=$nominals;
        
        $modoCons = $request->get('lstModoCons');        
        
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $archivo = $request->files->get('uploadFile');
        $archivoNombre = $archivo->getClientOriginalName();

        $archivoValido = $this->validarArchivo($archivo);
        
        if($archivoValido !== true){
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al validar el archivo: '.$archivoValido);
            return $this->render('GESTIONGestionBundle:Lote:index.html.twig', array(
                'usuario'=>$usuario
            ));
        }
        
        $em = $this->getDoctrine()->getManager();

        $consultaLote = new ConsultaLote();
        $consultaLote->setUsuario($this->getUser());        
        $consultaLote->setTipoLote($tipoLote);
        $consultaLote->setEstado('I');
        $consultaLote->setError(0);
        $consultaLote->setFecAlta( date('Y-m-d H:i:s') );
        $consultaLote->setArchivo(file_get_contents($archivo));
        $consultaLote->setArchivoNombre($archivoNombre);
        
        $arrayConsultas = explode(PHP_EOL,trim(file_get_contents($archivo)));

        $formatoValido = $this->validarLote($arrayConsultas,$tipoLote);
        
        if($formatoValido!== true){
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al validar el formato del lote: '.$formatoValido);
            return $this->render('GESTIONGestionBundle:Lote:index.html.twig', array(
                'usuario'=>$usuario
            ));
        }
        
        $em->persist($consultaLote);
        
        foreach($arrayConsultas as $consulta){
            $registro = explode(';',trim($consulta));
            
            $consultaLoteDetalle = new ConsultaLoteDetalle();
            
            $consultaLoteDetalle->setConsultaLoteId($consultaLote);
            $consultaLoteDetalle->setTipoConsulta($tipoCons);
            $consultaLoteDetalle->setModoConsulta($modoCons);
            $consultaLoteDetalle->setFecAlta( date('Y-m-d H:i:s') );
            
            switch ($consultaLote->getTipoLote()){
                case 'P':  // Persona
                    $consultaLoteDetalle->setApellido($registro[0]);
                    $consultaLoteDetalle->setNombre($registro[1]);
                    if(!strpos($registro[2],'/')){
                        $registro[2]=substr($registro[2],0,4).'/'.substr($registro[2],4,2).'/'.substr($registro[2],6,2);
                    }
                    $consultaLoteDetalle->setFechaNacimiento($registro[2]);
                    break;
                case 'D':  // Documento
                    $consultaLoteDetalle->setNroDocumento($registro[0]);
                    $consultaLoteDetalle->setTipoDocumento($registro[1]);
                    $consultaLoteDetalle->setPais($registro[2]);
                    break;
                case 'V':  // Vehículo
                    $consultaLoteDetalle->setVin($registro[0]);
                    $consultaLoteDetalle->setDominio($registro[1]);
                    $consultaLoteDetalle->setNroMotor($registro[2]);
            }
            
            $em->persist($consultaLoteDetalle);
            
            array_push($loteDetalles, $consultaLoteDetalle);
        }
        
        $em->flush();
        
        return $this->render('GESTIONGestionBundle:Lote:show.html.twig', array(
            'usuario'=>$usuario,
            'consultaLote'=>$consultaLote,
            'loteDetalles'=>$loteDetalles
        ));    
               
    }
    
    private function validarLote($arrayConsultas, $tipoLote){
        $linea = 1;
        
        foreach($arrayConsultas as $consulta){
            $registro = explode(';',trim($consulta));
            
            if(sizeof($registro)!=3){
                return "Error en línea ".$linea.": Cantidad de campos incorrecta. Se esperaban 3 y se encuentraron ".sizeof($registro).".";
            }
            
            
            switch ($tipoLote){
                case 'P':  // Persona
                    if(!strpos($registro[2],'/')){
                        $registro[2]=substr($registro[2],0,4).'/'.substr($registro[2],4,2).'/'.substr($registro[2],6,2);
                    }
                    
                    if(!$this->validar_fecha($registro[2])){
                        return "Error en línea ".$linea.": Formato de FECHA DE NACIMIENTO incorrecto. Los formatos admitidos son YYYY/MM/DD o YYYYMMDD.";
                    }
                    
                    break;
                case 'D':  // Documento
                    //Agregar Validación para tipo doc y pais, solo debe permitir algunos de los tipos especificados en Diccionario Repository
                    $mensajeRetorno = "";
                    //  $registro[1] = TIPO DOCUMENTO
                    //  $registro[2] = PAÍS
                    $Diccionario  = new Diccionario();
                    $oPaises = $Diccionario->getPaises();
                    $oTiposDocs = $Diccionario->getDocumentos();
                    
                    //echo "Dato buscado: [" . $registro[1]."]";
                    $existe = array_search($registro[1], array_column($oTiposDocs, 'COD'));
                    if(!$existe){
                        $mensajeRetorno = "Tipo de documento informado inexistente";
                    }
                   
                    //echo "Dato buscado: [" . $registro[2]."]";
                    $existe = array_search($registro[2], array_column($oPaises, 'COD_B'));
                    if(!$existe){
                        if($mensajeRetorno!="")
                            $mensajeRetorno.= ", ";
                        $mensajeRetorno .= "País incorrecto. ";
                    }
                    
                    if($mensajeRetorno!=""){
                        $mensajeRetorno = "Error en línea ".$linea.": ".$mensajeRetorno;
                        return $mensajeRetorno;
                    }
                    
                    break;
                case 'V':  // Vehículo
                    if( strlen($registro[0])==0 || strlen($registro[0])>100){
                        return "Error en línea ".$linea.": Formato de VIN incorrecto. Este campo debe tener entre 1 y 100 caracteres.";
                    }
                    if( strlen($registro[1])==0 || strlen($registro[1])>20){
                        return "Error en línea ".$linea.": Formato de DOMINIO incorrecto. Este campo debe tener entre 1 y 20 caracteres.";
                    }
                    if( strlen($registro[2])==0 || strlen($registro[2])>100){
                        return "Error en línea ".$linea.": Formato de NRO. DE MOTOR incorrecto. Este campo debe tener entre 1 y 100 caracteres.";
                    }
            }
            
            $linea++;            
        }
        
        return true;
        
    }
    
    
    private function validar_fecha($fecha) {
        $valores = explode('/', $fecha);
        if (count($valores) == 3 && is_numeric($valores[0]) && is_numeric($valores[1]) && is_numeric($valores[2]) 
           // && checkdate($valores[1], $valores[0], $valores[2])
            && intval($valores[0]) >= 1900 && intval($valores[0]) <= 2100
            && intval($valores[1]) >= 1 && intval($valores[1]) <= 12
            && intval($valores[2]) >= 1 && intval($valores[2]) <= 31 ) {
            return true;
        }
        return false;
    }
    
    
    /*
     * Validación del archivo de entrada
     */
    
    private function validarArchivo($file) {
        
        if ($file->getPathName() == '' || $file->getClientSize() == 0) {
            return 'El archivo vino vacio';
        }
        
        if ($file->getClientSize() > 20000000) {
            return 'El peso del archivo excede los 20 MB establecidos como maximo!';
        }
        
        
        if ($file->guessExtension() != 'txt' && $file->guessExtension() != 'csv') {
            return 'La extensión del archivo debe ser txt o csv' . $file->guessExtension();
        }
        
        $mime = $file->getMimeType();
    
        if ($mime != 'text/plain') {
            return 'Archivo CSV con errores o con formato no válido!';
        }
        //$Array = [ "delimiter" => [ "count", "key"=>"" ] ];
        
        $pathName = $file->getPathName();
        $Array = $this->analyse_file($pathName, filesize($pathName)); //20480: 2048MB o pasar filesize($filename)

        if (!is_array($Array)) {
            return $Array;
        }
        
        if (@$Array[delimiter][key] != 'punto_coma') {
            return 'Se detectó inconsistencia en formato csv:  se esperaba como caracter delimitador de campos [punto_coma], se obtuvo ["' . $Array[delimiter][key] . '"]';
        }
        
        if (@$Array[delimiter][results][pipe] > 0) {
            return 'Se detectó inconsistencia en formato csv: el documento contiene caracter/es pipe "|" no admitidos por las base!';
        }
    
        return true;
    }
    
    /*
     *
     */
    
    private function analyse_file($file, $capture_limit_in_kb = 10) {
        // capture starting memory usage (bytes)
        $output['peak_mem']['start'] = memory_get_peak_usage(true);
        
        // log the limit how much of the file was sampled (in Kb)
        $output['read_kb'] = $capture_limit_in_kb;
        
        // read in file
        $fh = fopen($file, 'r'); //almacena en contentes todo el contenido entero del archivo desde el inicio hasta x kb especificados
        
        if ($fh === false) {
            return "Error: fallo inesperado al intentar abrir archivo!";
        }
        
        $contents = fread($fh, ($capture_limit_in_kb * 1024)); //KB
        fclose($fh);
        
        $delimiters = array(
            'coma' => ',',
            'punto_coma' => ';',
            'tabulador' => "\t",
            'pipe' => '|',
            'dos_puntos' => ':'
        );
        
        // specify allowed line endings
        $line_endings = array(
            'rn' => "\r\n",
            'n' => "\n",
            'r' => "\r",
            'nr' => "\n\r"
        );
        
        // loop and count each line ending instance
        foreach ($line_endings as $key => $value) {
            $line_result[$key] = substr_count($contents, $value);
        }
        
        // sort by largest array value
        asort($line_result);
        
        // log to output array
        $output['line_ending']['results'] = $line_result;
        $output['line_ending']['count'] = end($line_result);
        $output['line_ending']['key'] = key($line_result); //situaria array line_ending_key pos 0 con valor pos 0 incidencia array line_result (rn)
        $output['line_ending']['value'] = $line_endings[$output['line_ending']['key']];
        $lines = explode($output['line_ending']['value'], $contents);
        
        // remove last line of array, as this maybe incomplete?
        array_pop($lines);
        
        // create a string from the legal lines
        $complete_lines = implode(' ', $lines);
        
        // log statistics to output array
        $output['lines']['count'] = count($lines);
        $output['lines']['length'] = strlen($complete_lines);
        
        // loop and count each delimiter instance
        foreach ($delimiters as $delimiter_key => $delimiter) {
            $delimiter_result[$delimiter_key] = substr_count($complete_lines, $delimiter);
        }
        
        // sort by largest array value
        asort($delimiter_result);
        
        // log statistics to output array with largest counts as the value
        $output['delimiter']['results'] = $delimiter_result;
        $output['delimiter']['count'] = end($delimiter_result);
        $output['delimiter']['key'] = key($delimiter_result);
        $output['delimiter']['value'] = $delimiters[$output['delimiter']['key']];
        
        // capture ending memory usage
        $output['peak_mem']['end'] = memory_get_peak_usage(true);
        return $output;
    }
    
    
    public function procesarAction(Request $request)
    {
        ini_set('max_execution_time', 600);
        session_write_close();
        $idConsultaLote = $request->get('idConsultaLote');
        $respuestas = array();  // Para devolver a la vista un array de pares idLoteDetalle, resultCode
        $em = $this->getDoctrine()->getManager();
        $consultaLote = $em->getRepository('GESTIONGestionBundle:ConsultaLote')->findById($idConsultaLote);
        
        $lote = $consultaLote[0];
        
        $lote->setError(0);
        $em->flush();
        $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->iniciarVariablesError($lote->getId());
       	$loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->traerDetalles($idConsultaLote);

        if(sizeof($loteDetalle)>=100)
            $cantRoutines = 100;
        else
            $cantRoutines = 10;
            
        if(sizeof($loteDetalle)>=$cantRoutines){
            $i=0;
            
            $sublotes=array();
            $routines=array();
            
            foreach($loteDetalle as $row){
                $sublotes[($i%$cantRoutines)][]=$row;
                $i++;
            }
            
            for($i=0;$i<$cantRoutines;$i++){
                $routine[$i] = new Coroutine($this->getResponse($lote,$sublotes[$i]));
                $respuestas[] = $routine[$i]->wait();
            }
            
            Loop\Run();
            
        }else{
            $respuestas = $this->obtenerRespuestas($lote, $loteDetalle);
        }
        
        $lote->setEstado('F');
        $em->flush();
        
        return new Response('Proceso Finalizado');
    }

    private function getResponse($lote,$loteDetalle){
        
        $delay = 0;//rand(1,5);
        $respuestas = $this->obtenerRespuestas($lote,$loteDetalle);
        $promise = Awaitable\resolve($respuestas);
        
        yield $promise->delay($delay);
        
    }
    
    
    private function obtenerRespuestas($lote,$loteDetalle){
        $errorGral = "";
        $em = $this->getDoctrine()->getManager();
        $usuario          = $this->getUser()->getUsuario();
        $usuarioIp        = $this->container->get('session')->get('ip');
        $usuarioApellido  = $this->getUser()->getApellido();
        $usuarioNombre    = $this->getUser()->getNombre();
        $usuarioDepen     = $this->getUser()->getDepenid()->getNombre();
        $usuarioDepenId   = $this->getUser()->getDepenid()->getCodigo();
        $legajo           = "";
        $usuarioTipoDoc   = $this->getUser()->getTipodoc();
        $usuarioDoc       = $this->getUser()->getNumerodoc();
        $usuarioJerarquia = $this->getUser()->getJerarquia();
        
        foreach($loteDetalle as $row){
        	$data = (object) array(
                "nombre"           => $row->getNombre(),
                "apellido"         => $row->getApellido(),
                "fechaNacimiento"  => $row->getFechaNacimiento(),
                "nroDoc"           => $row->getNroDocumento(),
                "pais"             => $row->getPais(),
                "tipoDoc"          => $row->getTipoDocumento(),
                "vin"              => $row->getVin(),
                "dominio"          => $row->getDominio(),
                "nroMotor"         => $row->getNroMotor(),
                "tipoCons"         => $row->getModoConsulta(),
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
            
            $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));
            
            $accion = $row->getTipoConsulta();
            switch($accion){
                case 'NOMINALS':
                    
                    try {
                    	$nominalsResponse = $interpol->getNOMINALS();
                    	if(isset($nominalsResponse->message)){
                    	    $errorGral = $nominalsResponse->message;
                    	}
                        
                    	if(isset($nominalsResponse->datas->search->origin->nominal)){
                    		$n = $nominalsResponse->datas->search->origin->nominal;
                            
                        }else{
                            $n = array();
                            
                        }
                    
                        if(count($n)==1){
                            $nominals[0]=$n;
                        }else{
                            $nominals=$n;
                        }
                        
                        foreach ($nominals as $n){
                            if(isset($n->date_of_birth)){
                                $n->date_of_birth = substr($n->date_of_birth,6,2).'/'.substr($n->date_of_birth,4,2).'/'.substr($n->date_of_birth,0,4);
                            }
                        }
                        
                        if(sizeof($nominals)>0){
                            $row->setResultCode('NO_ERROR');
                            $row->setRespuesta(json_encode($nominals));
                        }else{
                            if($errorGral!=""){
                                $row->setResultCode('ERROR');
                                $row->setRespuesta($errorGral);
                                $lote->setError($lote->getError()+1);
                            }else{
                                $row->setResultCode("NO_ANSWER");
                            }
                        }
                        
                    } catch (Exception $e) {
                    	$row->setResultCode('ERROR');
                        $row->setRespuesta($e->getMessage());
                        $lote->setError($lote->getError()+1);
                    }
                    
                    break;
                case 'NOMINALSEXACT':
                    
                    try {
                    	
                    	$nominalsResponse = $interpol->getNOMINALSEXACT();
                    	if(isset($nominalsResponse->message)){
                    	   $errorGral = $nominalsResponse->message;
                    	}
                        
                        //  EN CASO DE NOMINALSEXACT RETORNO UN ARRAY PARA LA POSICIÓN 0, SOLO PARA QUE EL RESTO DE LA INTERFAZ SE COMPORTE EXACTAMENTE IGUAL AL RESULTADO DE NOMINALS.
                    	if(isset($nominalsResponse->datas->search->origin->nominal)){
                    		$nominals[0] = $nominalsResponse->datas->search->origin->nominal;
                        }else{
                            $nominals[0] = array();
                        }
                        
                        if(is_array($nominals[0]) && count($nominals[0])==0){
                            $nominals = null;
                        }
                        
                        if(isset($nominals) && sizeof($nominals)>0){
                            $row->setResultCode('NO_ERROR');
                            $row->setRespuesta(json_encode($nominals));
                        }else{
                            
                            if($errorGral!=""){
                            	$row->setResultCode('ERROR');
                            	$row->setRespuesta($errorGral);
                            	$lote->setError($lote->getError()+1);
                            }else{
                                $row->setResultCode("NO_ANSWER");
                            }
                        }
                        
                    } catch (Exception $e) {
                    	$row->setResultCode('ERROR');
                    	$row->setRespuesta($e->getMessage());
                    	$lote->setError($lote->getError()+1);
                    }
                    
                    break;
                case 'SLTD':
                    try {
                    	$sltdResponse = $interpol->getSLTD();
                    	if(isset($sltdResponse->message)){
                    	   $errorGral = $sltdResponse->message;
                    	}
                    	
                    	if(isset($sltdResponse->datas->search->origin->document)){
                    		$documento[0] = $sltdResponse->datas->search->origin->document;
                    	}else{
                    	    $documento[0] = array();
                    	}

                    	if(is_array($documento[0]) && count($documento[0])==0){
                    	    $documento = null;
                    	}
                    		
                    	if(isset($documento) && sizeof($documento)>0){
                            $row->setResultCode('NO_ERROR');
                            $row->setRespuesta(json_encode($documento));
                        }else{
                            if($errorGral!=""){
                            	$row->setResultCode('ERROR');
                            	$row->setRespuesta($errorGral);
                            	$lote->setError($lote->getError()+1);
                            }else{
                                $row->setResultCode("NO_ANSWER");
                            }
                        }
                        
                        break;
                    } catch (Exception $e) {
                        
                    	$row->setResultCode('ERROR');
                    	$row->setRespuesta($e->getMessage());
                    	$lote->setError($lote->getError()+1);
                    }
                    
                case 'SMV':
                    try {
                        
                    	$smvResponse = $interpol->getSMV();
                    	
                    	if(isset($smvResponse->message)){
                    	   $errorGral = $smvResponse->message;
                    	}
                        
                    	if(isset($smvResponse->datas->search->origin->vehicle)){
                    		$smvs[0] = $smvResponse->datas->search->origin->vehicle;
                    	}else{
                    		$smvs[0] = array();
                    	}
                    	
                    	if(is_array($smvs[0]) && count($smvs[0])==0){
                    	    $smvs = null;
                    	}
                    	
                    	if(isset($smvs) && sizeof($smvs)>0){
                            $row->setResultCode('NO_ERROR');
                            $row->setRespuesta(json_encode($smvs));
                        }else{
                            if($errorGral!=""){
                            	$row->setResultCode('ERROR');
                            	$row->setRespuesta($errorGral);
                            	$lote->setError($lote->getError()+1);
                            }else{
                                $row->setResultCode("NO_ANSWER");
                            }
                        }
                        
                    } catch (Exception $e) {
                    	$row->setResultCode('ERROR');
                    	$row->setRespuesta($e->getMessage());
                    	$lote->setError($lote->getError()+1);
                    }
                    
                    break;
            }
            
            $r;
            
            switch($row->getResultCode()){
                case 'NO_ANSWER':
                    $r=0;
                    break;
                case 'NO_ERROR':
                    $r=1;
                    break;
                default:
                    $r=-1;
            }
            
            
            $respuestas[] = array('id'=>$row->getId(),'r'=>$r, 'ERROR'=>$errorGral);
            
            $row->setFecMod( date('Y-m-d H:i:s') );
            $em->flush();
        }
        
        return $respuestas;
    }
    
    
    public function getResultadosAction(Request $request){
        $id = $request->get('idConsultaLoteDetalle');
        
        $em = $this->getDoctrine()->getManager();
        $loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->findById($id);
        $loteDetalle = $loteDetalle[0];
        $tipo = $loteDetalle->getTipoConsulta();
        
        $resp = json_decode(stream_get_contents($loteDetalle->getRespuesta()));
        
        if($loteDetalle->getRespuestaDetails()!=null){
        	$respDetails = json_decode(stream_get_contents($loteDetalle->getRespuestaDetails()));
        }else{
        	$respDetails=null;
        }
        $entity = $loteDetalle->getEntityId();
        $request->attributes->set('txtNombre',$loteDetalle->getNombre());
        $request->attributes->set('txtApellido',$loteDetalle->getApellido());
        $request->attributes->set('txtFechaNac',$loteDetalle->getFechaNacimiento());        
        $request->attributes->set('lstTipoDoc',$loteDetalle->getTipoDocumento());
        $request->attributes->set('txtNumeroDoc', $loteDetalle->getNroDocumento());
        $request->attributes->set('lstPais',$loteDetalle->getPais());        
        $request->attributes->set('txtVin',$loteDetalle->getVin());
        $request->attributes->set('txtDominio',$loteDetalle->getDominio());
        $request->attributes->set('txtNroMotor',$loteDetalle->getNroMotor());
        $request->attributes->set('lstTipoCons',$loteDetalle->getModoConsulta());
        
        $usuario          = $this->getUser()->getUsuario();
        $usuarioIp        = $this->container->get('session')->get('ip');
        $usuarioApellido  = $this->getUser()->getApellido();
        $usuarioNombre    = $this->getUser()->getNombre();
        $usuarioDepen     = $this->getUser()->getDepenid()->getNombre();
        $usuarioDepenId   = $this->getUser()->getDepenid()->getCodigo();
        $legajo           = "";
        $usuarioTipoDoc   = $this->getUser()->getTipodoc();
        $usuarioDoc       = $this->getUser()->getNumerodoc();
        $usuarioJerarquia = $this->getUser()->getJerarquia();
        
        
        $data = (object) array(
            "entity"           => $entity,
            "nombre"           => $request->get('txtNombre'),
            "apellido"         => $request->get('txtApellido'),
            "fechaNacimiento"  => $request->get('txtFechaNac'),
            "tipoCons"         => $request->get('lstTipoCons'),
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
        
        $usuario = $this->getUser();
        
        switch($tipo){
            case 'NOMINALS':
                $detalle = null;
                if($respDetails != NULL && $respDetails != ''){
                    $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));
                    
                    $detalle = json_decode( json_encode($respDetails), true );
                    
                    $parametros = new \stdClass;
                    $parametros->entityId = $entity;
                    	
                    $arrayImg=array();
                    $idx=0;
                    
                    if(isset($detalle["file"][0])){
                    	foreach($detalle["file"] as $file){
                    		$parametros->path = isset($file["path"])?$file["path"]:'';
                    		
                    		if($parametros->path!=''){
                    			$nominalImage = $interpolPersona->getNOMINALSIMAGE ( $parametros );
                    			
                    			if(isset($nominalImage->imagen) && $nominalImage->imagen!=false){
                    				$img = '';
                    				$array = explode(",", $nominalImage->imagen);
                    				for ($a = 0; $a < count($array); $a++) {
                    					$img .= chr(intval($array[$a]));
                    				}
                    				
                    				$image = imagecreatefromstring($img);
                    				ob_start();
                    				imagejpeg($image);
                    				$contents = ob_get_contents();
                    				ob_end_clean();
                    				$arrayImg[$idx] = "data:image/jpeg;base64," . base64_encode($contents);
                    			}
                    		}
                    		$idx++;
                    	}
                    }else{
                    	$parametros->path = isset($detalle["file"]["path"])?$detalle["file"]["path"]:'';
                    	
                    	if($parametros->path!=''){
                    		$nominalImage = $interpolPersona->getNOMINALSIMAGE ( $parametros );
                    		
                    		if(isset($nominalImage->imagen) && $nominalImage->imagen!=false){
                    			$img = '';
                    			$array = explode(",", $nominalImage->imagen);
                    			for ($a = 0; $a < count($array); $a++) {
                    				$img .= chr(intval($array[$a]));
                    			}
                    			
                    			$image = imagecreatefromstring($img);
                    			ob_start();
                    			imagejpeg($image);
                    			$contents = ob_get_contents();
                    			ob_end_clean();
                    			$arrayImg[0] = "data:image/jpeg;base64," . base64_encode($contents);
                    		}
                    	}
                    }
                    
                    return $this->render('GESTIONGestionBundle:Persona:showdetails.html.twig', array(
                        'usuario'=>$usuario,
                        'nominalDetails' => $detalle,
                    	'arrayImg' => $arrayImg,
                        'titulos' => $interpolPersona->titulos  ,
                        'encabezados' => $interpolPersona->encabezados) );
                    
                }else{
                    return $this->render( 'GESTIONGestionBundle:Persona:show.html.twig', array( 
                        'usuario'=>$usuario,
                        'nominals' => $resp, 
                        'idConsultaLoteDetalle' => $id) );
                }
                break;
            case 'NOMINALSEXACT':
                if($respDetails != NULL && $respDetails != ''){
                    
                    $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));
                    
                    $detalle = json_decode( json_encode($respDetails), true );
                    
                        
                    $parametros = new \stdClass;
                    $parametros->entityId = $entity;
                    $arrayImg=array();
                    $idx=0;
                        
                    if(isset($detalle["file"][0])){
                        foreach($detalle["file"] as $file){
                        	$parametros->path = isset($file["path"])?$file["path"]:'';
                        		
                        	if($parametros->path!=''){
                        		$nominalImage = $interpolPersona->getNOMINALSIMAGE ( $parametros );
                        			
                        		if(isset($nominalImage->imagen) && $nominalImage->imagen!=false){
                        			$img = '';
                        			$array = explode(",", $nominalImage->imagen);
                        			for ($a = 0; $a < count($array); $a++) {
                        				$img .= chr(intval($array[$a]));
                        			}
                        				
                        			$image = imagecreatefromstring($img);
                        			ob_start();
                        			imagejpeg($image);
                        			$contents = ob_get_contents();
                        			ob_end_clean();
                        			$arrayImg[$idx] = "data:image/jpeg;base64," . base64_encode($contents);
                        		}
                        	}
                        	$idx++;
                        }
            		}else{
                        $parametros->path = isset($detalle["file"]["path"])?$detalle["file"]["path"]:'';
                        	
                        if($parametros->path!=''){
                        	$nominalImage = $interpolPersona->getNOMINALSIMAGE ( $parametros );
                        		
                        	if(isset($nominalImage->imagen) && $nominalImage->imagen!=false){
                        		$img = '';
                        		$array = explode(",", $nominalImage->imagen);
                        		for ($a = 0; $a < count($array); $a++) {
                        			$img .= chr(intval($array[$a]));
                        		}
                        			
                        		$image = imagecreatefromstring($img);
                        		ob_start();
                        		imagejpeg($image);
                        		$contents = ob_get_contents();
                        		ob_end_clean();
                        		$arrayImg[0] = "data:image/jpeg;base64," . base64_encode($contents);
                        	}
                        }
                    }
                    
                    return $this->render('GESTIONGestionBundle:Persona:showdetails.html.twig', array(
                        'usuario'=>$usuario,
                        'nominalDetails' => $detalle,
                    	'arrayImg' => $arrayImg,
                        'titulos' => $interpolPersona->titulos  ,
                        'encabezados'     => $interpolPersona->encabezados
                        ) );
                                        
                }else{
                    return $this->render( 'GESTIONGestionBundle:Persona:show.html.twig', array( 
                        'usuario'=>$usuario,
                        'nominals' => $resp , 
                        'idConsultaLoteDetalle' => $id
                    ) );
                }
               break;
            case 'SLTD':
                if($respDetails != NULL && $respDetails != ''){
                    $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));
                    
                    $document = json_decode( json_encode($respDetails), true );
                    
                    return $this->render('GESTIONGestionBundle:Documento:showdetails.html.twig', array(
                        'usuario'=>$usuario,
                        'documentDetails' => $document,
                        'titulos'         => $interpol->titulos  ,
                        'encabezados'     => $interpol->encabezados,
                        'paises'          => $interpol->paises
                    ) );
                    
                }else{
                    return $this->render( 'GESTIONGestionBundle:Documento:show.html.twig', array( 
                        'usuario'=>$usuario,
                        'documento' => $resp , 
                        'idConsultaLoteDetalle' => $id
                    ) );
                }
                break;
            case 'SMV':
                if($respDetails != NULL && $respDetails != ''){
                    $interpolVehiculo = new InterpolRepository($this->container, $data, $this->container->get('session'));
                    
                    $detalle = json_decode( json_encode($respDetails), true );
                    return $this->render('GESTIONGestionBundle:Vehiculo:showdetails.html.twig', array(
                        'usuario'=>$usuario,
                        'smvDetails' => $detalle,
                    	'encabezados'     => $interpolVehiculo->encabezados,
                    	'paises'     => $interpolVehiculo->paises	
                        )
                    );
                    
                }else{
                    return $this->render( 'GESTIONGestionBundle:Vehiculo:show.html.twig', array( 
                        'usuario'=>$usuario,
                        'smvs' => $resp , 
                        'idConsultaLoteDetalle' => $id
                        ) );
                }
        }
        
    }
    
    public function actualizarTablaAction(Request $request){
        $idConsultaLote = $request->get('idConsultaLote');        
        
        $respuestas = array();  // Para devolver a la vista un array de pares idLoteDetalle, resultCode
        $em = $this->getDoctrine()->getManager();
        
        $consultaLote = $em->getRepository('GESTIONGestionBundle:ConsultaLote')->find($idConsultaLote);
        
        $loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->resultadosParciales($consultaLote);
     
        if(sizeof($loteDetalle)>0){
            $lastFecMod = $loteDetalle[0]->getFecMod();
        }
        
        foreach($loteDetalle as $row){
            $row->setLeido('S');
            if($row->getRespuesta()!=null){
            	$respuestas[] = array('idConsultaDetalle'=>$row->getId(),'resultCode'=>$row->getResultCode(),'resultMessage'=>stream_get_contents($row->getRespuesta()));
            }else{
            	$respuestas[] = array('idConsultaDetalle'=>$row->getId(),'resultCode'=>$row->getResultCode(),'resultMessage'=>'');
            }
            
        }
        
        $em->flush();
        
        return new Response(json_encode($respuestas));
    }
    
    public function showAction(Request $request)
    {
        $usuario = $this->getUser();
        
        return $this->render( 'GESTIONGestionBundle:Lote:show.html.twig', array( 'usuario'=>$usuario) );
    }
    
    public function showProcesadosAction(Request $request)
    {
        $idConsultaLote = $request->get('idConsultaLote');
        
        $em = $this->getDoctrine()->getManager();
        
        $consultaLote = $em->getRepository('GESTIONGestionBundle:ConsultaLote')->findById($idConsultaLote);
        
        $loteDetalles = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->findBy(array('consultaLoteId'=>$idConsultaLote));        
        $usuario = $this->getUser();
        
        
        $i=0;
        foreach ($loteDetalles as $row){
        	if($row->getResultCode()=='ERROR' && $row->getRespuesta()!=null){
        		$loteDetalles[$i]->setRespuesta(stream_get_contents($row->getRespuesta()));
        	}
        	$i++;
        }
        
        return $this->render('ADMINAdminBundle:LotesProcesados:show.html.twig',array(
            'usuario'=>$usuario,
            'consultaLote'=>$consultaLote[0], 
            'loteDetalles'=>$loteDetalles            
        ));
    }
    
    
    public function indexProcesadosAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        
        $admin = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->find(1); // Administrador
        if(!$this->getUser()->getPerfilid()->contains($admin)){ 
        	$txtUsuario = $this->getUser();
        }else{
        	$txtUsuario = $request->get('txtUsuario')?$request->get( 'txtUsuario'):"";
        }
        
        $fDesde = $request->get('txtFechaDesde')?$request->get('txtFechaDesde'):"";
        $fHasta = $request->get('txtFechaHasta')?$request->get('txtFechaHasta'):"";
        $lstTipoCons = $request->get('lstTipoCons')?$request->get('lstTipoCons'):"";
        $lstResult = $request->get('lstResult')?$request->get('lstResult'):"";
        $page = $request->get('page')?$request->get( 'page'):'1';
        $usuario = $this->getUser();
        
        if($fDesde!='')
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        else
            $fDesde_A = '';
        
        if($fHasta!='')
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        else 
            $fHasta_A = '';
            
        $filter = array(
            "usuario"   => $txtUsuario,
            "fdesde"      => $fDesde_A,
            "fhasta"      => $fHasta_A,
            "tipolote"=> $lstTipoCons,
            "resultado"   => $lstResult
        );        
        
        $entities = $em->getRepository('GESTIONGestionBundle:ConsultaLote')->getByFilter($filter);
        
        $paginador = new Pagerfanta(new ArrayAdapter($entities));
        $paginador->setMaxPerpage(20);
        $paginador->setCurrentPage($page);
        
        return $this->render('ADMINAdminBundle:LotesProcesados:index.html.twig',array( 'usuario'=>$usuario, 'lotesprocesados'=> $paginador));
    }
    
    
    public function descargarAction(Request $request){
        
        $idConsultaLote = $request->get('idConsultaLote');
        
        $em = $this->getDoctrine()->getManager();
        
        $consultaLote = $em->getRepository('GESTIONGestionBundle:ConsultaLote')->findById($idConsultaLote);
        
        $file = $consultaLote[0]->getArchivo();
        
        $response = new \Symfony\Component\HttpFoundation\Response(stream_get_contents($file),
            200,
            array(
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="'.$consultaLote[0]->getArchivoNombre().'"',
            ));
        
        return $response;
    }
}
?>