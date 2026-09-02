<?php

namespace SEGURIDAD\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use GESTION\GestionBundle\Services\SessionManager;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use SEGURIDAD\SeguridadBundle\Entity\DependenciaRepository;


/**
 * @Route("/dependencia")
 */
class DependenciaController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    
    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        return $this->render('SEGURIDADSeguridadBundle:Default:index.html.twig', array('usuario'=>$usuario));
    }
     
    /**
     * @Route("/autocompletar", name="dependencia_autocompletar", methods={"GET","POST"})
     */
    public function autocompletarAction(Request $request)
    {
        $filter['term'] = $request->get('term');
        
        $em = $this->getDoctrine()->getManager();
        
        $dependencias = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->autocompletar($filter);
        
        return new Response($this->get('gestion.herramientasservice')->leerEnFormatoJson($dependencias));
    }

}
?>
