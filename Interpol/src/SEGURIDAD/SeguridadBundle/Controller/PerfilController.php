<?php

namespace SEGURIDAD\SeguridadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SEGURIDAD\SeguridadBundle\Entity\Perfil;
use SEGURIDAD\SeguridadBundle\Form\PerfilSuperAdminType;
use SEGURIDAD\SeguridadBundle\Form\PerfilType;
use SEGURIDAD\SeguridadBundle\Form\PerfilPermisoType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use GESTION\GestionBundle\Services\SessionManager;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use DateTime;
use SEGURIDAD\SeguridadBundle\Entity\PerfilRepository;

/**
 * Perfil controller.
 *
 * @Route("/admin/perfil")
 */
class PerfilController extends Controller
{

    /**
     * Lists all Perfil entities.
     *
     * @Route("/", name="admin_perfil")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction(Request $request, $page = 1)
    {   
        $em = $this->getDoctrine()->getManager();
        $filter    = [];
        $txtNombre = $request->get( 'txtNombre'   )  ? $request->get( 'txtNombre') : "";
        $txtDescripcion = $request->get( 'txtDescripcion'   )  ? $request->get( 'txtDescripcion') : "";
        $filter    = array(
            "txtNombre"      => $txtNombre,
            "txtDescripcion" => $txtDescripcion
        );
        
        
        $query = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->getByFilter($filter);
        
        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Nombre;Descripcion;Borrado";
            
            foreach ($query->getResult() as $event) {
                $data = array(
                    $event->getId(),
                    $event->getNombre(),
                    $event->getDescripcion(),
                    $event->getBorrado() == '1' ? utf8_decode('Sí') : 'No'
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="perfil_export_'.date('Y-m-d').'.csv"');
            
            ob_clean();
            
            return $response;
        }
        
        if($request->get('page')!=''){
            $page = $request->get('page');
        }
        
        $paginador = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginador->setMaxPerpage(50);
        $paginador->setCurrentPage($page);
        
        return array(
            'entities' => $paginador
        );
        
    }
    /**
     * Creates a new Perfil entity.
     *
     * @Route("/create", name="admin_perfil_create")
     * @Method({"GET","POST"})
     * @Template("SEGURIDADSeguridadBundle:Perfil:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Perfil();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Perfil creado correctamente.');
            
            return $this->redirect($this->generateUrl('admin_perfil', array('id' => $entity->getId())));
            
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al crear el perfil.');
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Perfil entity.
     *
     * @param Perfil $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Perfil $entity)
    {
        
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            $form = $this->createForm(new PerfilSuperAdminType(), $entity, array(
                'action' => $this->generateUrl('admin_perfil_create'),
                'method' => 'POST',
            ));
        }else{
            $form = $this->createForm(new PerfilType(), $entity, array(
                'action' => $this->generateUrl('admin_perfil_create'),
                'method' => 'POST',
            ));
        }
        
        


        return $form;
    }

    /**
     * Displays a form to create a new Perfil entity.
     *
     * @Route("/new", name="admin_perfil_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Perfil();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Perfil entity.
     *
     * @Route("/{id}", name="admin_perfil_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad perfil no encontrada.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Perfil entity.
     *
     * @Route("/{id}/edit", name="admin_perfil_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad perfil no encontrada.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Perfil entity.
    *
    * @param Perfil $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Perfil $entity)
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            $form = $this->createForm(new PerfilSuperAdminType(), $entity, array(
                'action' => $this->generateUrl('admin_perfil_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        } else {
            $form = $this->createForm(new PerfilType(), $entity, array(
                'action' => $this->generateUrl('admin_perfil_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        }
        return $form;
    }
    /**
     * Edits an existing Perfil entity.
     *
     * @Route("/{id}", name="admin_perfil_update")
     * @Method("PUT")
     * @Template("SEGURIDADSeguridadBundle:Perfil:edit.html.twig")
     */
    

/*     FUNCION PARA MODIFICACION DE USUARIOS*/
     public function updateAction(Request $request, $id)
    {
        echo "updateAction";
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad perfil no encontrado.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Perfil modificado correctamente.');
            return $this->redirect($this->generateUrl('admin_perfil_edit', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error modificar el perfil.');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


/*     FUNCION PARA ELIMINAR */

     /**
     * Deletes a Perfil entity.
     *
     * @Route("/{id}", name="admin_perfil_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Perfil no encontrado.');
            }
            
            $entity->setborrado(1);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Perfil eliminado correctamente.');
        }

        return $this->redirect($this->generateUrl('admin_perfil'));
    }

    /**
     * Creates a form to delete a Perfil entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_perfil_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Displays a form to edit an existing Perfil entity.
     *
     * @Route("/{id}/editpermisos", name="admin_perfil_edit_permisos")
     * @Method("GET")
     * @Template()
     */
    public function editPermisosAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Entidad perfil no encontrada.');
        }
        
        $editForm = $this->createEditPermisosForm($entity);
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }
        
    /**
     * Creates a form to edit Permisos of perfiles entity.
     *
     * @param Persiles $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditPermisosForm(Perfil $entity)
    {
        $form = $this->createForm(new PerfilPermisoType(), $entity, array(
            'action' => $this->generateUrl('perfil_update_permisos', array('id' => $entity->getId())),
            'method' => 'PUT'
        ));
        
        return $form;
    }
    
    
    
    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/permisos", name="perfil_update_permisos")
     * @Method("PUT")
     * @Template("SEGURIDADSeguridadBundle:Usuario:editPermisos.html.twig")
     */
    public function updatePermisosAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Perfil')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        
        $editForm = $this->createEditPermisosForm($entity);
        
        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            
            if(isset($request->get('seguridad_seguridadbundle_admin_perfil')['permisoid'])){
                $arrPerfilid = $request->get('seguridad_seguridadbundle_admin_perfil')['permisoid'];
            }else{
                $this->container->get('session')->getFlashBag()->add('msgError', 'Error: Debe seleccionar al menos un valor.');
                
                return $this->redirect($this->generateUrl('admin_perfil_edit_permisos', array('id' => $id)));
            }
            
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Los permisos del perfil '.$entity.' fueron editados correctamente.');
            
            return $this->redirect($this->generateUrl('admin_perfil_edit_permisos', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar editar los permisos del perfil.');
        }
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }
    
}
