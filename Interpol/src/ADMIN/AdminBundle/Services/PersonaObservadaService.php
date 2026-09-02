<?php

namespace ADMIN\AdminBundle\Services;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ADMIN\AdminBundle\Entity\PersonaObservada;
use DateTime;
include_once ('/apache/includes/ambiente.php');

class PersonaObservadaService 
{
    /** 
     * 
     * @var Container
     */
    public $container;

    /**
     * @var EntityManager
     */
    public $em;
    
    /**
     * @var Session
     */
    public $session;

    public function __construct(Container $container){              
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->session = $container->get('session');
    }

    public function movimientosPendientes($user = null, $personaObservada = null) {
        $fDesde_A = null;
        $fHasta_A = null;
        $fDependencia = null;
    	
        if(!empty($user)){
            $perfiles = $user->getPerfilid();
            $fDependencia = $user->getDepenid()->getId();
            foreach($perfiles as $perfil){
                if($perfil->getId()==1){
                    $fDependencia = null;
                }
            }
        }
        
    	$filter = array(
    	        "fPersonaObservada" => $personaObservada,
    			"fDesde"     => $fDesde_A,
    			"fHasta"     => $fHasta_A,
    	        "fEstado"    => 'P',
    	        "fDependencia" => $fDependencia
    	);
    	
    	$pendientes = $this->em->getRepository('ADMINAdminBundle:ConsultaPersonaObservada')->getByFilter($filter)->getResult();
    	
    	return sizeof($pendientes);
    }
    
    public function vencimientosPersonaObservada($dependencia){
        $aVencerse = $this->em->getRepository('ADMINAdminBundle:PersonaObservada')->getVencimientos($dependencia)->getResult();
        
        return $aVencerse;
    }
}


