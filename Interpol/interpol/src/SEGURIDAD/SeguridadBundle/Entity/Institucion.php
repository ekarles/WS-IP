<?php

namespace SEGURIDAD\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Institucion
 *
 * @ORM\Table(name="INSTITUCION")
 * @ORM\Entity(repositoryClass="SEGURIDAD\SeguridadBundle\Entity\InstitucionRepository")
 */
class Institucion
{

    /**
     * @var \Dependencia
     *
     * @ORM\ManyToOne(targetEntity="Dependencia")
     */

    /**
     * @var integer
     *
     * @ORM\Column(name="INSTITUCIONID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="INSTITUCION_INSTITUCIONID_seq", allocationSize=1, initialValue=1)
     * })

     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="PASSGENERICO", type="string", length=255, nullable=true)
     */
    private $passgenerico;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIOGENERICO", type="string", length=255, nullable=true)
     */
    private $usuariogenerico;

    public function __toString()
    {
        return $this->nombre;
    }

    
    public function getVars(){
        return get_object_vars($this);
    }
    
    /**
     * Get institucionid
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Institucion
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
     * Set passgenerico
     *
     * @param string $passgenerico
     * @return Institucion
     */
    public function setPassgenerico($passgenerico)
    {
        $this->passgenerico = $passgenerico;
    
        return $this;
    }

    /**
     * Get passgenerico
     *
     * @return string 
     */
    public function getPassgenerico()
    {
        return $this->passgenerico;
    }

    /**
     * Set usuariogenerico
     *
     * @param string $usuariogenerico
     * @return Institucion
     */
    public function setUsuariogenerico($usuariogenerico)
    {
        $this->usuariogenerico = $usuariogenerico;
    
        return $this;
    }

    /**
     * Get usuariogenerico
     *
     * @return string 
     */
    public function getUsuariogenerico()
    {
        return $this->usuariogenerico;
    }
}