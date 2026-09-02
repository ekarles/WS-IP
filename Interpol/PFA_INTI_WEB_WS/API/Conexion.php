<?php

require_once ('Inti.php');
require_once('Modelo/IntiModel.php');
require_once('../../Utilidades/BD/FuncionesObjetos.php');
require_once('DNMResponse.php');

/**
 * Description of ConexionInti
 *
 */
class Conexion {
    
    //Atributos
    private $logRoot;
    private $logRootDNM;
    private $ambiente;
    private $HTTP_RAW_POST_DATA;
    private $estado;
    private $mensaje;
    private $idConsulta;
    private $response;
    private $javaTime = array();
    private $t_ini;
    
    //Setter y Getters
    public function getAmbiente()
    {
        return $this->ambiente;
    }
    
    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
    }
    
    public function getLogRoot() {
        return $this->logRoot;
    }
    
    public function setLogRoot($logRoot) {
        $this->logRoot = $logRoot;
    }
    
    public function getHTTP_RAW_POST_DATA() {
        return $this->HTTP_RAW_POST_DATA;
    }
    
    public function setHTTP_RAW_POST_DATA($HTTP_RAW_POST_DATA) {
        $this->HTTP_RAW_POST_DATA = $HTTP_RAW_POST_DATA;
    }
    
    function getEstado() {
        return $this->estado;
    }
    
    function getMensaje() {
        return $this->mensaje;
    }
    
    function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }
    
    function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function getLogRootDNM()
    {
        return $this->logRootDNM;
    }
    
    public function setLogRootDNM($logRootDNM)
    {
        $this->logRootDNM = $logRootDNM;
    }
    
    public function getIdConsulta()
    {
        return $this->idConsulta;
    }
    
    public function setIdConsulta($idConsulta)
    {
        $this->idConsulta = $idConsulta;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function setResponse($response)
    {
        $this->response = $response;
    }
    
    
    
    //Constructor
    function __construct($logRoot,$ambiente,$idConsulta) {
        $this->logRoot = $logRoot;
        $this->inicializarLogger();
        $this->ambiente = $ambiente;
        $this->idConsulta = (isset($idConsulta))? $idConsulta : str_pad(rand(0,10000000),8,'0',STR_PAD_LEFT);
        $this->response = new DNMResponse('0','2','AMARILLO');  // Por defecto es error
    }
    
    public function ConsultaInterpolPersonas($parametrosConsulta) {
        
        try {
            
            $this->t_ini =microtime(true);;
            
            $this->getLogRoot()->trace('Parametros consulta ['.$this->idConsulta.']: ' . print_r($parametrosConsulta, true));
            
            $consulta = FuncionesObjetos::cast('IntiModel', $parametrosConsulta);
            
            $consulta->sistema = $this->getAmbiente()->getSistema();
            $consulta->token = $this->getAmbiente()->getToken();
            $consulta->origen = 'DNM';
            $consulta->pass = 'DNM2019PFA';
            $consulta->latitud='-34.614484'; // Por ahora este campo no es requerido para migraciones
            $consulta->longitud='-58.388816'; // Idem anterior
            $consulta->idConsulta=$this->idConsulta;
            $consulta->modoConsulta=$this->ambiente->getModoDnm();
            $consulta->fconsulta=$parametrosConsulta->fechaConsulta;
            $consulta->proceso=$parametrosConsulta->tipoConsulta;
            
            
            /* Validacion de parametros */
            
            if(!isset($consulta->apellido)||$consulta->apellido==''){
                // El apellido es obligatorio
                $this->getLogRoot()->error('Apellido obligatorio ['.$this->idConsulta.']: ' . print_r($parametrosConsulta, true));
                return $this->response->getResponse();
            }
            
            if(!isset($consulta->modoConsulta)||$consulta->modoConsulta==''){
                // El modo de consulta es obligatorio
                $this->getLogRoot()->error('Modo de consulta obligatorio ['.$this->idConsulta.']: ' . print_r($parametrosConsulta, true));
                return $this->response->getResponse();
            }elseif($consulta->modoConsulta!='BC' && $consulta->modoConsulta!='AD'){
                // Modo de consulta incorrecto
                $this->getLogRoot()->error('Modo de consulta incorrecto ['.$this->idConsulta.']: ' . print_r($parametrosConsulta, true));
                return $this->response->getResponse();
            }
            
            if(!isset($consulta->fechaNacimiento)||$consulta->fechaNacimiento==''){
                // La fecha de nacimiento es obligatoria
                $this->getLogRoot()->error('Fecha de nacimiento obligatoria ['.$this->idConsulta.']: ' . print_r($parametrosConsulta, true));
                return $this->response->getResponse();
            }else{
                $valores = $consulta->fechaNacimiento;
                if(!strpos($consulta->fechaNacimiento,'/')){
                    // Habria que mejorar la validacion de la fecha para que la transforme de varios formatos
                    $consulta->fechaNacimiento = substr($valores,6,2).'/'.substr($valores,4,2).'/'.substr($valores,0,4);
                }
            }
            
            /* Fin validacion de parametros */
            
            $inti = new Inti();
            $inti->setConsulta($consulta);
            
            $intiNOMINALS = clone $inti;
            $intiSLTD = clone $inti;
            
            if($this->verificarEspeciales($consulta->nombre.$consulta->apellido)){
                $intiNOMINALS->setAccion('NOMINALSEXACT');
                $intiNOMINALS->getConsulta()->accion='NOMINALSEXACT';
            }else{   //
                $intiNOMINALS->setAccion('NOMINALS');
                $intiNOMINALS->getConsulta()->accion='NOMINALS';
            }
            
            $intiNOMINALS->consultar();
            
            array_push($this->javaTime,$intiNOMINALS->getIntervalQuery());
            
            $t_fin =microtime(true);
            
            $intervalTotal = number_format(($t_fin-$this->t_ini),3)*1000;
            
            if(is_object($intiNOMINALS->getRespuesta()) && $intiNOMINALS->getAccion()=='NOMINALSEXACT'){  // Si es EXACTA y es un objeto es un resultado positivo => consulto el detalle (genera alarma en Interpol)
                return $this->obtenerDetalle($intiNOMINALS,$intiNOMINALS->getAccion(),$intiNOMINALS->getRespuesta());
            }
            
            if(isset($intiNOMINALS->getRespuesta()->datas->search->origin->nominal->date_of_birth)){
                $dateofbirth = json_decode(json_encode($intiNOMINALS->getRespuesta()->datas->search->origin->nominal->date_of_birth),true);
            }
            
            if(is_object($intiNOMINALS->getRespuesta()) && $dateofbirth[0] == $valores){  // Si es NOMINALS y es un objeto es un resultado positivo => consulto el detalle (genera alarma en Interpol) por las dudas vuelvo a verificar la fecha para evitar falsos positivos
                return $this->obtenerDetalle($intiNOMINALS,$intiNOMINALS->getAccion(),$intiNOMINALS->getRespuesta());
            }
            
            // Si no es un objeto la respuesta busco por documento
            if( isset($intiSLTD->getConsulta()->nroDoc)    && $intiSLTD->getConsulta()->nroDoc != '' &&
                isset($intiSLTD->getConsulta()->tipoDoc)   && $intiSLTD->getConsulta()->tipoDoc != '' &&
                isset($intiSLTD->getConsulta()->pais)      && $intiSLTD->getConsulta()->pais != '' ) {
                    
                    $intiSLTD->setAccion('SLTD');
                    $intiSLTD->getConsulta()->accion='SLTD';
                    $intiSLTD->getConsulta()->fechaNacimiento=null;  // No es necesaria para consulta de documento y evito la validacion de fecha del modelo
                    
                    
                    $intiSLTD->consultar();
                    
                    array_push($this->javaTime,$intiSLTD->getIntervalQuery());
                    $t_fin =microtime(true);
                    $intervalTotal = number_format(($t_fin-$this->t_ini),3)*1000;
                    
                    if(is_object($intiSLTD->getRespuesta())){   // Si es un objeto es un resultado positivo en el caso de documentos devolver NARANJA
                        
                        // 09/12/2019 V.Paredes Realizo una consulta extra de SLTDDETAILS para que se produzca la alarma como corresponde
                        
                        if(isset($intiSLTD->getRespuesta()->datas->search->origin->document->sltdId)){
                            $sltdId = json_decode(json_encode($intiSLTD->getRespuesta()->datas->search->origin->document->sltdId),true);
                            
                            $intiSLTD->setAccion('SLTDDETAILS');
                            $intiSLTD->getConsulta()->accion='SLTDDETAILS';
                            $intiSLTD->getConsulta()->sltdId=$sltdId[0];
                            $intiSLTD->consultar();
                            
                            array_push($this->javaTime,$intiSLTD->getIntervalQuery());
                            
                            $t_fin =microtime(true);
                            $intervalTotal = number_format(($t_fin-$this->t_ini),3)*1000;
                        }
                        
                        $this->response->setCodRta("2");
                        $this->response->setColor("NARANJA");
                        $this->response->setCodDetalleRta("0");
                        
                        if(isset($this->javaTime[2])){
                            $this->getLogRoot()->info('LATENCIA ['.$this->idConsulta.']: Java 1=' . $this->javaTime[0] . ' 2=' . $this->javaTime[1] . ' 3=' . $this->javaTime[2] . ' -- Java Total=' . ($this->javaTime[0]+$this->javaTime[1]+$this->javaTime[2]) . ' -- Total='. $intervalTotal );
                        }else{
                            $this->getLogRoot()->info('LATENCIA ['.$this->idConsulta.']: Java 1=' . $this->javaTime[0] . ' 2=' . $this->javaTime[1] . ' -- Java Total=' . ($this->javaTime[0]+$this->javaTime[1]) . ' -- Total='. $intervalTotal );
                        }
                        return $this->response->getResponse();
                    }else{
                        
                        if(!isset($this->javaTime[0]) || !is_numeric($this->javaTime[0]) ){
                            $this->javaTime[0] = 0;
                        }
                        if(!isset($this->javaTime[1]) || !is_numeric($this->javaTime[1]) ){
                            $this->javaTime[1] = 0;
                        }
                        if(!isset($this->javaTime[2]) || !is_numeric($this->javaTime[2]) ){
                            $this->javaTime[2] = 0;
                        }
                        
                        if(isset($this->javaTime[2])){
                            $this->getLogRoot()->info('LATENCIA ['.$this->idConsulta.']: Java 1=' . $this->javaTime[0] . ' 2=' . $this->javaTime[1] . ' 3=' . $this->javaTime[2] . ' -- Java Total=' . ($this->javaTime[0]+$this->javaTime[1]+$this->javaTime[2]) . ' -- Total='. $intervalTotal );
                        }else{
                            $this->getLogRoot()->info('LATENCIA ['.$this->idConsulta.']: Java 1=' . $this->javaTime[0] . ' 2=' . $this->javaTime[1] . ' -- Java Total=' . ($this->javaTime[0]+$this->javaTime[1]) . ' -- Total='. $intervalTotal );
                        }
                        return $this->evalResponse($intiSLTD);
                    }
                }else{
                    // Datos insuficientes para consultar por documento
                    $this->getLogRoot()->info('Datos insuficientes para consultar por documento ['.$this->idConsulta.']: ' . print_r($parametrosConsulta, true));
                    
                    $this->response->setCodRta("0");
                    $this->response->setColor("AMARILLO");
                    $this->response->setCodDetalleRta("3");
                    
                    return $this->response->getResponse();
                }
                
        } catch (Exception $e) {
            // Se produjo un error no tratado
            $this->getLogRoot()->error('Error al intentar realizar la consulta ['.$this->idConsulta.']: ' . print_r($e->getMessage(), true));
            
            $this->response->setCodRta("0");
            $this->response->setColor("AMARILLO");
            $this->response->setCodDetalleRta("2");
            
            return $this->response->getResponse();
        }
    }
    
    
    private function verificarEspeciales($pInput){
        if(preg_match("|^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$|", $pInput)){
            return true;
        }
        
        return false;
    }
    
    
    private function obtenerDetalle($inti,$accion,$resp){
        
        if($accion=='NOMINALS'){  // Pueden venir varios resultados, selecciono el que coincida con la fecha de nacimiento
            $persona=null;
            $nominals = json_decode(json_encode((array) $resp->datas->search->origin),1);
            
            if(isset($nominals['nominal']['date_of_birth'])){ // en este caso es solo un resultado
                $persona=$nominals['nominal'];
            }else{
                foreach($nominals['nominal'] as $row){
                    if($row['date_of_birth']==$inti->getConsulta()->fechaNacimiento){
                        $persona=$row;
                    }
                }
            }
        }
        
        $response=$resp->asXML();
        
        $intiDetails = new Inti();
        $intiDetails = clone $inti;
        $intiDetails->getConsulta()->fechaNacimiento=null;  // Este campo no es necesario en details y con esto salto la validacion de fecha del modelo
        
        if($accion=='NOMINALS'||$accion=='NOMINALSEXACT'){
            if(strpos($response,'i:entityId')){
                $intiDetails->setAccion('NOMINALSDETAILS');
                $intiDetails->getConsulta()->accion='NOMINALSDETAILS';
                
                $intiDetails->getConsulta()->entityId = ($accion=='NOMINALS') ? $persona['entityId'] : strval($resp->datas->search->origin->nominal->entityId);
            }else{ // Error al parsear
                $this->response->setCodRta("2");
                $this->response->setColor("NARANJA");
                $this->response->setCodDetalleRta("0");
                
                return $this->response->getResponse();
            }
        }else{ // Error al parsear
            $this->response->setCodRta("2");
            $this->response->setColor("NARANJA");
            $this->response->setCodDetalleRta("0");
            
            return $this->response->getResponse();
        }
        
        $intiDetails->consultar();
        
        array_push($this->javaTime,$intiDetails->getIntervalQuery());
        
        $t_fin =microtime(true);
        
        $intervalTotal = number_format(($t_fin-$this->t_ini),3)*1000;
        
        $this->getLogRoot()->info('LATENCIA ['.$this->idConsulta.']: Java 1=' . $this->javaTime[0] . ' 2=' . $this->javaTime[1] . ' -- Java Total=' . ($this->javaTime[0]+$this->javaTime[1]) . ' -- Total='. $intervalTotal );
        
        
        return $this->evalResponse($intiDetails);
    }
    
    
    private function evalResponse($inti){
        
        if($inti->getRespuesta()==='Sin resultados'){
            $this->response->setCodRta("1");
            $this->response->setColor("VERDE");
            $this->response->setCodDetalleRta("0");
        }elseif($inti->getRespuesta()==='Error desde el servidor'){
            $this->response->setCodRta("0");
            $this->response->setColor("AMARILLO");
            $this->response->setCodDetalleRta("2");
        }else{ // Respuestas con resultados, genera alertas de Interpol!!
            if(is_object($inti->getRespuesta())){
                $xmlResp = $inti->getRespuesta()->asXML();
                if(strpos($xmlResp,'RED')){
                    $this->response->setCodRta("2");
                    $this->response->setColor("ROJO");
                    $this->response->setCodDetalleRta("0");
                }else{  // Algun otro tipo de restriccion que no es RED
                    $this->response->setCodRta("2");
                    $this->response->setColor("NARANJA");
                    $this->response->setCodDetalleRta("0");
                }
            }
        }
        
        return $this->response->getResponse();
    }
    
    
    
    
    public function LoteInterpolPersonas($parametrosConsulta) {
        
        $xmlResponse=null;
        
        try{
            
            $this->getLogRoot()->trace('Parametros consulta: ' . print_r($parametrosConsulta, true));
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://localhost/admin/api/importarlote.php?tipoLote=persona",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => FuncionesObjetos::getUrlParametersFromObject($parametrosConsulta),
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if($err==null){
                
                $this->getLogRoot()->info('Devolucion desde el Servidor: ' . print_r($response, true));
                
                $xmlResponse = '<ns0:LoteInterpolPersonasResponse xmlns:ns0="http://ws.inti.dnm/">
             <return>'.$response.'</return>
          </ns0:LoteInterpolPersonasResponse>';
                
            }else{
                
                $xmlResponse = '<ns0:LoteInterpolPersonasResponse xmlns:ns0="http://ws.inti.dnm/">
             <return>Error: '.$err.'</return>
          </ns0:LoteInterpolPersonasResponse>';
                
                $this->getLogRoot()->error('Error desde el Servidor: ' . print_r($err, true));
            }
            
        }catch(Exception $e){
            
            $xmlResponse = '<ns0:LoteInterpolPersonasResponse xmlns:ns0="http://ws.inti.dnm/">
             <return>Error: '.$e->getMessage().'</return>
          </ns0:LoteInterpolPersonasResponse>';
            
            $this->getLogRoot()->error('Error: ' . print_r($e, true));
            
        }
        
        return new SoapVar($xmlResponse, XSD_ANYXML);
        
        
    }
    
    
    public function LoteDocumentos($parametrosConsulta) {
        
        $xmlResponse=null;
        
        try{
            
            $this->getLogRoot()->info('Parametros consulta: ' . print_r($parametrosConsulta, true));
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://localhost/admin/api/importarlote.php?tipoLote=documento",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => FuncionesObjetos::getUrlParametersFromObject($parametrosConsulta),
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if($err==null){
                
                $this->getLogRoot()->trace('Devolucion desde el Servidor: ' . print_r($response, true));
                
                $xmlResponse = '<ns0:LoteDocumentosResponse xmlns:ns0="http://ws.inti.dnm/">
             <return>'.$response.'</return>
          </ns0:LoteDocumentosResponse>';
                
            }else{
                
                $xmlResponse = '<ns0:LoteDocumentosResponse xmlns:ns0="http://ws.inti.dnm/">
             <return>Error: '.$err.'</return>
          </ns0:LoteDocumentosResponse>';
                
                $this->getLogRoot()->error('Error desde el Servidor: ' . print_r($err, true));
            }
            
        }catch(Exception $e){
            
            $xmlResponse = '<ns0:LoteDocumentosResponse xmlns:ns0="http://ws.inti.dnm/">
             <return>Error: '.$e->getMessage().'</return>
          </ns0:LoteDocumentosResponse>';
            
            $this->getLogRoot()->error('Error: ' . print_r($e, true));
            
        }
        
        return new SoapVar($xmlResponse, XSD_ANYXML);
        
    }
    
    
    protected function inicializarLogger() {
        Logger::configure('../Ambiente/configDNM.xml');
        $this->logRoot = Logger::getRootLogger();
    }
    
}
