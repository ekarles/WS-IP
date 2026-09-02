<?php

namespace SEGURIDAD\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use JOYAS\JoyasBundle\Services\SessionManager;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use GESTION\GestionBundle\Repository\Menu;

class DefaultController extends Controller
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
    
    public function usuarioDatosAction(Request $request)
    {
        $usuario = $this->getUser();
        
        return $this->render('SEGURIDADSeguridadBundle:Default:usuarioDatos.html.twig', array('usuario'=>$usuario));
    }

}
?>
