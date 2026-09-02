<?php

/**
 * Description of Auditoria

 */
require_once('../../Utilidades/BD/Conexion.php');

class Auditoria extends Conexion{
 
    
    public function __construct() {
        parent::__construct();
    }
    
    protected function Auditar(IntiModel $consulta){
        return true;
    }

}
