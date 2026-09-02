<?php

include_once '/apache/includes/ambiente.php';

/**
 * Configuración de ambiente
 * Este archivo debe ser modificado según el 
 * ambiente donde se aplique la solución
 */
class Ambiente {

    //Atributos
    private $ambiente;
    private $host;
    private $token;
    private $sistema;
    private $origen;
    private $hostFrontEnd;
    private $modoDnm;

    //Getters y Setters
    public function getAmbiente() {
        return $this->ambiente;
    }

    private function setAmbiente($ambiente) {
        $this->ambiente = $ambiente;
    }

    public function getHost() {
        return $this->host;
    }

    private function setHost($host) {
        $this->host = $host;
    }

    public function getSistema() {
        return $this->sistema;
    }

    private function setSistema($sistema) {
        $this->sistema = $sistema;
    }

    public function getOrigen() {
        return $this->origen;
    }

    private function setOrigen($origen) {
        $this->origen = $origen;
    }

    public function getToken() {
        return $this->token;
    }

    private function setToken($token) {
        $this->token = $token;
    }

    public function getHostFrontEnd() {
        return $this->hostFrontEnd;
    }

    private function setHostFrontEnd($hostFrontEnd) {
        $this->hostFrontEnd = $hostFrontEnd;
    }

    public function getModoDnm() {
        return $this->modoDnm;
    }
    
    private function setModoDnm($modoDnm) {
        $this->modoDnm = $modoDnm;
    }
    

    //Constructor
    function __construct() {
        $this->setAmbiente(AMBIENTE);
        $this->setHost(HOST_INTI_WEB);
        $this->setToken(TOKEN_INTI);
        $this->setSistema(SISTEMA_INTI);
        $this->setHostFrontEnd(HOST_FRONT_END);
        $this->setModoDnm(MODO_DNM); 
    }

}
