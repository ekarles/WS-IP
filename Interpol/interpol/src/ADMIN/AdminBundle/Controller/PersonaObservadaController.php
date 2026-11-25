<?php

namespace ADMIN\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ADMIN\AdminBundle\Entity\PersonaObservada;
use ADMIN\AdminBundle\Entity\AuditoriaPerObs;
use ADMIN\AdminBundle\Form\PersonaObservadaType;
use ADMIN\AdminBundle\Form\PersonaObsDependenciaType;
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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * PersonaObservada controller.
 *
 */
class PersonaObservadaController extends Controller
{
    private $admin=false;

    /**
     * Lists all PersonaObservada entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $txtApellido    = $request->get( 'txtApellido'    )  ? $request->get( 'txtApellido'    ) : "";
        $txtNombre      = $request->get( 'txtNombre'      )  ? $request->get( 'txtNombre'      ) : "";
        $lstEstado      = $request->get( 'lstEstado'      )  ? $request->get( 'lstEstado'      ) : "";

        $user = $this->getUser();
        $perfiles = $user->getPerfilid();
        $fDependencia = $user->getDepenid()->getId();
        foreach($perfiles as $perfil){
            if($perfil->getId()==1){
                $fDependencia = null;
            }
        }


        $filter    = array(
            "txtNombre"      => $txtNombre,
            "txtApellido"    => $txtApellido,
            "lstEstado"      => $lstEstado,
            "fDependencia"   => $fDependencia
        );


        $query = $em->getRepository('ADMINAdminBundle:PersonaObservada')->getByFilter($filter);

        if($request->get('accion') == 'csv'){
            $rows = array();

            foreach($perfiles as $perfil){
                if($perfil->getId()==1){
                    $this->admin = true;
                }
            }

            if($this->admin){
                $rows []= "Id;Apellido;Nombre;Estado;Fecha Nac.;Fecha Desde;Fecha Hasta;Dependencias;Usuario Alta;Fecha Alta;Usuario Mod;Fecha Mod;Mov. Pendientes";
            }else{
                $rows []= "Id;Apellido;Nombre;Estado;Fecha Nac.;Fecha Desde;Fecha Hasta;Mov. Pendientes";
            }

            foreach ($query->getResult() as $event) {

                if($isAdmin){
                    $data = array(
                        $event->getId(),
                        utf8_decode($event->getApellido()),
                        utf8_decode($event->getNombre()),
                        ($event->getBorrado()=="0"?"ACTIVO":"INACTIVO"),
                        substr($event->getFechaNac(),6,2).'/'.substr($event->getFechaNac(),4,2).'/'.substr($event->getFechaNac(),0,4),
                        ($event->getFecDesde()!==null ? $event->getFecDesde()->format('d/m/Y') : ''),
                        ($event->getFecHasta()!==null ? $event->getFecHasta()->format('d/m/Y') : ''),
                        $event->getDependencia(),
                        $event->getUsuAlta(),
                        $event->getFecAlta()->format('d/m/Y H:i:s'),
                        $event->getUsuMod(),
                        ($event->getFecMod()!==null?$event->getFecMod()->format('d/m/Y H:i:s'):''),
                        $this->get('admin.personaobservadaservice')->movimientosPendientes($this->getUser(),$event->getId())
                    );
                }else{
                    $data = array(
                        $event->getId(),
                        utf8_decode($event->getApellido()),
                        utf8_decode($event->getNombre()),
                        ($event->getBorrado()=="0"?"ACTIVO":"INACTIVO"),
                        substr($event->getFechaNac(),6,2).'/'.substr($event->getFechaNac(),4,2).'/'.substr($event->getFechaNac(),0,4),
                        ($event->getFecDesde()!==null ? $event->getFecDesde()->format('d/m/Y') : ''),
                        ($event->getFecHasta()!==null ? $event->getFecHasta()->format('d/m/Y') : ''),
                        $this->get('admin.personaobservadaservice')->movimientosPendientes($this->getUser(),$event->getId())
                    );
                }

                $rows[] = implode(';', $data);
            }

            $content = implode("\n", $rows);

            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="aviso_movimientos_export_'.date('Y-m-d').'.csv"');

            ob_clean();

            return $response;
        }

        if($request->get('page')!=''){
            $page = $request->get('page');
        }

        $entities = new Pagerfanta(new DoctrineORMAdapter($query));
        $entities->setMaxPerpage(50);
        $entities->setCurrentPage($page);

        return $this->render('ADMINAdminBundle:PersonaObservada:index.html.twig', array(
            'entities' => $entities,
        ));
    }


    /**
     * Lists all PersonaObservada entities.
     *
     */
    public function consultasAction(Request $request, $id, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $txtUsuario  = $request->get( 'txtUsuario'   ) ? $request->get( 'txtUsuario') : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde') ? $request->get( 'txtFechaDesde') : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta') ? $request->get( 'txtFechaHasta') : ""  ;
        $txtDependencia   = $request->get( 'txtDependencia') ? $request->get( 'txtDependencia') : ""  ;
        $lstResultado   = $request->get( 'lstResultado') ? $request->get( 'lstResultado') : ""  ;
        $lstEstado     = $request->get( 'lstEstado'      ) ? $request->get( 'lstEstado') : ""  ;
        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
            $fDesde_A = "";
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
            $fHasta_A = "";
        }

        $filter = array(
            "fDesde"        => $fDesde_A,
            "fHasta"        => $fHasta_A,
            "txtUsuario"    => $txtUsuario,
            "txtDependencia"=> $txtDependencia,
            "lstResultado"  => $lstResultado,
            "lstEstado"     => $lstEstado
        );

        $personaObservada = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);
        $query = $em->getRepository('ADMINAdminBundle:PersonaObservada')->getConsultas($id, $filter);

        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Fecha consulta;Usuario;Dependencia;Resultado;Estado";

            foreach ($query->getResult() as $event) {
                if($event->getIaId()->getIaResultCode() == 'NO_ERROR'){
                    $resultCode = 'POSITIVO(NO_ERROR)';
                }else if( $event->getIaId()->getIaResultCode() == 'NO_ANSWER'){
                    $resultCode = 'NEGATIVO(NO_ANSWER)';
                }else{
                    $resultCode = $event->getIaId()->getIaResultCode();
                }

                $data = array(
                    $event->getId(),
                    $event->getIaId()->getIaTimestamp()->format('d/m/Y H:i:s'),
                    $event->getIaId()->getIaUsuario(),
                    $event->getIaId()->getIaUsuarioDependencia(),
                    $resultCode,
                    $event->getEstado()
                );

                $rows[] = implode(';', $data);
            }

            $content = implode("\n", $rows);

            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="movimientos_'.str_replace(' ','_',$event->getPersonaObservada()).'_'.date('Y-m-d').'.csv"');

            ob_clean();

            return $response;
        }

        if($request->get('page')!=''){
            $page = $request->get('page');
        }

        $entities = new Pagerfanta(new DoctrineORMAdapter($query));
        $entities->setMaxPerpage(50);
        $entities->setCurrentPage($page);

        return $this->render('ADMINAdminBundle:PersonaObservada:indexConsultas.html.twig', array(
            'personaObservada' => $personaObservada,
            'entities' => $entities
        ));
    }


    /**
     * Lists all AuditoriaPersonaObservada entities.
     *
     */
    public function auditoriaAction(Request $request, $id, $page = 1)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            throw new AccessDeniedHttpException('You cannot access this page!');
        }
        
        $em = $this->getDoctrine()->getManager();

        $txtUsuario  = $request->get( 'txtUsuario'   ) ? $request->get( 'txtUsuario') : ""  ;
        $fDesde      = $request->get( 'txtFechaDesde') ? $request->get( 'txtFechaDesde') : ""  ;
        $fHasta      = $request->get( 'txtFechaHasta') ? $request->get( 'txtFechaHasta') : ""  ;

        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " 00:00:00";
        }else{
            $fDesde_A = "";
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " 23:59:59";
        }else{
            $fHasta_A = "";
        }

        $filter = array(
            "fDesde"        => $fDesde_A,
            "fHasta"        => $fHasta_A,
            "txtUsuario"    => $txtUsuario
        );

        $personaObservada = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);
        $query = $em->getRepository('ADMINAdminBundle:PersonaObservada')->getAuditoria($id, $filter);
        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Fecha Y Hora;Usuario;Accion";

            foreach ($query->getResult() as $event) {
                $data = array(
                    $event->getId(),
                    $event->getFecAlta()->format('d/m/Y H:i:s'),
                    $event->getUsuAlta(),
                    utf8_decode($event->getAccion())
                );

                $rows[] = implode(';', $data);
            }

            $content = implode("\n", $rows);

            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="auditoria_'.str_replace(' ','_',$event->getPersonaObservada()).'_'.date('Y-m-d').'.csv"');

            ob_clean();

            return $response;
        }

        if($request->get('page')!=''){
            $page = $request->get('page');
        }

        $entities = new Pagerfanta(new DoctrineORMAdapter($query));
        $entities->setMaxPerpage(50);
        $entities->setCurrentPage($page);

        return $this->render('ADMINAdminBundle:PersonaObservada:indexAuditoria.html.twig', array(
            'personaObservada' => $personaObservada,
            'entities' => $entities
        ));
    }


    /**
     * Creates a new PersonaObservada entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new PersonaObservada();
        $form = $this->createCreateForm($entity);
        $error = false;

        if(isset($request->get('admin_adminbundle_personaobservada')['fechaNacimiento'])&&$request->get('admin_adminbundle_personaobservada')['fechaNacimiento']!=''){
            $fecha = new \Datetime($request->get('admin_adminbundle_personaobservada')['fechaNacimiento']);
            $entity->setFechaNac($fecha->format('Ymd'));
        }

        $form->handleRequest($request);

        $frm = $request->get('admin_adminbundle_personaobservada');
        $m = str_replace(" ","",isset($frm['mail'])?$frm['mail']:'');
        
        if($m!=''){
            $mails = explode(',',$m);
            
            foreach($mails as $mail){
                if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $this->container->get('session')->getFlashBag()->add('msgError', 'Formato de mail inválido.');
                    $error=true;
                }
                
                $mailAux = explode('@',$mail);
                
                if($mailAux[1]!='policiafederal.gov.ar' && $mailAux[1]!='interpol.gov.ar'){
                    $this->container->get('session')->getFlashBag()->add('msgError', 'Formato de mail inválido. Solo se admiten direcciones con dominios "policiafederal.gov.ar" o "interpol.gov.ar".');
                    $error=true;
                }
                
                if($error){
                    return $this->render('ADMINAdminBundle:PersonaObservada:new.html.twig', array(
                        'entity' => $entity,
                        'form'   => $form->createView(),
                    ));
                }
            }
        }
        
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setBorrado(0);
            $entity->setUsuAlta($this->getUser());
            $entity->setFecAlta(new \Datetime());
            $entity->setDependencia($this->getUser()->getDepenid());

            $em->persist($entity);
            $em->flush();

            $auditoria = new AuditoriaPerObs();
            $auditoria->setUsuAlta($this->getUser());
            $auditoria->setPersonaObservada($entity);
            $auditoria->setFecAlta($entity->getFecAlta());
            $auditoria->setAccion("ALTA DE AVISO DE MOVIMIENTOS");

            $em->persist($auditoria);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('msgOk', 'La persona "'.$entity.'" fue agregada correctamente.');
            
            return $this->redirect($this->generateUrl('gestion_personaobservada'));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar agregar la persona "'.$entity.'".');
        }

        return $this->render('ADMINAdminBundle:PersonaObservada:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a PersonaObservada entity.
     *
     * @param PersonaObservada $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PersonaObservada $entity)
    {
        $form = $this->createForm(new PersonaObservadaType(), $entity, array(
            'action' => $this->generateUrl('gestion_personaobservada_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new PersonaObservada entity.
     *
     */
    public function newAction()
    {
        $entity = new PersonaObservada();
        $form   = $this->createCreateForm($entity);

        return $this->render('ADMINAdminBundle:PersonaObservada:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PersonaObservada entity.
     *
     */
    public function showAction(Request $request, $id)
    {
        $confirm = $request->get('confirm')!='' ? true : false;

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaObservada entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        foreach($this->getUser()->getPerfilid() as $perfil){
            if($perfil->getId()==1){
                $this->admin = true;
            }
        }
        
        if(!$this->admin && $entity->getDependencia()->getId()!=$this->getUser()->getDepenid()->getId()){
            throw $this->createAccessDeniedException('You cannot access this page!');
        }        
        
        return $this->render('ADMINAdminBundle:PersonaObservada:show.html.twig', array(
            'entity'      => $entity,
            'confirm'     => $confirm,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PersonaObservada entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaObservada entity.');
        }

        foreach($this->getUser()->getPerfilid() as $perfil){
            if($perfil->getId()==1){
                $this->admin = true;
            }
        }
        
        if(!$this->admin && $entity->getDependencia()->getId()!=$this->getUser()->getDepenid()->getId()){
            throw $this->createAccessDeniedException('You cannot access this page!');
        }        
        
        $editForm = $this->createEditForm($entity);

        return $this->render('ADMINAdminBundle:PersonaObservada:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a PersonaObservada entity.
    *
    * @param PersonaObservada $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PersonaObservada $entity)
    {
        $form = $this->createForm(new PersonaObservadaType(), $entity, array(
            'action' => $this->generateUrl('gestion_personaobservada_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing PersonaObservada entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);
        $error = false;
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaObservada entity.');
        }

        
        $perfiles = $this->getUser()->getPerfilid();
        
        foreach($perfiles as $perfil){
            if($perfil->getId()==1){
                $this->admin = true;
            }
        }
        
        $entityOld = clone($entity);

        if(isset($request->get('admin_adminbundle_personaobservada')['fechaNacimiento'])&&$request->get('admin_adminbundle_personaobservada')['fechaNacimiento']!=''){
            $fecha = new \Datetime($request->get('admin_adminbundle_personaobservada')['fechaNacimiento']);
            $entity->setFechaNac($fecha->format('Ymd'));
        }

        if(!$this->admin && $entity->getDependencia()->getId()!=$this->getUser()->getDepenid()->getId()){
            throw $this->createAccessDeniedException('You cannot access this page!');
        } 
        
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $frm = $request->get('admin_adminbundle_personaobservada');
        $m = str_replace(" ","",isset($frm['mail'])?$frm['mail']:'');
        
        if($m!=''){
            $mails = explode(',',$m);
            
            foreach($mails as $mail){
                if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $this->container->get('session')->getFlashBag()->add('msgError', 'Formato de mail inválido.');
                    $error=true;                    
                }
                
                $mailAux = explode('@',$mail);
                
                if($mailAux[1]!='policiafederal.gov.ar' && $mailAux[1]!='interpol.gov.ar'){
                    $this->container->get('session')->getFlashBag()->add('msgError', 'Formato de mail inválido. Solo se admiten direcciones con dominios "policiafederal.gov.ar" o "interpol.gov.ar".');
                    $error=true;
                }
                
                if($error){
                    return $this->render('ADMINAdminBundle:PersonaObservada:edit.html.twig', array(
                        'entity'      => $entity,
                        'edit_form'   => $editForm->createView()
                    ));
                }
            }
        }
        
        
        if ($editForm->isValid()) {

            $entity->setUsuMod($this->getUser());
            $entity->setFecMod(new \Datetime());
            $cambios=0;

            $auditoria = new AuditoriaPerObs();
            $auditoria->setUsuAlta($this->getUser());
            $auditoria->setPersonaObservada($entity);
            $auditoria->setFecAlta($entity->getFecMod());

            $accion = "EDICION DE AVISO DE MOVIMIENTOS";
            if($entityOld->getNombre()!=$entity->getNombre()){
                $accion.= " - NOMBRE[old]=\"".$entityOld->getNombre()."\" [new]=\"".$entity->getNombre()."\"";
                $cambios++;
            }
            if($entityOld->getApellido()!=$entity->getApellido()){
                $accion.= " - APELLIDO[old]=\"".$entityOld->getApellido()."\" [new]=\"".$entity->getApellido()."\"";
                $cambios++;
            }
            if($entityOld->getFechaNac()!=$entity->getFechaNac()){
                $accion.= " - FECHA NAC.[old]=\"".$entityOld->getFechaNac()."\" [new]=\"".$entity->getFechaNac()."\"";
                $cambios++;
            }
            if($entityOld->getFecDesde()!=$entity->getFecDesde()){
                $accion.= " - FECHA DESDE[old]=\"";
                if($entityOld->getFecDesde()!==NULL){
                    $accion.=$entityOld->getFecDesde()->format('d/m/Y');
                }
                $accion.="\" [new]=\"";
                if($entity->getFecDesde()!==NULL){
                    $accion.=$entity->getFecDesde()->format('d/m/Y');
                }
                $accion.="\"";
                $cambios++;
            }
            if($entityOld->getFecHasta()!=$entity->getFecHasta()){
                $accion.= " - FECHA HASTA[old]=\"";
                if($entityOld->getFecHasta()!==NULL){
                    $accion.=$entityOld->getFecHasta()->format('d/m/Y');
                }
                $accion.="\" [new]=\"";
                if($entity->getFecHasta()!==NULL){
                    $accion.=$entity->getFecHasta()->format('d/m/Y');
                }
                $accion.="\"";
                $cambios++;
            }
            if($entityOld->getMail()!=$entity->getMail()){
                $accion.= " - MAIL[old]=\"";
                if($entityOld->getMail()!==NULL){
                    $accion.=$entityOld->getMail();
                }
                $accion.="\" [new]=\"";
                if($entity->getMail()!==NULL){
                    $accion.=$entity->getMail();
                }
                $accion.="\"";
                $cambios++;
            }
            
            if($cambios>0){
                $auditoria->setAccion($accion);
                $em->persist($auditoria);
            }

            $em->flush();

            $this->container->get('session')->getFlashBag()->add('msgOk', 'La persona "'.$entity.'" fue editada correctamente.');

            return $this->redirect($this->generateUrl('gestion_personaobservada_edit', array('id' => $id)));
        }else{
            $this->container->get('session')->getFlashBag()->add('msgError', 'Error al intentar editar la persona "'.$entity.'".');
        }

        return $this->render('ADMINAdminBundle:PersonaObservada:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }
    /**
     * Deletes a PersonaObservada entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PersonaObservada entity.');
            }

            foreach($this->getUser()->getPerfilid() as $perfil){
                if($perfil->getId()==1){
                    $this->admin = true;
                }
            }
            
            if(!$this->admin && $entity->getDependencia()->getId()!=$this->getUser()->getDepenid()->getId()){
                throw $this->createAccessDeniedException('You cannot access this page!');
            }            
            
            $this->container->get('session')->getFlashBag()->add('msgOk', 'La persona "'.$entity.'" fue deshabilitada correctamente.');
            $entity->setBorrado(1);
            $entity->setUsuMod($this->getUser());
            $entity->setFecMod(new \Datetime());

            $auditoria = new AuditoriaPerObs();
            $auditoria->setUsuAlta($this->getUser());
            $auditoria->setPersonaObservada($entity);
            $auditoria->setFecAlta($entity->getFecMod());
            $auditoria->setAccion("AVISO DE MOVIMIENTOS DESHABILITADO");
            $em->persist($auditoria);

            $em->flush();
        }

        return $this->redirect($this->generateUrl('gestion_personaobservada'));
    }

    /**
     * Reactiva una PersonaObservada entity.
     *
     */
    public function reactivarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        foreach($this->getUser()->getPerfilid() as $perfil){
            if($perfil->getId()==1){
                $this->admin = true;
            }
        }
        
        if(!$this->admin && $entity->getDependencia()->getId()!=$this->getUser()->getDepenid()->getId()){
            throw $this->createAccessDeniedException('You cannot access this page!');
        } 
        
        $this->container->get('session')->getFlashBag()->add('msgOk', 'La persona "'.$entity.'" fue reactivada correctamente.');
        $entity->setBorrado(0);
        $entity->setUsuMod($this->getUser());
        $entity->setFecMod(new \Datetime());

        $auditoria = new AuditoriaPerObs();
        $auditoria->setUsuAlta($this->getUser());
        $auditoria->setPersonaObservada($entity);
        $auditoria->setFecAlta($entity->getFecMod());
        $auditoria->setAccion("AVISO DE MOVIMIENTOS REACTIVADO");
        $em->persist($auditoria);

        $em->flush();


        return $this->redirect($this->generateUrl('gestion_personaobservada'));
    }

    /**
     * Creates a form to delete a PersonaObservada entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gestion_personaobservada_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * PersonaObsDependencia a PersonaObservada entity.
     *
     */
    public function personaObsDependenciaAction(Request $request, $id)
    {
        $dependencia = $request->get('txtDescripcion');
        $accion      = $request->get('accion');
        $arrayDepen = array();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ADMINAdminBundle:PersonaObservada')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        foreach($entity->getDependencias() as $depen){
            $arrayDepen []= $depen->getId();
        }

        $jsonDepen = json_encode($arrayDepen);

        $editForm = $this->createPersonaObsDependenciaForm($entity, $dependencia);
        $editForm->handleRequest($request);

        if($accion!="busqueda"){
            if ($editForm->isValid()) {

                $auditoria = new AuditoriaPerObs();
                $auditoria->setUsuAlta($this->getUser());
                $auditoria->setPersonaObservada($entity);
                $auditoria->setFecAlta(new \Datetime());

                $accion = "EDICION DE AVISO DE MOVIMIENTOS";

                $arrayDependOld = json_decode($request->get('jsonDepen'));

                $arrayDepenNew = array();
                $cambios = 0;

                foreach($entity->getDependencias() as $depen){
                    $arrayDepenNew []= $depen->getId();
                    if(!in_array($depen->getId(),$arrayDependOld)){
                        $accion.= " - SE AGREGÓ LA DEPENDENCIA \"".$depen->getNombre()."\"";
                    }
                    $cambios++;
                }

                foreach($arrayDependOld as $depenOld){
                    if(!in_array($depenOld,$arrayDepenNew)){
                        $dependenciaOld = $em->getRepository('SEGURIDADSeguridadBundle:Dependencia')->find($depenOld);

                        $accion.= " - SE QUITÓ LA DEPENDENCIA \"".$dependenciaOld->getNombre()."\"";
                    }
                    $cambios++;
                }

                if($cambios>0){
                    $auditoria->setAccion($accion);
                    $em->persist($auditoria);
                }

                $em->flush();
                $this->container->get('session')->getFlashBag()->add('msgOk', 'Dependencias de la persona '.$entity.' asignadas correctamente.');
                return $this->redirect($this->generateUrl('gestion_personaobservada_personaObsDependencia', array('id' => $id)));
            }
        }
        return $this->render('ADMINAdminBundle:PersonaObservada:personaObsDependencia.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'jsonDepen'   => $jsonDepen
        ));

    }

    /**
     * Creates a form to edit perfiles of Usuario entity.
     *
     * @param PersonaObservada $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createPersonaObsDependenciaForm(PersonaObservada $entity, $dependencia)
    {
        $form = $this->createForm(new PersonaObsDependenciaType($dependencia), $entity, array(
            'action' => $this->generateUrl('gestion_personaobservada_personaObsDependencia', array('id' => $entity->getId())),
            'method' => 'PUT'
        ));

        return $form;
    }

}
