<?php

/**
 * Descripcion of Api
 *
 * @author pfa27667140
 */
require_once 'Inti.php';
require_once 'UsuarioGenerico.php';
require_once('../../Utilidades/BD/FuncionesObjetos.php');

class Api {

    //Atributos
    private $accion;
    private $obj;
    private $inti;
    private $acciones = array('NOMINALS', 'NOMINALSEXACT', 'NOMINALSDETAILS', 'NOMINALSIMAGE', 'SLTDDETAILS', 'SLTD', 'SMVDETAILS', 'SMV');
    private $parametrosObligatorios = array('sistema', 'token', 'origen', 'pass', 'usuario', 'usuarioIp', 'usuarioApellido', 'usuarioNombre', 'usuarioDepen', 'usuarioTipoDoc', 'usuarioDoc', 'latitud', 'longitud');

    //Setters y Getters
    private function getAccion() {
        return $this->accion;
    }

    private function setAccion($accion) {
        $this->accion = $accion;
    }

    private function getObj() {
        return $this->obj;
    }

    private function setObj($obj) {
        $this->obj = $obj;
    }

    public function __construct() {
        //echo "Api.php->__construct();";
        $this->inti = new Inti();
    }

    //Métodos
    public function API() {
        //echo "Api.php->API();";
        if (!($method = filter_var(getenv('REQUEST_METHOD')))) {
            $this->inti->response(403, "error", "Acceso Denegado.");
            exit();
        };

        $uri = $_SERVER['REQUEST_URI'];
        $exp = explode("/", $uri);
        $this->setAccion(end($exp));
        switch ($method) {
            case 'POST':
                $data = file_get_contents('php://input');
                $funcionesObjetos = new FuncionesObjetos();
                if (!$funcionesObjetos->jsonValidator($data)) {
                    $this->inti->response(400, "error", "Se espera un Json.");
                    exit();
                }

                $this->obj = json_decode($data);

                $this->inti->getLogRoot()->info('Datos de entrada: '.$data);
                
                $this->obj->accion = $this->getAccion();
                //validación de datos recibidos
                $this->validar();
                $this->inti->obtenerConsulta($this->getAccion(), $this->obj);

                $this->inti->consultar();
            default://metodo NO soportado
                $this->inti->response(405, "error", "Método no soportado");
                exit();
                break;
        }
    }

    /*
     * Validaciones para el uso del servicio de PFA_INTI_WS
     */

    private function validar() {
        if ($this->obj === null || !((Array) $this->obj)) {
            $this->inti->response(400, "error", "Formato Incorrecto.");
            exit();
        }

        //Validación de método solicitado
        if (!in_array($this->accion, $this->acciones)) {
            $this->inti->response(400, "error", "El método solicitado no existe para este servicio");
            exit();
        }

        //Validación de campos obligatorios y no sean nulos        
        $consulta = (array) $this->obj;
        foreach ($this->parametrosObligatorios as $parametro) {
            if (!array_key_exists($parametro, $consulta) || empty($consulta[$parametro])) {
                $this->inti->response(400, "error", $parametro . " es un dato obligatorio y no puede estar vacío");
                exit();
            }
        }

        $bandera = false;
        
        $usuarioGenerico = new UsuarioGenerico();
        
        if($this->obj->pass == ""){
            $bandera=false;
        }
        
        $ExisteEnArray = 1;
        
        if( !array_search( $this->obj->origen, array_column($usuarioGenerico->getUsuarios(), 'user') )){
            $ExisteEnArray = 0;
        }
        if ( !$bandera ){
            $origen = $this->obj->origen;
            $pwd = hash(WS_HASH_ALGORITMO, crypt( $origen, PASS_INT_GENERAL ) );
            
            if((strtoupper($pwd) == strtoupper($this->obj->pass))){
                
                if($ExisteEnArray==0){
                    $usr = [ "user" => $origen, "pass" => strtoupper($pwd) ];
                    $usuarioGenerico->setUsuario($usr);
                }
                $bandera=true;
            }
        }
        
        
        if ($this->obj->origen == 'SIPER') {
            /*
             * Hay que validar usuario contral el sipfa web
             * Esto se puede mejorar
             */
            $bandera = true;
        } else {
            
            $credencial = array('user' => $this->obj->origen, 'pass' => $this->obj->pass);
            //$usuarioGenerico = new UsuarioGenerico();
            foreach ($usuarioGenerico->getUsuarios() as $usuario) {
                
                
                if ($usuario === $credencial) {
                    //echo "OK";
                    $bandera = true;
                    $acciones = $usuarioGenerico->getAcciones($credencial['user']);
                    //print_r($acciones);
                    if ($acciones !== true && !in_array($this->accion, $acciones)) {
                        $this->inti->response(403, "error", "El método solicitado no se encuetra habilitado para este perfil");
                        exit();
                    }
                }
            }
        }
        
        if (!$bandera) {
            $this->inti->response(401, "error", "Acceso Denegado");
            exit();
        }
        
        if ($this->obj->sistema != $this->inti->getAmbiente()->getSistema()) {
            $this->inti->response(400, "error", "Sistema incorrecto");
            exit();
        }
    }

}
