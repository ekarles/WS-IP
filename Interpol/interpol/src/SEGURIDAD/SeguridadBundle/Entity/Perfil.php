<?php

namespace SEGURIDAD\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Perfil
 *
 * @ORM\Table(name="PERFIL")
 * @ORM\Entity(repositoryClass="SEGURIDAD\SeguridadBundle\Entity\PerfilRepository")
 */
class Perfil
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PERFILID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="PERFIL_PERFILID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="BORRADO", type="integer", nullable=true)
     */
    private $borrado;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION", type="string", length=80, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=60, nullable=true)
     */
    private $nombre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="perfilid")
     */
    private $usuarioid;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\ManyToMany(targetEntity="Permiso", inversedBy="perfilid")
     * @ORM\JoinTable(name="perfilpermiso",
     *   joinColumns={
     *     @ORM\JoinColumn(name="PERFILID", referencedColumnName="PERFILID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="PERMISOID", referencedColumnName="PERMISOID")
     *   }
     * )
     */
    private $permisoid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="ADMIN", type="integer", nullable=true)
     */
    private $admin;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="ADMIN_EXT", type="integer", nullable=true)
     */
    private $admin_ext;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarioid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->permisoid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    /**
     * ToString
     */
    public function __toString()
    {
        return strtoupper($this->nombre).' ('.$this->descripcion.')';
    }
    

    public function getVars(){
        $vars = get_object_vars($this);
        
        $vars['permisoid']=array();
        foreach ($this->getPermisoid() as $permiso){
            $vars['permisoid'][] = $permiso->getVars();
        }
        
        return $vars;
    }
    
    /**
     * Get perfilid
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set borrado
     *
     * @param integer $borrado
     * @return Perfil
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

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Perfil
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
     * Set nombre
     *
     * @param string $nombre
     * @return Perfil
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
     * Add usuarioid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Usuario $usuarioid
     * @return Perfil
     */
    public function addUsuarioid(\SEGURIDAD\SeguridadBundle\Entity\Usuario $usuarioid)
    {
        if (!$this->usuarioid->contains($usuarioid)) {
            $this->usuarioid[] = $usuarioid;
            $usuarioid->addPerfilid($this);
        }
        
        return $this;
    }

    /**
     * Remove usuarioid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Usuario $usuarioid
     */
    public function removeUsuarioid(\SEGURIDAD\SeguridadBundle\Entity\Usuario $usuarioid)
    {
        $this->usuarioid->removeElement($usuarioid);
        $usuarioid->removePerfilid($this);
    }

    /**
     * Get usuarioid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarioid()
    {
        return $this->usuarioid;
    }

    /**
     * Add permisoid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Permiso $permisoid
     * @return Perfil
     */
    public function addPermisoid(\SEGURIDAD\SeguridadBundle\Entity\Permiso $permisoid)
    {
        $this->permisoid[] = $permisoid;
    
        return $this;
    }

    /**
     * Remove permisoid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Permiso $permisoid
     */
    public function removePermisoid(\SEGURIDAD\SeguridadBundle\Entity\Permiso $permisoid)
    {
        $this->permisoid->removeElement($permisoid);
    }

    /**
     * Get permisoid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermisoid()
    {
        return $this->permisoid;
    }
    
    /**
     * Set admin
     *
     * @param integer $admin
     * @return Perfil
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        
        return $this;
    }
    
    /**
     * Get admin
     *
     * @return integer
     */
    public function getAdmin()
    {
        return $this->admin;
    }
    
    /**
     * Get admin_ext
     *
     * @return integer
     */
    public function getAdminext()
    {
        return $this->admin_ext;
    }
    
    /**
     * Set admin_ext
     *
     * @param integer $admin_ext
     * @return Perfil
     */
    public function setAdminext($admin_ext)
    {
        $this->admin_ext = $admin_ext;
        
        return $this;
    }
    
}