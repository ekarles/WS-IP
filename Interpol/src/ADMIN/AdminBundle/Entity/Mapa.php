<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ADMIN\AdminBundle\Entity\MapaRepository;

/**
 * Mapa
 *
 * @ORM\Table(name="MAPA")
 * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\MapaRepository")
 */
class Mapa
{
    /**
     * @var integer
     *
     * @ORM\Column(name="MAPAID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="MAPA_MAPAID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION", type="string", length=500, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="LATITUD", type="decimal", nullable=true)
     */
    private $latitud;

    /**
     * @var string
     *
     * @ORM\Column(name="LONGITUD", type="decimal", nullable=true)
     */
    private $longitud;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZOOM", type="integer", nullable=true)
     */
    private $zoom;

    /**
     * @ORM\ManyToMany(targetEntity="SEGURIDAD\SeguridadBundle\Entity\Dependencia", inversedBy="depenid" , cascade={"persist"})
     * @ORM\JoinTable(name="mapadetalle",
     *      joinColumns={@ORM\JoinColumn(name="MAPAID", referencedColumnName="MAPAID")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="DEPENID", referencedColumnName="DEPENID")}
     *      )
     */
    private $dependencias;

    public function __construct()
    {
        //parent::__construct();
        $this->dependencias = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->descripcion;
    }
    
    /**
     * Get mapaid
     *
     * @return integer 
     */
    public function getid()
    {
        return $this->id;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Mapa
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set latitud
     *
     * @param string $latitud
     * @return Mapa
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    
        return $this;
    }

    /**
     * Get latitud
     *
     * @return string 
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param string $longitud
     * @return Mapa
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    
        return $this;
    }

    /**
     * Get longitud
     *
     * @return string 
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Mapa
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
     * Set zoom
     *
     * @param integer $zoom
     * @return Mapa
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
    
        return $this;
    }

    /**
     * Get zoom
     *
     * @return integer 
     */
    public function getZoom()
    {
        return $this->zoom;
    }
    
    /**
     * Add dependencia
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Dependencia $dependencia
     * @return Mapa
     */
    public function addDependencia(\SEGURIDAD\SeguridadBundle\Entity\Dependencia $dependencia)
    {
        if (!$this->dependencias->contains($dependencia)) {
            $this->dependencias[] = $dependencia;
            $dependencia->addMapa($this);
        }
        
        return $this;
    }
    
    /**
     * Remove dependencia
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Dependencia $dependencia
     */
    public function removeDependencia(\SEGURIDAD\SeguridadBundle\Entity\Dependencia $dependencia)
    {
        $this->dependencias->removeElement($dependencia);
        $dependencia->removeMapa($this);
    }
    
    
    /**
     * Get dependencia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDependencias()
    {
        return $this->dependencias;
    }
    
    
}