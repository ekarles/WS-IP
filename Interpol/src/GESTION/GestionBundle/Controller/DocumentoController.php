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
 * Documento controller.
 *
 */
class DocumentoController extends Controller {
    
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    
    public function indexAction(Request $request)
    {
        $data = (object) array();
        $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));
        $usuario = $this->getUser();
        
        return $this->render('GESTIONGestionBundle:Documento:index.html.twig', array(
            'paises' => $interpol->paises,
            'documentos' => $interpol->documentos,
            'usuario' => $usuario           
        ));
    }
    
    /**
     * Muestra consulta realizada
     */
    public function showAction(Request $request, $id = 0) {

        $em = $this->getDoctrine()->getManager();
        
        $tipoDoc  = $request->get("lstTipoDoc");
        $nroDoc   = $request->get('txtNumeroDoc');
        $pais     = $request->get('lstPais');
        $tipoCons = $request->get('lstTipoCons');
        
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
            "nroDoc"           => $nroDoc,
            "pais"             => $pais,
            "tipoDoc"          => $tipoDoc,
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
        
        $user->setCantDocumento($user->getCantDocumento()+1);
        $em->flush();
        
        $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));
        
        $sltdResponse = $interpol->getSLTD();
        
        $documento = [];
        if(isset($sltdResponse->datas->search->origin->document)){
            $docs = $sltdResponse->datas->search->origin->document;
            //  INCORPORAMOS EL PAÍS DEL DOCUMENTO
            $paises = new Diccionario();
            
            foreach ($docs as $doc) {
                //  SI SOLO VINO UN REGISTRO ME ASEGURO QUE RETORNE EL MISMO FORMATO QUE SI TRATARA UN ARRAY
                if(!is_array($docs)){
                    $doc = $docs;
                }
                //  SI SOLO VINO UN REGISTRO ME ASEGURO QUE RETORNE EL MISMO FORMATO QUE SI TRATARA UN ARRAY
                
                $doc->country_description = $paises->getPais($doc->country_of_issuance_id)["DESCRIPCION"];
            }
                        
            if(is_array($docs) && count($docs)>1){
                $documento = $docs;
        	}else{
        	    $documento[0] = $docs;
        	}
        }
        
        $usuario = $this->getUser();
        
        if(!isset($documento) || ( isset($sltdResponse->message) && $sltdResponse->message != "Sin resultados")){
            //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACI�N.
        	$documento =["ERROR"=>$sltdResponse->message];
        }elseif(is_string($sltdResponse)&&$sltdResponse!="Sin resultados"){
            $documento =["ERROR"=>$sltdResponse];
        }
        
        return $this->render( 'GESTIONGestionBundle:Documento:show.html.twig', 
            array( 'documento' => $documento,
                   'paises' => $interpol->paises,
                   'documentos' => $interpol->documentos));
    }
    
    public function showdetailsAction(Request $request, $id = 0) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity   = $request->get("txtEntity");
        $tipoDoc  = $request->get("lstTipoDoc");
        $nroDoc   = $request->get('txtNumeroDoc');
        $pais     = $request->get('lstPais');
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
            "sltdId"           => $entity,
            "nroDoc"           => $nroDoc,
            "pais"             => $pais,
            "tipoDoc"          => $tipoDoc,
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
        
        $user->setCantDocumento($user->getCantDocumento()+1);
        $em->flush();
        
        $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));
        
        $sltdResponse = $interpol->getSLTDDETAILS( $entity );
        
        $documentDetails = [];
        if(isset($sltdResponse->datas->origin->document)){
        	$documentDetails = $sltdResponse->datas->origin->document;
        }
        
        if($idConsultaLoteDetalle != ''){ // Para la consulta por lotes graba la respuesta para futuras reconsultas
            $em = $this->getDoctrine()->getManager();
            $loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->find($idConsultaLoteDetalle);
            $loteDetalle->setRespuestaDetails(json_encode($documentDetails));
            $loteDetalle->setEntityId($entity);
            $em->flush();
        }
        $document = json_decode( json_encode( $documentDetails ), true );
        $usuario = $this->getUser();
        
        if( !isset($documentDetails) || ( isset($sltdResponse->message) && $sltdResponse->message!="Sin resultados" ) ){
            $document =["ERROR"=>$sltdResponse->message];
        }
        
        return $this->render('GESTIONGestionBundle:Documento:showdetails.html.twig', array( 
            'documentDetails' => $document, 
            'titulos'         => $interpol->titulos  ,
            'encabezados'     => $interpol->encabezados,
            'paises'          => $interpol->paises     ,
        ) );
        
    }   //  public function showdetailsAction(Request $request, $id = 0) {
    
}
?>