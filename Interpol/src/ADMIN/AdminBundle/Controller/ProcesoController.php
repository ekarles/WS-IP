<?php

namespace ADMIN\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ADMIN\AdminBundle\Entity\Proceso;
use ADMIN\AdminBundle\Entity\Parametro;
use ADMIN\AdminBundle\Form\ProcesoType;
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
use RuntimeException;
use Symfony\Component\Console\Output\StreamOutput;
use ADMIN\AdminBundle\Services\StreamedOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ADMIN\AdminBundle\Command\PersonaObservadaCommand;
use ADMIN\AdminBundle\Command\CnrtInterpolCommand;
use ADMIN\AdminBundle\Command\ErroresWISDMCommand;


/**
 * Proceso controller.
 *
 */
class ProcesoController extends Controller
{    
    private $nombre;
    
    /**
     * Lists all Proceso entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        
        $txtNombre   = $request->get( 'txtNombre'    ) ? $request->get( 'txtNombre'    ) : "";
        $fDesde      = $request->get( 'txtFechaDesde') ? $request->get( 'txtFechaDesde') : "";
        $fHasta      = $request->get( 'txtFechaHasta') ? $request->get( 'txtFechaHasta') : "";
        
        if($fDesde!=''){
            $fDesde_A = substr($fDesde, 8, 2) . "/" . substr($fDesde, 5, 2) . "/" . substr($fDesde, 0, 4) . " " . substr($fDesde, 11, 5) . ":00";
        }else{
            $fDesde_A = "";
        }
        if($fHasta!=''){
            $fHasta_A = substr($fHasta, 8, 2) . "/" . substr($fHasta, 5, 2) . "/" . substr($fHasta, 0, 4) . " " . substr($fHasta, 11, 5) . ":59";
        }else{
            $fHasta_A = "";
        }
        
        $filter    = array(
            "fDesde"        => $fDesde_A,
            "fHasta"        => $fHasta_A,
            "txtNombre"      => $txtNombre
        );
        
        
        $query = $em->getRepository('ADMINAdminBundle:Proceso')->getByFilter($filter);
        
        if($request->get('accion') == 'csv'){
            $rows = array();
            $rows []= "Id;Nombre;Fecha Desde;Fecha Hasta;Resultado";
            
            foreach ($query->getResult() as $event) {
                $data = array(
                    $event->getId(),
                    $event->getNombre(),
                    $event->getFechaIni()->format('d-m-Y H:m:s'),
                    $event->getFechaFin()->format('d-m-Y H:m:s'),
                    str_replace("\n", "", $event->getResultado())
                );
                
                $rows[] = implode(';', $data);
            }
            
            $content = implode("\n", $rows);
            
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="procesos_export_'.date('Y-m-d').'.csv"');
            
            ob_clean();
            
            return $response;
        }
        
        if($request->get('page')!=''){
            $page = $request->get('page');
        }
        
        $entities = new Pagerfanta(new DoctrineORMAdapter($query));
        $entities->setMaxPerpage(50);
        $entities->setCurrentPage($page);
        
        return $this->render('ADMINAdminBundle:Proceso:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Proceso entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ADMINAdminBundle:Proceso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proceso entity.');
        }

        return $this->render('ADMINAdminBundle:Proceso:show.html.twig', array(
            'entity'      => $entity
        ));
    }
    
    /**
     * Show process in realtime
     *
     */
    public function testAction(Request $request){
        $this->nombre = $request->get('nombre');

        $response = new StreamedResponse(function() {
            
            $arrInput=[];
            switch($this->nombre){
                case 'admin:admin:cnrt_interpol':
                    $command = new CnrtInterpolCommand();
                    break;
                case 'admin:admin:cnrt_reprocesar':
                    $command = new CnrtInterpolCommand();
                    $arrInput=['funcion'=>'reprocesar'];
                    break;
                case 'admin:admin:persona_observada':
                    $command = new PersonaObservadaCommand();
                    break;
                case 'admin:admin:errores_wisdm':
                    $command = new ErroresWISDMCommand();
                    break;
                default:
                    echo "Error: No se recibió un nombre de proceso correcto.";
                    exit();
            }
            
            $input = new ArrayInput($arrInput);
            $input->setInteractive(false);
            
            $output = new StreamedOutput(fopen('php://stdout', 'w'));
            
            $command->setContainer($this->container);
            $command->run($input,$output);
        });
            
        return $response;
    }
    
    public function parametrosAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        
        $txtNombre   = $request->get( 'txtNombre'    ) ? $request->get( 'txtNombre'    ) : "";
        
        $filter    = array(
            "txtNombre"      => $txtNombre
        );
        
        $query = $em->getRepository('ADMINAdminBundle:Proceso')->getParametros($filter);
        
        if($request->get('page')!=''){
            $page = $request->get('page');
        }
        
        $entities = new Pagerfanta(new DoctrineORMAdapter($query));
        $entities->setMaxPerpage(50);
        $entities->setCurrentPage($page);
        
        return $this->render('ADMINAdminBundle:Proceso:parametros.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    public function updateParametroAction(Request $request){
        
        $estado = "OK";
        $mensaje = "ACTUALIZACION FINALIZADA CORRECTAMENTE";
        
        $txtId   = $request->get( 'id');
        $txtValor = $request->get("valor");
        $txtValorChar = $request->get("valorchar");
        
        $parametros    = array(
            "id"    => $txtId,
            "valornum" => $txtValor,
            "valorchar" => $txtValorChar
        );
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ADMINAdminBundle:Proceso')->updateParametro($parametros);
        
        if(!$query){
            $estado = "ERROR";
            $mensaje = "ERROR AL INTENTAR EJECUTAR LA ACTUALIZACION.";
        }else{
            
        }
        
        //$this->container->get('session')->getFlashBag()->add('msgErr', 'Parametro actualizado correctamente "'.$request->get( 'id').'".');
        
        $oRetorno = '{"estado":'.$estado.', "mensaje":'.$mensaje.'}';
        
        return new JsonResponse( $oRetorno );
    }
    
    
}
