<?php

/*
 * PHP SOAP 
 */
ini_set('soap.wsdl_cache_enabled', 0);

header('Content-Type: text/xml; charset=utf-8');

include_once('../Ambiente/parameters.php');

$ambiente = new Ambiente();
if ($ambiente->getAmbiente() == 'DESARROLLO') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}


require_once('../../Utilidades/Herramientas/Log4Php/Logger.php');
require_once ('MyHandlerClassDecorator.php');
require_once ('Conexion.php');

Logger::configure('../Ambiente/configDNM.xml');
$logger= Logger::getRootLogger();

//create a new SOAP server
$server = new SoapServer("DNM_INTI_WSService.wsdl", array("encoding" => "UTF8", 'cache_wsdl' => WSDL_CACHE_NONE));

//Mando al log los datos recibidos via HTTP

if (isset($HTTP_RAW_POST_DATA)) {
    $data = $HTTP_RAW_POST_DATA;
} else {
    $data = '';
}

$idConsulta = str_pad(rand(0,10000000),8,'0',STR_PAD_LEFT);  // Para trackear en logs

$logger->trace('HTTP RECIBIDO ['.$idConsulta.']: ' . var_export($data, true));

$server->setObject(new MyHandlerClassDecorator(new Conexion($logger,$ambiente,$idConsulta), $data, $idConsulta));

ob_end_flush();
ob_start();

$server->handle();

$soapXml = ob_get_contents();

ob_end_clean();

Logger::configure('../Ambiente/configDNM.xml');
$logger= Logger::getRootLogger();

$logger->info("RESPONSE [".$idConsulta."]: ".preg_replace('/(\r\n|\r|\n|\s)+/','', $soapXml));

echo $soapXml;   // Devuelvo la respuesta