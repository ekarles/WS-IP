<?php

namespace SEGURIDAD\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permiso
 *
 * @ORM\Table(name="PERMISO")
 * @ORM\Entity
 */
class Permiso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PERMISOID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="PERMISO_PERMISOID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="CODIGO", type="string", length=255, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="PERMISO", type="string", length=255, nullable=true)
     */
    private $permiso;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", inversedBy="permisoid", cascade={"persist"})
     * @ORM\JoinTable(name="usuariopermisos",
     *   joinColumns={
     *     @ORM\JoinColumn(name="PERMISOID", referencedColumnName="PERMISOID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="USUARIOID", referencedColumnName="USUARIOID")
     *   }
     * )
     */
    private $usuarioid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Perfil", mappedBy="permisoid", cascade={"persist"})
     * @ORM\JoinTable(name="perfilpermiso",
     *   joinColumns={
     *     @ORM\JoinColumn(name="PERMISOID", referencedColumnName="PERMISOID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="PERFILID", referencedColumnName="PERFILID")
     *   }
     * )
     */
    private $perfilid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarioid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->perfilid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    public function getVars(){
        return get_object_vars($this);
    }
    
    /**
     * Get permisoid
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
     * @return Permiso
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
     * Set permiso
     *
     * @param string $permiso
     * @return Permiso
     */
    public function setPermiso($permiso)
    {
        $this->permiso = $permiso;
    
        return $this;
    }

    /**
     * Get permiso
     *
     * @return string 
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * Add usuarioid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Usuario $usuarioid
     * @return Permiso
     */
    public function addUsuarioid(\SEGURIDAD\SeguridadBundle\Entity\Usuario $usuarioid)
    {
        $this->usuarioid[] = $usuarioid;
    
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
     * Add perfilid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Perfil $perfilid
     * @return Permiso
     */
    public function addPerfilid(\SEGURIDAD\SeguridadBundle\Entity\Perfil $perfilid)
    {
        $this->perfilid[] = $perfilid;
    
        return $this;
    }

    /**
     * Remove perfilid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Perfil $perfilid
     */
    public function removePerfilid(\SEGURIDAD\SeguridadBundle\Entity\Perfil $perfilid)
    {
        $this->perfilid->removeElement($perfilid);
    }

    /**
     * Get perfilid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPerfilid()
    {
        return $this->perfilid;
    }
    
    
    /**
     * ToString
     */
    public function __toString()
    {
        return strtoupper($this->permiso).' ('.$this->permiso.')';
    }
    
    
}