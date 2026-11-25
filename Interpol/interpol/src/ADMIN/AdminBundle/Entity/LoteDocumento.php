<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoteDocumento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\LoteDocumentoRepository")
 */
class LoteDocumento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellido", type="string", length=50)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="OtrosApellidos", type="string", length=100)
     */
    private $otrosApellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="OtrosNombres", type="string", length=100)
     */
    private $otrosNombres;

    /**
     * @var string
     *
     * @ORM\Column(name="FechaNacimiento", type="string", length=10)
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="Sexo", type="string", length=1)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoDoc", type="string", length=50)
     */
    private $tipoDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="NumeroDoc", type="string", length=50)
     */
    private $numeroDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="IdNacionalidad", type="string", length=3)
     */
    private $idNacionalidad;

    /**
     * @var string
     *
     * @ORM\Column(name="IdPaisEmisor", type="string", length=3)
     */
    private $idPaisEmisor;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoDenuncia", type="string", length=50)
     */
    private $tipoDenuncia;

    /**
     * @var string
     *
     * @ORM\Column(name="Accion", type="string", length=1)
     */
    private $accion;

    /**
     * @var string
     *
     * @ORM\Column(name="FechaRegistro", type="string", length=19)
     */
    private $fechaRegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="IdPersona", type="string", length=50)
     */
    private $idPersona;

    /**
     * @var string
     *
     * @ORM\Column(name="Motivo", type="string", length=100)
     */
    private $motivo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fecha", type="string")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="Estado", type="string", length=1)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="Descargado", type="string", length=1)
     */
    private $descargado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaHoraDesc", type="string")
     */
    private $fechaHoraDesc;

    /**
     * Many ConsultaLote has One Usuario
     *
     * @ORM\ManyToOne(targetEntity="\SEGURIDAD\SeguridadBundle\Entity\Usuario"))
     * @ORM\JoinColumn(name="USUDESC", referencedColumnName="USUARIOID", nullable=true)
     **/
    private $usuDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="MensajeRemoto", type="string")
     */
    private $mensajeRemoto;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ITEMID", type="string")
     */
    private $itemid;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return LoteDocumento
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
     * Set otrosApellidos
     *
     * @param string $otrosApellidos
     * @return LoteDocumento
     */
    public function setOtrosApellidos($otrosApellidos)
    {
        $this->otrosApellidos = $otrosApellidos;
    
        return $this;
    }

    /**
     * Get otrosApellidos
     *
     * @return string 
     */
    public function getOtrosApellidos()
    {
        return $this->otrosApellidos;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return LoteDocumento
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
     * Set otrosNombres
     *
     * @param string $otrosNombres
     * @return LoteDocumento
     */
    public function setOtrosNombres($otrosNombres)
    {
        $this->otrosNombres = $otrosNombres;
    
        return $this;
    }

    /**
     * Get otrosNombres
     *
     * @return string 
     */
    public function getOtrosNombres()
    {
        return $this->otrosNombres;
    }

    /**
     * Set fechaNacimiento
     *
     * @param string $fechaNacimiento
     * @return LoteDocumento
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;
    
        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return string 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return LoteDocumento
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    
        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set tipoDoc
     *
     * @param string $tipoDoc
     * @return LoteDocumento
     */
    public function setTipoDoc($tipoDoc)
    {
        $this->tipoDoc = $tipoDoc;
    
        return $this;
    }

    /**
     * Get tipoDoc
     *
     * @return string 
     */
    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }

    /**
     * Set numeroDoc
     *
     * @param string $numeroDoc
     * @return LoteDocumento
     */
    public function setNumeroDoc($numeroDoc)
    {
        $this->numeroDoc = $numeroDoc;
    
        return $this;
    }

    /**
     * Get numeroDoc
     *
     * @return string 
     */
    public function getNumeroDoc()
    {
        return $this->numeroDoc;
    }

    /**
     * Set idNacionalidad
     *
     * @param string $idNacionalidad
     * @return LoteDocumento
     */
    public function setIdNacionalidad($idNacionalidad)
    {
        $this->idNacionalidad = $idNacionalidad;
    
        return $this;
    }

    /**
     * Get idNacionalidad
     *
     * @return string 
     */
    public function getIdNacionalidad()
    {
        return $this->idNacionalidad;
    }

    /**
     * Set idPaisEmisor
     *
     * @param string $idPaisEmisor
     * @return LoteDocumento
     */
    public function setIdPaisEmisor($idPaisEmisor)
    {
        $this->idPaisEmisor = $idPaisEmisor;
    
        return $this;
    }

    /**
     * Get idPaisEmisor
     *
     * @return string 
     */
    public function getIdPaisEmisor()
    {
        return $this->idPaisEmisor;
    }

    /**
     * Set tipoDenuncia
     *
     * @param string $tipoDenuncia
     * @return LoteDocumento
     */
    public function setTipoDenuncia($tipoDenuncia)
    {
        $this->tipoDenuncia = $tipoDenuncia;
    
        return $this;
    }

    /**
     * Get tipoDenuncia
     *
     * @return string 
     */
    public function getTipoDenuncia()
    {
        return $this->tipoDenuncia;
    }

    /**
     * Set accion
     *
     * @param string $accion
     * @return LoteDocumento
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;
    
        return $this;
    }

    /**
     * Get accion
     *
     * @return string 
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set fechaRegistro
     *
     * @param string $fechaRegistro
     * @return LoteDocumento
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;
    
        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return string 
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set idPersona
     *
     * @param string $idPersona
     * @return LoteDocumento
     */
    public function setIdPersona($idPersona)
    {
        $this->idPersona = $idPersona;
    
        return $this;
    }

    /**
     * Get idPersona
     *
     * @return string 
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return LoteDocumento
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    
        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return LoteDocumento
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
     * Set estado
     *
     * @param string $estado
     * @return LoteDocumento
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set descargado
     *
     * @param string $descargado
     * @return LoteDocumento
     */
    public function setDescargado($descargado)
    {
        $this->descargado = $descargado;
    
        return $this;
    }

    /**
     * Get descargado
     *
     * @return string 
     */
    public function getDescargado()
    {
        return $this->descargado;
    }

    /**
     * Set fechaHoraDesc
     *
     * @param \DateTime $fechaHoraDesc
     * @return LoteDocumento
     */
    public function setFechaHoraDesc($fechaHoraDesc)
    {
        $this->fechaHoraDesc = $fechaHoraDesc;
    
        return $this;
    }

    /**
     * Get fechaHoraDesc
     *
     * @return \DateTime 
     */
    public function getFechaHoraDesc()
    {
        return $this->fechaHoraDesc;
    }

    /**
     * Set usuDesc
     *
     * @param integer $usuDesc
     * @return LoteDocumento
     */
    public function setUsuDesc($usuDesc)
    {
        $this->usuDesc = $usuDesc;
    
        return $this;
    }

    /**
     * Get usuDesc
     *
     * @return integer 
     */
    public function getUsuDesc()
    {
        return $this->usuDesc;
    }

    /**
     * Set mensajeremoto
     *
     * @param string $mensajeRemoto
     * @return LoteDocumento
     */
    public function setMensajeremoto($mensajeRemoto)
    {
        $this->mensajeRemoto = $mensajeRemoto;
        
        return $this;
    }
    
    /**
     * Get mensajeremoto
     *
     * @return string
     */
    public function getMensajeRemoto()
    {
        return $this->mensajeRemoto;
    }

    /**
     * Set itemid
     *
     * @param string $itemid
     * @return LoteDocumento
     */
    public function setItemId($itemid)
    {
        $this->itemid = $itemid;
        
        return $this;
    }
    
    /**
     * Get itemid
     *
     * @return string
     */
    public function getItemId()
    {
        return $this->itemid;
    }

}