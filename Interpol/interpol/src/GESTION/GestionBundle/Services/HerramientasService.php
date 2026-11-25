<?php

namespace GESTION\GestionBundle\Services;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

include_once ('/apache/includes/ambiente.php');

class HerramientasService 
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

    public function leerEnFormatoJson($results) {
    	$resultado = array();
    	foreach ($results as $fila) {
    		$fila = array_values($fila);
    		if (count($fila) > 2) {
    			if (isset($fila[4])) {
    				$desc4 = $fila[4];
    			} else {
    				$desc4 = '';
    			}
    			if (isset($fila[5])) {
    				$desc5 = $fila[5];
    			} else {
    				$desc5 = '';
    			}
    			
    			array_push($resultado, array("id" => $fila[0],
    					"label" => $fila[1],
    					"value" => strip_tags($fila[1]),
    					"desc" => $fila[2],
    					"desc1" => (isset($fila[3])?$fila[3]:""),
    					"desc2" => (isset($desc4)?$desc4:""),
    					"desc3" => (isset($desc5)?$desc5:"")
    			)
    					);
    		} else {
    			array_push($resultado, array("id" => $fila[0],
    					"label" => $fila[1],
    					"value" => strip_tags($fila[1])
    			)
    					);
    		}
    	}
    	$resultado = $this->deArrayAJson($resultado);
    	return $resultado;
    }
    
    private function deArrayAJson($array) {
    	if (!is_array($array)) {
    		return false;
    	}
    	$associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));
    	if ($associative) {
    		$construct = array();
    		foreach ($array as $key => $value) {
    			// We first copy each key/value pair into a staging array,
    			// formatting each key and value properly as we go.
    			// Format the key:
    			if (is_numeric($key)) {
    				$key = "key_$key";
    			}
    			$key = "\"" . addslashes($key) . "\"";
    			// Format the value:
    			if (is_array($value)) {
    				$value = $this->deArrayAJson($value);
    			} else if (!is_numeric($value) || is_string($value)) {
    				$value = "\"" . addslashes($value) . "\"";
    			}
    			// Add to staging array:
    			$construct[] = "$key: $value";
    		}
    		// Then we collapse the staging array into the JSON form:
    		$result = "{ " . implode(", ", $construct) . " }";
    	} else { // If the array is a vector (not associative):
    		$construct = array();
    		foreach ($array as $value) {
    			// Format the value:
    			if (is_array($value)) {
    				$value = $this->deArrayAJson($value);
    			} else if (!is_numeric($value) || is_string($value)) {
    				$value = "'" . addslashes($value) . "'";
    			}
    			// Add to staging array:
    			$construct[] = $value;
    		}
    		// Then we collapse the staging array into the JSON form:
    		$result = "[ " . implode(", ", $construct) . " ]";
    	}
    	return $result;
    }
    
    public function existe_archivo($filename){
        return file_exists($filename);
    }
    
    public function obtener_hash($string){
        return strtoupper( hash( "adler32", crypt( strtoupper( $string ), strtoupper( $this->getParameter('secret') ) ) ) );
    }
    
    
    /**
     * Set ParameterBag for repository
     *
     * @param ParameterBagInterface $params
     */
    public function setParameterBag(ParameterBagInterface $params)
    {
        $this->parameterBag = $params;
    }
    
    /**
     * Get parameter from ParameterBag
     *
     * @param string $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->parameterBag->get($name);
    }
}


