<?php

namespace ADMIN\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ADMIN\AdminBundle\Entity\PersonaObservada;
use ADMIN\AdminBundle\Entity\AuditoriaPerObs;
use ADMIN\AdminBundle\Form\PersonaObservadaType;
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
 * ConsultaPersonaObservada controller.
 *
 */
class ConsultaPersonaObservadaController extends Controller
{

    
    /**
     * Finds and displays a ConsultaPersonaObservada entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaObservada entity.');
        }
        
        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render('ADMINAdminBundle:ConsultaPersonaObservada:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));

    }
    
    
    /**
     * Finds and displays a InterpolLogMv entity.
     *
     */
    public function interpolLogAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('ADMINAdminBundle:ConsultaPersonaObservada')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaObservada entity.');
        }
        
        return $this->render('ADMINAdminBundle:PersonaObservada:interpolLog.html.twig', array(
            'entity'      => $entity
        ));
    }

    
    /**
     * Cambia el estado de una ConsultaPersonaObservada entity.
     *
     */
    public function estadoAction(Request $request, $id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $estado = $request->get('estado');
        
        $entity = $em->getRepository('ADMINAdminBundle:ConsultaPersonaObservada')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConsultaPersonaObservada entity.');
        }
        
        $entityOld = clone($entity);
        
        $this->container->get('session')->getFlashBag()->add('msgOk', 'Se cambió el estado correctamente.');
        $entity->setEstado($estado);
        
        $auditoria = new AuditoriaPerObs();
        $auditoria->setUsuAlta($this->getUser());
        $auditoria->setPersonaObservada($entity->getPersonaObservada());
        $auditoria->setFecAlta(new \Datetime());
        
        $accion = "ESTADO CONSULTA PERSONA OBSERVADA";
        $accion.= " - ID CONSULTA=\"".$entity->getId()."\" FECHA CONSULTA=\"".$entity->getIaId()->getIaTimestamp()->format('d/m/Y H:m:s')."\"";
        $accion.= " - ESTADO[old]=\"".$entityOld->getEstado()."\" [new]=\"".$entity->getEstado()."\"";
        $auditoria->setAccion($accion);
        $em->persist($auditoria);        
        
        $em->flush();
        
        
        return $this->redirect($this->generateUrl('gestion_personaobservada_consultas',['id'=>$entity->getPersonaObservada()->getId()]));
    }

    
    
}
