<?php

/**
 * Description of Auditoria
 *
 * @author pfa27667140
 */
require_once('../../Utilidades/BD/Conexion.php');

class Auditoria extends Conexion{
 
    
    public function __construct() {
        parent::__construct();
    }
    
    protected function Auditar(IntiModel $consulta){
        var_dump($consulta);
        
        
        //$this->guardarConsulta($objeto);
        return true;
    }

}
