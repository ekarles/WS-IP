<?phpnamespace ADMIN\AdminBundle\Entity;use Doctrine\ORM\Mapping as ORM;
/** * CnrtPersona * * @ORM\Table(name="CNRT_PERSONA") * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\CnrtPersonaRepository") */class CnrtPersona{
    /**     * @var integer     *     * @ORM\Column(name="ID_CNRT", type="integer")     * @ORM\Id     * @ORM\GeneratedValue(strategy="SEQUENCE")     * @ORM\SequenceGenerator(sequenceName="CNRT_PERSONA_SEQ", allocationSize=1, initialValue=1)     */
    private $id;    
    /**     * @var integer     *     * @ORM\Column(name="ID_PASAJERO", type="integer")     */
    private $idPasajero;
    /**     * @var \DateTime     *     * @ORM\Column(name="FECHA_CONSULTA", type="datetime", nullable=true)     */
    private $fechaConsulta;        /**     * @var string     *     * @ORM\Column(name="FECHA_NACIMIENTO", type="string", length=10, nullable=true)     */
    private $fechaNacimiento;

    /**     * @var integer     *     * @ORM\Column(name="ESTADO", type="integer")     */
    private $estado;
      /**     * @var string     *     * @ORM\Column(name="NUMERO_DOCUMENTO", type="string", length=11, nullable=true)     */    private $numeroDocumento;        /**     * @var string     *     * @ORM\Column(name="APELLIDO", type="string", length=50, nullable=true)     */    private $apellido;        /**     * @var string     *     * @ORM\Column(name="NOMBRE", type="string", length=50, nullable=true)     */    private $nombre;    /**     * @var string     *     * @ORM\Column(name="SEXO", type="string", length=1, nullable=true)     */    private $sexo;        /**     * @var integer     *     * @ORM\Column(name="RESULTADO", type="integer")     */    private $resultado;        /**     * @var string     *     * @ORM\Column(name="RESPUESTA_DETAILS", type="blob", nullable=true)     */    private $respuestaDetails;            /**     * @var \string     *     * @ORM\Column(name="FECHA_INICIO", type="string", nullable=true)     */    private $fechaInicio;        /**     * @var \string     *     * @ORM\Column(name="FECHA_FIN", type="string", nullable=true)     */    private $fechaFin;            /**     * @var string     *     * @ORM\Column(name="ORIGEN", type="string", length=100, nullable=true)     */    private $origen;            /**     * @var string     *     * @ORM\Column(name="PCIA_ORIGEN", type="string", length=100, nullable=true)     */    private $pciaOrigen;            /**     * @var string     *     * @ORM\Column(name="DESTINO", type="string", length=100, nullable=true)     */    private $destino;            /**     * @var string     *     * @ORM\Column(name="PCIA_DESTINO", type="string", length=100, nullable=true)     */    private $pciaDestino;            /**     * @var string     *     * @ORM\Column(name="NRO_BUTACA", type="string", length=10, nullable=true)     */    private $nroButaca;            /**     * @var string     *     * @ORM\Column(name="DOMINIO", type="string", length=20, nullable=true)     */    private $dominio;            /**     * @var string     *     * @ORM\Column(name="NRO_EMPRESA", type="string", length=10, nullable=true)     */    private $nroEmpresa;        /**     * @var string     *     * @ORM\Column(name="DESC_EMPRESA", type="string", length=100, nullable=true)     */    private $descEmpresa;            /**     * @var string     *     * @ORM\Column(name="OBSERVACIONES", type="string", length=1000, nullable=true)     */    private $observaciones;            /**     * @var \DateTime     *     * @ORM\Column(name="FECHA_CANCELACION", type="datetime", nullable=true)     */    private $fechaCancelacion;            public function __toString()    {        return $this->nombre." ".$this->apellido.' - DNI: '.$this->numeroDocumento.' - ID CNRT: '.$this->idPasajero;    }        
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
     * Set idPasajero
     *
     * @param integer $idPasajero
     * @return CnrtPersona
     */
    public function setIdPasajero($idPasajero)
    {
        $this->idPasajero = $idPasajero;
        return $this;
    }

    /**
     * Get idPasajero
     *
     * @return integer 
     */
    public function getIdPasajero()
    {
        return $this->idPasajero;
    }

    /**
     * Set fechaConsulta
     *
     * @param \DateTime $fechaConsulta
     * @return CnrtPersona
     */
    public function setFechaConsulta($fechaConsulta)
    {
        $this->fechaConsulta = $fechaConsulta;
        return $this;
    }

    /**
     * Get fechaConsulta
     *
     * @return \DateTime 
     */
    public function getFechaConsulta()
    {
        return $this->fechaConsulta;
    }

    /**
     * Set fechaNacimiento
     *
     * @param string $fechaNacimiento
     * @return CnrtPersona
     */
    public function setFechaNacimiento($fechaNacimiento)
    {        $this->fechaNacimiento = $fechaNacimiento;
        return $this;    }

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
     * Set estado
     *
     * @param integer $estado
     * @return CnrtPersona
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }
    /**
     * Set resultado
     *
     * @param integer $resultado
     * @return CnrtPersona
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
        return $this;
    }

    /**
     * Get resultado
     *
     * @return integer 
     */
    public function getResultado()
    {
        return $this->resultado;
    }        /**     * Set respuestaDetails     *     * @param string $respuestaDetails     * @return ConsultaLoteDetalle     */    public function setRespuestaDetails($respuestaDetails)    {        $this->respuestaDetails = $respuestaDetails;                return $this;    }        /**     * Get respuestaDetails     *     * @return string     */    public function getRespuestaDetails()    {        return $this->respuestaDetails;        //return stream_get_contents( $this->respuestaDetails );    }        /**     * Set numeroDocumento     *     * @param string $numeroDocumento     * @return CnrtPersona     */    public function setNumeroDocumento($numeroDocumento)    {                $this->numeroDocumento = $numeroDocumento;                return $this;    }        /**     * Get numeroDocumento     *     * @return string     */    public function getNumeroDocumento()    {        return $this->numeroDocumento;    }        /**     * Set apellido     *     * @param string $apellido     * @return CnrtPersona     */    public function setApellido($apellido)    {                $this->apellido = $apellido;                return $this;    }        /**     * Get apellido     *     * @return string     */    public function getApellido()    {        return $this->apellido;    }        /**     * Set nombre     *     * @param string $nombre     * @return CnrtPersona     */    public function setNombre($nombre)    {                $this->nombre = $nombre;                return $this;    }        /**     * Get nombre     *     * @return string     */    public function getNombre()    {        return $this->nombre;    }        /**     * Set sexo     *     * @param string $sexo     * @return CnrtPersona     */    public function setSexo($sexo)    {                $this->sexo = $sexo;                return $this;    }        /**     * Get sexo     *     * @return string     */    public function getSexo()    {        return $this->sexo;    }        /**     * Set fechaInicio     *     * @param \string $fechaInicio     * @return CnrtPersona     */    public function setFechaInicio($fechaInicio)    {        $this->fechaInicio = $fechaInicio;                return $this;    }        /**     * Get fechaInicio     *     * @return \string     */    public function getFechaInicio()    {        return $this->fechaInicio;    }    /**     * Set fechaFin     *     * @param \string $fechaFin     * @return CnrtPersona     */    public function setFechaFin($fechaFin)    {        $this->fechaFin = $fechaFin;                return $this;    }        /**     * Get fechaFin     *     * @return \string     */    public function getFechaFin()    {        return $this->fechaFin;    }    /**     * @return string     */    public function getOrigen()
    {
        return $this->origen;    }
    /**     * @return string     */    public function getPciaOrigen()
    {
        return $this->pciaOrigen;    }
    /**     * @return string     */    public function getDestino()
    {
        return $this->destino;    }
    /**     * @return string     */    public function getPciaDestino()
    {
        return $this->pciaDestino;    }
    /**     * @return string     */    public function getNroButaca()
    {
        return $this->nroButaca;    }
    /**     * @return string     */    public function getDominio()
    {
        return $this->dominio;    }
    /**     * @return string     */    public function getNroEmpresa()
    {
        return $this->nroEmpresa;    }
    /**     * @return string     */    public function getDescEmpresa()
    {
        return $this->descEmpresa;    }
    /**     * @return string     */    public function getObservaciones()
    {
        return $this->observaciones;    }
    /**     * @param string $origen     */    public function setOrigen($origen)
    {
        $this->origen = $origen;    }
    /**     * @param string $pciaOrigen     */    public function setPciaOrigen($pciaOrigen)
    {
        $this->pciaOrigen = $pciaOrigen;    }
    /**     * @param string $destino     */    public function setDestino($destino)
    {
        $this->destino = $destino;    }
    /**     * @param string $pciaDestino     */    public function setPciaDestino($pciaDestino)
    {
        $this->pciaDestino = $pciaDestino;    }
    /**     * @param string $nroButaca     */    public function setNroButaca($nroButaca)
    {
        $this->nroButaca = $nroButaca;    }
    /**     * @param string $dominio     */    public function setDominio($dominio)
    {
        $this->dominio = $dominio;    }
    /**     * @param string $nroEmpresa     */    public function setNroEmpresa($nroEmpresa)
    {
        $this->nroEmpresa = $nroEmpresa;    }
    /**     * @param string $descEmpresa     */    public function setDescEmpresa($descEmpresa)
    {
        $this->descEmpresa = $descEmpresa;    }
    /**     * @param string $observaciones     */    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;    }             /**     * Set fechaCancelacion     *     * @param \DateTime $fechaCancelacion     * @return CnrtPersona     */    public function setFechaCancelacion($fechaCancelacion)    {        $this->fechaCancelacion = $fechaCancelacion;                return $this;    }        /**     * Get fechaCancelacion     *     * @return \DateTime     */    public function getFechaCancelacion()    {        return $this->fechaCancelacion;    }    }
?>