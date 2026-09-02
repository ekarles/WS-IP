<?php

namespace ADMIN\AdminBundle\Controller;

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
use ADMIN\AdminBundle\Entity\Alarma;
use ADMIN\AdminBundle\Entity\Alarmadetalle;
use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;
use DateTime;
use GESTION\GestionBundle\Repository\Diccionario;
use GESTION\GestionBundle\Repository\InterpolRepository;

include_once ('/apache/includes/ambiente.php');

class AlarmaController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    

    public function indexAction(Request $request, $page = 1){
        
        $em = $this->getDoctrine()->getManager();
        $busqueda    = $request->get( 'busqueda'     ) ? $request->get( 'busqueda') : ""  ;
        $txtUsuario  = $request->get( 'txtUsuario'   ) ? $request->get( 'txtUsuario') : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde') ? $request->get( 'txtFechaDesde') : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta') ? $request->get( 'txtFechaHasta') : ""  ;
        $txtDependencia   = $request->get( 'txtDependencia') ? $request->get( 'txtDependencia') : ""  ;
        $lstTipoAlar = $request->get( 'lstTipoAlar' );
        $lstModo     = $request->get( 'lstModo'      ) ? $request->get( 'lstModo') : ""  ;
        if($fDesde!=''){
                        
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
        	$date = new DateTime();
        	$date->modify("+1 day");
        	$fDesde_A = $date->format("d/m/Y H:i:s");
        	$request->attributes->set( 'txtFechaDesde',$date->format("Y-m-d"));
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
        	$date = new DateTime();
        	$fHasta_A = $date->format("d/m/Y H:i:s");
        	$request->attributes->set( 'txtFechaHasta',$date->format("Y-m-d"));
        }
        $chkError = ( $request->get( 'chkError' ) . "" );
        
        
        $filter = array(
            "fDesde"        => $fDesde_A,
            "fHasta"        => $fHasta_A,
            "txtUsuario"    => $txtUsuario,
            "txtDependencia"=> $txtDependencia,
            "lstModo"       => $lstModo,
            "lstTipoAlar"   => $lstTipoAlar
        );
        
        $query = $em->getRepository('ADMINAdminBundle:Alarma')->getByFilter($filter);
        
        if($request->get('page')!=''){
        	$page = $request->get('page');
        }
        
        $paginador = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginador->setMaxPerpage(30);
        $paginador->setCurrentPage($page);
        
        // Query Ultimas 24 hs
        if(AMBIENTE=='PRODUCCION'){
        	$date = new DateTime();
        	$date->modify("-1 day");
        }else{
        	$date = new DateTime('2018-10-10 00:00:00');
        }
        $fDesde_A = $date->format("d/m/Y H:i:s");
        $date->modify("+1 day");
        $fHasta_A = $date->format("d/m/Y H:i:s");
        
        $filter24 = array(
        		"fDesde"     => $fDesde_A,
        		"fHasta"     => $fHasta_A,
                "lstTipoAlar"   => "",
                "pendiente"  => true
        );
        
        $ultimas24 = $em->getRepository('ADMINAdminBundle:Alarma')->getByFilter($filter24)->getResult();

        $alarmastipo = $em->getRepository('ADMINAdminBundle:Alarmatipo')->getAlarmasTipos();
        
        $usuario = $this->getUser();
        
        return $this->render(
            'ADMINAdminBundle:Alarma:index.html.twig', 
            array( 'alarmas'=> $paginador,
            	   'ultimas24' => $ultimas24,
                   'usuario'=>$usuario,
            	   'alarmastipo' => $alarmastipo
            )
        );
    }

    
    public function showAction(Request $request, $id = null){
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$alarma = $em->getRepository('ADMINAdminBundle:Alarma')->find($id);
    	
    	// Genero un registro de nuevo inicio en alarmadetalle si no existe uno previo con el mismo usuario
    	$alarmaDetalleIni = $em->getRepository('ADMINAdminBundle:Alarmadetalle')->findBy(['alarid'=>$id,'usuarioid'=>$this->getUser()]);
    	
    	if(sizeof($alarmaDetalleIni)==0){
    		$alarmaDetalleTipo = $em->getRepository('ADMINAdminBundle:Alarmadetalletipo')->find(2);
    		
    		$alarmaDetalle = new Alarmadetalle();
    		$alarmaDetalle->setAlarid($alarma);
    		$alarmaDetalle->setFecha(new DateTime('NOW'));
    		$alarmaDetalle->setAldtid($alarmaDetalleTipo);
    		$alarmaDetalle->setUsuarioid($this->getUser());
    	
    		$em->persist($alarmaDetalle);
    		$em->flush();
    	}
    	
    	
    	$respuesta = $alarma->getRespuesta();
    	
    	$ini = (strpos($respuesta, "<RESPUESTA>")+11);
    	$fin = strpos($respuesta, "</RESPUESTA>");
    	
    	$respuesta = substr($respuesta, $ini, ($fin-$ini));
    	
    	$respuesta = json_decode(json_encode(simplexml_load_string($respuesta, 'SimpleXMLElement', LIBXML_NOCDATA, 'i', true)),true);
    	
    	$diccionario = new Diccionario();
    	
    	if(isset($respuesta['datas']['search']['origin'])){
    		$respuesta = $respuesta['datas']['search']['origin'];
    	}elseif($respuesta['datas']['origin']){
    		$respuesta = $respuesta['datas']['origin'];
    	}
    	
    	if(isset($respuesta['nominal']['file'])){
    		if(isset($respuesta['nominal']['file'][0])){
    			foreach($respuesta['nominal']['file'] as $file){
    				$file['path'] = addslashes($file['path']);
    			}
    		}else{
    			$respuesta['nominal']['file']['path'] = addslashes($respuesta['nominal']['file']['path']);
    		}
    	}
    	
    	$dependencia = null;
    	
    	if(!empty($alarma->getUsuariodepen())){
    		$dependencia = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->findOneBy(["nombre"=>$alarma->getUsuariodepen()]);
    	}
    	
    	$alarmaDetalle = $em->getRepository('ADMINAdminBundle:Alarmadetalle')->findBy(['alarid'=>$id],['fecha'=>'asc']);
    	
    	$alarmastipo = $em->getRepository('ADMINAdminBundle:Alarmatipo')->findAll();
    	$tiposdelito = $em->getRepository('ADMINAdminBundle:Tipodelito')->findAll();
    	
    	return $this->render('ADMINAdminBundle:Alarma:show.html.twig',
    					array( 'alarma'=> $alarma,
    						   'dependencia'=>$dependencia,
    						   'paises'=> $diccionario->getPaises(),
    						   'respuesta'=>$respuesta,
    						   'alarmaDetalle'=>$alarmaDetalle,
    						   'alarmastipo' => $alarmastipo,
    						   'tiposdelito' => $tiposdelito
    						)
    					);
    }

    
    public function trabajarAction(Request $request,$id = null){
    	
    	$opcion = $request->get('opcion');
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$alarma = $em->getRepository('ADMINAdminBundle:Alarma')->find($id);
    	$estadoActual = $alarma->getEstadoid();
    	
    	$alarmaDetalle = new Alarmadetalle();
    	$alarmaDetalle->setAlarid($alarma);
    	$alarmaDetalle->setFecha(new DateTime('NOW'));
    	$alarmaDetalle->setUsuarioid($this->getUser());
    
    	switch($opcion){
    		case 'inicio':
    			$tipodetalle = $em->getRepository('ADMINAdminBundle:Alarmadetalletipo')->find(6);
    			$alarmaDetalle->setAldtid($tipodetalle);
    			$alarmaDetalle->setDescripcion('Estado Anterior: ('.$estadoActual->getEstadoid().') '.$estadoActual->getNombre());
    			$estado = $em->getRepository('ADMINAdminBundle:Alarmaestado')->find(3);
    			$alarma->setEstadoid($estado);
    			break;
    		case 'cerrar':
    			$tipodetalle = $em->getRepository('ADMINAdminBundle:Alarmadetalletipo')->find(3);
    			$alarmaDetalle->setAldtid($tipodetalle);
    			$alarmaDetalle->setDescripcion('Estado Anterior: ('.$estadoActual->getEstadoid().') '.$estadoActual->getNombre());
    			$estado = $em->getRepository('ADMINAdminBundle:Alarmaestado')->find(2);
    			$alarma->setEstadoid($estado);
    			break;
    		case 'reabrir':
    			$tipodetalle = $em->getRepository('ADMINAdminBundle:Alarmadetalletipo')->find(5);
    			$alarmaDetalle->setAldtid($tipodetalle);
    			$alarmaDetalle->setDescripcion('Estado Anterior: ('.$estadoActual->getEstadoid().') '.$estadoActual->getNombre());
    			$estado = $em->getRepository('ADMINAdminBundle:Alarmaestado')->find(1);
    			$alarma->setEstadoid($estado);
    			break;
    		case 'comentario':
    			$tipodetalle = $em->getRepository('ADMINAdminBundle:Alarmadetalletipo')->find(1);
    			$alarmaDetalle->setAldtid($tipodetalle);
    			$alarmaDetalle->setDescripcion($request->get('descripcion'));
    			break;
    		case 'tipificacion':
    			$desc = '';
    			$tipodetalle = null;
    			
    			if($request->get('alarmaTipoId')!=$alarma->getAltiid()->getAltiid()){
    				$altiidActual = $alarma->getAltiid();
    				$altiid = $em->getRepository('ADMINAdminBundle:Alarmatipo')->find($request->get('alarmaTipoId'));
    				$tipodetalle = $em->getRepository('ADMINAdminBundle:Alarmadetalletipo')->find(4);
    				$alarma->setAltiid($altiid);
    				$desc .= 'Anterior: ('.$altiidActual->getAltiid().') '.$altiidActual->getNombre().' - Nuevo: ('.$altiid->getAltiid().') '.$altiid->getNombre();
    			}
    			
    			$tipodelitoidActual = $alarma->getTipodelitoid();
    			$tipodelitoid = $em->getRepository('ADMINAdminBundle:Tipodelito')->find($request->get('tipoDelitoId'));
    			
    			if($tipodelitoid!=$tipodelitoidActual){
    				
    				$alarma->setTipodelitoid($tipodelitoid);
    				$tipodetalle = $em->getRepository('ADMINAdminBundle:Alarmadetalletipo')->find(47);
    				
    				if($tipodelitoidActual!==null){
    				    $desc .= ' Tipo Delito Anterior: ('.$tipodelitoidActual->getId().') '.$tipodelitoidActual->getNombre();
    				}else{
    				    $desc .= ' Tipo Delito Anterior: ';
    				}
    				
    				if($tipodelitoid!==null){
    				    $desc .= ' - Nuevo: ('.$tipodelitoid->getId().') '.$tipodelitoid->getNombre();
    				}else{
    				    $desc .= ' - Nuevo: ';
    				}
    			}
    			
    			if($tipodetalle==null){
    			    return new Response(json_encode(['fecha'=>date('d-m-Y H:i:s'),
    			        'tipo'=>'TIPIFICACI&Oacute;N',
    			        'usuario'=>$this->getUser()->getUsuario(),
    			        'descripcion'=>'No se realizaron cambios',
    			        'estado'=>$alarma->getEstadoid()->getNombre()
    			    ]));
    			}
    			
    			$alarmaDetalle->setAldtid($tipodetalle);
    			$alarmaDetalle->setDescripcion(trim($desc));
    			break;
    	}
    	
    	$em->persist($alarmaDetalle);
    	$em->flush();
    	
    	return new Response(json_encode(['fecha'=>$alarmaDetalle->getFecha()->format('d-m-Y H:i:s'),
    									 'tipo'=>$alarmaDetalle->getAldtid()->getNombre(),
    									 'usuario'=>$alarmaDetalle->getUsuarioid()->getUsuario(),
    									 'descripcion'=>$alarmaDetalle->getDescripcion(),
    									 'estado'=>$alarma->getEstadoid()->getNombre()	
    	]));
    }
    
    public function buscarImagenAction(Request $request){
    	
    	$error=null;
    	$imagenBase64 = null;
    	
    	
    	$entityId = $request->get('entityId');
    	$path = $request->get('path');
    	$tipoCons         = 'AD';
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
    	
    	$parametros = (object) array(
    			"entityId"         => $entityId,
    			"path"			   => $path,	
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
    	
    	
    	$interpolRepo = new InterpolRepository($this->container, $parametros, $this->container->get('session'));
    	
    	if($parametros->path!=''){
    		$nominalImage    = $interpolRepo->getNOMINALSIMAGE ( $parametros );
    		
    		if(isset($nominalImage->imagen) && $nominalImage->imagen!=false){
    			$img = '';
    			$array = explode(",", $nominalImage->imagen);
    			
    			for ($a = 0; $a < count($array); $a++) {
    				$img .= chr(intval($array[$a]));
    			}
    			if(strlen($img)>0){
    				$image = @imagecreatefromstring($img);
    					
    				ob_start();
    				@imagejpeg($image);
    				$contents = @ob_get_contents();
    				ob_end_clean();
    				
    				$imagenBase64 = "data:image/jpeg;base64," . base64_encode($contents);
    				
    			}else{
    				$error = 'Error al buscar la imagen';
    			}
    		}else{
    			$error = 'Error al buscar la imagen';
    		}
    	}
    	
    	return new Response(json_encode(['imagenBase64'=>$imagenBase64,
    									 'error'=>$error
    	]));
    }
    
    
    public function estadisticasAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        
        $fDesde      = $request->get( 'txtFechaDesde') ? $request->get( 'txtFechaDesde') : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta') ? $request->get( 'txtFechaHasta') : ""  ;
        $txtDependencia   = $request->get( 'txtDependencia') ? $request->get( 'txtDependencia') : ""  ;
        $lstTipoAlar = $request->get( 'lstTipoAlar' );

        
        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
            $date = new DateTime();
            $date->modify("-1 year");
            $fDesde_A = $date->format("d/m/Y H:i:s");
            $request->attributes->set( 'txtFechaDesde',$date->format("Y-m-d"));
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
            $date = new DateTime();
            $fHasta_A = $date->format("d/m/Y H:i:s");
            $request->attributes->set( 'txtFechaHasta',$date->format("Y-m-d"));
        }
        
        $filter = array(
            "fDesde"        => $fDesde_A,
            "fHasta"        => $fHasta_A,
            "txtDependencia"=> $txtDependencia,
            "lstTipoAlar"   => $lstTipoAlar
        );
        
        $totalesAlarmas = $em->getRepository('ADMINAdminBundle:Alarma')->getTotalesXTipo($filter);
        
        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Tipo de alarma;Cantidad Generada";
            
            foreach ($totalesAlarmas as $event) {
                $data = array(
                    $event['tipo'],
                    $event['cant']
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="totales_alarmas_por_tipo_export_'.date('Y-m-d').'.csv"');
            
            ob_clean();
            
            return $response;
        }
        
        
        
        
        $alarmastipo = $em->getRepository('ADMINAdminBundle:Alarmatipo')->getAlarmasTipos();
        
        return $this->render(
            'ADMINAdminBundle:Alarma:estadisticas.html.twig',
                array(  'totalesAlarmas'=> $totalesAlarmas,
                        'alarmastipo' => $alarmastipo
                  )
            );
    }
}

?>