<?php

include_once('/apache/includes/ambiente.php');

final class UsuarioGenerico {
    
    private static $usuarios = array(
        array('user' => 'POLICIACIUDAD', 'pass' => PASS_INTI_CIUDAD),
        array('user' => 'SIPER', 'pass' => PASS_INTI_SIPER),
        array('user' => 'SIVE', 'pass' =>  PASS_INTI_SIVE),
        array('user' => 'RENAPER','pass' => PASS_INTI_RENAPER),
        array('user' => 'DNM','pass' => PASS_INTI_DNM),
        array('user' => 'INTERPOL','pass' => PASS_INTI_INTERPOL),
        array('user' => 'VIALIDAD', 'pass' => PASS_INTI_VIALIDAD)	
    );
    private static $acciones = array('RENAPER'=> array('NOMINALS', 'NOMINALSDETAILS', 'NOMINALSIMAGE'),
    								 'VIALIDAD'=> array('NOMINALSEXACT', 'NOMINALSDETAILS', 'NOMINALSIMAGE')
    								);

    public static function getUsuarios() {
        return self::$usuarios;
    }
    public static function getAcciones($usuario) {
        if (array_key_exists ( $usuario , self::$acciones) ){
            return self::$acciones[$usuario];
        }else {
            return true;
        }
    }

}
