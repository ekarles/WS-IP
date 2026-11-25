<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alarmaestado
 *
 * @ORM\Table(name="ALARMAESTADO")
 * @ORM\Entity
 */
class Alarmaestado
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ESTADOID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ALARMAESTADO_ESTADOID_seq", allocationSize=1, initialValue=1)
     */
    private $estadoid;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    private $nombre;



    /**
     * Get estadoid
     *
     * @return integer 
     */
    public function getEstadoid()
    {
        return $this->estadoid;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Alarmaestado
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
}