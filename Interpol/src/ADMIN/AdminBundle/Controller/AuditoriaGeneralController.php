<?php

namespace ADMIN\AdminBundle\Controller;

ini_set('memory_limit', '512M');

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation as DI;


include_once ('/apache/includes/ambiente.php');

class AuditoriaGeneralController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;

    /**
     * Redirige a módulo PHP puro externo preservando la sesión de Symfony/FOSUserBundle
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        
        if (!$usuario) {
            // Si no hay usuario autenticado, redirigir al login
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        
        // Obtener la sesión de Symfony
        $session = $this->container->get('session');
        // Asegurar que la sesión PHP esté iniciada para que $_SESSION sea usable
        $session->start();
        
        // Guardar datos del usuario en la sesión Symfony y en $_SESSION (compatibilidad)
        $session->set('symfony_user_id', $usuario->getId());
        $session->set('symfony_username', $usuario->getUsername());
        $session->set('valida_parent', false);
        $session->set('usuario', $usuario->getUsername());
        $session->set('UsuarioAuditoria', strtoupper($usuario->getUsername()));
        $session->set('apellido_usuario', $usuario->getApellido());
        $session->set('nombre_usuario', $usuario->getNombre());
        $session->set('jerarquia_usuario', $usuario->getJerarquia());
        $session->set('lp_usuario', '');
        $session->set('pwdusupfa', '');
        $session->set('ultimo_login', $usuario->getUltimologin()->format('d-M-Y H:i:s'));
        $session->set('tipo_doc', $usuario->getTipodoc());
        $session->set('nro_doc', $usuario->getNumerodoc()); // Ajustar según tu entidad
        $session->set('cod_dep', $usuario->getDepenid()->getCodigo());
        $session->set('desc_dep', $usuario->getDepenid()->getNombre());
        $session->set('id_dep', $usuario->getDepenid()->getId());
        $session->set('ONLINE', 0);
        $session->set('USUARIO', $usuario->getNumerodoc());
        $session->set('AUTENTIF', true);
        $session->set('WS_MENU', true);
        $session->set('SYS_ARS', 'D591');
        $session->set('css', 'sipfa.css');
        $session->set('perfil', 'INTI_ADM');
        
        // Guardar roles del usuario
        $roles = $usuario->getRoles();
        $session->set('symfony_roles', $roles);
        
        foreach($usuario->getPerfilid()->toArray() as $perfil) {
            $perfiles[] = $perfil->getDescripcion(); 
        }
        
        $session->set('perfiles', $perfiles);
        
        foreach ($_SESSION['_sf2_attributes'] as $key => $value) {
            $_SESSION[$key] = $value;
        }
        
        $_SESSION['sistema'] = 'INTI';
        
        // Ruta absoluta dentro del mismo host
        $externalPath = '/INTI/INTI2010W.PHP';
        
        
        //return $this->redirect($externalPath);
        return $this->render(
            'ADMINAdminBundle:AuditoriaGeneral:index.html.twig',
                array( 'externalPath' => $externalPath
                )
            );
        
    }
    
    
}

?>
