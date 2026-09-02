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
use SEGURIDAD\SeguridadBundle\Entity\InstitucionRepository;
use ADMIN\AdminBundle\Entity\InterpolLogMvRepository;

include_once ('/apache/includes/ambiente.php');

class PanelController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    

    public function indexAction(Request $request, $page = 1){
        $institucion = $request->get('institucion');
        $filter = array();
        
        if(!empty($institucion)){
            $filter['institucion'] = $institucion;
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $this->container->get('session')->migrate($destroy = false, $lifetime = null);
        
        $response = $em->getRepository('ADMINAdminBundle:InterpolLogMv')->ultimasConsultasList($filter);
        
        $consultasDepen = $em->getRepository('ADMINAdminBundle:InterpolLogMv')->consultasDependencias($filter);
        
        $instituciones = $em->getRepository('SEGURIDADSeguridadBundle:Institucion')->getAll(); 
        
        $consultas24CP = $em->getRepository('ADMINAdminBundle:InterpolLogMv')->consultas24CP($filter);
 
        $consultas = $em->getRepository('ADMINAdminBundle:InterpolLogMv')->consultasHoy($filter);
        
        // Query últimas 24 hs
        if(AMBIENTE=='DESARROLLO'){
            $date = new DateTime('2018-10-10 00:00:00');
        }else{
            $date = new DateTime();
            $date->modify("-1 day");
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
        
        $ultimas24 = $em->getRepository('ADMINAdminBundle:Alarma')->getTotalesXTipo($filter24);
        
        return $this->render(
            'ADMINAdminBundle:Panel:index.html.twig', 
            array( 'consultas'=>$consultas,
                   'instituciones' => $instituciones,
                   'ultimas24' => $ultimas24,
                   'consultasDepen' => $consultasDepen,
                   'consultas24CP' => $consultas24CP
            )
        );
    }
    
    public function UltimasConsultasListAction(Request $request){
        $institucion = $request->get('institucion');
        
        $filter = array();
        
        if(!empty($institucion)){
            $filter['institucion'] = $institucion;
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $response = $em->getRepository('ADMINAdminBundle:InterpolLogMv')->ultimasConsultasList($filter);
        
        return new Response(json_encode($response));
        
    }

}

?>