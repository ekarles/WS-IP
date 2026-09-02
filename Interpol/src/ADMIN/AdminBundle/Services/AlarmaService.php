<?php

namespace ADMIN\AdminBundle\Services;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
include_once ('/apache/includes/ambiente.php');

class AlarmaService 
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

    public function alarmasUltimas24Hs() {
    	// Query últimas 24 hs
    	if(AMBIENTE=='PRODUCCION'){
    		$date = new DateTime();
    		$date->modify("-1 day");
    	}else{
    		$date = new DateTime('2018-10-10 00:00:00');
    	}
    	$fDesde_A = $date->format("d/m/Y H:i:s");
    	$date->modify("+1 day");
    	$fHasta_A = $date->format("d/m/Y H:i:s");
    	
    	$filter24 = array(
    			"fDesde"     => $fDesde_A,
    			"fHasta"     => $fHasta_A,
    	        "lstTipoAlar"   => "",
                "pendiente"  => true
    	);
    	
    	$ultimas24 = $this->em->getRepository('ADMINAdminBundle:Alarma')->getByFilter($filter24)->getResult();
    	
    	return sizeof($ultimas24);
    }
}


