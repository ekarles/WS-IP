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
use ADMIN\AdminBundle\Entity\LoteDocumento;
use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;
use DateTime;
use GESTION\GestionBundle\Repository\Diccionario;


include_once ('/apache/includes/ambiente.php');

class AuditoriaWisdmController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    
    public function indexAction(Request $request, $page = 1)
    {
        $usuario = $this->getUser();
        
        
        $fechaDesde = $request->get("fechaDesde")==''?'':substr($request->get("fechaDesde"),8,2).'/'.substr($request->get("fechaDesde"),5,2).'/'.substr($request->get("fechaDesde"),0,4);
        $fechaHasta = $request->get("fechaHasta")==''?'':substr($request->get("fechaHasta"),8,2).'/'.substr($request->get("fechaHasta"),5,2).'/'.substr($request->get("fechaHasta"),0,4);
        $fechaRobo  = $request->get("fechaRobo") ==''?'':substr($request->get("fechaRobo"),0,4).substr($request->get("fechaRobo"),5,2).substr($request->get("fechaRobo"),8,2);
        
        $params=[
            "sistema"          =>"INTI",
            "usuario"          =>"PFA",
            "pass"             => PASS_BACKEND_INTI,
            "token"            => TOKEN_INTI,
            "origen"           =>"INTI",
            "numerodoc"        => $request->get("numerodoc"),
            "depciaUsuario"    => $request->get("depciaUsuario"),
            "user"             => $request->get("user"),
            "respservicio"     => $request->get("respservicio"),
            "fechaDesde"       => $fechaDesde,
            "fechaHasta"       => $fechaHasta,
            "fechaRobo"        => $fechaRobo,
            "usuarioIp"        =>$this->container->get('request')->getClientIp(),
            "usuarioApellido"  =>$usuario->getApellido(),
            "usuarioNombre"    =>$usuario->getNombre(),
            "usuarioDepen"     =>$usuario->getDepenid()->getNombre(),
            "usuarioJerarquia" =>$usuario->getJerarquia(),
            "usuarioDepenId"   =>$usuario->getDepenid()->getCodigo(),
            "paginaNro"        => $page,
            "paginaCant"        => 100
            
        ];

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://' . HOST_WISDM . ':' . PORT_WISDM . '/BackEndInterpol/index.php/consultaAuditoriaWISDM',
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
        $resp = json_decode($response,true);
        curl_close($curl);
        
        if($error==''){
            if(isset($resp['status']) && $resp['status']=='success' ){
                
                $maxPag = ceil($resp['totalRespuesta']/100);
                
                return $this->render(
                    'ADMINAdminBundle:AuditoriaWisdm:index.html.twig',
                    array(  'results'=> $resp['respuesta'],
                            'cant' => $resp['totalRespuesta'],
                            'maxPag' => $maxPag,
                            'page' => $page
                        )
                    );
            }
            
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar obtener los datos: '.isset($resp['message'])?$resp['message']:'');
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar obtener los datos: '.$error);
        }
        
        return $this->render(
            'ADMINAdminBundle:AuditoriaWisdm:index.html.twig',
            array(  'results'=> [],
                    'cant' => isset($resp['totalRespuesta'])?$resp['totalRespuesta']:0,
                    'maxPag' => 0,
                    'page' => $page
                )
            );
        
    }

    
    
}


?>