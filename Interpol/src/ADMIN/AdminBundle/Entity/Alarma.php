<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alarma
 *
 * @ORM\Table(name="ALARMA")
 * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\AlarmaRepository")
 */
class Alarma
{
    /**
     * @var string
     *
     * @ORM\Column(name="ENTITYID", type="string", length=255, nullable=true)
     */
    private $entityid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="MODOCONSULTA", type="string", length=255, nullable=true)
     */
    private $modoconsulta;

    /**
     * @var string
     *
     * @ORM\Column(name="PASSGENERICO", type="string", length=255, nullable=true)
     */
    private $passgenerico;

    /**
     * @var string
     *
     * @ORM\Column(name="RESPUESTA", type="text", nullable=true)
     */
    private $respuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIO", type="string", length=255, nullable=true)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIOAPELLIDO", type="string", length=255, nullable=true)
     */
    private $usuarioapellido;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIODEPEN", type="string", length=255, nullable=true)
     */
    private $usuariodepen;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIODEPENID", type="string", length=255, nullable=true)
     */
    private $usuariodepenid;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIODOC", type="string", length=255, nullable=true)
     */
    private $usuariodoc;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIOGENERICO", type="string", length=255, nullable=true)
     */
    private $usuariogenerico;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIOIP", type="string", length=255, nullable=true)
     */
    private $usuarioip;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIOJERARQUIA", type="string", length=255, nullable=true)
     */
    private $usuariojerarquia;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIONOMBRE", type="string", length=255, nullable=true)
     */
    private $usuarionombre;

    /**
     * @var string
     *
     * @ORM\Column(name="USUARIOTIPODOC", type="string", length=255, nullable=true)
     */
    private $usuariotipodoc;

    /**
     * @var \Alarmatipo
     *
     * @ORM\ManyToOne(targetEntity="Alarmatipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ALTIIDINICIAL", referencedColumnName="ALTIID")
     * })
     */
    private $altiidinicial;

    /**
     * @var \InterpolLogMv
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="InterpolLogMv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ALARID", referencedColumnName="IA_ID")
     * })
     */
    private $alarid;

    /**
     * @var \Alarmaestado
     *
     * @ORM\ManyToOne(targetEntity="Alarmaestado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ESTADOID", referencedColumnName="ESTADOID")
     * })
     */
    private $estadoid;

    /**
     * @var \Tipodelito
     *
     * @ORM\ManyToOne(targetEntity="Tipodelito")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TIPODELITOID", referencedColumnName="TIPODELITOID")
     * })
     */
    private $tipodelitoid;

    /**
     * @var \Alarmatipo
     *
     * @ORM\ManyToOne(targetEntity="Alarmatipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ALTIID", referencedColumnName="ALTIID")
     * })
     */
    private $altiid;



    /**
     * Set entityid
     *
     * @param string $entityid
     * @return Alarma
     */
    public function setEntityid($entityid)
    {
        $this->entityid = $entityid;
    
        return $this;
    }

    /**
     * Get entityid
     *
     * @return string 
     */
    public function getEntityid()
    {
        return $this->entityid;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Alarma
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set modoconsulta
     *
     * @param string $modoconsulta
     * @return Alarma
     */
    public function setModoconsulta($modoconsulta)
    {
        $this->modoconsulta = $modoconsulta;
    
        return $this;
    }

    /**
     * Get modoconsulta
     *
     * @return string 
     */
    public function getModoconsulta()
    {
        return $this->modoconsulta;
    }

    /**
     * Set passgenerico
     *
     * @param string $passgenerico
     * @return Alarma
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
     * Set respuesta
     *
     * @param string $respuesta
     * @return Alarma
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    
        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Alarma
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
     * Set usuarioapellido
     *
     * @param string $usuarioapellido
     * @return Alarma
     */
    public function setUsuarioapellido($usuarioapellido)
    {
        $this->usuarioapellido = $usuarioapellido;
    
        return $this;
    }

    /**
     * Get usuarioapellido
     *
     * @return string 
     */
    public function getUsuarioapellido()
    {
        return $this->usuarioapellido;
    }

    /**
     * Set usuariodepen
     *
     * @param string $usuariodepen
     * @return Alarma
     */
    public function setUsuariodepen($usuariodepen)
    {
        $this->usuariodepen = $usuariodepen;
    
        return $this;
    }

    /**
     * Get usuariodepen
     *
     * @return string 
     */
    public function getUsuariodepen()
    {
        return $this->usuariodepen;
    }

    /**
     * Set usuariodepenid
     *
     * @param string $usuariodepenid
     * @return Alarma
     */
    public function setUsuariodepenid($usuariodepenid)
    {
        $this->usuariodepenid = $usuariodepenid;
    
        return $this;
    }

    /**
     * Get usuariodepenid
     *
     * @return string 
     */
    public function getUsuariodepenid()
    {
        return $this->usuariodepenid;
    }

    /**
     * Set usuariodoc
     *
     * @param string $usuariodoc
     * @return Alarma
     */
    public function setUsuariodoc($usuariodoc)
    {
        $this->usuariodoc = $usuariodoc;
    
        return $this;
    }

    /**
     * Get usuariodoc
     *
     * @return string 
     */
    public function getUsuariodoc()
    {
        return $this->usuariodoc;
    }

    /**
     * Set usuariogenerico
     *
     * @param string $usuariogenerico
     * @return Alarma
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

    /**
     * Set usuarioip
     *
     * @param string $usuarioip
     * @return Alarma
     */
    public function setUsuarioip($usuarioip)
    {
        $this->usuarioip = $usuarioip;
    
        return $this;
    }

    /**
     * Get usuarioip
     *
     * @return string 
     */
    public function getUsuarioip()
    {
        return $this->usuarioip;
    }

    /**
     * Set usuariojerarquia
     *
     * @param string $usuariojerarquia
     * @return Alarma
     */
    public function setUsuariojerarquia($usuariojerarquia)
    {
        $this->usuariojerarquia = $usuariojerarquia;
    
        return $this;
    }

    /**
     * Get usuariojerarquia
     *
     * @return string 
     */
    public function getUsuariojerarquia()
    {
        return $this->usuariojerarquia;
    }

    /**
     * Set usuarionombre
     *
     * @param string $usuarionombre
     * @return Alarma
     */
    public function setUsuarionombre($usuarionombre)
    {
        $this->usuarionombre = $usuarionombre;
    
        return $this;
    }

    /**
     * Get usuarionombre
     *
     * @return string 
     */
    public function getUsuarionombre()
    {
        return $this->usuarionombre;
    }

    /**
     * Set usuariotipodoc
     *
     * @param string $usuariotipodoc
     * @return Alarma
     */
    public function setUsuariotipodoc($usuariotipodoc)
    {
        $this->usuariotipodoc = $usuariotipodoc;
    
        return $this;
    }

    /**
     * Get usuariotipodoc
     *
     * @return string 
     */
    public function getUsuariotipodoc()
    {
        return $this->usuariotipodoc;
    }

    /**
     * Set altiidinicial
     *
     * @param \ADMIN\AdminBundle\Entity\Alarmatipo $altiidinicial
     * @return Alarma
     */
    public function setAltiidinicial(\ADMIN\AdminBundle\Entity\Alarmatipo $altiidinicial = null)
    {
        $this->altiidinicial = $altiidinicial;
    
        return $this;
    }

    /**
     * Get altiidinicial
     *
     * @return \ADMIN\AdminBundle\Entity\Alarmatipo 
     */
    public function getAltiidinicial()
    {
        return $this->altiidinicial;
    }

    /**
     * Set alarid
     *
     * @param \ADMIN\AdminBundle\Entity\InterpolLogMv $alarid
     * @return Alarma
     */
    public function setAlarid(\ADMIN\AdminBundle\Entity\InterpolLogMv $alarid)
    {
        $this->alarid = $alarid;
    
        return $this;
    }

    /**
     * Get alarid
     *
     * @return \ADMIN\AdminBundle\Entity\InterpolLogMv 
     */
    public function getAlarid()
    {   
        return $this->alarid;
    }

    /**
     * Set estadoid
     *
     * @param \ADMIN\AdminBundle\Entity\Alarmaestado $estadoid
     * @return Alarma
     */
    public function setEstadoid(\ADMIN\AdminBundle\Entity\Alarmaestado $estadoid = null)
    {
        $this->estadoid = $estadoid;
    
        return $this;
    }

    /**
     * Get estadoid
     *
     * @return \ADMIN\AdminBundle\Entity\Alarmaestado 
     */
    public function getEstadoid()
    {
        return $this->estadoid;
    }

    /**
     * Set tipodelitoid
     *
     * @param \ADMIN\AdminBundle\Entity\Tipodelito $tipodelitoid
     * @return Alarma
     */
    public function setTipodelitoid(\ADMIN\AdminBundle\Entity\Tipodelito $tipodelitoid = null)
    {
        $this->tipodelitoid = $tipodelitoid;
    
        return $this;
    }

    /**
     * Get tipodelitoid
     *
     * @return \ADMIN\AdminBundle\Entity\Tipodelito 
     */
    public function getTipodelitoid()
    {
        return $this->tipodelitoid;
    }

    /**
     * Set altiid
     *
     * @param \ADMIN\AdminBundle\Entity\Alarmatipo $altiid
     * @return Alarma
     */
    public function setAltiid(\ADMIN\AdminBundle\Entity\Alarmatipo $altiid = null)
    {
        $this->altiid = $altiid;
    
        return $this;
    }

    /**
     * Get altiid
     *
     * @return \ADMIN\AdminBundle\Entity\Alarmatipo 
     */
    public function getAltiid()
    {
        return $this->altiid;
    }
}