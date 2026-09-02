<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parametro
 *
 * @ORM\Table(name="PARAMETRO")
 * @ORM\Entity
 */
class Parametro
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PARAMETROID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="PARAMETRO_PARAMETROID_seq", allocationSize=1, initialValue=1)
     */
    private $parametroid;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=20, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="VALORCHAR", type="string", length=1000, nullable=true)
     */
    private $valorchar;

    /**
     * @var integer
     *
     * @ORM\Column(name="VALORNUM", type="integer", nullable=true)
     */
    private $valornum;



    /**
     * Get parametroid
     *
     * @return integer 
     */
    public function getParametroid()
    {
        return $this->parametroid;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Parametro
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
     * Set valorchar
     *
     * @param string $valorchar
     * @return Parametro
     */
    public function setValorchar($valorchar)
    {
        $this->valorchar = $valorchar;
    
        return $this;
    }

    /**
     * Get valorchar
     *
     * @return string 
     */
    public function getValorchar()
    {
        return $this->valorchar;
    }

    /**
     * Set valornum
     *
     * @param integer $valornum
     * @return Parametro
     */
    public function setValornum($valornum)
    {
        $this->valornum = $valornum;
    
        return $this;
    }

    /**
     * Get valornum
     *
     * @return integer 
     */
    public function getValornum()
    {
        return $this->valornum;
    }
}