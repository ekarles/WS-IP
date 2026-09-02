<?php

namespace SEGURIDAD\SeguridadBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use SEGURIDAD\SeguridadBundle\Entity\Menu;

/**
 * Usuario controller.
 *
 * @Route("/seguridad")
 */
class SeguridadController extends Controller
{

    /**
     * Allow remote login
     *
     * @Route("/remotelogin", name="remote_login")
     * @Method({"GET","POST"})
     */
    public function loginAction(Request $request)
    {
        
        $_username = $request->get('_username');
        $_password = $this->decrypt($request->get('_password'),$this->container->getParameter('secret'));
        
        $factory = $this->get('security.encoder_factory');

        $user_manager = $this->get('fos_user.user_manager');
        $user = $user_manager->findUserByUsername($_username);
        
        $em = $this->getDoctrine()->getManager();
        
        
        if(!$user){
            return new Response(
                $this->encrypt('Usuario o password incorrecto.',$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
        }
        
        /// Start verification
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();
        
        if(!$user || !$encoder->isPasswordValid($user->getPassword(), $_password, $salt)) {
            return new Response(
                $this->encrypt('Usuario o password incorrecto.',$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
        }
        
        
        if(!$user->getActivo()){
            return new Response(
                $this->encrypt('El usuario está desactivado.',$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
        }
        
        if($user->getBorrado()){
            return new Response(
                $this->encrypt('El usuario fue borrado.',$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
        }
        
        $_SESSION["_sf2_attributes"]["ip"] = $this->getIP();
        
        if (isset($_SESSION["_sf2_attributes"]["ip"]) && $_SESSION["_sf2_attributes"]["ip"]!='UNKNOWN' && !$user->ipHabilitada($_SESSION["_sf2_attributes"]["ip"])) {
            
            return new Response(
                $this->encrypt('No está autorizado a conectarse desde esta IP: '.$_SESSION["_sf2_attributes"]["ip"],$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
            
        }
        
        if ($user->passwordExpirado()) {
            
            return new Response(
                $this->encrypt('Su clave ha expirado.',$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
        }
        
        if (!$user->isAccountNonLocked()) {
            return new Response(
                $this->encrypt('User account is locked.',$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
            
        }
        
        if (!$user->isEnabled()) {
            
            return new Response(
                $this->encrypt('User account is disabled.',$this->container->getParameter('secret')),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
            
        }
        
        if (!$user->isAccountNonExpired()) {
            
            return new Response(
                'User account has expired.',$this->container->getParameter('secret'),
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'text/plain')
                );
            
        }
        
        
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        
        $session = $this->get('session');
        
        
        $session->set('_security_main', serialize($token));
        
       
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        
        $user->setLastLogin(new \Datetime());
        $user->setUltimologin(new \Datetime());
        
        if (isset($_SESSION["_sf2_attributes"]["ip"]) && $_SESSION["_sf2_attributes"]["ip"]!='UNKNOWN') {
            $user->setUltimaip($_SESSION["_sf2_attributes"]["ip"]);
        }
        
        $em->flush();
        
        $oMenu = new Menu();
        $oMenu->setUsuario( $user );
        $oMenu->getOpcionesMenu();
        
        $session->set( 'Menu', $oMenu->getMenu() );
        $session->set( 'SubMenu', $oMenu->getSuMenu() );
        
        $permiso2 = $oMenu->existe( 2 );
        $permiso4 = $oMenu->existe( 4 );
        $permiso9 = $oMenu->existe( 9 );
        $permisoDetalle = [
            "NOMINALSDETAILS" => $permiso2, //  PERSONAS
            "SLTDDETAILS"     => $permiso4, // DOCUMENTOS
            "SMVDETAILS" => $permiso9 // VEHICULOS
        ];

        $session->set('PERMISODETALLE', $permisoDetalle);
        
        $usuario = $user->getVars();
        
        return new Response($this->encrypt(json_encode([
            'Menu' => $session->get('Menu'),
            'SubMenu' => $session->get('SubMenu'),
            'PERMISODETALLE' => $session->get('PERMISODETALLE'),
            'usuario' => $usuario
            ]),$this->container->getParameter('secret')
                ), Response::HTTP_OK, array(
                'Content-type' => 'application/json'
            ));
    }

    private function getIP()
    {
        if (getenv('HTTP_CLIENT_IP'))

            $ipaddress = getenv('HTTP_CLIENT_IP');

        else if (getenv('HTTP_X_FORWARDED_FOR'))

            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');

        else if (getenv('HTTP_X_FORWARDED'))
                    
            $ipaddress = getenv('HTTP_X_FORWARDED');
                    
        else if (getenv('HTTP_FORWARDED_FOR'))
            
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
            
        else if (getenv('HTTP_FORWARDED'))
            
            $ipaddress = getenv('HTTP_FORWARDED');
            
        else if (getenv('REMOTE_ADDR'))
            
            $ipaddress = getenv('REMOTE_ADDR');
            
        else
            $ipaddress = 'UNKNOWN';
        
        return $ipaddress;    
    }
    
    private function decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }
    
    function encrypt($string, $key) {
        $result = '';
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result.=$char;
        }
        return base64_encode($result);
    }

}