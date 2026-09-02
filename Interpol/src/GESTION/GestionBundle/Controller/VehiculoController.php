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
use GESTION\GestionBundle\Repository\InterpolRepository;
use GESTION\GestionBundle\Repository\Diccionario;
/**
 * Vehiculo controller.
 *
 */
class VehiculoController extends Controller {

    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    //Array de Tipos de Búsquedas 

    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        
        return $this->render('GESTIONGestionBundle:Vehiculo:index.html.twig', array(
            'usuario'=>$usuario
        ));
    }
    
    /**
     * Muestra consulta realizada
     */
    public function showAction(Request $request, $id = 0) {
        
        $em = $this->getDoctrine()->getManager();
        
        $vin       = $request->get("txtVin");
        $dominio   = $request->get('txtDominio');
        $nroMotor  = $request->get('txtNroMotor');
        $nroChasis = $request->get('txtNroChasis');
        $tipoCons  = $request->get('lstTipoCons');
        
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
            "vin"              => $vin,
            "dominio"          => $dominio,
            "nroMotor"         => $nroMotor,
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
        
        $user->setCantVehiculo($user->getCantVehiculo()+1);
        $em->flush();
        
        $interpolVehiculo = new InterpolRepository($this->container, $data, $this->container->get('session'));
        
        $smvResponse = $interpolVehiculo->getSMV();
        
        $smvs = [];
        if( isset($smvResponse->datas->search->origin->vehicle) ){
        	$smvs = $smvResponse->datas->search->origin->vehicle;
        }
        
        if( !isset($smvs) || (isset($smvResponse->message) && $smvResponse->message!="")){
            //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIÓN.
        	$smvs =["ERROR"=>$smvResponse->message];
        }
        
        return $this->render( 'GESTIONGestionBundle:Vehiculo:show.html.twig', array( 'smvs' => $smvs ) );
    }
    
    public function showdetailsAction(Request $request, $id = 0) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity   = $request->get("txtEntity");
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
        
        $user->setCantVehiculo($user->getCantVehiculo()+1);
        $em->flush();
        
        $interpolVehiculo = new InterpolRepository($this->container, $data, $this->container->get('session'));
        $smvResponse = $interpolVehiculo->getSMVDETAILS( $entity );
        $smvDetails = [];
        
        if( isset($smvResponse->datas) ){
            $smvDetails = $smvResponse->datas->origin->vehicle;
        }
        
        if($idConsultaLoteDetalle != ''){ // Para la consulta por lotes graba la respuesta para futuras reconsultas
        	$em = $this->getDoctrine()->getManager();
        	$loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->find($idConsultaLoteDetalle);
        	$loteDetalle->setRespuestaDetails(json_encode($smvDetails));
                $loteDetalle->setEntityId($entity);
        	$em->flush();
        }
        
        $detalle = $detalle = json_decode( json_encode( $smvDetails ), true );
        
        if( !isset($smvDetails) || (isset($smvResponse->message) && $smvResponse->message!="")){
        	$detalle =["ERROR"=>$smvResponse->message];
        }
        
        return $this->render('GESTIONGestionBundle:Vehiculo:showdetails.html.twig', array(
             'smvDetails'  => $detalle,
             'encabezados' => $interpolVehiculo->encabezados,
             'paises'     => $interpolVehiculo->paises,
             'coloresId'  => $interpolVehiculo->coloresId
            ) 
        );
    }   //  public function showdetailsAction(Request $request, $id = 0) {
    
    
}
?>