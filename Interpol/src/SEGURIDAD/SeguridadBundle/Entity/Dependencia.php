<?php

namespace SEGURIDAD\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ADMIN\AdminBundle\Entity\Mapa;

/**
 * Dependencia
 *
 * @ORM\Table(name="DEPENDENCIA")
 * @ORM\Entity(repositoryClass="SEGURIDAD\SeguridadBundle\Entity\DependenciaRepository")
 */
class Dependencia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="DEPENID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DEPENDENCIA_DEPENID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="CODIGO", type="string", length=6, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="CONTACTO", type="string", length=1000, nullable=true)
     */
    private $contacto;

    /**
     * @var string
     *
     * @ORM\Column(name="MAIL", type="string", length=1000, nullable=true)
     */
    private $mail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="DIRECCION", type="string", length=255, nullable=true)
     */
    private $direccion;

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
     * @var integer
     *
     * @ORM\Column(name="MAXIMOUSUARIOS", type="integer", nullable=true)
     */
    private $maximousuarios;

    /**
     * @var integer
     *
     * @ORM\Column(name="MINAVISO", type="integer", nullable=true)
     */
    private $minaviso;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="OBSERVACIONES", type="string", length=1000, nullable=true)
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="ORDEN", type="string", length=20, nullable=true)
     */
    private $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO", type="string", length=255, nullable=true)
     */
    private $tipo;

    /**
     * @var \Institucion
     *
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="INSTITUCIONID", referencedColumnName="INSTITUCIONID")
     * })
     * 
     */
    private $institucionid;
    
    /**
     * @ORM\ManyToMany(targetEntity="ADMIN\AdminBundle\Entity\Mapa", inversedBy="mapaid" , cascade={"persist"})
     * @ORM\JoinTable(name="mapadetalle",
     *      joinColumns={@ORM\JoinColumn(name="DEPENID", referencedColumnName="DEPENID")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="MAPAID", referencedColumnName="MAPAID")}
     *      )
     */
    private $mapas;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="BORRADO", type="integer", nullable=true)
     */
    private $borrado;
    
    
    public function __construct()
    {
        $this->mapas= new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    public function __toString(){
        return $this->nombre;
    }

    
    public function getVars(){
        $vars = get_object_vars($this);
        
        $vars['institucionid'] = $this->getInstitucionid()->getVars();
        
        return $vars;
    }

    /**
     * Get depenid
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Dependencia
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
     * Set contacto
     *
     * @param string $contacto
     * @return Dependencia
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;
    
        return $this;
    }

    /**
     * Get contacto
     *
     * @return string 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Dependencia
     */
    public function setMail($mail)
    {
        $this->mail = str_replace(" ","",$mail);
        
        return $this;
    }
    
    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Dependencia
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set latitud
     *
     * @param string $latitud
     * @return Dependencia
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
     * @return Dependencia
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
     * Set maximousuarios
     *
     * @param integer $maximousuarios
     * @return Dependencia
     */
    public function setMaximousuarios($maximousuarios)
    {
        $this->maximousuarios = $maximousuarios;
    
        return $this;
    }

    /**
     * Get maximousuarios
     *
     * @return integer 
     */
    public function getMaximousuarios()
    {
        return $this->maximousuarios;
    }

    /**
     * Set minaviso
     *
     * @param integer $minaviso
     * @return Dependencia
     */
    public function setMinaviso($minaviso)
    {
        $this->minaviso = $minaviso;
    
        return $this;
    }

    /**
     * Get minaviso
     *
     * @return integer 
     */
    public function getMinaviso()
    {
        return $this->minaviso;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Dependencia
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
     * Set observaciones
     *
     * @param string $observaciones
     * @return Dependencia
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set orden
     *
     * @param string $orden
     * @return Dependencia
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    
        return $this;
    }

    /**
     * Get orden
     *
     * @return string 
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Dependencia
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set institucionid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Institucion $institucionid
     * @return Dependencia
     */
    
    public function setInstitucionid(\SEGURIDAD\SeguridadBundle\Entity\Institucion $institucionid = null)
    {
        $this->institucionid = $institucionid;
    
        return $this;
    }

    /**
     * Get institucionid
     *
     * @return \SEGURIDAD\SeguridadBundle\Entity\Institucion 
     */
    public function getInstitucionid()
    {
        
        /*try {
            if ($this->institucionid === null){
                return null;
            }else{
                $nombre = $this->institucionid->getNombre();
            }
        } catch (EntityNotFoundException $e) {
            return null;
        }*/
        
        return $this->institucionid;
    }
    
    
    /**
     * Add mapas
     *
     * @param \ADMIN\AdminBundle\Entity\Mapa $mapa
     * @return Dependencia
     */
    public function addMapa(\ADMIN\AdminBundle\Entity\Mapa $mapa)
    {
        if (!$this->mapas->contains($mapa)) {
            $this->mapas[] = $mapa;
            $mapa->addDependencia($this);
        }
        
        return $this;
    }
    
    /**
     * Remove mapa
     *
     * @param \ADMIN\AdminBundle\Entity\Mapa $mapa
     */
    public function removeMapa(\ADMIN\AdminBundle\Entity\Mapa $mapa)
    {
        $this->mapas->removeElement($mapa);
        $mapa->removeDependencia($this);
    }
    
    
    /**
     * Get mapa
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMapas()
    {
        return $this->mapas;
    }
    
    
    /**
     * Set borrado
     *
     * @param integer $borrado
     * @return Dependencia
     */
    public function setBorrado($borrado)
    {
        $this->borrado = $borrado;
        
        return $this;
    }
    
    /**
     * Get borrado
     *
     * @return integer
     */
    public function getBorrado()
    {
        return $this->borrado;
    }
    
}