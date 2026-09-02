<?php

class MyHandlerClassDecorator {

    //Atributos
    private $decorated = null;
    private $logRoot;
    private $idConsulta;

    //Getters y Setters
    public function getLogRoot() {
        return $this->logRoot;
    }

    public function setLogRoot($logRoot) {
        $this->logRoot = $logRoot;
    }

    //Constructor
    public function __construct(Conexion $decorated,$HTTP_RAW,$idConsulta) {
        $this->decorated = $decorated;
        $this->idConsulta = (isset($idConsulta)) ? $idConsulta : '';
        $this->inicializarLogger();        
        $this->decorated->setHTTP_RAW_POST_DATA($HTTP_RAW);                 
    }

    //Métodos
    public function __call($method, $params) {  
        
            $this->getLogRoot()->info('METODO ['.$this->idConsulta.']:'.$method);
            $this->getLogRoot()->info('PARAMS ['.$this->idConsulta.']:'.json_encode($params));
            //$this->getLogRoot()->trace('DECORATED:'. print_r($this->decorated,true));       
        
        // do something with the $method and $params
        // then call the real $method
        if (method_exists($this->decorated, $method)) {            
            return call_user_func_array(
                    array($this->decorated, $method), $params);
            
        } else {
            $this->getLogRoot()->error('ERROR DE MÉTODO 1:'.print_r($method));
            $this->getLogRoot()->error('ERROR DE MÉTODO PARAMS:'.print_r($params));
            throw new BadMethodCallException();
        }
    }

    protected function inicializarLogger() {
        $this->setLogRoot($this->decorated->getLogRoot());
    }

}
