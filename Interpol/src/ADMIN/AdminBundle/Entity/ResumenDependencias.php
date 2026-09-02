<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResumenDependencias
 *
 * @ORM\Table(name="RESUMEN_DEPENDENCIAS")
 * @ORM\Entity
 */
class ResumenDependencias
{
    /**
     * @var integer
     *
     * @ORM\Column(name="DEPENID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="RESUMEN_DEPENDENCIAS_DEPENID_s", allocationSize=1, initialValue=1)
     */
    private $depenid;

    /**
     * @var string
     *
     * @ORM\Column(name="ALARMAS", type="string", length=2000, nullable=true)
     */
    private $alarmas;

    /**
     * @var string
     *
     * @ORM\Column(name="CODIGO", type="string", length=20, nullable=true)
     */
    private $codigo;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONSULTAS", type="integer", nullable=true)
     */
    private $consultas;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ULTIMACONSULTA", type="date", nullable=true)
     */
    private $ultimaconsulta;



    /**
     * Get depenid
     *
     * @return integer 
     */
    public function getDepenid()
    {
        return $this->depenid;
    }

    /**
     * Set alarmas
     *
     * @param string $alarmas
     * @return ResumenDependencias
     */
    public function setAlarmas($alarmas)
    {
        $this->alarmas = $alarmas;
    
        return $this;
    }

    /**
     * Get alarmas
     *
     * @return string 
     */
    public function getAlarmas()
    {
        return $this->alarmas;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return ResumenDependencias
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set consultas
     *
     * @param integer $consultas
     * @return ResumenDependencias
     */
    public function setConsultas($consultas)
    {
        $this->consultas = $consultas;
    
        return $this;
    }

    /**
     * Get consultas
     *
     * @return integer 
     */
    public function getConsultas()
    {
        return $this->consultas;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return ResumenDependencias
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
     * Set ultimaconsulta
     *
     * @param \DateTime $ultimaconsulta
     * @return ResumenDependencias
     */
    public function setUltimaconsulta($ultimaconsulta)
    {
        $this->ultimaconsulta = $ultimaconsulta;
    
        return $this;
    }

    /**
     * Get ultimaconsulta
     *
     * @return \DateTime 
     */
    public function getUltimaconsulta()
    {
        return $this->ultimaconsulta;
    }
}