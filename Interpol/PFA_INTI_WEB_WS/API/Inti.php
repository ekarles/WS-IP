<?php

// Solo habilitar en ambiente de desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../../Utilidades/Herramientas/Log4Php/Logger.php');
require_once('../Ambiente/parameters.php');
require_once('Modelo/IntiModel.php');
require_once('Modelo/MOK.php');

/**
 * Description of Inti
 *
 * @author pfa27667140
 * SERVICIO FRONTEND DE CONSUMO DEL SERVICIO INTERPOL FRANCIA
 */
class Inti
{

    //Atributos
    protected $consulta;
    protected $logRoot;
    protected $accion;
    protected $respuesta = '';
    protected $ambiente;
    protected $hash = '';
    protected $idConsulta;
    protected $intervalQuery = 0;
    // se agraga esto
    private $version = "1";




    public function setVersion($v)
    {
        $this->version = $v;
    }

    //Setter y Getters
    function getConsulta()
    {
        return $this->consulta;
    }

    function getAccion()
    {
        return $this->accion;
    }

    function setConsulta(IntiModel $consulta)
    {
        $this->consulta = $consulta;
    }

    function setAccion($accion)
    {
        $this->accion = $accion;
    }

    function getLogRoot()
    {
        return $this->logRoot;
    }

    function setLogRoot($logRoot)
    {
        $this->logRoot = $logRoot;
    }

    function getRespuesta()
    {
        return $this->respuesta;
    }

    function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    }

    function getAmbiente()
    {
        return $this->ambiente;
    }

    function setAmbiente(Ambiente $ambiente)
    {
        $this->ambiente = $ambiente;
    }

    private function getHash()
    {
        return $this->hash;
    }

    private function setHash($hash)
    {
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
    function __construct()
    {
        Logger::configure('../Ambiente/config.xml');
        $this->logRoot = Logger::getRootLogger();
        $this->setAmbiente(new Ambiente());
    }

    //Metodos
    public function obtenerConsulta($accion, $data)
    {
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
        }
        ;

        if (!isset($consulta->fconsulta) || $consulta->fconsulta == '') {
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

    public function consultar()
    {
        //Si no pasa la validacion retorna la respuesta desde el mismo metodo        
        $r = $this->consulta->validar($this->getAccion());

        $this->idConsulta = (isset($this->consulta->idConsulta) && ($this->consulta->idConsulta != '')) ? ' [' . $this->consulta->idConsulta . ']' :
            ' [' . str_pad(rand(0, 10000000), 8, '0', STR_PAD_LEFT) . ']'; // Agregado para tracear en logs

        if ($r === true) {
            try {
                $p_error = $this->consulta();
                if ($p_error != '') {
                    $this->response(500, "error", "No se pudo concretar la consulta");
                }
            } catch (Exception $e) {
                $this->response(403, "error", "Accion no contemplada");
            }
        } else {
            $this->response($r["cod"], $r["desc"], $r["msg"]);
        }



        if ($this->getConsulta()->origen != 'DNM') {
            exit();
        }
    }

    public function response($code = 200, $status = "", $message = "")
    {
        if ($code === 200 && is_array($this->respuesta) && isset($this->respuesta['error']) && $this->respuesta['error'] === true) {
            $code = isset($this->respuesta['code']) ? intval($this->respuesta['code']) : 400;
            $status = "error";
            $message = isset($this->respuesta['message']) ? $this->respuesta['message'] : "Error en la consulta";
        }
        http_response_code($code);

        if (!empty($status) && !empty($message)) {
            $response = json_encode(array("status" => $code, "result" => $status, "message" => $message, "respuesta" => $this->respuesta, "fecha" => date("d-m-Y H:i:s"), "hash" => $this->getHash()));
            $this->getLogRoot()->info('Respuesta' . $this->idConsulta . ': ' . $response);
            if (isset($this->getConsulta()->origen) && $this->getConsulta()->origen != 'DNM') {
                echo $response;
            }
        }
    }





    protected function consulta()
    {

        if ($this->consulta->accion == 'NOMINALS' || $this->consulta->accion == 'NOMINALSEXACT') {
            $validVersions = ['1', '1.1', '1.2'];


            if (!isset($this->version) || empty($this->version)) {
                $this->logRoot->error('ERROR -400- La versión es obligatoria para NOMINALS');
                $this->response(400, "error", "La versión es obligatoria. Versiones permitidas: 1, 1.1, 1.2");
                return;
            }


            if (!in_array($this->version, $validVersions)) {
                $this->logRoot->error('ERROR -400- Version no soportada: ' . $this->version);
                $this->response(400, "error", "Version no soportada. Versiones permitidas: " . implode(', ', $validVersions));
                return;
            }
        }

        $this->getLogRoot()->info('Consulta ' . $this->idConsulta . ': ' . json_encode($this->consulta));

        // Normalización de fecha
        if (!empty($this->consulta->fechaNacimiento) && strpos($this->consulta->fechaNacimiento, '/') !== false) {
            $fechaNac = $this->consulta->fechaNacimiento;
            $valores = str_replace("/", "", $fechaNac);
            $fecha = substr($valores, 4, 4) . substr($valores, 2, 2) . substr($valores, 0, 2);
            $this->consulta->fechaNacimiento = $fecha;
        }

        $curl = curl_init();

        if ($this->version == "1.1" || $this->version == "1.2") {

            $url = "http://" . BACKEND_HOST_INTI . "/BackEndInti/API/index.php/" . $this->accion;

            // Agregar credenciales directamente al objeto
            $this->consulta->usuario = USUARIO_INTI;
            $this->consulta->sistema = SISTEMA_INTI_1_2;
            $this->consulta->pass = PASS_INTI;
            $this->consulta->token = TOKEN_INTI_1_2;
            if (!isset($this->consulta->origen))
                $this->consulta->origen = ORIGEN_INTI;

            // Codificar todo el objeto en JSON
            $postFields = json_encode($this->consulta);

            $headers = [
                "Content-Type: application/json"
            ];

        } else if ($this->version == '1') {
            // BACKEND VIEJO (XML)
            $url = "http://" . $this->getAmbiente()->getHost() . "/Interpol/interpol.php";
            $postFields = http_build_query($this->consulta);
            $headers = [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ];
        } else {
            $this->response(400, "error", "Version no soportada. Versiones permitidas: 1, 1.1, 1.2");
            return;
        }


        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $this->getLogRoot()->trace('ERROR CURL: ' . $err);
            $this->response(500, "error", "Error de Comunicacion: " . $err . " | URL: " . $url);
        } else {

            // SWITCH DE RESPUESTA
            if ($this->version == "1.1" || $this->version == "1.2") {

                $decoded = json_decode($response, true);

                if ($this->consulta->origen == 'DNM') {
                    $this->respuesta = $this->obtenerRespuesta2($decoded['respuesta']);
                } else {
                    $this->respuesta = $decoded['respuesta'] ?? $decoded;
                }

            } else {
                $this->respuesta = $this->obtenerRespuesta($response);
            }

            // Hash
            if (is_array($this->respuesta)) {
                $this->setHash(hash("sha256", stripslashes(json_encode($this->respuesta))));
            } else {
                $this->setHash(hash("sha256", stripslashes($this->respuesta)));
            }

            $this->response(200, "success", "Consulta ejecutada correctamente");
        }

        if (isset($this->getConsulta()->origen) && $this->getConsulta()->origen != 'DNM') {
            exit();
        }
    }





    private function obtenerRespuesta($response)
    {
        //Para tomar los campos traidos desde JAVA
        echo "respuesta: " . json_encode($response);
        die();
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
                        $row->vinId = $row->attributes();
                    }
                }

                if (isset($response->datas->search->origin->vehicle)) {
                    foreach ($response->datas->search->origin->vehicle as $row) {
                        $row->vinId = $row->attributes();
                    }
                }
            }
        }

        return $response;
    }

    private function obtenerRespuesta2($response)
    {
        //Para tomar los campos traidos desde PHP/JSON

        if ($this->accion == 'NOMINALSIMAGE') {
            $response = array('imagen' => $this->getResponse2($response));
        } else {
            $respuesta = $this->getResponse2($response);
            if ($respuesta === FALSE) {
                $response = 'Sin resultados';
            } else if ($respuesta === TRUE) {
                $response = 'Error desde el servidor';
            } else {
                $response = simplexml_load_string($respuesta, 'SimpleXMLElement', LIBXML_NOCDATA, 'i', true);
            }
        }

        return $response;
    }



    private function getResponse($response, $inicio)
    {

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

        $inicio = strpos($response, '<RESPUESTA>') + 11;
        $fin = strpos($response, '</RESPUESTA>');

        $responseInti = substr_replace($response, '', $inicio, $fin - $inicio);

        $salida = substr_replace($responseInti, '', $inicio);

        return $salida;
    }

    private function getResponse2($response)
    {


        $responseError = $response['resultCode'];

        if ($responseError == 'NO_ANSWER') {
            return false;
        }
        if ($responseError != 'NO_ERROR') {
            return true;
        }

        return $response;
    }

}
