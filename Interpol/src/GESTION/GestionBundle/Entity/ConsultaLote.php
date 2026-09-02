<?php

namespace GESTION\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ConsultaLote
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GESTION\GestionBundle\Entity\ConsultaLoteRepository")
 */
class ConsultaLote
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
     * Many ConsultaLote has One Usuario
     * 
     * @ORM\ManyToOne(targetEntity="\SEGURIDAD\SeguridadBundle\Entity\Usuario"))
     * @ORM\JoinColumn(name="USUARIOID", referencedColumnName="USUARIOID", nullable=false)
     **/
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoLote", type="string", length=1)
     */
    private $tipoLote;

    /**
     * @var string
     *
     * @ORM\Column(name="Archivo", type="blob")
     */
    private $archivo;

    /**
     * @var string
     *
     * @ORM\Column(name="ArchivoNombre", type="string", length=250)
     */
    private $archivoNombre;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FecAlta", type="string")
     */
    private $fecAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="Estado", type="string", length=1)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="Error", type="integer")
     */
    private $error;

    /**
     * @ORM\OneToMany(targetEntity="ConsultaLoteDetalle", mappedBy="consultaLoteId")
     */
    private $consultaLoteDetalle;
    
    
    public function __construct() {
    	
    }
    
    /**
     * @return string
     */
    public function getArchivoNombre()
    {
        return $this->archivoNombre;
    }

    /**
     * @param string $archivoNombre
     */
    public function setArchivoNombre($archivoNombre)
    {
        $this->archivoNombre = $archivoNombre;
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

    /**
     * Set usuario
     *
     * @param \SEGURIDAD\SeguridadBundle\Entity\Usuario $usuario
     * @return ConsultaLote
     */
    public function setUsuario(\SEGURIDAD\SeguridadBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get usuario
     *
     * @return \SEGURIDAD\SeguridadBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set tipoLote
     *
     * @param string $tipoLote
     * @return ConsultaLote
     */
    public function setTipoLote($tipoLote)
    {
        $this->tipoLote = $tipoLote;
    
        return $this;
    }

    /**
     * Get tipoLote
     *
     * @return string 
     */
    public function getTipoLote()
    {
        return $this->tipoLote;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     * @return ConsultaLote
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;
    
        return $this;
    }

    /**
     * Get archivo
     *
     * @return string 
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set fecAlta
     *
     * @param \DateTime $fecAlta
     * @return ConsultaLote
     */
    public function setFecAlta($fecAlta)
    {
        $this->fecAlta = $fecAlta;
    
        return $this;
    }

    /**
     * Get fecAlta
     *
     * @return \DateTime 
     */
    public function getFecAlta()
    {
        return $this->fecAlta;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return ConsultaLote
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
     * Set error
     *
     * @param string $error
     * @return ConsultaLote
     */
    public function setError($error)
    {
        $this->error = $error;
    
        return $this;
    }

    /**
     * Get error
     *
     * @return string 
     */
    public function getError()
    {
        return $this->error;
    }
    
    
    
    public function getConsultaLoteDetalle() {
    	return $this->consultaLoteDetalle;
    }
        
}