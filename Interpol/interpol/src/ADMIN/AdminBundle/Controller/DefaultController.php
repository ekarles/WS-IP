<?php

namespace ADMIN\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use JOYAS\JoyasBundle\Services\SessionManager;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $session = new Session();
        
        $usuario = $this->getUser();
        $oMenu = new Menu();
        $oMenu->setUsuario( $usuario );
        $oMenu->getOpcionesMenu();
        
        $session->set( 'Menu', $oMenu->getMenu() );
        $session->set( 'SubMenu', $oMenu->getSuMenu() );
        
        $permiso2 = $oMenu->existe( 2 );
        $permiso4 = $oMenu->existe( 4 );
        $permiso9 = $oMenu->existe( 9 );
        
        $permisoDetalle = [
            "NOMINALSDETAILS" => $permiso2, //  PERSONAS
            "SLTDDETAILS"     => $permiso4, //  DOCUMENTOS
            "SMVDETAILS"      => $permiso9  //  VEHICULOS
        ];
        
        $session->set( 'PERMISODETALLE', $permisoDetalle );
        return $this->render('ADMINAdminBundle:Default:index.html.twig', array('name' => $name, 'usuario' => $usuario));
    }
    
    public function enConstruccionAction(){
    	return $this->render('ADMINAdminBundle:Default:enConstruccion.html.twig');
    }
}
