<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proceso
 *
 * @ORM\Table(name="PROCESO")
 * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\ProcesoRepository")
 */
class Proceso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="PROCESO_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=100, nullable=false)
     */
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="RESULTADO", type="blob", nullable=true)
     */
    private $resultado;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHAINI", type="datetime", nullable=false)
     */
    private $fechaIni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHAFIN", type="datetime", nullable=true)
     */
    private $fechaFin;
        
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set resultado
     *
     * @param string $resultado
     * @return Proceso
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
        
        return $this;
    }
    
    /**
     * Get respuesta
     *
     * @return string
     */
    public function getResultado()
    {
        if(is_resource($this->resultado)){
            return stream_get_contents($this->resultado);
        }else{
            return $this->resultado;
        }
    }
    
    
    /**
     * @return \DateTime
     */
    public function getFechaIni()
    {
        return $this->fechaIni;
    }

    /**
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * @param string $nombre
     * @return Proceso
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }


    /**
     * @param \DateTime $fechaIni
     * @return Proceso
     */
    public function setFechaIni($fechaIni)
    {
        $this->fechaIni = $fechaIni;
        return $this;
    }

    /**
     * @param \DateTime $fechaFin
     * @return Proceso
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
        return $this;
    }


    
   
}