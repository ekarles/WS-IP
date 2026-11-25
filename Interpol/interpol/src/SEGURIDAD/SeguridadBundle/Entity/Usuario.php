<?php

namespace SEGURIDAD\SeguridadBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityNotFoundException;



/**
 * Usuario
 *
 * @ORM\Table(name="USUARIO")
 * @ORM\Entity(repositoryClass="SEGURIDAD\SeguridadBundle\Entity\UsuarioRepository")
 * @UniqueEntity("usuario",message="Este nombre de usuario ya fue utilizado, por favor pruebe otro")
 
 */


class Usuario extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="USUARIOID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="USUARIO_USUARIOID_seq", allocationSize=1, initialValue=1)
     * 
     */
    protected $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="ACTIVO", type="integer", nullable=true)
     */
    protected $activo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="APELLIDO", type="string", length=255, nullable=true)
     */
    protected $apellido;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="BORRADO", type="integer", nullable=true)
     */
    protected $borrado;
    
    /**
     * @var string
     *
     * @ORM\Column(name="CONSULTA", type="string", length=50, nullable=true)
     */
    protected $consulta;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EXPIRACIONPASSWORD", type="datetime", nullable=true)
     */
    protected $expiracionpassword;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHAALTA", type="datetime", nullable=true)
     */
    protected $fechaalta;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHABORRADO", type="datetime", nullable=true)
     */
    protected $fechaborrado;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHADESACTIVADO", type="datetime", nullable=true)
     */
    protected $fechadesactivado;
    
    /**
     * @var string
     *
     * @ORM\Column(name="IPHABILITADA", type="string", length=250, nullable=true)
     */
    protected $iphabilitada;
    
    /**
     * @var string
     *
     * @ORM\Column(name="JERARQUIA", type="string", length=255, nullable=true)
     */
    protected $jerarquia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    protected $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="NUMERODOC", type="string", length=255, nullable=true)
     */
    protected $numerodoc;
    
    /**
     * @var string
     *
     * @ORM\Column(name="SESSIONID", type="string", length=50, nullable=true)
     */
    protected $sessionid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="TIPODOC", type="string", length=255, nullable=true)
     */
    protected $tipodoc;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ULTIMAIP", type="string", length=60, nullable=true)
     */
    protected $ultimaip;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ULTIMOLOGIN", type="datetime", nullable=true)
     */
    protected $ultimologin;
    
    /**
     * @var string
     *
     * @ORM\Column(name="USUARIO", type="string", length=255, nullable=true)
     */
    protected $usuario;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="USUARIOALTA", type="integer", nullable=true)
     */
    protected $usuarioalta;
    
    /**
     * @ORM\ManyToMany(targetEntity="Perfil", inversedBy="usuarioid" , cascade={"persist"})
     * @ORM\JoinTable(name="usuarioperfil",
     *      joinColumns={@ORM\JoinColumn(name="USUARIOID", referencedColumnName="USUARIOID")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="PERFILID", referencedColumnName="PERFILID")}
     *      )
     */
    protected $perfilid;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Permiso", mappedBy="usuarioid", cascade={"persist"})
     */
    protected $permisoid;
    
    /**
     * @var \Dependencia
     *
     * @ORM\ManyToOne(targetEntity="Dependencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="DEPENID", referencedColumnName="DEPENID")
     * })
     */
    protected $depenid;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="CANTPERSONA", type="integer", nullable=true, options={"default": "0"})
     */
    protected $cantPersona;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="CANTDOCUMENTO", type="integer", nullable=true, options={"default": "0"})
     */
    protected $cantDocumento;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="CANTVEHICULO", type="integer", nullable=true, options={"default": "0"})
     */
    protected $cantVehiculo;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="CANTCOMBINADA", type="integer", nullable=true, options={"default": "0"})
     */
    protected $cantCombinada;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="CANTLOTE", type="integer", nullable=true, options={"default": "0"})
     */
    protected $cantLote;
    
    /**
     * @var string
     *
     * @ORM\Column(name="OBSERVACION", type="string", nullable=true, options={"default": null})
     */
    protected $observacion;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->perfilid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->permisoid = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->usuario;
    }
    
    
    public function getVars(){
        $vars = get_object_vars($this);
        
        $vars['depenid'] = $this->getDepenid()->getVars();
        
        $vars['permisoid']=array();
        foreach($this->getPermisoid() as $permiso){
            $vars['permisoid'][] = $permiso->getVars();
        }
        
        $vars['perfilid']=array();
        foreach($this->getPerfilid() as $perfil){
            $vars['perfilid'][] = $perfil->getVars();
        }
        
        return $vars;
    }
    
    
    /**
     * Get id
     *
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }
    
    /**
     * Set activo
     *
     * @param integer $activo
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
        
        return $this;
    }
    
    /**
     * Set enabled
     *
     * @param integer $enabled
     * @return Usuario
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        
        return $this;
    }
    
    /**
     * Get activo
     *
     * @return integer
     */
    public function getActivo()
    {
        return $this->activo;
    }
    
    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Usuario
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
     * Set borrado
     *
     * @param integer $borrado
     * @return Usuario
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
     * Set consulta
     *
     * @param string $consulta
     * @return Usuario
     */
    public function setConsulta($consulta)
    {
        $this->consulta = $consulta;
        
        return $this;
    }
    
    /**
     * Get consulta
     *
     * @return string
     */
    public function getConsulta()
    {
        return $this->consulta;
    }
    
    /**
     * Set expiracionpassword
     *
     * @param \DateTime $expiracionpassword
     * @return Usuario
     */
    public function setExpiracionpassword($expiracionpassword)
    {
        $this->expiracionpassword = $expiracionpassword;
        
        return $this;
    }
    
    /**
     * Get expiracionpassword
     *
     * @return \DateTime
     */
    public function getExpiracionpassword()
    {
        return $this->expiracionpassword;
    }
    
    /**
     * Set fechaalta
     *
     * @param \DateTime $fechaalta
     * @return Usuario
     */
    public function setFechaalta($fechaalta)
    {
        $this->fechaalta = $fechaalta;
        
        return $this;
    }
    
    /**
     * Get fechaalta
     *
     * @return \DateTime
     */
    public function getFechaalta()
    {
        return $this->fechaalta;
    }
    
    /**
     * Set fechaborrado
     *
     * @param \DateTime $fechaborrado
     * @return Usuario
     */
    public function setFechaborrado($fechaborrado)
    {
        $this->fechaborrado = $fechaborrado;
        
        return $this;
    }
    
    /**
     * Get fechaborrado
     *
     * @return \DateTime
     */
    public function getFechaborrado()
    {
        return $this->fechaborrado;
    }
    
    /**
     * Set fechadesactivado
     *
     * @param \DateTime $fechadesactivado
     * @return Usuario
     */
    public function setFechadesactivado($fechadesactivado)
    {
        $this->fechadesactivado = $fechadesactivado;
        
        return $this;
    }
    
    /**
     * Get fechadesactivado
     *
     * @return \DateTime
     */
    public function getFechadesactivado()
    {
        return $this->fechadesactivado;
    }
    
    /**
     * Set iphabilitada
     *
     * @param string $iphabilitada
     * @return Usuario
     */
    public function setIphabilitada($iphabilitada)
    {
        $this->iphabilitada = $iphabilitada;
        
        return $this;
    }
    
    /**
     * Get iphabilitada
     *
     * @return string
     */
    public function getIphabilitada()
    {
        return $this->iphabilitada;
    }
    
    /**
     * Set jerarquia
     *
     * @param string $jerarquia
     * @return Usuario
     */
    public function setJerarquia($jerarquia)
    {
        $this->jerarquia = $jerarquia;
        
        return $this;
    }
    
    /**
     * Get jerarquia
     *
     * @return string
     */
    public function getJerarquia()
    {
        return $this->jerarquia;
    }
    
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
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
     * Set numerodoc
     *
     * @param string $numerodoc
     * @return Usuario
     */
    public function setNumerodoc($numerodoc)
    {
        $this->numerodoc = $numerodoc;
        
        return $this;
    }
    
    /**
     * Get numerodoc
     *
     * @return string
     */
    public function getNumerodoc()
    {
        return $this->numerodoc;
    }
    
    /**
     * Set sessionid
     *
     * @param string $sessionid
     * @return Usuario
     */
    public function setSessionid($sessionid)
    {
        $this->sessionid = $sessionid;
        
        return $this;
    }
    
    /**
     * Get sessionid
     *
     * @return string
     */
    public function getSessionid()
    {
        return $this->sessionid;
    }
    
    /**
     * Set tipodoc
     *
     * @param string $tipodoc
     * @return Usuario
     */
    public function setTipodoc($tipodoc)
    {
        $this->tipodoc = $tipodoc;
        
        return $this;
    }
    
    /**
     * Get tipodoc
     *
     * @return string
     */
    public function getTipodoc()
    {
        return $this->tipodoc;
    }
    
    /**
     * Set ultimaip
     *
     * @param string $ultimaip
     * @return Usuario
     */
    public function setUltimaip($ultimaip)
    {
        $this->ultimaip = $ultimaip;
        
        return $this;
    }
    
    /**
     * Get ultimaip
     *
     * @return string
     */
    public function getUltimaip()
    {
        return $this->ultimaip;
    }
    
    /**
     * Set ultimologin
     *
     * @param \DateTime $ultimologin
     * @return Usuario
     */
    public function setUltimologin($ultimologin)
    {
        $this->ultimologin = $ultimologin;
        
        return $this;
    }
    
    /**
     * Get ultimologin
     *
     * @return \DateTime
     */
    public function getUltimologin()
    {
        return $this->ultimologin;
    }
    
    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        
        return $this;
    }
    
    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    /**
     * Set usuarioalta
     *
     * @param integer $usuarioalta
     * @return Usuario
     */
    public function setUsuarioalta($usuarioalta)
    {
        $this->usuarioalta = $usuarioalta;
        
        return $this;
    }
    
    /**
     * Get usuarioalta
     *
     * @return integer
     */
    public function getUsuarioalta()
    {
        return $this->usuarioalta;
    }
    
    /**
     * Set observacion
     *
     * @param string $observacion
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
        
        return $this;
    }
    
    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }
    
    /**
     * Add perfilid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Perfil $perfilid
     * @return Usuario
     */
    public function addPerfilid(\SEGURIDAD\SeguridadBundle\Entity\Perfil $perfilid)
    {
        if (!$this->perfilid->contains($perfilid)) {
            $this->perfilid[] = $perfilid;
            $perfilid->addUsuarioid($this);
        }
        
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
        $perfilid->removeUsuarioid($this);
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
     * Add permisoid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Permiso $permisoid
     * @return Usuario
     */
    public function addPermisoid(\SEGURIDAD\SeguridadBundle\Entity\Permiso $permisoid)
    {
        if (!$this->permisoid->contains($permisoid)) {
            $this->permisoid[] = $permisoid;
            $permisoid->addUsuarioid($this);
        }
        
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
     * Set depenid
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Dependencia $depenid
     * @return Usuario
     */
    public function setDepenid(\SEGURIDAD\SeguridadBundle\Entity\Dependencia $depenid = null)
    {
        $this->depenid = $depenid;
        
        return $this;
    }
    
    /**
     * Get depenid
     *
     * @return \SEGURIDAD\SeguridadBundle\Entity\Dependencia
     */
    public function getDepenid()
    {
        try {
            if ($this->depenid === null){
                throw new EntityNotFoundException();
            }else{
                $nombre = $this->depenid->getNombre();
            }
        } catch (EntityNotFoundException $e) {
            return null;
        }
        
        return $this->depenid;
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    public function isIpHabilitada($ipAddress){
        
        if(empty($this->iphabilitada)){
            return true;
        }
        
        
        $arrayIp = explode(',',$this->iphabilitada);
        
        return in_array($ipAddress,$arrayIp);
        
    }
    
    public function passwordExpirado(){
        $hoy = new \Datetime();
        
        if($this->expiracionpassword<=$hoy){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getCantPersona()
    {
        return $this->cantPersona;
    }
    
    /**
     * @return integer
     */
    public function getCantDocumento()
    {
        return $this->cantDocumento;
    }
    
    /**
     * @return integer
     */
    public function getCantVehiculo()
    {
        return $this->cantVehiculo;
    }
    
    /**
     * @return integer
     */
    public function getCantCombinada()
    {
        return $this->cantCombinada;
    }
    
    /**
     * @return integer
     */
    public function getCantLote()
    {
        return $this->cantLote;
    }
    
    /**
     * @param integer $cantPersona
     */
    public function setCantPersona($cantPersona)
    {
        $this->cantPersona = $cantPersona;
    }
    
    /**
     * @param integer $cantDocumento
     */
    public function setCantDocumento($cantDocumento)
    {
        $this->cantDocumento = $cantDocumento;
    }
    
    /**
     * @param integer $cantVehiculo
     */
    public function setCantVehiculo($cantVehiculo)
    {
        $this->cantVehiculo = $cantVehiculo;
    }
    
    /**
     * @param integer $cantCombinada
     */
    public function setCantCombinada($cantCombinada)
    {
        $this->cantCombinada = $cantCombinada;
    }
    
    /**
     * @param integer $cantLote
     */
    public function setCantLote($cantLote)
    {
        $this->cantLote = $cantLote;
    }
}