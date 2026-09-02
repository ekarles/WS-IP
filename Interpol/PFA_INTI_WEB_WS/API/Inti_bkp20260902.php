<?php

// Solo habilitar en ambiente de desarrollo
require_once('../../Utilidades/Herramientas/Log4Php/Logger.php');
require_once('../Ambiente/parameters.php');
require_once('Modelo/IntiModel.php');
require_once('Modelo/MOK.php');

/**
 * Description of Inti
 *
 * SERVICIO FRONTEND DE CONSUMO DEL SERVICIO INTERPOL FRANCIA
 */
class Inti {

    //Atributos
    protected $consulta;
    protected $logRoot;
    protected $accion;
    protected $respuesta = '';
    protected $ambiente;
    protected $hash = '';
    protected $idConsulta;
    protected $intervalQuery = 0;

    //Setter y Getters
    function getConsulta() {
        return $this->consulta;
    }

    function getAccion() {
        return $this->accion;
    }

    function setConsulta(IntiModel $consulta) {
        $this->consulta = $consulta;
    }

    function setAccion($accion) {
        $this->accion = $accion;
    }

    function getLogRoot() {
        return $this->logRoot;
    }

    function setLogRoot($logRoot) {
        $this->logRoot = $logRoot;
    }

    function getRespuesta() {
        return $this->respuesta;
    }

    function setRespuesta($respuesta) {
        $this->respuesta = $respuesta;
    }

    function getAmbiente() {
        return $this->ambiente;
    }

    function setAmbiente(Ambiente $ambiente) {
        $this->ambiente = $ambiente;
    }

    private function getHash() {
        return $this->hash;
    }

    private function setHash($hash) {
        $this->hash = $hash;
    }

    public function getIdConsulta()
    {
        return $this->idConsulta;
    }

    public function setIdConsulta($idConsulta)
    {
        $this->idConsulta = $idConsulta;
    }

    public function getIntervalQuery()
    {
        return $this->intervalQuery;
    }

    public function setIntervalQuery($intervalQuery)
    {
        $this->intervalQuery = $intervalQuery;
    }

    //Constructor
    function __construct() {
        Logger::configure('../Ambiente/config.xml');
        $this->logRoot = Logger::getRootLogger();
        $this->setAmbiente(new Ambiente());
    }

    //M�todos
    public function obtenerConsulta($accion, $data) {
        $this->accion = $accion;

        //Genero objeto con datos de consulta
        $data->accion = $accion;
        
        $funcionesObjetos = new FuncionesObjetos();
        $consulta = $funcionesObjetos->cast('IntiModel', $data);
        
        $consultaLog = clone $consulta;
        
        $consultaLog->token = '******';
        $consultaLog->pass = '******';
        
        if ($consulta === FALSE) {
            $this->response(400, "error", "Formato Incorrecto");
            exit();
        };
        
        if (!isset($consulta->fconsulta) || $consulta->fconsulta=='') {
            $hoy = new DateTime();
            $consulta->fconsulta = $hoy->format('d-m-Y H:i:s');
        }
        
        if ($consulta->origen == 'RENAPER') {
            $consulta->usuarioJerarquia = "OPERADOR";
            $consulta->modoConsulta = 'AD';
        }
        
        $consulta->proceso = $consulta->origen;
        
        $this->setConsulta($consulta);
    
    }

    public function consultar() {
        //Si no pasa la validaci�n retorna la respuesta desde el mismo método        
        $r = $this->consulta->validar($this->getAccion());
        
        $this->idConsulta=(isset($this->consulta->idConsulta)&&($this->consulta->idConsulta!=''))?' ['.$this->consulta->idConsulta.']':
                            ' ['.str_pad(rand(0,10000000),8,'0',STR_PAD_LEFT).']'; // Agregado para tracear en logs

        if ($r === true) {
            try {
                $p_error = $this->consulta();
                if ($p_error != '') {
                    $this->response(500, "error", "No se pudo concretar la consulta");
                }
            } catch (Exception $e) {
                $this->response(403, "error", "Acción no contemplada");
            }
        } else {
            $this->response($r["cod"], $r["desc"], $r["msg"]);
        }
        
        if($this->getConsulta()->origen != 'DNM'){
            exit();
        }
    }

    public function response($code = 200, $status = "", $message = "") {
        if (!function_exists('http_response_code')) {

            //En php 5.3 no existe esta función entonces 
            //se redefine 
            function http_response_code($newcode = NULL) {
                static $code = 200;
                if ($newcode !== NULL) {
                    header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
                    if (!headers_sent()) {
                        $code = $newcode;
                    }
                }
                return $code;
            }

        }
        http_response_code($code);

        if (!empty($status) && !empty($message)) {
            $response = json_encode(array("status" => $status, "message" => $message, "respuesta" => $this->respuesta, "fecha" => date("d-m-Y H:i:s"), "hash" => $this->getHash()));
            $this->getLogRoot()->info('Respuesta'.$this->idConsulta.': ' . $response);
            if(isset($this->getConsulta()->origen) && $this->getConsulta()->origen != 'DNM'){
                echo $response;
            }
        }
    }

    protected function consulta() {
        
        $this->getLogRoot()->info('Consulta'.$this->idConsulta.': '.json_encode($this->consulta));
        
        if($this->consulta->fechaNacimiento!=="" && strpos($this->consulta->fechaNacimiento, '/')){
			
			$fechaNac=$this->consulta->fechaNacimiento;
			
			$valores = str_replace("/","",$fechaNac);
			$fecha=substr($valores,4,4).substr($valores,2,2).substr($valores,0,2);
		
        	$this->consulta->fechaNacimiento = $fecha;

		}
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://" . $this->getAmbiente()->getHost() . "/Interpol/interpol.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($this->consulta),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));        
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $this->getLogRoot()->trace('ERROR CURL: ' . $err);
            $this->response(500, "error", "Error de Comunicacion.");
        } else {
            $this->respuesta = $this->obtenerRespuesta($response);

            //Generar Hash de la respuesta            
            if (is_array($this->respuesta)) {
                $this->setHash(hash("sha256", stripslashes(json_encode($this->respuesta))));
            } else {
                $this->setHash(hash("sha256", stripslashes($this->respuesta)));
            }

            $this->response(200, "success", "Consulta ejecutada correctamente");
        }
        
        if(isset($this->getConsulta()->origen) && $this->getConsulta()->origen != 'DNM'){
            exit();
        }
    }

    private function obtenerRespuesta($response) {
        //Para tomar los campos traidos desde JAVA

        if ($this->accion == 'NOMINALSIMAGE') {
            $response = array('imagen' => $this->getResponse($response, 0));
        } else {
            $respuesta = $this->getResponse($response, 21);
            if ($respuesta === FALSE) {
                $response = 'Sin resultados';
            } else if ($respuesta === TRUE) {
                $response = 'Error desde el servidor';
            } else {
                
                $response = simplexml_load_string($respuesta, 'SimpleXMLElement', LIBXML_NOCDATA, 'i', true);

                if (isset($response->datas->search->origin->nominal)) {                    
                    foreach ($response->datas->search->origin->nominal as $row) {
                        $row->entityId = $row->attributes();
                    }    
                }
                
                if (isset($response->datas->origin->nominal->document)) {
                    foreach ($response->datas->origin->nominal->document as $row) {
                        $row->sltdId = $row->attributes();
                    }
                }
                
                if (isset($response->datas->search->origin->document)) {
                    foreach ($response->datas->search->origin->document as $row) {
                        $row->sltdId = $row->attributes();
                    }
                    
                }

                if (isset($response->datas->origin->document)) {
                    foreach ($response->datas->origin->document as $row) {
                        $row->sltdId = $row->attributes();
                    }
                }
                
                if (isset($response->datas->origin->vehicle)) {
                    foreach ($response->datas->origin->vehicle as $row) {
                        $row->vinId =$row->attributes();
                    }
                }

                if (isset($response->datas->search->origin->vehicle)) {
                    foreach ($response->datas->search->origin->vehicle as $row) {
                        $row->vinId =  $row->attributes();
                    }
                }                
            }
        }
        
        return $response;
    }

    private function getResponse($response, $inicio) {

        $responseInterval = $response;
        
        $inicioInterval = strpos($responseInterval, '<INTERVAL_QUERY>') + 16;
        $finInterval = strpos($responseInterval, '</INTERVAL_QUERY>');
        
        $interval = substr($responseInterval, $inicioInterval, $finInterval - $inicioInterval);
        
        $this->setIntervalQuery($interval);
        
        $responseError = $response;
        $inicioError = strpos($responseError, '<ERROR>') + 7;

        $finError = strpos($responseError, '</ERROR>');
        $responseError = substr($responseError, $inicioError, $finError - $inicioError);
        
        if ($responseError == 'NO_ANSWER') {
            return false;
        }
        if ($responseError != 'NO_ERROR') {
            return TRUE;
        }

        $fin = strpos($response, '<RESPUESTA>') + 11;
        $responseInti = substr_replace($response, '', $inicio, $fin - $inicio);

        $inicio = strpos($responseInti, '</RESPUESTA>');
        $salida = substr_replace($responseInti, '', $inicio);        
        
        return $salida;
    }

}
