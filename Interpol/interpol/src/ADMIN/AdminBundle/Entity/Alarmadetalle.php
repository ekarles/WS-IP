<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alarmadetalle
 *
 * @ORM\Table(name="ALARMADETALLE")
 * @ORM\Entity
 */
class Alarmadetalle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ALDETID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ALARMADETALLE_ALDETID_seq", allocationSize=1, initialValue=1)
     */
    private $aldetid;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION", type="string", length=1000, nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="SEGURIDAD\SeguridadBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="USUARIOID", referencedColumnName="USUARIOID")
     * })
     */
    private $usuarioid;

    /**
     * @var \Alarmadetalletipo
     *
     * @ORM\ManyToOne(targetEntity="Alarmadetalletipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ALDTID", referencedColumnName="ALDTID")
     * })
     */
    private $aldtid;

    /**
     * @var \Alarma
     *
     * @ORM\ManyToOne(targetEntity="Alarma")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ALARID", referencedColumnName="ALARID")
     * })
     */
    private $alarid;



    /**
     * Get aldetid
     *
     * @return integer 
     */
    public function getAldetid()
    {
        return $this->aldetid;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Alarmadetalle
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Alarmadetalle
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
     * Set usuarioid
     *
     * @param integer $usuarioid
     * @return Alarmadetalle
     */
    public function setUsuarioid($usuarioid)
    {
        $this->usuarioid = $usuarioid;
    
        return $this;
    }

    /**
     * Get usuarioid
     *
     * @return integer 
     */
    public function getUsuarioid()
    {
        return $this->usuarioid;
    }

    /**
     * Set aldtid
     *
     * @param \ADMIN\AdminBundle\Entity\Alarmadetalletipo $aldtid
     * @return Alarmadetalle
     */
    public function setAldtid(\ADMIN\AdminBundle\Entity\Alarmadetalletipo $aldtid = null)
    {
        $this->aldtid = $aldtid;
    
        return $this;
    }

    /**
     * Get aldtid
     *
     * @return \ADMIN\AdminBundle\Entity\Alarmadetalletipo 
     */
    public function getAldtid()
    {
        return $this->aldtid;
    }

    /**
     * Set alarid
     *
     * @param \ADMIN\AdminBundle\Entity\Alarma $alarid
     * @return Alarmadetalle
     */
    public function setAlarid(\ADMIN\AdminBundle\Entity\Alarma $alarid = null)
    {
        $this->alarid = $alarid;
    
        return $this;
    }

    /**
     * Get alarid
     *
     * @return \ADMIN\AdminBundle\Entity\Alarma 
     */
    public function getAlarid()
    {
        return $this->alarid;
    }
}