<?php

namespace SEGURIDAD\SeguridadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SEGURIDAD\SeguridadBundle\Entity\Usuario;
use SEGURIDAD\SeguridadBundle\Form\UsuarioType;
use SEGURIDAD\SeguridadBundle\Form\UsuarioPerfilesType;
use SEGURIDAD\SeguridadBundle\Form\UsuarioSinRolesType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use GESTION\GestionBundle\Services\SessionManager;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use SEGURIDAD\SeguridadBundle\Entity\UsuarioRepository;
use DateTime;

/**
 * Usuario controller.
 *
 * @Route("/admin/usuario")
 */
class UsuarioController extends Controller
{

    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    
    /**
     * Lists all Usuario entities.
     *
     * @Route("/", name="usuario")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request, $page = 1){
        
        $em = $this->getDoctrine()->getManager();
        
        $txtUsuario  = $request->get( 'txtUsuario'   ) ? $request->get( 'txtUsuario') : ""  ;
        $txtNombre   = $request->get( 'txtNombre'   )  ? $request->get( 'txtNombre') : ""  ;
        $txtApellido = $request->get( 'txtApellido'   ) ? $request->get( 'txtApellido') : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde') ? $request->get( 'txtFechaDesde') : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta') ? $request->get( 'txtFechaHasta') : ""  ;
        $txtDependencia   = $request->get( 'txtDependencia') ? $request->get( 'txtDependencia') : ""  ;
        $optEstado   = $request->get( 'optEstado') ? $request->get( 'optEstado') : ""  ;
        $optPerfiles   = $request->get( 'optPerfiles') ? $request->get( 'optPerfiles') : ""  ;

        if($fDesde!=''){
            $fDesde = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }
        if($fHasta!=''){
            $fHasta = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }
        
        $filter = array(
            "fDesde"        => $fDesde,
            "fHasta"        => $fHasta,
            "txtUsuario"    => $txtUsuario,
            "txtNombre"     => $txtNombre,
            "txtApellido"   => $txtApellido,
            "txtDependencia"=> $txtDependencia,
            "optEstado"     => $optEstado,
            "optPerfiles"   => $optPerfiles
        );
        
        $usuarios = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->getByFilter($filter);
        
        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "usuario;apellido;nombre;dependencia;tipodoc;numerodoc;jerarquia;institucion;borrado;activo;expiracionpassword;fechaborrado;fechadesactivado;fechaalta;iphabilitada;consulta;cantconsultas";
            
            foreach ($usuarios as $event) {
                $data = array(
                    $event->getUsuario(),
                    $event->getApellido(),
                    $event->getNombre(),
                    $event->getDepenid() !== null ? $event->getDepenid()->getNombre() : '',
                    $event->getTipodoc(),
                    $event->getNumerodoc(),
                    $event->getJerarquia(),
                    $event->getDepenid() !== null ? $event->getDepenid()->getInstitucionid()->getNombre() : '',
                    $event->getBorrado(),
                    $event->getActivo(),
                    $event->getExpiracionpassword() === null    ? '' : $event->getExpiracionpassword()->format('d/m/Y'),
                    $event->getFechaborrado() === null          ? '' : $event->getFechaborrado()->format('d/m/Y'),
                    $event->getFechadesactivado() === null      ? '' : $event->getFechadesactivado()->format('d/m/Y'),
                    $event->getFechaalta() === null             ? '' : $event->getFechaalta()->format('d/m/Y'),
                    $event->getIphabilitada(),
                    $event->getConsulta(),
                    $event->getCantPersona()+$event->getCantDocumento()+$event->getCantVehiculo()+$event->getCantCombinada()+$event->getCantLote()
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="usuarios_export_'.date('Y-m-d').'.csv"');
            
            ob_clean();
            
            return $response;
        }

        
        $perfiles = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->findBy(['borrado'=>0]);
        
        $usuario = $this->getUser();
        
        return $this->render(
            'SEGURIDADSeguridadBundle:Usuario:index.html.twig',
            array(  'usuarios'=> $usuarios,
                    'cant' => count($usuarios),
                    'usuario'=>$usuario,
                    'perfiles' => $perfiles
                )
            );
    }
    /**
     * Creates a new Usuario entity.
     *
     * @Route("/create", name="usuario_create")
     * @Method("POST")
     * @Template("SEGURIDADSeguridadBundle:Usuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Usuario();
        $entity->setExpiracionpassword(new \Datetime("+6 months"));
        
        $errores = '';
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            if(!empty($request->get('seguridad_seguridadbundle_usuario')['password'])){
                $entity->setPassword(md5($request->get('seguridad_seguridadbundle_usuario')['password']));
            }
            
            $entity->setLocked(false);
            $entity->setExpired(false);
            $entity->setSalt(null);
            $entity->setCredentialsExpired(false);
            $entity->setFechaalta(new \Datetime());
            $entity->setUsuarioAlta($this->getUser()->getId());
            $entity->setCantPersona(0);
            $entity->setCantDocumento(0);
            $entity->setCantVehiculo(0);
            $entity->setCantCombinada(0);
            $entity->setCantLote(0);
            
            $arrPerfilid = $request->get('seguridad_seguridadbundle_usuario')['perfilid'];
            
            
            $oPerfil = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->getByAdministradores($arrPerfilid);
            
            $admin = $oPerfil["ADMIN"];
            $admin_ext = $oPerfil["ADMIN_EXT"];
            
            if($admin == 1) {
                $entity->addRole('ROLE_ADMIN');
            }else{
                $entity->removeRole('ROLE_ADMIN');
            }
            
            if($admin_ext == 1) {
                $entity->addRole('ROLE_ADMIN_EXT');
            }else{
                $entity->removeRole('ROLE_ADMIN_EXT');
            }
            
            $estado = $request->get('seguridad_seguridadbundle_usuario')['estado'];
            
            switch($estado){ 
                case 'I':
                    $entity->setActivo(false);
                    $entity->setBorrado(false);
                    break;
                case 'B':
                    $entity->setActivo(true);
                    $entity->setBorrado(true);
                    break;
                default:
                    $entity->setActivo(true);
                    $entity->setBorrado(false);
            }
            
            $em->persist($entity);
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El usuario '.$entity.' fue creado correctamente.');

            return $this->redirect($this->generateUrl('usuario'));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar crear el usuario.');
            $errores = $form->getErrorsAsString();
        }
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => $errores
        );
    }

    /**
     * Creates a form to create a Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Usuario $entity)
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            $form = $this->createForm(new UsuarioType(), $entity, array(
                'action' => $this->generateUrl('usuario_create'),
                'method' => 'POST',
            ));
        }else{
            $form = $this->createForm(new UsuarioSinRolesType(), $entity, array(
                'action' => $this->generateUrl('usuario_create'),
                'method' => 'POST',
            ));
        }

        return $form;
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     * @Route("/new", name="usuario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Usuario();
        $entity->setExpiracionpassword(new \Datetime("+6 months"));
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores'=>''
        );
    }

    /**
     * Finds and displays a Usuario entity.
     *
     * @Route("/{id}", name="usuario_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        
        return array(
            'entity'      => $entity,
            'errores'     => ''
        );
        
        
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/edit", name="usuario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        
        $sixMonthsFromNow = new \Datetime("+6 months");

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'sixMonthsFromNow' => $sixMonthsFromNow,
            'errores'     => ''
        );
    }
    
    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/editperfiles", name="usuario_edit_perfiles")
     * @Method("GET")
     * @Template()
     */
    public function editPerfilesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        
        $editForm = $this->createEditPerfilesForm($entity);
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }
    
    /**
     * Creates a form to edit perfiles of Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditPerfilesForm(Usuario $entity)
    {
        $form = $this->createForm(new UsuarioPerfilesType(), $entity, array(
            'action' => $this->generateUrl('usuario_update_perfiles', array('id' => $entity->getId())),
            'method' => 'PUT'
        ));
        return $form;
    }
    
    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/perfiles", name="usuario_update_perfiles")
     * @Method("PUT")
     * @Template("SEGURIDADSeguridadBundle:Usuario:editPerfiles.html.twig")
     */
    public function updatePerfilesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        
        $editForm = $this->createEditPerfilesForm($entity);
        
        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            
            if(isset($request->get('seguridad_seguridadbundle_usuario')['perfilid'])){
                $arrPerfilid = $request->get('seguridad_seguridadbundle_usuario')['perfilid'];
            }else{
                $this->container->get('session')->getFlashBag()->add('msgError', 'Error: Debe seleccionar al menos un valor.');
                
                return $this->redirect($this->generateUrl('usuario_edit_perfiles', array('id' => $id)));
            }
            
            $oPerfil = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->getByAdministradores($arrPerfilid);
            
            $admin = $oPerfil["ADMIN"];
            $admin_ext = $oPerfil["ADMIN_EXT"];
                        
            if($admin == 1) {
                $entity->addRole('ROLE_ADMIN');
            }else{
                $entity->removeRole('ROLE_ADMIN');
            }
            
            if($admin_ext == 1) {
                $entity->addRole('ROLE_ADMIN_EXT');
            }else{
                $entity->removeRole('ROLE_ADMIN_EXT');
            }
            
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Los pefiles del usuario '.$entity.' fueron editados correctamente.');
            
            return $this->redirect($this->generateUrl('usuario_edit_perfiles', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar editar los perfiles del usuario.');
        }
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }
    
    /**
    * Creates a form to edit a Usuario entity.
    *
    * @param Usuario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Usuario $entity)
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            $form = $this->createForm(new UsuarioType(), $entity, array(
                'action' => $this->generateUrl('usuario_update', array('id' => $entity->getId())),
                'method' => 'PUT'
            ));
        }else{
            $form = $this->createForm(new UsuarioSinRolesType(), $entity, array(
                'action' => $this->generateUrl('usuario_update', array('id' => $entity->getId())),
                'method' => 'PUT'
            ));
        }
        

        return $form;
    }
    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/update", name="usuario_update")
     * @Method({"PUT","POST"})
     * @Template("SEGURIDADSeguridadBundle:Usuario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $errores = '';

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);

        $password = $entity->getPassword();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createEditForm($entity);
               
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if(!empty($request->get('seguridad_seguridadbundle_usuario')['password'])){
                $entity->setPassword(md5($request->get('seguridad_seguridadbundle_usuario')['password']));
            }else{
                $entity->setPassword($password);
            }

            $arrPerfilid = $request->get('seguridad_seguridadbundle_usuario')['perfilid'];
            
            
            $oPerfil = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->getByAdministradores($arrPerfilid);
            
            $admin = $oPerfil["ADMIN"];
            $admin_ext = $oPerfil["ADMIN_EXT"];
            
            if($admin == 1) {
                $entity->addRole('ROLE_ADMIN');
            }else{
                $entity->removeRole('ROLE_ADMIN');
            }
            
            if($admin_ext == 1) {
                $entity->addRole('ROLE_ADMIN_EXT');
            }else{
                $entity->removeRole('ROLE_ADMIN_EXT');
            }
            
            $estado = $request->get('seguridad_seguridadbundle_usuario')['estado'];
            
            switch($estado){
                case 'I':
                    $entity->setActivo(false);
                    $entity->setBorrado(false);
                    break;
                case 'B':
                    $entity->setActivo(true);
                    $entity->setBorrado(true);
                    break;
                default:
                    $entity->setActivo(true);
                    $entity->setBorrado(false);
            }
            
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El usuario '.$entity.' fue editado correctamente.');

            return $this->redirect($this->generateUrl('usuario_edit', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar editar al usuario.');
            $errores = $editForm->getErrorsAsString();
        }
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'errores'     => $errores
        );
    }
    /**
     * Deletes a Usuario entity.
     *
     * @Route("/{id}", name="usuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

        }

        return $this->redirect($this->generateUrl('usuario'));
    }

    
    /**
     * Activar an existing Usuario entity.
     *
     * @Route("/{id}/activar", name="usuario_activar")
     * @Method("GET")
     */
    public function activarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $opcion = $request->get('opcion');
        
        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        
        $entity->setActivo($opcion);
        
        if(!$opcion)
            $entity->setFechadesactivado(new \Datetime());
        
        $em->flush();
        
        $msg = $opcion ? 'activado' : 'desactivado';
        
        $this->container->get('session')->getFlashBag()->add('msgOk', 'El usuario '.$entity.' fue '.$msg.' correctamente.');
        
        return $this->redirect($this->generateUrl('usuario'));
        
    }
    
    /**
     * Borrar an existing Usuario entity.
     *
     * @Route("/{id}/borrar", name="usuario_borrar")
     * @Method("GET")
     */
    public function borrarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $opcion = $request->get('opcion');
        
        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Usuario')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        
        $entity->setBorrado($opcion);
        
        if($opcion)
            $entity->setFechaborrado(new \Datetime());
        
        $em->flush();
        
        $msg = $opcion ? 'borrado' : 'recuperado';
        
        $this->container->get('session')->getFlashBag()->add('msgOk', 'El usuario '.$entity.' fue '.$msg.' correctamente.');
        
        return $this->redirect($this->generateUrl('usuario'));
        
    }
    
    /**
     * Creates a form to delete a Usuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
