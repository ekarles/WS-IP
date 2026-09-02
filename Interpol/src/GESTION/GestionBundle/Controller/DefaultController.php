<?php
namespace GESTION\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use JOYAS\JoyasBundle\Services\SessionManager;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
//use GESTION\GestionBundle\Repository\Menu;
use SEGURIDAD\SeguridadBundle\Entity\Menu;

class DefaultController extends Controller
{
	/**
	 * @var SessionManager
	 * @DI\Inject("session.manager")
	 */
	public $sessionManager;
	
    public function indexAction(Request $request)
    {
        $session = $this->container->get('session');
        
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
            "SMVDETAILS"      => $permiso9 //  VEHICULOS
        ];
        
        $session->set( 'PERMISODETALLE', $permisoDetalle );
        if($oMenu->existe( 12 )==1){
            $session->set( 'ALARMASULTIMAS24HS',$this->get('admin.alarmaservice')->alarmasUltimas24Hs());
        }
        if($oMenu->existe( 122 )==1 || $oMenu->existe( 123 )==1){
            $session->set( 'MOVIMIENTOSPENDIENTES',$this->get('admin.personaobservadaservice')->movimientosPendientes($this->getUser(),null));
            
            
            $aVencerse = $this->get('admin.personaobservadaservice')->vencimientosPersonaObservada($this->getUser()->getDepenid());
            
            foreach($aVencerse as $row){
                $this->container->get('session')->getFlashBag()->add('msgWarn', 'El aviso de movimientos para "<a href="'.$this->generateUrl('gestion_personaobservada_show',['id'=>$row->getId()]).'">'.$row.'</a>" dejar&aacute; de estar vigente el d&iacute;a '.$row->getFecHasta()->modify("+1 day")->format('d/m/Y'));
            }
            
        }

        return $this->render('GESTIONGestionBundle:Default:index.html.twig', 
            array(
                "usuario" => $usuario,
                "PermisoDetalle" => $permisoDetalle
            ) );
	}
	
}

?>
