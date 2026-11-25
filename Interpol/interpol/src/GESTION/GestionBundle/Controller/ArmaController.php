<?php
namespace GESTION\GestionBundle\Controller;
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
use GESTION\GestionBundle\Repository\InterpolRepositoryiArms;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalle;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalleRepository;

/**
 * Persona controller.
 *
 */
class ArmaController extends Controller {

    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        return $this->render('GESTIONGestionBundle:Arma:index.html.twig', array(
            'usuario'=>$usuario
        ));
    }
    
    /**
    * Muestra consulta realizada
    */
    public function showAction(Request $request, $id = 0) {
        
        /*
         {
         "sistema":"IARMS",
         "usuario":"PFA",
         "pass":"IARMS2022PFA",
         "usuarioApellido":"USER_APE",
         "usuarioNombre":"USER_NOM",
         "usuarioTipoDoc":"DNI",
         "usuarioDoc":"11111111",
         "usuarioDepen":"USER_DEPEN",
         "usuarioJerarquia":"USER_JERARQUIA",
         "usuarioDepenId":"1603",
         "usuarioIp":"1.1.1.1",
         "latitud":"-34.614484",	
         "longitud":"-58.388816",
         "token":"PFA IARMS WS V.1",
         "origen":"INTERPOL",
         "nroSerie":"123*", 
         "marca":"", 
         "modelo":"18c", 
         "calibre":"", 
         "fabricante":"", 
         "tipo":"", 
         "officialRecordId":"", 
         "fechaDesde":"", 
         "fechaHasta":""
         }
         */
        
        $em = $this->getDoctrine()->getManager();
        
        $nroSerie   = $request->get("txtSN"        );
        $modelo     = $request->get('txtModelo'    );
        $calibre    = $request->get('txtCalibre'   );
        $marca      = $request->get('txtMarca'     );
        $fabricante = $request->get('txtFabricante');
        $tipo       = $request->get('txtTipo'      );
        $fecDesde   = $request->get('dtfechaDesde' );
        $fecHasta   = $request->get('dtFechaHasta' );
        
        $user             = $this->getUser();
        $usuario          = $user->getUsuario();
        $usuarioIp        = $this->container->get('session')->get('ip');
        $usuarioApellido  = $user->getApellido();
        $usuarioNombre    = $user->getNombre();
        $usuarioDepen     = $user->getDepenid()->getNombre();
        $usuarioDepenId   = $user->getDepenid()->getCodigo();
        $legajo           = "";
        $usuarioTipoDoc   = $user->getTipodoc();
        $usuarioDoc       = $user->getNumerodoc();
        $usuarioJerarquia = $user->getJerarquia();
        
        $data = (object) array(
            "nroSerie"         => $nroSerie,
            "marca"            => $marca,
            "modelo"           => $modelo,
            "calibre"          => $calibre,
            "fabricante"       => $fabricante,
            "tipo"             => $tipo,
            "fechaDesde"       => $fecDesde,
            "fechaHasta"       => $fecHasta,
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
        
        //$user->setCantPersona($user->getCantPersona()+1);
        $em->flush();
        
        $interpolArmas = new InterpolRepositoryiArms($this->container, $data, $this->container->get('session'));
                
        $respuesta = $interpolArmas->getIARMS($data);
        	
        echo "<pre>";
        //print_r($data);
        echo "Respuesta:<br>";
        print_r( $respuesta );
        echo "</pre>";
        /*
        	if(isset($respuestaNominals->datas)){
        		$n = $respuestaNominals->datas->search->origin->nominal;
                if(is_array($n) && count($n)>1){
                    $nominals = $n;
                }else{
                    $nominals[0] = $n;
                }
            }else{
                $nominals =[];
            }
        
        foreach ($nominals as $n){
            if(isset($n->date_of_birth)){
                $n->date_of_birth = substr($n->date_of_birth,6,2).'/'.substr($n->date_of_birth,4,2).'/'.substr($n->date_of_birth,0,4);
            }
        }
        
        //  HACEMOS ESTE TRATAMIENTO PORQUE PODRÍA PASAR QUE EL CAMPO FORENAME VENGA CON UN OBJETO VACÍO EN LUGAR DE UN STRING
        //  ESTA SITUACIÓN HACE QUE NO SE REALICE EL RENDER DE LA PÁGINA, POR LO TANTO SOLO CAMBIAMOS EL OBJETO VACÍO POR EL
        //  VALOR "NO DATA", CONTEMPLANDO QUE ADEMÁS DE ESTE CASO PUDIERA INFORMARSE UN OBJETO CON NOMBRES.
        foreach ($nominals as $claveFila => $valorFila){
            foreach ($valorFila as $clave => $valor){
                if($clave=="forename"){
                    //echo "clave: ".$clave."<br>";
                    if (is_object($valor)){
                        if(!(Array)$valor){
                            //echo "forname VacÃ­o.";
                            $nominals[$claveFila]->forename = "NO DATA";
                        }
                        foreach ((Array)$valor as $valorclave => $valorvalor){
                            $nominals[$claveFila]->forename .= $valorvalor;
                        }
                    }
                }   //  if($clave=="forename"){
            }
        }
                
        //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIÓN.
        if(count($nominals)==0 && isset($respuestaNominals->message) && $respuestaNominals->message != 'Sin resultados'){
        	$nominals =["ERROR"=>$respuestaNominals->message];
        }

        return 
        */
        return $this->render( 'GESTIONGestionBundle:Arma:show.html.twig', array( 'armas' => $respuesta ) );;
    }
    
    public function showdetailsAction(Request $request, $id = 0) {
        /*
        $em = $this->getDoctrine()->getManager();
        
        $entity   = $request->get("txtEntity");
        $nombre   = $request->get("txtNombre");
        $apellido = $request->get('txtApellido');
        $fechaNac = $request->get('txtFechaNac');
        $tipoCons = $request->get('lstTipoCons');
        $idConsultaLoteDetalle = $request->get('idConsultaLoteDetalle');
        
        $user             = $this->getUser();
        $usuario          = $user->getUsuario();
        $usuarioIp        = $this->container->get('session')->get('ip');
        $usuarioApellido  = $user->getApellido();
        $usuarioNombre    = $user->getNombre();
        $usuarioDepen     = $user->getDepenid()->getNombre();
        $usuarioDepenId   = $user->getDepenid()->getCodigo();
        $legajo           = "";
        $usuarioTipoDoc   = $user->getTipodoc();
        $usuarioDoc       = $user->getNumerodoc();
        $usuarioJerarquia = $user->getJerarquia();
        
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
        
        $user->setCantPersona($user->getCantPersona()+1);
        $em->flush();
        
        $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));
        
        $respuestaNominals  = $interpolPersona->getNOMINALSDETAILS( $entity );
        
        $nominalDetails = [];
        if(isset($respuestaNominals->datas->origin->nominal))
        	$nominalDetails = $respuestaNominals->datas->origin->nominal;
        if($idConsultaLoteDetalle != ''){ // Para la consulta por lotes graba la respuesta para futuras reconsultas
            $em = $this->getDoctrine()->getManager();
            $loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->find($idConsultaLoteDetalle);        
            $loteDetalle->setRespuestaDetails(json_encode($nominalDetails));
            $loteDetalle->setEntityId($entity);
            $em->flush();
        }   
        
        $detalle = json_decode( json_encode( $nominalDetails ), true );
        
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
        
        
        $usuario = $this->getUser();
        
        //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIÓN.
        if(!isset($nominalDetails) || ( isset( $respuestaNominals->message ) && $respuestaNominals->message != "Sin resultados" ) ){
        	$detalle =["ERROR"=>$respuestaNominals->message];
        }

        */
        return $this->render('GESTIONGestionBundle:Arma:showdetails.html.twig', array( 
            'nominalDetails' => $detalle, 
        	'arrayImg'       => $arrayImg, 
            'titulos'        => $interpolPersona->titulos,
            'encabezados'    => $interpolPersona->encabezados,
            'colores'        => $interpolPersona->colores,
            'paises'         => $interpolPersona->paises,
            'Offences_Code'  => $interpolPersona->Offences_Code
            ));
    }   //  public function showdetailsAction(Request $request, $id = 0) {
    
}
?>