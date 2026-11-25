<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ADMIN\AdminBundle\Entity\TipodelitoRepository;

/**
 * Tipodelito
 *
 * @ORM\Table(name="TIPODELITO")
 * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\TipodelitoRepository")
 */
class Tipodelito
{
    /**
     * @var integer
     *
     * @ORM\Column(name="TIPODELITOID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="TIPODELITO_TIPODELITOID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    private $nombre;


    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Get tipodelitoid
     *
     * @return integer 
     */
    public function getid()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Tipodelito
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