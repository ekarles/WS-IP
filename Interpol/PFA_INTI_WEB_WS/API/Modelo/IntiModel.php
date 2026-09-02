<?php

/**
 * Description of IntiModel
 *
 * @author PFA27667140
 */
class IntiModel {

    //Atributos  

    public $sistema;
    public $token;
    public $origen;
    public $pass;
    public $usuario;
    public $usuarioIp;
    public $RemoteAddress;
    public $usuarioApellido;
    public $usuarioNombre;
    public $usuarioDepen;
    public $legajo;
    public $usuarioTipoDoc;
    public $usuarioDoc;
    public $usuarioJerarquia;
    public $idConsulta;
    public $modoConsulta;
    public $usuarioDepenId;
    public $NRecord;
    public $proceso;
    public $fconsulta;
    public $transito;
    public $genero;
    public $nroDoc;
    public $pais;
    public $tipoDoc;
    public $sltdId;
    public $NomTdId;
    public $ageMax;
    public $ageMin;
    public $fechaNacimiento;
    public $apellido;
    public $nombre;
    public $lote;
    public $entityId;
    public $imagePath;
    public $PdfPath;
    public $nroMotor;
    public $RegistrationMark;
    public $vin;
    public $GearBoxNr;
    public $SecurityNr;
    public $vinId;
    public $latitud;
    public $longitud;
    public $accion;

    //Constructor
    function __construct() {
        
    }

    //Métodos

    /*
     * Validación de datos recibidos
     */
    public function validar() {
        /*
         * Crear validaciones de formatos de datos
         * y tamaños máximos 
         */

        if (isset($this->fechaNacimiento) && $this->fechaNacimiento != '' && !($this->validar_fecha($this->fechaNacimiento))) {            
            $response = array("cod" => "400", "desc" => "error", "msg" => "Formato de fecha incorrecto (dd/mm/yyyy)");
            return $response;
        }

        if (!is_numeric($this->latitud) || !is_numeric($this->longitud)) {
            $response = array("cod" => "400", "desc" => "error", "msg" => "Formato de latitud o longitud incorrecto (-##.######)");
            return $response;
        }

        return true;
    }

    private function validar_fecha($fecha) {        
        $valores = explode('/', $fecha);        
        if (count($valores) == 3 && is_numeric($valores[0]) && is_numeric($valores[1]) && is_numeric($valores[2]) && checkdate($valores[1], $valores[0], $valores[2])) {
            return true;
        }
        return false;
    }

}
