<?php

namespace ADMIN\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ADMIN\AdminBundle\Entity\Mapa;
use ADMIN\AdminBundle\Form\MapaType;
use ADMIN\AdminBundle\Form\MapaDependenciaType;
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
use SEGURIDAD\SeguridadBundle\Entity\Dependencia;

/**
 * Mapa controller.
 *
 */
class MapaController extends Controller
{
    
    //     * @Method({"GET","POST"})
    /**
     * Lists all Mapa entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em             = $this->getDoctrine()->getManager();
        $filter         = [];
        $txtNombre      = $request->get( 'txtNombre'      )  ? $request->get( 'txtNombre'      ) : "";
        $txtDescripcion = $request->get( 'txtDescripcion' )  ? $request->get( 'txtDescripcion' ) : "";
        $filter    = array(
            "txtNombre"      => $txtNombre,
            "txtDescripcion" => $txtDescripcion
        );
        
        $query = $em->getRepository('ADMINAdminBundle:Mapa')->getByFilter($filter);
        
        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Descripcion;Latitud;Longitud;Nombre;Zoom";
            
            foreach ($query->getResult() as $event) {
                $data = array(
                    $event->getId(),
                    $event->getDescripcion(),
                    $event->getLatitud(),
                    $event->getLongitud(),
                    $event->getNombre(),
                    $event->getZoom()
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="mapa_export_'.date('Y-m-d').'.csv"');
            
            ob_clean();
            
            return $response;
        }
        
        if($request->get('page')!=''){
            $page = $request->get('page');
        }
        
        $entities = new Pagerfanta(new DoctrineORMAdapter($query));
        $entities->setMaxPerpage(50);
        $entities->setCurrentPage($page);
        
        return $this->render('ADMINAdminBundle:Mapa:index.html.twig',
            array(
                'entities' => $entities
            )
            );
        
    }
    /**
     * Creates a new Mapa entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Mapa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El mapa '.$entity.' fue creado correctamente.');
            
            return $this->redirect($this->generateUrl('admin_mapa', array('id' => $entity->getId())));
        
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar crear el mapa.');
        }        
        
        return $this->render('ADMINAdminBundle:Mapa:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Mapa entity.
     *
     * @param Mapa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Mapa $entity)
    {
        $form = $this->createForm(new MapaType(), $entity, array(
            'action' => $this->generateUrl('admin_mapa_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Mapa entity.
     *
     */
    public function newAction()
    {
        $entity = new Mapa();
        $form   = $this->createCreateForm($entity);

        return $this->render('ADMINAdminBundle:Mapa:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Mapa entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:Mapa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mapa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ADMINAdminBundle:Mapa:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Mapa entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:Mapa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mapa entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ADMINAdminBundle:Mapa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Mapa entity.
    *
    * @param Mapa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Mapa $entity)
    {
        $form = $this->createForm(new MapaType(), $entity, array(
            'action' => $this->generateUrl('admin_mapa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Mapa entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        echo "updateAction";
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:Mapa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mapa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El mapa '.$entity.' fue editado correctamente.');
            
            return $this->redirect($this->generateUrl('admin_mapa', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar editar el mapa.');
        }

        return $this->render('ADMINAdminBundle:Mapa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Mapa entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ADMINAdminBundle:Mapa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mapa entity.');
            }

            //$em->remove($entity);
            //$em->flush();
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'El mapa '.$entity.' fue borrado correctamente.');
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar borrar el mapa.');
        }

        return $this->redirect($this->generateUrl('admin_mapa'));
    }

    /**
     * Creates a form to delete a Mapa entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_mapa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * mapaDependencia a Mapa entity.
     *
     */
    public function mapaDependenciaAction(Request $request, $id)
    {
        $dependencia = $request->get('txtDescripcion');
        $accion      = $request->get('accion');
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ADMINAdminBundle:Mapa')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mapa entity.');
        }
        $editForm = $this->createMapaDependenciaForm($entity, $dependencia);
        $editForm->handleRequest($request);
        
        if($accion!="busqueda"){
            if ($editForm->isValid()) {
                $em->flush();
                $this->container->get('session')->getFlashBag()->add('msgOk', 'Dependencias del mapa '.$entity.' editadas correctamente.');
                return $this->redirect($this->generateUrl('admin_mapa_mapaDependencia', array('id' => $id)));
            }
        }
        return $this->render('ADMINAdminBundle:Mapa:mapaDependencia.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
        
    }
    
    /**
     * Creates a form to edit perfiles of Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createMapaDependenciaForm(Mapa $entity, $dependencia)
    {
        $form = $this->createForm(new MapaDependenciaType($dependencia), $entity, array(
            'action' => $this->generateUrl('admin_mapa_mapaDependencia', array('id' => $entity->getId())),
            'method' => 'PUT'
        ));
        
        return $form;
    }
    
}
