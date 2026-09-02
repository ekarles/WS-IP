<?php 

class DNMResponse {

    private $codRta;
    private $color;
    private $codDetalleRta;
    /**
     * @return mixed
     */
    public function getCodRta()
    {
        return $this->codRta;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return mixed
     */
    public function getCodDetalleRta()
    {
        return $this->codDetalleRta;
    }

    /**
     * @param mixed $codRta
     */
    public function setCodRta($codRta)
    {
        $this->codRta = $codRta;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @param mixed $codDetalleRta
     */
    public function setCodDetalleRta($codDetalleRta)
    {
        $this->codDetalleRta = $codDetalleRta;
    }
    
    
    function __construct($codRta,$codDetalleRta,$color){
        $this->codRta=$codRta;
        $this->codDetalleRta=$codDetalleRta;
        $this->color=$color;
    }
    
    public function getResponse(){
        $xmlResponse='<ns0:ConsultaInterpolPersonasResponse xmlns:ns0="http://ws.inti.dnm/">
         <return><![CDATA[<?xml version="1.0" encoding="UTF-8"?>
<PFA>
   <COD_RTA>'.$this->codRta.'</COD_RTA>
   <COLOR>'.$this->color.'</COLOR>
   <COD_DETALLE_RTA>'.$this->codDetalleRta.'</COD_DETALLE_RTA>
</PFA>]]></return>
      </ns0:ConsultaInterpolPersonasResponse>';
        
        return new SoapVar($xmlResponse, XSD_ANYXML);        
    }
    
}


?>