<?php

namespace GESTION\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConsultaLoteDetalle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GESTION\GestionBundle\Entity\ConsultaLoteDetalleRepository")
 */
class ConsultaLoteDetalle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GESTION\GestionBundle\Entity\ConsultaLote", inversedBy="consultaLoteDetalle")
     * @ORM\JoinColumn(name="ConsultaLoteId", referencedColumnName="id")
     */
    private $consultaLoteId;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellido", type="string", length=100, nullable=true)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=200, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="FechaNacimiento", type="string", length=200, nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoDocumento", type="string", length=10, nullable=true)
     */
    private $tipoDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="NroDocumento", type="string", length=100, nullable=true)
     */
    private $nroDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="Pais", type="string", length=5, nullable=true)
     */
    private $pais;

    /**
     * @var string
     *
     * @ORM\Column(name="Vin", type="string", length=100, nullable=true)
     */
    private $vin;

    /**
     * @var string
     *
     * @ORM\Column(name="Dominio", type="string", length=20, nullable=true)
     */
    private $dominio;

    /**
     * @var string
     *
     * @ORM\Column(name="NroMotor", type="string", length=100, nullable=true)
     */
    private $nroMotor;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoConsulta", type="string", length=20)
     */
    private $tipoConsulta;

    /**
     * @var string
     *
     * @ORM\Column(name="ModoConsulta", type="string", length=2)
     */
    private $modoConsulta;

    /**
     * @var string
     *
     * @ORM\Column(name="ResultCode", type="string", length=255, nullable=true)
     */
    private $resultCode;

    /**
     * @var string
     *
     * @ORM\Column(name="Respuesta", type="blob", nullable=true)
     */
    private $respuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="EntityId", type="string", length=255, nullable=true)
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="RespuestaDetails", type="blob", nullable=true)
     */
    private $respuestaDetails;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FecAlta", type="string")
     */
    private $fecAlta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FecMod", type="string", nullable=true)
     */
    private $fecMod;

    /**
     * @var string
     *
     * @ORM\Column(name="Leido", type="string", length=1, nullable=true)
     */
    private $leido;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set consultaLoteId
     *
     * @param integer $consultaLoteId
     * @return ConsultaLoteDetalle
     */
    public function setConsultaLoteId($consultaLoteId)
    {
        $this->consultaLoteId = $consultaLoteId;
    
        return $this;
    }

    /**
     * Get consultaLoteId
     *
     * @return integer 
     */
    public function getConsultaLoteId()
    {
        return $this->consultaLoteId;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return ConsultaLoteDetalle
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return ConsultaLoteDetalle
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return ConsultaLoteDetalle
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;
    
        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     * @return ConsultaLoteDetalle
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;
    
        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set nroDocumento
     *
     * @param string $nroDocumento
     * @return ConsultaLoteDetalle
     */
    public function setNroDocumento($nroDocumento)
    {
        $this->nroDocumento = $nroDocumento;
    
        return $this;
    }

    /**
     * Get nroDocumento
     *
     * @return string 
     */
    public function getNroDocumento()
    {
        return $this->nroDocumento;
    }

    /**
     * Set pais
     *
     * @param string $pais
     * @return ConsultaLoteDetalle
     */
    public function setPais($pais)
    {
        $this->pais = $pais;
    
        return $this;
    }

    /**
     * Get pais
     *
     * @return string 
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set vin
     *
     * @param string $vin
     * @return ConsultaLoteDetalle
     */
    public function setVin($vin)
    {
        $this->vin = $vin;
    
        return $this;
    }

    /**
     * Get vin
     *
     * @return string 
     */
    public function getVin()
    {
        return $this->vin;
    }

    /**
     * Set dominio
     *
     * @param string $dominio
     * @return ConsultaLoteDetalle
     */
    public function setDominio($dominio)
    {
        $this->dominio = $dominio;
    
        return $this;
    }

    /**
     * Get dominio
     *
     * @return string 
     */
    public function getDominio()
    {
        return $this->dominio;
    }

    /**
     * Set nroMotor
     *
     * @param string $nroMotor
     * @return ConsultaLoteDetalle
     */
    public function setNroMotor($nroMotor)
    {
        $this->nroMotor = $nroMotor;
    
        return $this;
    }

    /**
     * Get nroMotor
     *
     * @return string 
     */
    public function getNroMotor()
    {
        return $this->nroMotor;
    }

    /**
     * Set tipoConsulta
     *
     * @param string $tipoConsulta
     * @return ConsultaLoteDetalle
     */
    public function setTipoConsulta($tipoConsulta)
    {
        $this->tipoConsulta = $tipoConsulta;
    
        return $this;
    }

    /**
     * Get tipoConsulta
     *
     * @return string 
     */
    public function getTipoConsulta()
    {
        return $this->tipoConsulta;
    }

    /**
     * Set modoConsulta
     *
     * @param string $modoConsulta
     * @return ConsultaLoteDetalle
     */
    public function setModoConsulta($modoConsulta)
    {
        $this->modoConsulta = $modoConsulta;
    
        return $this;
    }

    /**
     * Get modoConsulta
     *
     * @return string 
     */
    public function getModoConsulta()
    {
        return $this->modoConsulta;
    }

    /**
     * Set resultCode
     *
     * @param string $resultCode
     * @return ConsultaLoteDetalle
     */
    public function setResultCode($resultCode)
    {
        $this->resultCode = $resultCode;
    
        return $this;
    }

    /**
     * Get resultCode
     *
     * @return string 
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    /**
     * Set respuesta
     *
     * @param string $respuesta
     * @return ConsultaLoteDetalle
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    
        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
    	return $this->respuesta;
    }

    /**
     * Set entityId
     *
     * @param string $entityId
     * @return ConsultaLoteDetalle
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    
        return $this;
    }

    /**
     * Get entityId
     *
     * @return string 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set respuestaDetails
     *
     * @param string $respuestaDetails
     * @return ConsultaLoteDetalle
     */
    public function setRespuestaDetails($respuestaDetails)
    {
        $this->respuestaDetails = $respuestaDetails;
    
        return $this;
    }

    /**
     * Get respuestaDetails
     *
     * @return string 
     */
    public function getRespuestaDetails()
    {
        return $this->respuestaDetails;
    }

    /**
     * Set fecAlta
     *
     * @param \DateTime $fecAlta
     * @return ConsultaLoteDetalle
     */
    public function setFecAlta($fecAlta)
    {
        $this->fecAlta = $fecAlta;
    
        return $this;
    }

    /**
     * Get fecAlta
     *
     * @return \DateTime 
     */
    public function getFecAlta()
    {
        return $this->fecAlta;
    }

    /**
     * Set fecMod
     *
     * @param \DateTime $fecMod
     * @return ConsultaLoteDetalle
     */
    public function setFecMod($fecMod)
    {
        $this->fecMod = $fecMod;
    
        return $this;
    }

    /**
     * Get fecMod
     *
     * @return \DateTime 
     */
    public function getFecMod()
    {
        return $this->fecMod;
    }
    
    
    /**
     * Get leido
     * 
     * @return string
     */
    public function getLeido()
    {
        return $this->leido;
    }

    /**
     * Set leido
     * 
     * @param string $leido
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;
    }

    
    
}