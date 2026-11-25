<?php
namespace ADMIN\AdminBundle\Controller;
ini_set('memory_limit', '512M');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use DateTime;
use GESTION\GestionBundle\Repository\Menu;
use ADMIN\AdminBundle\Entity\LoteDocumento;
use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;
use GESTION\GestionBundle\Repository\Diccionario;

include_once ('/apache/includes/ambiente.php');

class LoteDocumentoController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    
    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        return $this->render('ADMINAdminBundle:LoteDocumento:index.html.twig', array('usuario'=>$usuario));
    }

    
    public function indexCargaMasivaAction(Request $request)
    {
        $usuario = $this->getUser();
        return $this->render('ADMINAdminBundle:LoteDocumento:indexCargaMasiva.html.twig', array('usuario'=>$usuario));
    }
    
    public function actualizarMensajeAction(Request $request){
        $retorno = ["estado"=>"OK", "mensaje"=>""];
        $em = $this->getDoctrine()->getManager();
                
        $parameters = array(
            "ID"        => $request->get("ID"),
            "mensajeRemoto"   => $request->get("mensajeRemoto"),
            "NroDoc"    => $request->get("NroDoc"),
            "itemid"    => $request->get("ITEMID"),
        	"UserId"	=> $this->getUser()->getId()
        );
        
        $EntityMan = $em->getRepository('ADMINAdminBundle:LoteDocumento')->updateLoteDocumentoById($parameters);
        return new JsonResponse($retorno);
    }  
    
    public function procesarCurlDocAction(Request $request){
        
        $dFecha = substr($request->get("fecharegistro"  ), 6, 4) . "" . substr($request->get("fecharegistro"  ), 3, 2) . "" . substr($request->get("fecharegistro" ), 0, 2);
        
        $usuario = $this->getUser();
        $TipoDocumento = "PAS";
        if( $request->get("TipoDoc") != "1"){
            $TipoDocumento = $request->get("TipoDoc");
        }
        
        $params=[
            "sistema"          =>"WISDM",
            "usuario"          =>"PFA",
            "pass"             =>"WISDM2020PFA",
            "token"            =>"PFA WISDM WS V.1",
            "origen"           =>"WISDM",
            "documento"        => $request->get("NroDoc"         ),
            "paisreg"          => $request->get("idnacionalidad" ),
            "tdocum"           => $TipoDocumento,
            "tipofraude"       => "STL",
            "tipoDenuncia"     => $request->get("tipodenuncia"   ),
            "numref"           => "",
            "numrefocn"        => "",
            "fecharobo"        => $dFecha,
            "fechaemi"         => $dFecha,
            "fechaexp"         => $dFecha,
            "paisrobo"         => "110",
            "infoad"           => "",
            "id"               => $request->get("Id"),
            "user"             =>$usuario->getUsuario(),
            "usuarioIp"        =>$this->container->get('request')->getClientIp(),
            "usuarioApellido"  =>$usuario->getApellido(),
            "usuarioNombre"    =>$usuario->getNombre(),
            "usuarioDepen"     =>$usuario->getDepenid()->getNombre(),
            "usuarioJerarquia" =>$usuario->getJerarquia(),
            "usuarioDepenId"   =>$usuario->getDepenid()->getCodigo()
        ];
        
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://' . HOST_WISDM . ':' . PORT_WISDM . '/BackEndWisdm/index.php/insertarActualizar',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode( $params ),
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));
            $response = curl_exec($curl);
            $error = curl_error($curl);
            $retorno = json_decode($response);
            curl_close($curl);
        return new JsonResponse( $retorno );
    }  
    
    public function consultaAction(Request $request){
        
        
        if($request->get("ID")==""){
            $salida = ["ID"       =>null,
                "nombre"          =>null,
                "apellido"        =>null,
                "otrosapellidos"  =>null,
                "otrosnombres"    =>null,
                "fechanacimiento" =>null,
                "sexo"            =>null,
                "tipodoc"         =>null,
                "numerodoc"       =>null,
                "idnacionalidad"  =>null,
                "idpaisemisor"    =>null,
                "tipodenuncia"    =>null,
                "accion"          =>null,
                "fecharegistro"   =>null,
                "idpersona"       =>null,
                "motivo"          =>null,
                "fecha"           =>null,
                "estado"          =>null,
                "descargado"      =>null,
                "fechahoradesc"   =>null,
                "usudesc"         =>null,
                "mensajeremoto"   =>null,
                "itemid"          =>null
            ];
        }else{
            $em = $this->getDoctrine()->getManager();
            $parameters = array(
                "ID"     => $request->get("ID")
            );
            
            $EntityMan = $em->getRepository('ADMINAdminBundle:LoteDocumento')->getById($parameters);
            
            $salida = [
                "ID"               =>$EntityMan[0]->getId(),
                "nombre"           =>$EntityMan[0]->getNombre(), 
                "apellido"         =>$EntityMan[0]->getApellido(),
                "otrosapellidos"   =>$EntityMan[0]->getOtrosApellidos(),
                "otrosnombres"     =>$EntityMan[0]->getOtrosNombres(),
                "fechanacimiento"  =>$EntityMan[0]->getFechaNacimiento(),
                "sexo"             =>$EntityMan[0]->getSexo(),
                "tipodoc"          =>$EntityMan[0]->getTipoDoc(),
                "numerodoc"        =>$EntityMan[0]->getNumeroDoc(),
                "idnacionalidad"   =>$EntityMan[0]->getIdNacionalidad(),
                "idpaisemisor"     =>$EntityMan[0]->getIdPaisEmisor(),
                "tipodenuncia"     =>$EntityMan[0]->getTipoDenuncia(),
                "accion"           =>$EntityMan[0]->getAccion(),
                "fecharegistro"    =>$EntityMan[0]->getFechaRegistro(),
                "idpersona"        =>$EntityMan[0]->getIdPersona(),
                "motivo"           =>$EntityMan[0]->getMotivo(),
                "fecha"            =>$EntityMan[0]->getFecha(),
                "estado"           =>$EntityMan[0]->getEstado(),
                "descargado"       =>$EntityMan[0]->getDescargado(),
                "fechahoradesc"    =>$EntityMan[0]->getFechaHoraDesc(),
                "usudesc"          =>$EntityMan[0]->getUsuDesc(),
                "mensajeremoto"    =>$EntityMan[0]->getMensajeRemoto(),
                "itemid"           =>$EntityMan[0]->getItemId()
                ];
        }
            
        return new JsonResponse( $salida );
    }
    
    
    public function showAction(Request $request, $page = 1){
        
        $em = $this->getDoctrine()->getManager();
        $txtNombre   = $request->get( 'txtNombre'     ) ? $request->get( 'txtNombre'     )                  : ""  ;
        $txtApellido = $request->get( 'txtApellido'   ) ? $request->get( 'txtApellido'   )                  : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde' ) ? $request->get( 'txtFechaDesde' )                  : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta' ) ? $request->get( 'txtFechaHasta' )                  : ""  ;
        $txtTipDoc   = $request->get( 'txtTipDoc'     ) ? $request->get( 'txtTipDoc'     )                  : ""  ;
        $txtNroDoc   = $request->get( 'txtNroDoc'     ) ? $request->get( 'txtNroDoc'     )                  : ""  ;
        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
            $fDesde_A = null;
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
            $fHasta_A = null;
        }
        $chkError = ( $request->get( 'chkError' ) . "" );
        
        $filter = array(
            "fDesde"     => $fDesde_A   ,
            "fHasta"     => $fHasta_A   ,
            "Nombre"     => $txtNombre  ,
            "Apellido"   => $txtApellido,
            "txtTipDoc"  => $txtTipDoc  ,
            "txtNroDoc"  => $txtNroDoc  ,
            "chkError"   => $chkError
        );
        
        $query = $em->getRepository('ADMINAdminBundle:LoteDocumento')->getByFilter($filter);
        $paginador = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginador->setMaxPerpage(20);
        $paginador->setCurrentPage($request->get('page'));

        $usuario = $this->getUser();

        return $this->render(
            'ADMINAdminBundle:LoteDocumento:show.html.twig', 
            array( 'loteDocumento'=> $paginador,
            		'cant' => $paginador->count(),
                	'usuario'=>$usuario)
        );
    }
    
    
    
    public function cargaMasivaProgresoAction(Request $request, $page = 1){
    	
    	$em = $this->getDoctrine()->getManager();
    	$txtNombre   = $request->get( 'txtNombre'     ) ? $request->get( 'txtNombre'     )                  : ""  ;
    	$txtApellido = $request->get( 'txtApellido'   ) ? $request->get( 'txtApellido'   )                  : ""  ;
    	$fDesde      = $request->get( 'txtFechaDesde' ) ? $request->get( 'txtFechaDesde' )                  : ""  ;
    	$fHasta      = $request->get( 'txtFechaHasta' ) ? $request->get( 'txtFechaHasta' )                  : ""  ;
    	$txtTipDoc   = $request->get( 'txtTipDoc'     ) ? $request->get( 'txtTipDoc'     )                  : ""  ;
    	$txtNroDoc   = $request->get( 'txtNroDoc'     ) ? $request->get( 'txtNroDoc'     )                  : ""  ;
    	if($fDesde!=''){
    		$fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
    	}else{
    		$fDesde_A = null;
    	}
    	if($fHasta!=''){
    		$fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
    	}else{
    		$fHasta_A = null;
    	}
    	$chkError = ( $request->get( 'chkError' ) . "" );
    	
    	$filter = array(
    			"fDesde"     => $fDesde_A   ,
    			"fHasta"     => $fHasta_A   ,
    			"Nombre"     => $txtNombre  ,
    			"Apellido"   => $txtApellido,
    			"txtTipDoc"  => $txtTipDoc  ,
    			"txtNroDoc"  => $txtNroDoc  ,
    			"chkError"   => $chkError   ,
    			"count" 	 => true
    	);
    	
    	$cant = $em->getRepository('ADMINAdminBundle:LoteDocumento')->getByFilter($filter);

    	return new Response($cant);
    }
    
    
    public function showCargaMasivaAction(Request $request, $page = 1){
        
        $em = $this->getDoctrine()->getManager();
        $txtNombre   = $request->get( 'txtNombre'     ) ? $request->get( 'txtNombre'     )                  : ""  ;
        $txtApellido = $request->get( 'txtApellido'   ) ? $request->get( 'txtApellido'   )                  : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde' ) ? $request->get( 'txtFechaDesde' )                  : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta' ) ? $request->get( 'txtFechaHasta' )                  : ""  ;
        $txtTipDoc   = $request->get( 'txtTipDoc'     ) ? $request->get( 'txtTipDoc'     )                  : ""  ;
        $txtNroDoc   = $request->get( 'txtNroDoc'     ) ? $request->get( 'txtNroDoc'     )                  : ""  ;
        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
            $fDesde_A = null;
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
            $fHasta_A = null;
        }
        $chkError = ( $request->get( 'chkError' ) . "" );
        
        $filter = array(
            "fDesde"     => $fDesde_A   ,
            "fHasta"     => $fHasta_A   ,
            "Nombre"     => $txtNombre  ,
            "Apellido"   => $txtApellido,
            "txtTipDoc"  => $txtTipDoc  ,
            "txtNroDoc"  => $txtNroDoc  ,
            "chkError"   => $chkError
        );
        
        $query = $em->getRepository('ADMINAdminBundle:LoteDocumento')->getByFilter($filter);
        $paginador = new Pagerfanta(new DoctrineORMAdapter($query));

        $paginador->setMaxPerpage(20);
        $paginador->setCurrentPage($request->get('page'));
        
        $usuario = $this->getUser();
        
        return $this->render(
            'ADMINAdminBundle:LoteDocumento:showCargaMasiva.html.twig',
            array( 	'loteDocumento'=> $paginador,
            		'cant' => $paginador->count(),
                	'usuario'=>$usuario)
            );
    }
    
    
    public function cargaMasivaProcesarAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $txtNombre   = $request->get( 'txtNombre'     ) ? $request->get( 'txtNombre'     )                  : ""  ;
        $txtApellido = $request->get( 'txtApellido'   ) ? $request->get( 'txtApellido'   )                  : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde' ) ? $request->get( 'txtFechaDesde' )                  : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta' ) ? $request->get( 'txtFechaHasta' )                  : ""  ;
        $txtTipDoc   = $request->get( 'txtTipDoc'     ) ? $request->get( 'txtTipDoc'     )                  : ""  ;
        $txtNroDoc   = $request->get( 'txtNroDoc'     ) ? $request->get( 'txtNroDoc'     )                  : ""  ;
        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
            $fDesde_A = null;
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
            $fHasta_A = null;
        }
        $chkError = ( $request->get( 'chkError' ) . "" );
        
        $filter = array(
            "fDesde"     => $fDesde_A   ,
            "fHasta"     => $fHasta_A   ,
            "Nombre"     => $txtNombre  ,
            "Apellido"   => $txtApellido,
            "txtTipDoc"  => $txtTipDoc  ,
            "txtNroDoc"  => $txtNroDoc  ,
            "chkError"   => $chkError
        );
        
        $entities = $em->getRepository('ADMINAdminBundle:LoteDocumento')->getByFilter($filter);
        
        ini_set('max_execution_time', 600);
        session_write_close();
        
        foreach($entities as $documento){
        	$documento->setDescargado('N');
        	$em->flush();
        }  
        
        $cant=sizeof($entities);
        
        if($cant>=100)
        	$cantRoutines = 100;
        else
        	$cantRoutines = 10;
        		
       	if($cant>=$cantRoutines){
       		$i=0;
        			
        	$sublotes=array();
        	$routines=array();
        			
        	foreach($entities as $row){
        		$sublotes[($i%$cantRoutines)][]=$row;
        		$i++;
        	}
        			
        	for($i=0;$i<$cantRoutines;$i++){
        		$routine[$i] = new Coroutine($this->getResponse($sublotes[$i]));
        		$respuestas[] = $routine[$i]->wait();
        	}
        			
        	Loop\Run();
        			
        }else{
        	$respuestas = $this->enviarDocumentos($entities);
        }
        
        return new JsonResponse(json_encode(['resultado'=>'Se procesaron correctamente '.$cant.' documentos.']));
        
    }
    
    private function getResponse($sublote){
    	
    	$delay = rand(1,5);
    	$respuestas = $this->enviarDocumentos($sublote);
    	$promise = Awaitable\resolve($respuestas);
    	
    	yield $promise->delay($delay);
    	
    }

    private function enviarDocumentos($documentos){
    	$respuestas = array();
    	$em = $this->getDoctrine()->getManager();
    	
    	foreach ($documentos as $documento){
    		$fechaRegistro = $documento->getFechaRegistro();
    		$tipoDoc = $documento->getTipoDoc();
    		
    		$dFecha = substr($fechaRegistro, 6, 4) . "" . substr($fechaRegistro, 3, 2) . "" . substr($fechaRegistro, 0, 2);
    		$documento->setFechaRegistro($dFecha);
    		
    		$documento->setDescargado('S');
    		$documento->setFechaHoraDesc(date('Y-m-d H:i:s'));
    		$documento->setUsuDesc($this->getUser());
    		
    		if(empty($tipoDoc)||$tipoDoc=='1'){
    			$documento->setTipoDoc('PAS');
    		}
    		
    		if($documento->getIdPaisEmisor()=='ARG'){
    			$documento->setIdPaisEmisor(110);
    		}
    		
    		$mensajeRemoto = $this->enviarDocumento($documento);
    		if(isset($mensajeRemoto->respuesta)){
    			$documento->setMensajeremoto($mensajeRemoto->respuesta);
    		}else{
    			$documento->setMensajeremoto('Error al intentar enviar el registro.');
    		}
    		if(isset($mensajeRemoto->itemid)){
    		    $documento->setItemId($mensajeRemoto->itemid);
    		}
                		
    		$documento->setFechaRegistro($fechaRegistro);
    		
    		$respuestas[]=[
    		    'idLoteDocumento'=>$documento->getId(),
    		    'numeroDocumento'=>$documento->getNumeroDoc(),
    		    'mensajeRemoto'=>$documento->getMensajeremoto(),
    		    'itemid'=>$documento->getItemId()
    		];
    		
    		$em->flush();
    	}
    	
    	return $respuestas;
    }
    
    private function enviarDocumento(LoteDocumento $documento){
        
        $usuario = $this->getUser();
        
        $params=[
            "sistema"=>"WISDM",
            "usuario"=>"PFA",
            "pass"=>"WISDM2020PFA",
            "token"=>"PFA WISDM WS V.1",
            "origen"=>"WISDM",
            "documento"       => $documento->getNumeroDoc(),
            "paisreg"         => $documento->getIdNacionalidad(),
            "tdocum"          => $documento->getTipoDoc(),
            "tipofraude"      => "STL",
            "tipoDenuncia"    => $documento->getTipoDenuncia(),
            "numref"          => "",
            "numrefocn"       => "",
            "fecharobo"       => $documento->getFechaRegistro(),
            "fechaemi"        => $documento->getFechaRegistro(),
            "fechaexp"        => $documento->getFechaRegistro(),
            "paisrobo"        => $documento->getIdPaisEmisor(),
            "infoad"          => "",
            "id"              => $documento->getId(),
            "user"             =>$usuario->getUsuario(),
            "usuarioIp"        => $this->container->get('request')->getClientIp(),
            "usuarioApellido"  =>$usuario->getApellido(),
            "usuarioNombre"    =>$usuario->getNombre(),
            "usuarioDepen"     =>$usuario->getDepenid()->getNombre(),
            "usuarioJerarquia" =>$usuario->getJerarquia(),
            "usuarioDepenId"   =>$usuario->getDepenid()->getCodigo()
        ];
        
        $curl = curl_init();
        
        //  $documento, $paisreg, $tdocum, $tipofraude, $numref, $numrefocn, $fecharobo, $paisrobo, $fechaemi, $fechaexp, $infoad
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://' . HOST_WISDM . ':' . PORT_WISDM . '/BackEndWisdm/index.php/insertarActualizar',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode( $params ),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        $retorno = json_decode($response);
        curl_close($curl);
        
        if(!empty($error)){
        	return $error;
        }
        return $retorno;
    }
    
    public function ejecutarStartInitAction(Request $request){
        
        $retorno = ["estado"=>"OK", "message"=>""];
        $usuario = $this->getUser();
        $params=[
            "sistema"          =>"WISDM",
            "user"             =>$usuario->getUsuario(),
            "usuario"          =>"PFA",
            "pass"             =>"WISDM2020PFA",
            "token"            =>"PFA WISDM WS V.1",
            "origen"           =>"WISDM",
            "id"               => "0",
            "usuarioIp"        => $this->container->get('request')->getClientIp(),
            "usuarioApellido"  =>$usuario->getApellido(),
            "usuarioNombre"    =>$usuario->getNombre(),
            "usuarioDepen"     =>$usuario->getDepenid()->getNombre(),
            "usuarioJerarquia" =>$usuario->getJerarquia(),
            "usuarioDepenId"   =>$usuario->getDepenid()->getCodigo()
        ];
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://' . HOST_WISDM . ':' . PORT_WISDM . '/BackEndWisdm/index.php/IniciarProceso',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode( $params ),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        $retorno = json_decode($response);
        curl_close($curl);
        
        if(!empty($error)){
            return $error;
        }
        
        return new JsonResponse($retorno);
        
    } 
    
    public function ejecutarFinalizeInitAction(Request $request){
        
        $retorno = ["estado"=>"OK", "message"=>""];
        $usuario = $this->getUser();
        $params=[
            "sistema"          =>"WISDM",
            "user"             =>$usuario->getUsuario(),
            "usuario"          =>"PFA",
            "pass"             =>"WISDM2020PFA",
            "token"            =>"PFA WISDM WS V.1",
            "origen"           =>"WISDM",
            "id"               => "0",
            "usuarioIp"        => $this->container->get('request')->getClientIp(),
            "usuarioApellido"  =>$usuario->getApellido(),
            "usuarioNombre"    =>$usuario->getNombre(),
            "usuarioDepen"     =>$usuario->getDepenid()->getNombre(),
            "usuarioJerarquia" =>$usuario->getJerarquia(),
            "usuarioDepenId"   =>$usuario->getDepenid()->getCodigo()
        ];
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://' . HOST_WISDM . ':' . PORT_WISDM . '/BackEndWisdm/index.php/FinalizarProceso',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode( $params ),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        $retorno = json_decode($response);
        curl_close($curl);
        
        if(!empty($error)){
            return $error;
        }
        
        return new JsonResponse($retorno);
        
    }  
    
}


?>