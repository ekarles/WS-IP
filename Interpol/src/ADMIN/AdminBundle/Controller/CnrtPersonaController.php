<?php

namespace ADMIN\AdminBundle\Controller;

ini_set('memory_limit', '512M');

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use GESTION\GestionBundle\Repository\Menu;
use ADMIN\AdminBundle\Entity\CnrtPersona;
use ADMIN\AdminBundle\Entity\AuditoriaCnrtPersona;
use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;
use DateTime;
use GESTION\GestionBundle\Repository\Diccionario;
use GESTION\GestionBundle\Repository\InterpolRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

include_once ('/apache/includes/ambiente.php');

class CnrtPersonaController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    
    public function indexAction(Request $request, $page = 1)
    {
        $usuario = $this->getUser();
        $retorno = [];
        $is_admin = false;
        
        $txtIdPasajero = $request->get( "txtIdPasajero" );
        $txtNroDocumento = $request->get( "txtNroDocumento" );
        $txtApellido     = $request->get( "txtApellido"     );
        $txtNombre       = $request->get( "txtNombre"       );
        $dtfechaDesde    = $request->get( "dtfechaDesde"    );
        $dtfechaHasta    = $request->get( "dtfechaHasta"    );
        $lstEstados      = $request->get( "lstEstados"      );
        $lstResultado    = $request->get( "lstResultado"    );
                
        foreach($usuario->getPerfilid() as $perfil){
            foreach($perfil->getPermisoid() as $permiso){
                if($permiso->getId() == 127){
                    $lstResultado = 1;
                }
            }
            if($perfil->getId()==1){
                $is_admin = true;
            }
        }
                       
        
        $em = $this->getDoctrine()->getManager();
        
        
        $fecha = new \DateTime();
        $fechaHastaDefault = $fecha->format('Y-m-d');
        $fecha->modify('-1 month');       
        $fechaDesdeDefault = $fecha->format('Y-m-d'); 
        
        
        $request->query->set('dtfechaDesde', $dtfechaDesde ??  $fechaDesdeDefault);
        $request->query->set('dtfechaHasta', $dtfechaHasta ??  $fechaHastaDefault);
        
        $parameters = array(
            "txtIdPasajero" => $txtIdPasajero ,
            "txtNroDocumento" => $txtNroDocumento ,
            "txtApellido"     => $txtApellido     ,
            "txtNombre"       => $txtNombre       ,
            "dtfechaDesde"    => $dtfechaDesde ??  $fechaDesdeDefault ,
            "dtfechaHasta"    => $dtfechaHasta ??  $fechaHastaDefault ,
            "lstEstados"      => $lstEstados      ,
            "UserId"	      => $this->getUser()->getId(),
            "lstResultado"    => $lstResultado      ,
        );
        
        $oQuery = $em->getRepository('ADMINAdminBundle:CnrtPersona')->leerPersonasFiltro($parameters);
        
        
        if($request->get('page')!=''){
            $page = $request->get('page');
        }
        
        $paginador = new Pagerfanta(new DoctrineORMAdapter($oQuery));
        $paginador->setMaxPerpage(50);
        $paginador->setCurrentPage($page);
        
        $em = $this->getDoctrine()->getManager();
        $oEstados = $em->getRepository('ADMINAdminBundle:CnrtPersona')->leerEstados();
        
        if($is_admin){
            $diasSinCnrt = $this->get('admin.cnrtpersonaservice')->diasSinRegistrosCnrt();
            
            if($diasSinCnrt>0){
                $this->container->get('session')->getFlashBag()->add('msgWarn', 'No se han recibido nuevos registros de CNRT desde hace más de 24 hs. Por favor dar aviso a los administradores del sistema si la falla persiste.');
            }
        }
            
        return $this->render(
            'ADMINAdminBundle:CnrtPersona:index.html.twig',
            array(
                'results' => $paginador,
                'TotalRegistros'    => count( isset( $paginador ) ? $paginador : 0 ),
                'page'    => $page,
                'Estados' => $oEstados
            )
         );
    }
    
    public function consultarPersonasCnrtIdAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ADMINAdminBundle:CnrtPersona')->find($id);
        
        $auditoria = new AuditoriaCnrtPersona();
        $auditoria->setUsuAlta($this->getUser());
        $auditoria->setFecAlta(new \Datetime());
        $auditoria->setCnrtPersona($entity);
        $auditoria->setAccion("Consulta de persona CNRT.");
        $em->persist($auditoria);
        $em->flush();
        
        $retorno = [
            "estado"=>"OK",
            "mensaje"=>""
        ];
        $retorno = $em->getRepository('ADMINAdminBundle:CnrtPersona')->leerCNRTPersonasId($id);

        $auditoria->setAccion($auditoria->getAccion()." Resultado= ".$retorno);
        $em->flush();
        
        return new JsonResponse($retorno);
    } 
    
    public function auditarReportePDFAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ADMINAdminBundle:CnrtPersona')->find($id);
        $info = $em->getRepository('ADMINAdminBundle:CnrtPersona')->leerCNRTPersonasId($id);
        
        $auditoria = new AuditoriaCnrtPersona();
        $auditoria->setUsuAlta($this->getUser());
        $auditoria->setFecAlta(new \Datetime());
        $auditoria->setCnrtPersona($entity);
        $auditoria->setAccion("Exportación a PDF de persona CNRT. Resultado= ".$info);
        $em->persist($auditoria);
        $em->flush();
        
        $retorno = json_encode(['resultado'=>'Ok']);
        
        return new JsonResponse($retorno);
    }
    
    
    public function verificarDatosPasajeroAction(Request $request, $id){
        $idCnrtPersona = $request->get('idCnrtPersona');
        $ambienteIp =  $this->getParameter('ambienteIp');
        $urlWSPFA = $this->getParameter('urlWSPFA');
        $usuario = $this->getParameter('usuarioCnrtInti');
        $pass = $this->getParameter('passCnrtInti');
        $token = $this->getParameter('tokenCnrtInti');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $cnrtPersona = $em->getRepository('ADMINAdminBundle:CnrtPersona')->findById($idCnrtPersona);
        $entity = $cnrtPersona[0];
        
        $curlCNRT = curl_init();
        
        $auditoria = new AuditoriaCnrtPersona();
        $auditoria->setUsuAlta($user);
        $auditoria->setFecAlta(new \Datetime());
        $auditoria->setCnrtPersona($entity);
        $auditoria->setAccion("Verificación de datos contra CNRT.");
        $em->persist($auditoria);
        $em->flush();
        
        curl_setopt_array($curlCNRT, array(
            CURLOPT_URL => 'http://'.$urlWSPFA.'/PFA_CNRT_CLIENTE_WS/datosPasajero',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                                    "sistema":"CNRT",
                                    "origen":"PFA",
                                    "token":"'.$token.'",
                                    "usuario":"'.$usuario.'",
                                    "pass":"'.$pass.'",
                                    "usuarioApellido":"'.$user->getApellido().'",
                                    "usuarioNombre":"'.$user->getNombre().'",
                                    "usuarioTipoDoc":"'.$user->getTipodoc().'",
                                    "usuarioDoc":"'.$user->getNumerodoc().'",
                                    "usuarioDepen":"'.$user->getDepenid()->getNombre().'",
                                    "usuarioIp":"'.$this->get('session')->get('ip').'",
                                    "usuarioJerarquia":"'.$user->getJerarquia().'",
                                    "usuarioDepenId":"'.$user->getDepenid()->getCodigo().'",
                                    "latitud":"'.LATITUD_DEFAULT.'",
                                    "longitud":"'.LONGITUD_DEFAULT.'",
                                    "id":"'.$id.'"
                                  }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
  
        $response = curl_exec($curlCNRT);
        $status = curl_getinfo ( $curlCNRT, CURLINFO_HTTP_CODE);
        
        $err = curl_error($curlCNRT);
        $retorno = '';
        
        if($err!=''){
            $r = ['error'=>'Error de conexion [1]: '.print_r($err,true)];
            $retorno = json_encode($r);
        }else{
            $resp = json_decode($response,true);
            $respuesta = $resp["respuesta"][0];
            
            if ($err || $status!=200 || !isset($respuesta["NRO_EMPRESA"])) {
                $r = ['error'=>'Error de conexion [2]: Status='.$status.' - Desc. Error='.print_r($err,true).' - Resp='.print_r($response,true)];
                $retorno = json_encode($r);
            }else{
                
                $modif = false;
                $cancelado = false;
                
                if($entity->getNroEmpresa()!=$respuesta["NRO_EMPRESA"]){
                    $entity->setNroEmpresa($respuesta["NRO_EMPRESA"]);
                    $modif = true;
                }
                if(mb_strtoupper(trim($entity->getDescEmpresa()), 'UTF-8')!=mb_strtoupper(trim($respuesta["DESC_EMPRESA"]), 'UTF-8')){
                    $entity->setDescEmpresa($respuesta["DESC_EMPRESA"]);
                    $modif = true;
                }
                if($entity->getObservaciones()!=$respuesta["OBSERVACIONES"]){
                    $entity->setObservaciones($respuesta["OBSERVACIONES"]);
                    $modif = true;
                }
                if($entity->getFechaInicio()!=$respuesta["FECHA_INICIO"]){
                    $entity->setFechaInicio($respuesta["FECHA_INICIO"]);
                    $modif = true;
                }
                if($entity->getFechaFin()!=$respuesta["FECHA_FIN"]){
                    $entity->setFechaFin($respuesta["FECHA_FIN"]);
                    $modif = true;
                }
                if($entity->getOrigen()!=$respuesta["ORIGEN"]){
                    $entity->setOrigen($respuesta["ORIGEN"]);
                    $modif = true;
                }
                if($entity->getPciaOrigen()!=$respuesta["PROVINCIA_ORIGEN"]){
                    $entity->setPciaOrigen($respuesta["PROVINCIA_ORIGEN"]);
                    $modif = true;
                }
                if($entity->getDestino()!=$respuesta["DESTINO"]){
                    $entity->setDestino($respuesta["DESTINO"]);
                    $modif = true;
                }
                if($entity->getPciaDestino()!=$respuesta["PROVINCIA_DESTINO"]){
                    $entity->setPciaDestino($respuesta["PROVINCIA_DESTINO"]);
                    $modif = true;
                }
                if($entity->getNroButaca()!=$respuesta["NRO_BUTACA"]){
                    $entity->setNroButaca($respuesta["NRO_BUTACA"]);
                    $modif = true;
                }
                if($entity->getDominio()!=$respuesta["DOMINIO"]){
                    $entity->setDominio($respuesta["DOMINIO"]);
                    $modif = true;
                }
                if($entity->getFechaCancelacion()==null && !empty($respuesta["FECHA_CANCELACION"])){
                    $entity->setFechaCancelacion(new \DateTime($respuesta["FECHA_CANCELACION"]));
                    $cancelado = true;
                }elseif($entity->getFechaCancelacion()!=null && $entity->getFechaCancelacion()->format('d-m-Y H:i:s') != $respuesta["FECHA_CANCELACION"]){
                    if(empty($respuesta["FECHA_CANCELACION"])){
                        $entity->setFechaCancelacion(null);
                    }else{
                        $entity->setFechaCancelacion(new \DateTime($respuesta["FECHA_CANCELACION"]));
                    }
                    $modif = true;
                }
                
                if($modif||$cancelado){
                    $em->flush();
                }
                
                $r = ['modif'=>$modif,'cancelado'=>$cancelado];
                
                $retorno = json_encode($r);
                
            }            
        }
        
        curl_close($curlCNRT);
        
        $auditoria->setAccion($auditoria->getAccion()." Resultado= ".$retorno);
        $em->flush();
        
        return new JsonResponse($retorno);
    } 
    
    
    public function reprocesarInterpolAction(Request $request, $id){
        $retorno = '{"estado":"OK", "mensaje":""}';
        $em = $this->getDoctrine()->getManager();
        $cnrtPersona = $em->getRepository('ADMINAdminBundle:CnrtPersona')->find($id);
        
        $auditoria = new AuditoriaCnrtPersona();
        $auditoria->setUsuAlta($this->getUser());
        $auditoria->setFecAlta(new \Datetime());
        $auditoria->setCnrtPersona($cnrtPersona);
        $auditoria->setAccion("Reprocesamiento de persona.");
        $em->persist($auditoria);
        $em->flush();
        
        
        if( $cnrtPersona->getFechaNacimiento() =="" || strlen( trim( $cnrtPersona->getFechaNacimiento() ) )<10 ){
            $respIdge = $this->consultarIdge( $cnrtPersona->getNumeroDocumento() );

            if( isset($respIdge->respuesta[0]->fecha_nacimiento) ){
                $cnrtPersona->setFechaNacimiento( $respIdge->respuesta[0]->fecha_nacimiento );
            }
            $respIdge = null;
        }

        if( $cnrtPersona->getFechaNacimiento() =="" || strlen( trim( $cnrtPersona->getFechaNacimiento() ) )<10 ){
            $respRenaper = $this->consultarRenaper( $cnrtPersona->getNumeroDocumento() );
            
            if( isset($respRenaper->respuesta[0]->FECHANACIMIENTO) ){
                $cnrtPersona->setFechaNacimiento( $respRenaper->respuesta[0]->FECHANACIMIENTO);
            }
            $respIdge = null;
        }
        
        $sDetalle = "";
        $respuestas = null;
        if( $cnrtPersona->getFechaNacimiento() !='' && strlen(trim($cnrtPersona->getFechaNacimiento()))==10){
            $nominals = $this->consultarInterpol( $cnrtPersona );
            if($nominals!==false){
                 $cnrtPersona->setEstado( 1 );
                 if(is_object($nominals)){
                     //En este caso la persona tiene alguna notificación de Interpol
                     $cnrtPersona->setResultado(1);
                     $cnrtPersona->setRespuestaDetails( json_encode( $nominals ) );
                     $sDetalle = json_encode( $nominals );
                     $resp= date('Y-m-d H:i:s')." ID=".$cnrtPersona->getId()." FechaNac=".$cnrtPersona->getFechaNacimiento()." Resp.INTI=POSITIVO!!!\n";
                     $respuestas.= $resp;
                 }elseif(count($nominals)==0){
                     //En este caso la persona se encuentra sin novedad
                     $cnrtPersona->setResultado(0);
                     $resp= date('Y-m-d H:i:s')." ID=".$cnrtPersona->getId()." FechaNac=".$cnrtPersona->getFechaNacimiento()." Resp.INTI=Sin Novedad\n";
                     $respuestas.= $resp;
                 }
             }else{
                 $cnrtPersona->setEstado(4);
                 $erroresInterpol[] = $oPersona;
                 
                 $resp= date('Y-m-d H:i:s')." ID=".$cnrtPersona->getId()." Error al intentar consultar a Interpol\n";
                 $respuestas.= $resp;
             }
        }else{
            $cnrtPersona->setEstado(3);
            $erroresRenaper[] = $cnrtPersona;
            $resp= date('Y-m-d H:i:s')." ID=".$cnrtPersona->getId()." Error al consultar a IDGE\n";
            $respuestas.= $resp;
        }
        $em->flush();
        
        $oRetorno = [
                        "ID"                => $cnrtPersona->getId()       , 
                        "ESTADO"            => $cnrtPersona->getEstado()   ,
                        "RESULTADO"         => $cnrtPersona->getResultado(), 
                        "RESPUESTA_DETAILS" => $sDetalle
                    ];
        
        
        $auditoria->setAccion($auditoria->getAccion()." Resultado= ".print_r($oRetorno,true));
        $em->flush();
        
        return new JsonResponse( $oRetorno );
    }
    
    function consultarIdge($dato){
        
        $urlWSBACKEND = AMBIENTE_WS_BACKEND;
        $ambienteIp   = AMBIENTE_IP;
        $user         = $this->getUser();
        $usuario      = $user->getUsuario();
        $pass         = $this->getParameter('passIdgeInti');
        $token        = $this->getParameter('tokenIdgeInti');
        
        $curl = curl_init();
        $url = 'http://'.$urlWSBACKEND."/sipfaweb/BackEndIdge/API/index.php/fechanacimiento";
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{   "sistema"         : "IDGE",
                                      "origen"          : "CNRT",
                                      "usuario"         : "'.$usuario.'",
                                      "pass"            : "'.$pass.'",
                                      "token"           : "'.$token.'",
                                      "usuarioApellido" : "USER_APE",
                                      "usuarioNombre"   : "USER_NOM",
                                      "usuarioTipoDoc"  : "DNI",
                                      "usuarioDoc"      : "'.DNI_DEFAULT.'",
                                      "usuarioDepen"    : "USER_DEPEN",
                                      "usuarioIp"       : "'.$ambienteIp.'",
                                      "latitud"         : "'.LATITUD_DEFAULT.'",
                                      "longitud"        : "'.LONGITUD_DEFAULT.'",
                                      "nombre"          : "",
                                      "apellido"        : "",
                                      "fechaNac"        : "",
                                      "dni"             : "'.$dato.'"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $responseIdge = curl_exec($curl);
        curl_close($curl);
        $respIdge = json_decode($responseIdge);
        return $respIdge;
    }
    
    
    function consultarRenaper($dato){
        $urlWSPFA = AMBIENTE_WS_PFA;
        $ambienteIp =  AMBIENTE_IP;
        $usuario = USUARIO_RENAPER_INTI;
        $pass = PASS_RENAPER_INTI;
        $token = TOKEN_RENAPER_INTI;
        
        $curl = curl_init();
        
        $sUrl = 'http://'.$urlWSPFA.'/PFA_RENAPER_FRONT_WS/personas';
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $sUrl,
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
                                            "foto":"N"
                                            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $responseRenaper = curl_exec($curl);
        curl_close($curl);
        
        $respRenaper = json_decode($responseRenaper);
        
        return $respRenaper;
        
    }
    
    
    function consultarInterpol($datos){

        $usrLogin = $this->getUser();
        
        $nombre   = $datos->getNombre();
        $apellido = $datos->getApellido();
        $fechaNac = $datos->getFechaNacimiento();
        
        $tipoCons = 'AD';  //Por ser un proceso automático todas las consultas son investigativas
        
        $usuario          = $usrLogin->getUsuario();
        $usuarioIp        = AMBIENTE_IP;
        $usuarioApellido  = $usrLogin->getApellido();
        $usuarioNombre    = $usrLogin->getNombre();
        $usuarioDepen     = $usrLogin->getDepenid()->getNombre();
        $usuarioDepenId   = $usrLogin->getDepenid()->getId();
        $legajo           = '';
        $usuarioTipoDoc   = $usrLogin->getTipodoc();
        $usuarioDoc       = $usrLogin->getNumerodoc();
        $usuarioJerarquia = $usrLogin->getJerarquia();
        
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
            "usuarioJerarquia" => $usuarioJerarquia,
            "latitud"          => LATITUD_DEFAULT,
            "longitud"         => LONGITUD_DEFAULT
        );
        
        $interpolPersona = new InterpolRepository($this->container, $data, null);

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
            
            $interpolPersona = new InterpolRepository($this->container, $data, null);
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
    
    
    /**
     * Lists all AuditoriaPersonaObservada entities.
     *
     */
    public function auditoriaAction(Request $request, $id, $page = 1)
    {
        $adminCnrt = false;
        foreach($this->getUser()->getPerfilid() as $perfil){
            foreach($perfil->getPermisoid() as $permiso){
                if($permiso->getId() == 125){
                    $adminCnrt = true;
                }
            }
        }
        
        if(!$adminCnrt || !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            throw new AccessDeniedHttpException('You cannot access this page!');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $txtUsuario  = $request->get( 'txtUsuario'   ) ? $request->get( 'txtUsuario') : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde') ? $request->get( 'txtFechaDesde') : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta') ? $request->get( 'txtFechaHasta') : ""  ;
        
        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
            $fDesde_A = "";
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
            $fHasta_A = "";
        }
        
        $filter = array(
            "fDesde"        => $fDesde_A,
            "fHasta"        => $fHasta_A,
            "txtUsuario"    => $txtUsuario
        );
        
        $cnrtPersona = $em->getRepository('ADMINAdminBundle:CnrtPersona')->find($id);
        $query = $em->getRepository('ADMINAdminBundle:CnrtPersona')->getAuditoria($id, $filter);
        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Fecha Y Hora;Usuario;Accion";
            
            foreach ($query->getResult() as $event) {
                $data = array(
                    $event->getId(),
                    $event->getFecAlta()->format('d/m/Y H:i:s'),
                    $event->getUsuAlta(),
                    utf8_decode($event->getAccion())
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="auditoria_cnrt_interpol_'.date('Y-m-d').'.csv"');
            
            ob_clean();
            
            return $response;
        }
        
        if($request->get('page')!=''){
            $page = $request->get('page');
        }
        
        $entities = new Pagerfanta(new DoctrineORMAdapter($query));
        $entities->setMaxPerpage(50);
        $entities->setCurrentPage($page);
        
        return $this->render('ADMINAdminBundle:CnrtPersona:indexAuditoria.html.twig', array(
            'cnrtPersona' => $cnrtPersona,
            'entities' => $entities
        ));
    }
}
?>