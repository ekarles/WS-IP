<?php

namespace ADMIN\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ADMIN\AdminBundle\Entity\Tipodelito;
use ADMIN\AdminBundle\Form\TipodelitoType;
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
 * Tipodelito controller.
 *
 */
class TipodelitoController extends Controller
{

    //     * @Method({"GET","POST"})
    /**
     * Lists all Tipodelito entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $filter    = [];
        $txtNombre = $request->get( 'txtNombre' ) ? $request->get( 'txtNombre' ) : "";
        $filter    = array(
            "txtNombre"     => $txtNombre
        );
        
        $query = $em->getRepository('ADMINAdminBundle:Tipodelito')->getByFilter($filter);

        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Nombre";
            
            foreach ($query->getResult() as $event) {
                $data = array(
                    $event->getId(),
                    $event->getNombre()
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="tipodelito_export_'.date('Y-m-d').'.csv"');
            
            ob_clean();
            
            return $response;
        }
        
        if($request->get('page')!=''){
            $page = $request->get('page');
        }
        
        $paginador = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginador->setMaxPerpage(50);
        $paginador->setCurrentPage($page);
        
        return $this->render('ADMINAdminBundle:Tipodelito:index.html.twig', 
            array(
                'tiposdelito' => $paginador
            )
        );
    }
    /**
     * Creates a new Tipodelito entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tipodelito();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El tipo de delito '.$entity.' fue creado correctamente.');
            
            return $this->redirect($this->generateUrl('admin_Tipodelito', array('id' => $entity->getId())));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar crear el tipo de delito.');
        } 

        return $this->render('ADMINAdminBundle:Tipodelito:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Tipodelito entity.
     *
     * @param Tipodelito $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tipodelito $entity)
    {
        $form = $this->createForm(new TipodelitoType(), $entity, array(
            'action' => $this->generateUrl('admin_Tipodelito_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Tipodelito entity.
     *
     */
    public function newAction()
    {
        $entity = new Tipodelito();
        $form   = $this->createCreateForm($entity);

        return $this->render('ADMINAdminBundle:Tipodelito:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tipodelito entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:Tipodelito')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tipodelito entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ADMINAdminBundle:Tipodelito:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tipodelito entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:Tipodelito')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tipodelito entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ADMINAdminBundle:Tipodelito:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tipodelito entity.
    *
    * @param Tipodelito $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tipodelito $entity)
    {
        $form = $this->createForm(new TipodelitoType(), $entity, array(
            'action' => $this->generateUrl('admin_Tipodelito_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Tipodelito entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:Tipodelito')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tipodelito entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El tipo de delito '.$entity.' fue editado correctamente.');

            return $this->redirect($this->generateUrl('admin_Tipodelito_edit', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar editar el tipo de delito.');
        }

        return $this->render('ADMINAdminBundle:Tipodelito:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tipodelito entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ADMINAdminBundle:Tipodelito')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tipodelito entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El tipo de delito '.$entity.' fue borrado correctamente.');
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar borrar el tipo de delito.');
        }

        return $this->redirect($this->generateUrl('admin_Tipodelito'));
    }

    /**
     * Creates a form to delete a Tipodelito entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_Tipodelito_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
