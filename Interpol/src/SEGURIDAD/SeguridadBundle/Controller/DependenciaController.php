<?php

namespace SEGURIDAD\SeguridadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SEGURIDAD\SeguridadBundle\Entity\Dependencia;
use SEGURIDAD\SeguridadBundle\Form\DependenciaType;
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
 * Dependencia controller.
 *
 * @Route("/admin/dependencia")
 */
class DependenciaController extends Controller
{

    /**
     * Lists all Dependencia entities.
     *
     * @Route("/", name="admin_dependencia")
     * @Method({"GET", "POST"})
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
        
        $query = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->getByFilter($filter);
    
        if($request->get('accion') == 'csv'){
             $rows = array();
             $rows []= "Id;Codigo;Nombre;Direccion;Latitud;Longitud;Tipo;InstitucionId";
             
             foreach ($query->getResult() as $event) {
                 $data = array(
                     $event->getId(),
                     $event->getCodigo(),
                     $event->getNombre(),
                     $event->getDireccion(),
                     $event->getLatitud(),
                     $event->getLongitud(),
                     $event->getTipo(),
                     $event->getInstitucionid() !== null ? $event->getInstitucionid()->getNombre() : ''
                 );
                 
                 $rows[] = implode(';', $data);
             }
             
             $content = implode("\n", $rows);
             
             $response = new Response($content);
             $response->headers->set('Content-Encoding', 'UTF-8');
             $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
             $response->headers->set('Content-Disposition', 'attachment; filename="dependencia_export_'.date('Y-m-d').'.csv"');
             
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
     * Creates a new Dependencia entity.
     *
     * @Route("admin/dependencia/create", name="admin_dependencia_create")
     * @Method("POST")
     * @Template("SEGURIDADSeguridadBundle:Dependencia:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Dependencia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        $frm = $request->get('seguridad_seguridadbundle_dependencia');
        $m = str_replace(" ","",isset($frm['mail'])?$frm['mail']:'');
        
        if($m!=''){
            $mails = explode(',',$m);
    
            foreach($mails as $mail){
                if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $this->container->get('session')->getFlashBag()->add('msgError', 'Formato de mail inválido.');
                    
                    return array(
                        'entity' => $entity,
                        'form'   => $form->createView(),
                    );
                }
            }
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('msgOk', 'Dependencia creada correctamente.');
            
            return $this->redirect($this->generateUrl('admin_dependencia', array('id' => $entity->getId())));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al crear la dependencia.');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Dependencia entity.
     *
     * @param Dependencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Dependencia $entity)
    {
        $form = $this->createForm(new DependenciaType(), $entity, array(
            'action' => $this->generateUrl('admin_dependencia_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Dependencia entity.
     *
     * @Route("admin/dependencia/new", name="admin_dependencia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Dependencia();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Dependencia entity.
     *
     * @Route("admin/dependencia/{id}", name="admin_dependencia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Dependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Dependencia entity.
     *
     * @Route("admin/dependencia/{id}/edit", name="admin_dependencia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Dependencia entity.');
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
    * Creates a form to edit a Dependencia entity.
    *
    * @param Dependencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Dependencia $entity)
    {
        $form = $this->createForm(new DependenciaType(), $entity, array(
            'action' => $this->generateUrl('admin_dependencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Dependencia entity.
     *
     * @Route("admin/dependencia/{id}", name="admin_dependencia_update")
     * @Method("PUT")
     * @Template("SEGURIDADSeguridadBundle:Dependencia:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Dependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        
        $frm = $request->get('seguridad_seguridadbundle_dependencia');
        $m = str_replace(" ","",isset($frm['mail'])?$frm['mail']:'');
        if($m!=''){
            $mails = explode(',',$m);
            
            foreach($mails as $mail){
                if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $this->container->get('session')->getFlashBag()->add('msgError', 'Formato de mail inválido.');
                    
                    return array(
                        'entity'      => $entity,
                        'edit_form'   => $editForm->createView(),
                        'delete_form' => $deleteForm->createView(),
                    );
                }
            }
        }

        if ($editForm->isValid()) {
            $em->flush();
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Dependencia modificada correctamente.');
            return $this->redirect($this->generateUrl('admin_dependencia_edit', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error modificar la dependencia.');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Dependencia entity.
     *
     * @Route("admin/dependencia/{id}", name="admin_dependencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if($id!=""){
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Dependencia entity.');
            }

            $entity->setBorrado(1);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add('msgOk', 'Dependencia eliminada correctamente.');
        }

        return $this->redirect($this->generateUrl('admin_dependencia'));
    }

    /**
     * Creates a form to delete a Dependencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_dependencia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * @Route("dependencia/autocompletar", name="dependencia_autocompletar", methods={"GET","POST"})
     */
    public function autocompletarAction(Request $request)
    {
        $filter['term'] = $request->get('term');
        
        $em = $this->getDoctrine()->getManager();
        
        $dependencias = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->autocompletar($filter);
        
        return new Response($this->get('gestion.herramientasservice')->leerEnFormatoJson($dependencias));
    }
    
}