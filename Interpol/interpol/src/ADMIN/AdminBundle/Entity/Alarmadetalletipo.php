<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alarmadetalletipo
 *
 * @ORM\Table(name="ALARMADETALLETIPO")
 * @ORM\Entity
 */
class Alarmadetalletipo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ALDTID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ALARMADETALLETIPO_ALDTID_seq", allocationSize=1, initialValue=1)
     */
    private $aldtid;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=true)
     */
    private $nombre;



    /**
     * Get aldtid
     *
     * @return integer 
     */
    public function getAldtid()
    {
        return $this->aldtid;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Alarmadetalletipo
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
}