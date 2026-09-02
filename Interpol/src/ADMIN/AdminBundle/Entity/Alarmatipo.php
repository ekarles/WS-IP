<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alarmatipo
 *
 * @ORM\Table(name="ALARMATIPO")
 * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\AlarmatipoRepository")
 */
class Alarmatipo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ALTIID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ALARMATIPO_ALTIID_seq", allocationSize=1, initialValue=1)
     */
    private $altiid;

    /**
     * @var string
     *
     * @ORM\Column(name="BACKCOLOR", type="string", length=255, nullable=true)
     */
    private $backcolor;

    /**
     * @var string
     *
     * @ORM\Column(name="CAPTION", type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="TEXTCOLOR", type="string", length=255, nullable=true)
     */
    private $textcolor;



    /**
     * Get altiid
     *
     * @return integer 
     */
    public function getAltiid()
    {
        return $this->altiid;
    }

    /**
     * Set backcolor
     *
     * @param string $backcolor
     * @return Alarmatipo
     */
    public function setBackcolor($backcolor)
    {
        $this->backcolor = $backcolor;
    
        return $this;
    }

    /**
     * Get backcolor
     *
     * @return string 
     */
    public function getBackcolor()
    {
        return $this->backcolor;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return Alarmatipo
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Alarmatipo
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
     * Set textcolor
     *
     * @param string $textcolor
     * @return Alarmatipo
     */
    public function setTextcolor($textcolor)
    {
        $this->textcolor = $textcolor;
    
        return $this;
    }

    /**
     * Get textcolor
     *
     * @return string 
     */
    public function getTextcolor()
    {
        return $this->textcolor;
    }
}