<?php

namespace GESTION\GestionBundle\Repository;

/**
 * Description of ConsultaCurl
 *
 */
class ConsultaCurl
{

    //Atributos
    private $url;
    private $accion;
    private $consulta;
    private $data;
    private $sesion;
    private $container;



    //Getters y Setters
    protected function getUrl()
    {
        return $this->url;
    }

    protected function setUrl($url)
    {
        $this->url = $url;
    }

    protected function getAccion()
    {
        return $this->accion;
    }

    public function setAccion($accion)
    {
        $this->accion = $accion;
    }

    protected function getConsulta()
    {
        return $this->consulta;
    }

    protected function setConsulta($consulta)
    {
        $this->consulta = $consulta;
    }

    protected function getData()
    {
        return $this->data;
    }

    protected function setData($data)
    {
        $this->data = $data;
    }

    protected function getSesion()
    {
        return $this->sesion;
    }

    protected function setSesion($sesion)
    {
        $this->sesion = $sesion;
    }
    function getContainer()
    {
        return $this->container;
    }

    function setContainer($container)
    {
        $this->container = $container;
    }

    //Constructor    
    function __construct($container, $data = null, $sesion = null)
    {
        $this->container = $container;
        $this->data = $data;
        $this->sesion = $sesion;
    }

    //Métodos

    /*
     * Realizar conexión por curl al ws front
     */
    protected function consulta()
    {
        $curl = curl_init();

        //echo "<pre>ConsultaCurl.php<br>";
        //echo "URL: ". $this->url . $this->accion."<br/></pre>";
        //$url = "http://10.1.103.42/PFA_INTI_WS/NOMINALS";
        //echo "URL: ". $this->url.$this->accion;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . $this->accion,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->consulta, //Hay que verificar que venga un json correctamente
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));

        echo "<pre>";
        echo ($this->url);
        echo "</pre>";

        //DEPURACION DATOS ENVIADOR AL ENDPOINT
        echo "<pre>";
        echo "DATOS ENVIADOS:\n";
        echo $this->consulta;
        echo "\n\n";
        echo "URL: " . $this->url . $this->accion;
        echo "</pre>";
        //FIN DEPURACION DATOS ENVIADOR AL ENDPOINT

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);


        // ===== DEPURACIÓN SIMPLE =====
        echo "<pre>";
        echo "RESPUESTA DEL SERVIDOR:\n";

        // Decodificar y mostrar como JSON bonito
        $json = json_decode($response);
        if ($json) {
            echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo $response; // Si no es JSON, mostrar el texto plano
        }

        echo "\n\nCÓDIGO HTTP: " . $code;
        if ($err) {
            echo "\nERROR CURL: " . $err;
        }
        echo "</pre>";
        // ===== FIN DEPURACIÓN =====



        if ($err !== '') {
            $resp = new \stdClass();
            $resp->message = $err;
            $resp->status = $code;

            return $resp;




        } else {
            $respuesta = json_decode($response);
            if (isset($respuesta->status) && $respuesta->status == 'error') {

                return $respuesta;
            } else {
                if (isset($respuesta->respuesta)) {
                    return $respuesta->respuesta;
                } else {
                    return false;
                }
            }
        }

    }


}
