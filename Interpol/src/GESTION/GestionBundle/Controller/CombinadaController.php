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
 * Combinada controller.
 *
 */
class CombinadaController extends Controller {

    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    //Array de Tipos de Búsquedas 

    public function indexAction(Request $request)
    {
        $data = (object) array();
        $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));
        
        $usuario = $this->getUser();
        
        return $this->render('GESTIONGestionBundle:Combinada:index.html.twig', array(
            'paises' => $interpol->paises, 
            'documentos' => $interpol->documentos, 
            'usuario'=>$usuario         
        ));
    }
    
    /**
     * Muestra consulta realizada
     */
    public function showAction(Request $request, $id = 0) {
        $nombre   = $request->get("txtNombre");
        $apellido = $request->get('txtApellido');
        $fechaNac = $request->get('txtFechaNac');
        $tipoCons = $request->get('lstTipoConsPer');
        
        if(!isset($nombre)){
            $nombre = "";
        }
        if(!isset($apellido)){
            $apellido = "";
        }
        
        $user  = $this->getUser();
        $user->setCantCombinada($user->getCantCombinada()+1);
        $this->getDoctrine()->getManager()->flush();
        
        if(trim($apellido) != ''){
            
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
            
            $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));
            
            if($tipoCons=="AD"){
            	
            	$nominalsResponse = $interpolPersona->getNOMINALS();
            	
            	if(isset($nominalsResponse->datas)){
            		$n = $nominalsResponse->datas->search->origin->nominal;
            		
                    if(isset($n->entityId) || count($n)==1){
                        $nominals[0]=$n;
                    }else{
                        $nominals=$n;
                    }
                }else{
                    $nominals =[];
                }
            }else{
                //  EN CASO DE NOMINALSEXACT RETORNO UN ARRAY PARA LA POSICIÓN 0, SOLO PARA QUE EL RESTO DE LA INTERFAZ SE COMPORTE EXACTAMENTE IGUAL AL RESULTADO DE NOMINALS.
                $respuestaNominals = $interpolPersona->getNOMINALSEXACT();
                
                if(isset($respuestaNominals->datas->search->origin->nominal)){
                    $nominals[0] = $respuestaNominals->datas->search->origin->nominal;
                    if(is_array($nominals[0])&&count($nominals[0])==0){
                        $nominals = [];
                    }
                }else{
                    $nominals = [];
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
                                //echo "forname Vacío.";
                                $nominals[$claveFila]->forename = "NO DATA";
                            }
                            foreach ((Array)$valor as $valorclave => $valorvalor){
                                //var_dump($valorvalor);
                                //echo "claveclave: ".$valorclave."<br>";
                                //echo "valorValor:".$valorvalor;
                                $nominals[$claveFila]->forename .= $valorvalor;
                            }
                        }
                    }   //  if($clave=="forename"){
                }
            }
                        
            foreach ($nominals as $n){
                if(isset($n->date_of_birth)){
                    $n->date_of_birth = substr($n->date_of_birth,6,2).'/'.substr($n->date_of_birth,4,2).'/'.substr($n->date_of_birth,0,4);
                }
            }
        }else{
            $nominals = [];
        }
        
        $tipoDoc  = $request->get("lstTipoDoc");
        $nroDoc   = $request->get('txtNumeroDoc');
        $pais     = $request->get('lstPais');
        $tipoCons = $request->get('lstTipoConsDoc');
        
        if(trim($nroDoc) != ''){
        
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
            
            $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));
            
            $sltdResponse = $interpol->getSLTD();
            
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
            
        }else{
            $documento = [];
        }        
        
        $vin       = $request->get("txtVin");
        $dominio   = $request->get('txtDominio');
        $nroMotor  = $request->get('txtNroMotor');
        $nroChasis = $request->get('txtNroChasis');
        $tipoCons  = $request->get('lstTipoConsVeh');
        
        if(trim($vin)!=''||trim($dominio)!=''||trim($nroMotor)!=''){
        
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
	        
	        $interpolVehiculo = new InterpolRepository($this->container, $data, $this->container->get('session'));
	        
	        $smvResponse = $interpolVehiculo->getSMV();
	        $smvs = [];
	        if( isset($smvResponse->datas->search->origin->vehicle) ){
	        	$smvs = $smvResponse->datas->search->origin->vehicle;
	        }
	        
        }else{
        	$smvs = [];
        }
		
        if(!isset($nominals)){
            if( isset($nominalsResponse->message) && $nominalsResponse->message != "Sin resultados"){
                //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIóN.
                $nominals =["ERROR"=>$nominalsResponse->message];
            }else{
                $nominals =["ERROR"=>"Error desde el servidor"];
            }
        }
        
        if(!isset($documento)){
            if(isset($sltdResponse->message) && $sltdResponse->message != "Sin resultados"){
                //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIóN.
                $documento =["ERROR"=>$sltdResponse->message];
            }else{
                $documento =["ERROR"=>"Error desde el servidor"];
            }
            
        }elseif(is_string($sltdResponse) && $sltdResponse!="Sin resultados"){
            $documento =["ERROR"=>$sltdResponse];
        }
        
        if(!isset($smvs)){
            if(isset($smvResponse->message) && $smvResponse->message != "Sin resultados"){
                //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIÓN.
                $smvs =["ERROR"=>$smvResponse->message];
            }else{
                $documento =["ERROR"=>"Error desde el servidor"];
            }
        }
        
        
        $paises=isset($interpolPersona)?$interpolPersona->paises:(isset($interpol)?$interpol->paises:(isset($interpolVehiculo)?$interpolVehiculo->paises:array()));
        $documentos=isset($interpolPersona)?$interpolPersona->documentos:(isset($interpol)?$interpol->documentos:(isset($interpolVehiculo)?$interpolVehiculo->documentos:array()));
        
        return $this->render( 'GESTIONGestionBundle:Combinada:show.html.twig', array(
            'nominals' => $nominals,
            'documento' => $documento,
            'smvs' => $smvs,
            'paises' => $paises,
            'documentos' => $documentos
            ));
    }
    
}
?>