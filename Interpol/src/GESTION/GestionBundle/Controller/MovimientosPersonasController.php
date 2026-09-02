<?php
namespace GESTION\GestionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use GESTION\GestionBundle\Repository\InterpolRepository;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalle;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalleRepository;

/**
 * MovimientosPersonas controller.
 *
 */
class MovimientosPersonasController extends Controller {

    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        return $this->render('GESTIONGestionBundle:MovimientosPersonas:index.html.twig', array(
            'usuario'=>$usuario
        ));
    }
    
    /**
    * Muestra consulta realizada
    */
    public function showAction(Request $request, $id = 0) {
        
        $nominals=null;
        return $this->render( 'GESTIONGestionBundle:MovimientosPersonas:show.html.twig', array( 'nominals' => $nominals ) );
    }
    
    public function showdetailsAction(Request $request, $id = 0) {
        
        return $this->render('GESTIONGestionBundle:MovimientosPersonas:showdetails.html.twig', array('nominalDetails' => null ) );
    }   //  public function showdetailsAction(Request $request, $id = 0) {
    
}
?>