<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mapadetalle
 *
 * @ORM\Table(name="MAPADETALLE")
 * @ORM\Entity
 */
class Mapadetalle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="MAPADETALLEID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="MAPADETALLE_MAPADETALLEID_seq", allocationSize=1, initialValue=1)
     */
    private $mapadetalleid;

    /**
     * @var integer
     *
     * @ORM\Column(name="DEPENID", type="integer", nullable=true)
     */
    private $depenid;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAPAID", type="integer", nullable=true)
     */
    private $mapaid;



    /**
     * Get mapadetalleid
     *
     * @return integer 
     */
    public function getMapadetalleid()
    {
        return $this->mapadetalleid;
    }

    /**
     * Set depenid
     *
     * @param integer $depenid
     * @return Mapadetalle
     */
    public function setDepenid($depenid)
    {
        $this->depenid = $depenid;
    
        return $this;
    }

    /**
     * Get depenid
     *
     * @return integer 
     */
    public function getDepenid()
    {
        return $this->depenid;
    }

    /**
     * Set mapaid
     *
     * @param integer $mapaid
     * @return Mapadetalle
     */
    public function setMapaid($mapaid)
    {
        $this->mapaid = $mapaid;
    
        return $this;
    }

    /**
     * Get mapaid
     *
     * @return integer 
     */
    public function getMapaid()
    {
        return $this->mapaid;
    }
}