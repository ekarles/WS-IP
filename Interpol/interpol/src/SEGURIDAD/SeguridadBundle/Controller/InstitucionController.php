<?php

namespace SEGURIDAD\SeguridadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SEGURIDAD\SeguridadBundle\Entity\Institucion;
use SEGURIDAD\SeguridadBundle\Form\InstitucionType;
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

/**
 * Institucion controller.
 *
 * @Route("/admin/institucion")
 */
class InstitucionController extends Controller
{
    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
    */
    public $sessionManager;
    
    /**
     * Lists all Institucion entities.
     *
     * @Route("/", name="admin_institucion")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $filter    = [];
        $txtNombre = $request->get( 'txtNombre'   )  ? $request->get( 'txtNombre') : "";
        $filter    = array(
                           "txtNombre"     => $txtNombre
                           );
        
        $query = $em->getRepository('SEGURIDADSeguridadBundle:Institucion')->getByFilter($filter);

        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Nombre;Usuario";
            
            foreach ($query->getResult() as $event) {
                $data = array(
                    $event->getId(),
                    $event->getNombre(),
                    $event->getUsuariogenerico()
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="institucion_export_'.date('Y-m-d').'.csv"');
            
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
            'instituciones' => $paginador            
        );
    }
    /**
     * Creates a new Institucion entity.
     *
     * @Route("/create", name="admin_institucion_create")
     * @Method({"GET","POST"})
     * @Template("SEGURIDADSeguridadBundle:Institucion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Institucion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($entity);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('msgOk', 'Institución creada correctamente.');
            
            return $this->redirect($this->generateUrl('admin_institucion', array('id' => $entity->getId())));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al crear la institución.');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Institucion entity.
     *
     * @param Institucion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Institucion $entity)
    {
        $form = $this->createForm(new InstitucionType(), $entity, array(
            'action' => $this->generateUrl('admin_institucion_create'),
            'method' => 'POST',
        ));
        
        return $form;
    }

    /**
     * Displays a form to create a new Institucion entity.
     *
     * @Route("/new", name="admin_institucion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Institucion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Institucion entity.
     *
     * @Route("/{id}", name="admin_institucion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Institucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad institución no encontrada.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Institucion entity.
     *
     * @Route("/{id}/edit", name="admin_institucion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Institucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad institución no encontrada.');
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
    * Creates a form to edit a Institucion entity.
    *
    * @param Institucion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Institucion $entity)
    {
        $form = $this->createForm(new InstitucionType(), $entity, array(
            'action' => $this->generateUrl('admin_institucion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Institucion entity.
     *
     * @Route("/{id}", name="admin_institucion_update")
     * @Method("PUT")
     * @Template("SEGURIDADSeguridadBundle:Institucion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Institucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad institución no encontrada.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('msgOk', 'Institución modificada correctamente.');
            
            return $this->redirect($this->generateUrl('admin_institucion_edit', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error modificar la institución.');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Institucion entity.
     *
     * @Route("/{id}", name="admin_institucion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if($id!=""){
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SEGURIDADSeguridadBundle:Institucion')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Entidad institución no encontrada.');
            }
            $em->remove($entity);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Institución eliminada correctamente.');
        }
        
        return $this->redirect($this->generateUrl('admin_institucion'));
    }

    /**
     * Creates a form to delete a Institucion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_institucion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
