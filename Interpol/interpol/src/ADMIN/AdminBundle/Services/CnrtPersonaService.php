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

class CnrtPersonaService 
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

    public function diasSinRegistrosCnrt() {
    	$dias = 0;
        
        $maxFechaConsulta = $this->em->getRepository('ADMINAdminBundle:CnrtPersona')->getMaxFechaConsulta();
        
        if(isset($maxFechaConsulta[0]["maxFechaConsulta"])){
            $maxFC = new DateTime($maxFechaConsulta[0]["maxFechaConsulta"]);
            $now = new DateTime();
            $diff = $maxFC->diff($now);
            
            $dias = $diff->format('%d');
        }
        
    	return $dias;
    }
}


