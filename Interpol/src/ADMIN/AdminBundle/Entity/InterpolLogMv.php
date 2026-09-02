<?php

namespace ADMIN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InterpolLogMv
 *
 * @ORM\Table(name="INTERPOL_LOG_MV")
 * @ORM\Entity(repositoryClass="ADMIN\AdminBundle\Entity\InterpolLogMvRepository")
 */
class InterpolLogMv
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IA_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="INTERPOL_LOG_MV_IA_ID_seq", allocationSize=1, initialValue=1)
     */
    private $iaId;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_CONSULTA_ID", type="string", length=100, nullable=true)
     */
    private $iaConsultaId;

    /**
     * @var integer
     *
     * @ORM\Column(name="IA_CONSULTA_MAX_REG", type="integer", nullable=true)
     */
    private $iaConsultaMaxReg;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_CONSULTA_TIPO", type="string", length=100, nullable=true)
     */
    private $iaConsultaTipo;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_FECHA_CLIENTE", type="string", length=25, nullable=true)
     */
    private $iaFechaCliente;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_GENERO", type="string", length=1, nullable=true)
     */
    private $iaGenero;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IA_SLTD_DIN", type="string", length=100, nullable=true)
     */
    private $iaIaSltdDin;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_DATEOFBIRD", type="string", length=100, nullable=true)
     */
    private $iaIdgeDateofbird;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_IDGEAPELLIDO", type="string", length=100, nullable=true)
     */
    private $iaIdgeIdgeapellido;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_IDGEDOC", type="string", length=100, nullable=true)
     */
    private $iaIdgeIdgedoc;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_IDGENOMBRE", type="string", length=100, nullable=true)
     */
    private $iaIdgeIdgenombre;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_IDGETIPODOC", type="string", length=100, nullable=true)
     */
    private $iaIdgeIdgetipodoc;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_LOTE", type="string", length=100, nullable=true)
     */
    private $iaIdgeLote;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_RESTRICCIONES", type="string", length=100, nullable=true)
     */
    private $iaIdgeRestricciones;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_IDGE_SOLO_POSITIVOS", type="string", length=100, nullable=true)
     */
    private $iaIdgeSoloPositivos;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_INTERPOL_RESPUESTA", type="text", nullable=true)
     */
    private $iaInterpolRespuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALS_AGEMAX", type="string", length=100, nullable=true)
     */
    private $iaNominalsAgemax;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALS_AGEMIN", type="string", length=100, nullable=true)
     */
    private $iaNominalsAgemin;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALS_DATEOFBIRTH", type="string", length=100, nullable=true)
     */
    private $iaNominalsDateofbirth;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALSDETAILS_ENTITYID", type="string", length=100, nullable=true)
     */
    private $iaNominalsdetailsEntityid;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALS_DOC", type="string", length=100, nullable=true)
     */
    private $iaNominalsDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALS_FORENAME", type="string", length=100, nullable=true)
     */
    private $iaNominalsForename;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALSIMAGE_ENTITYID", type="string", length=100, nullable=true)
     */
    private $iaNominalsimageEntityid;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALSIMAGE_IMAGE_PATH", type="string", length=255, nullable=true)
     */
    private $iaNominalsimageImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALS_LOTE", type="string", length=100, nullable=true)
     */
    private $iaNominalsLote;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALS_NAME", type="string", length=100, nullable=true)
     */
    private $iaNominalsName;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMINALSPDF_PDFPATH", type="string", length=255, nullable=true)
     */
    private $iaNominalspdfPdfpath;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMTD_COUNTRYOFREGISTRATION", type="string", length=100, nullable=true)
     */
    private $iaNomtdCountryofregistration;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMTDDETAILS_NOMTD_ID", type="string", length=100, nullable=true)
     */
    private $iaNomtddetailsNomtdId;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMTD_DIN", type="string", length=100, nullable=true)
     */
    private $iaNomtdDin;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_NOMTD_TYPEOFDOCUMENT", type="string", length=100, nullable=true)
     */
    private $iaNomtdTypeofdocument;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_OBSERVACIONES", type="string", length=4000, nullable=true)
     */
    private $iaObservaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_QUERY_MODE", type="string", length=2, nullable=true)
     */
    private $iaQueryMode;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_REMOTE_ADDRESS", type="string", length=25, nullable=true)
     */
    private $iaRemoteAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_RESULT_CODE", type="string", length=250, nullable=true)
     */
    private $iaResultCode;


    /**
     * @var string
     *
     * @ORM\Column(name="IA_SISTEMA", type="string", length=100, nullable=true)
     */
    private $iaSistema;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_SLTD_COUNTRYOFREGISTRATION", type="string", length=100, nullable=true)
     */
    private $iaSltdCountryofregistration;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_SLTDDETAILS_ID", type="string", length=100, nullable=true)
     */
    private $iaSltddetailsId;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_SLTD_TYPEOFDOCUMENT", type="string", length=100, nullable=true)
     */
    private $iaSltdTypeofdocument;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_SVDETAILS_VINID", type="string", length=100, nullable=true)
     */
    private $iaSvdetailsVinid;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_SV_ENGINENR", type="string", length=100, nullable=true)
     */
    private $iaSvEnginenr;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_SV_REGISTRATIONMARK", type="string", length=100, nullable=true)
     */
    private $iaSvRegistrationmark;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_SV_VIN", type="string", length=100, nullable=true)
     */
    private $iaSvVin;

    /**
     * @var integer
     *
     * @ORM\Column(name="IA_TIME_RESPONSE", type="integer", nullable=true)
     */
    private $iaTimeResponse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="IA_TIMESTAMP", type="datetime", nullable=false)
     */
    private $iaTimestamp;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_TIPO_PROCESO", type="string", length=15, nullable=true)
     */
    private $iaTipoProceso;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_TRANSITO", type="string", length=1, nullable=true)
     */
    private $iaTransito;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO", type="string", length=100, nullable=true)
     */
    private $iaUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_APELLIDO", type="string", length=40, nullable=true)
     */
    private $iaUsuarioApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_DEPENDENCIA", type="string", length=100, nullable=true)
     */
    private $iaUsuarioDependencia;

    /**
     * @var integer
     *
     * @ORM\Column(name="IA_USUARIO_DEPENDENCIA_ID", type="integer", nullable=true)
     */
    private $iaUsuarioDependenciaId;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_DEPENDENCIA_ID_STR", type="string", length=25, nullable=true)
     */
    private $iaUsuarioDependenciaIdStr;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_DOC", type="string", length=10, nullable=true)
     */
    private $iaUsuarioDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_DOC_TIPO", type="string", length=5, nullable=true)
     */
    private $iaUsuarioDocTipo;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_IP", type="string", length=40, nullable=true)
     */
    private $iaUsuarioIp;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_JERARQUIA", type="string", length=50, nullable=true)
     */
    private $iaUsuarioJerarquia;

    /**
     * @var string
     *
     * @ORM\Column(name="IA_USUARIO_NOMBRE", type="string", length=40, nullable=true)
     */
    private $iaUsuarioNombre;

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cnrtPersonas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    /**
     * Get iaId
     *
     * @return integer 
     */
    public function getIaId()
    {
        return $this->iaId;
    }

    /**
     * Set iaConsultaId
     *
     * @param string $iaConsultaId
     * @return InterpolLogMv
     */
    public function setIaConsultaId($iaConsultaId)
    {
        $this->iaConsultaId = $iaConsultaId;
    
        return $this;
    }

    /**
     * Get iaConsultaId
     *
     * @return string 
     */
    public function getIaConsultaId()
    {
        return $this->iaConsultaId;
    }

    /**
     * Set iaConsultaMaxReg
     *
     * @param integer $iaConsultaMaxReg
     * @return InterpolLogMv
     */
    public function setIaConsultaMaxReg($iaConsultaMaxReg)
    {
        $this->iaConsultaMaxReg = $iaConsultaMaxReg;
    
        return $this;
    }

    /**
     * Get iaConsultaMaxReg
     *
     * @return integer 
     */
    public function getIaConsultaMaxReg()
    {
        return $this->iaConsultaMaxReg;
    }

    /**
     * Set iaConsultaTipo
     *
     * @param string $iaConsultaTipo
     * @return InterpolLogMv
     */
    public function setIaConsultaTipo($iaConsultaTipo)
    {
        $this->iaConsultaTipo = $iaConsultaTipo;
    
        return $this;
    }

    /**
     * Get iaConsultaTipo
     *
     * @return string 
     */
    public function getIaConsultaTipo()
    {
        return $this->iaConsultaTipo;
    }

    /**
     * Set iaFechaCliente
     *
     * @param string $iaFechaCliente
     * @return InterpolLogMv
     */
    public function setIaFechaCliente($iaFechaCliente)
    {
        $this->iaFechaCliente = $iaFechaCliente;
    
        return $this;
    }

    /**
     * Get iaFechaCliente
     *
     * @return string 
     */
    public function getIaFechaCliente()
    {
        return $this->iaFechaCliente;
    }

    /**
     * Set iaGenero
     *
     * @param string $iaGenero
     * @return InterpolLogMv
     */
    public function setIaGenero($iaGenero)
    {
        $this->iaGenero = $iaGenero;
    
        return $this;
    }

    /**
     * Get iaGenero
     *
     * @return string 
     */
    public function getIaGenero()
    {
        return $this->iaGenero;
    }

    /**
     * Set iaIaSltdDin
     *
     * @param string $iaIaSltdDin
     * @return InterpolLogMv
     */
    public function setIaIaSltdDin($iaIaSltdDin)
    {
        $this->iaIaSltdDin = $iaIaSltdDin;
    
        return $this;
    }

    /**
     * Get iaIaSltdDin
     *
     * @return string 
     */
    public function getIaIaSltdDin()
    {
        return $this->iaIaSltdDin;
    }

    /**
     * Set iaIdgeDateofbird
     *
     * @param string $iaIdgeDateofbird
     * @return InterpolLogMv
     */
    public function setIaIdgeDateofbird($iaIdgeDateofbird)
    {
        $this->iaIdgeDateofbird = $iaIdgeDateofbird;
    
        return $this;
    }

    /**
     * Get iaIdgeDateofbird
     *
     * @return string 
     */
    public function getIaIdgeDateofbird()
    {
        return $this->iaIdgeDateofbird;
    }

    /**
     * Set iaIdgeIdgeapellido
     *
     * @param string $iaIdgeIdgeapellido
     * @return InterpolLogMv
     */
    public function setIaIdgeIdgeapellido($iaIdgeIdgeapellido)
    {
        $this->iaIdgeIdgeapellido = $iaIdgeIdgeapellido;
    
        return $this;
    }

    /**
     * Get iaIdgeIdgeapellido
     *
     * @return string 
     */
    public function getIaIdgeIdgeapellido()
    {
        return $this->iaIdgeIdgeapellido;
    }

    /**
     * Set iaIdgeIdgedoc
     *
     * @param string $iaIdgeIdgedoc
     * @return InterpolLogMv
     */
    public function setIaIdgeIdgedoc($iaIdgeIdgedoc)
    {
        $this->iaIdgeIdgedoc = $iaIdgeIdgedoc;
    
        return $this;
    }

    /**
     * Get iaIdgeIdgedoc
     *
     * @return string 
     */
    public function getIaIdgeIdgedoc()
    {
        return $this->iaIdgeIdgedoc;
    }

    /**
     * Set iaIdgeIdgenombre
     *
     * @param string $iaIdgeIdgenombre
     * @return InterpolLogMv
     */
    public function setIaIdgeIdgenombre($iaIdgeIdgenombre)
    {
        $this->iaIdgeIdgenombre = $iaIdgeIdgenombre;
    
        return $this;
    }

    /**
     * Get iaIdgeIdgenombre
     *
     * @return string 
     */
    public function getIaIdgeIdgenombre()
    {
        return $this->iaIdgeIdgenombre;
    }

    /**
     * Set iaIdgeIdgetipodoc
     *
     * @param string $iaIdgeIdgetipodoc
     * @return InterpolLogMv
     */
    public function setIaIdgeIdgetipodoc($iaIdgeIdgetipodoc)
    {
        $this->iaIdgeIdgetipodoc = $iaIdgeIdgetipodoc;
    
        return $this;
    }

    /**
     * Get iaIdgeIdgetipodoc
     *
     * @return string 
     */
    public function getIaIdgeIdgetipodoc()
    {
        return $this->iaIdgeIdgetipodoc;
    }

    /**
     * Set iaIdgeLote
     *
     * @param string $iaIdgeLote
     * @return InterpolLogMv
     */
    public function setIaIdgeLote($iaIdgeLote)
    {
        $this->iaIdgeLote = $iaIdgeLote;
    
        return $this;
    }

    /**
     * Get iaIdgeLote
     *
     * @return string 
     */
    public function getIaIdgeLote()
    {
        return $this->iaIdgeLote;
    }

    /**
     * Set iaIdgeRestricciones
     *
     * @param string $iaIdgeRestricciones
     * @return InterpolLogMv
     */
    public function setIaIdgeRestricciones($iaIdgeRestricciones)
    {
        $this->iaIdgeRestricciones = $iaIdgeRestricciones;
    
        return $this;
    }

    /**
     * Get iaIdgeRestricciones
     *
     * @return string 
     */
    public function getIaIdgeRestricciones()
    {
        return $this->iaIdgeRestricciones;
    }

    /**
     * Set iaIdgeSoloPositivos
     *
     * @param string $iaIdgeSoloPositivos
     * @return InterpolLogMv
     */
    public function setIaIdgeSoloPositivos($iaIdgeSoloPositivos)
    {
        $this->iaIdgeSoloPositivos = $iaIdgeSoloPositivos;
    
        return $this;
    }

    /**
     * Get iaIdgeSoloPositivos
     *
     * @return string 
     */
    public function getIaIdgeSoloPositivos()
    {
        return $this->iaIdgeSoloPositivos;
    }

    /**
     * Set iaInterpolRespuesta
     *
     * @param string $iaInterpolRespuesta
     * @return InterpolLogMv
     */
    public function setIaInterpolRespuesta($iaInterpolRespuesta)
    {
        $this->iaInterpolRespuesta = $iaInterpolRespuesta;
    
        return $this;
    }

    /**
     * Get iaInterpolRespuesta
     *
     * @return string 
     */
    public function getIaInterpolRespuesta()
    {
        return $this->iaInterpolRespuesta;
    }

    /**
     * Set iaNominalsAgemax
     *
     * @param string $iaNominalsAgemax
     * @return InterpolLogMv
     */
    public function setIaNominalsAgemax($iaNominalsAgemax)
    {
        $this->iaNominalsAgemax = $iaNominalsAgemax;
    
        return $this;
    }

    /**
     * Get iaNominalsAgemax
     *
     * @return string 
     */
    public function getIaNominalsAgemax()
    {
        return $this->iaNominalsAgemax;
    }

    /**
     * Set iaNominalsAgemin
     *
     * @param string $iaNominalsAgemin
     * @return InterpolLogMv
     */
    public function setIaNominalsAgemin($iaNominalsAgemin)
    {
        $this->iaNominalsAgemin = $iaNominalsAgemin;
    
        return $this;
    }

    /**
     * Get iaNominalsAgemin
     *
     * @return string 
     */
    public function getIaNominalsAgemin()
    {
        return $this->iaNominalsAgemin;
    }

    /**
     * Set iaNominalsDateofbirth
     *
     * @param string $iaNominalsDateofbirth
     * @return InterpolLogMv
     */
    public function setIaNominalsDateofbirth($iaNominalsDateofbirth)
    {
        $this->iaNominalsDateofbirth = $iaNominalsDateofbirth;
    
        return $this;
    }

    /**
     * Get iaNominalsDateofbirth
     *
     * @return string 
     */
    public function getIaNominalsDateofbirth()
    {
        return $this->iaNominalsDateofbirth;
    }

    /**
     * Set iaNominalsdetailsEntityid
     *
     * @param string $iaNominalsdetailsEntityid
     * @return InterpolLogMv
     */
    public function setIaNominalsdetailsEntityid($iaNominalsdetailsEntityid)
    {
        $this->iaNominalsdetailsEntityid = $iaNominalsdetailsEntityid;
    
        return $this;
    }

    /**
     * Get iaNominalsdetailsEntityid
     *
     * @return string 
     */
    public function getIaNominalsdetailsEntityid()
    {
        return $this->iaNominalsdetailsEntityid;
    }

    /**
     * Set iaNominalsDoc
     *
     * @param string $iaNominalsDoc
     * @return InterpolLogMv
     */
    public function setIaNominalsDoc($iaNominalsDoc)
    {
        $this->iaNominalsDoc = $iaNominalsDoc;
    
        return $this;
    }

    /**
     * Get iaNominalsDoc
     *
     * @return string 
     */
    public function getIaNominalsDoc()
    {
        return $this->iaNominalsDoc;
    }

    /**
     * Set iaNominalsForename
     *
     * @param string $iaNominalsForename
     * @return InterpolLogMv
     */
    public function setIaNominalsForename($iaNominalsForename)
    {
        $this->iaNominalsForename = $iaNominalsForename;
    
        return $this;
    }

    /**
     * Get iaNominalsForename
     *
     * @return string 
     */
    public function getIaNominalsForename()
    {
        return $this->iaNominalsForename;
    }

    /**
     * Set iaNominalsimageEntityid
     *
     * @param string $iaNominalsimageEntityid
     * @return InterpolLogMv
     */
    public function setIaNominalsimageEntityid($iaNominalsimageEntityid)
    {
        $this->iaNominalsimageEntityid = $iaNominalsimageEntityid;
    
        return $this;
    }

    /**
     * Get iaNominalsimageEntityid
     *
     * @return string 
     */
    public function getIaNominalsimageEntityid()
    {
        return $this->iaNominalsimageEntityid;
    }

    /**
     * Set iaNominalsimageImagePath
     *
     * @param string $iaNominalsimageImagePath
     * @return InterpolLogMv
     */
    public function setIaNominalsimageImagePath($iaNominalsimageImagePath)
    {
        $this->iaNominalsimageImagePath = $iaNominalsimageImagePath;
    
        return $this;
    }

    /**
     * Get iaNominalsimageImagePath
     *
     * @return string 
     */
    public function getIaNominalsimageImagePath()
    {
        return $this->iaNominalsimageImagePath;
    }

    /**
     * Set iaNominalsLote
     *
     * @param string $iaNominalsLote
     * @return InterpolLogMv
     */
    public function setIaNominalsLote($iaNominalsLote)
    {
        $this->iaNominalsLote = $iaNominalsLote;
    
        return $this;
    }

    /**
     * Get iaNominalsLote
     *
     * @return string 
     */
    public function getIaNominalsLote()
    {
        return $this->iaNominalsLote;
    }

    /**
     * Set iaNominalsName
     *
     * @param string $iaNominalsName
     * @return InterpolLogMv
     */
    public function setIaNominalsName($iaNominalsName)
    {
        $this->iaNominalsName = $iaNominalsName;
    
        return $this;
    }

    /**
     * Get iaNominalsName
     *
     * @return string 
     */
    public function getIaNominalsName()
    {
        return $this->iaNominalsName;
    }

    /**
     * Set iaNominalspdfPdfpath
     *
     * @param string $iaNominalspdfPdfpath
     * @return InterpolLogMv
     */
    public function setIaNominalspdfPdfpath($iaNominalspdfPdfpath)
    {
        $this->iaNominalspdfPdfpath = $iaNominalspdfPdfpath;
    
        return $this;
    }

    /**
     * Get iaNominalspdfPdfpath
     *
     * @return string 
     */
    public function getIaNominalspdfPdfpath()
    {
        return $this->iaNominalspdfPdfpath;
    }

    /**
     * Set iaNomtdCountryofregistration
     *
     * @param string $iaNomtdCountryofregistration
     * @return InterpolLogMv
     */
    public function setIaNomtdCountryofregistration($iaNomtdCountryofregistration)
    {
        $this->iaNomtdCountryofregistration = $iaNomtdCountryofregistration;
    
        return $this;
    }

    /**
     * Get iaNomtdCountryofregistration
     *
     * @return string 
     */
    public function getIaNomtdCountryofregistration()
    {
        return $this->iaNomtdCountryofregistration;
    }

    /**
     * Set iaNomtddetailsNomtdId
     *
     * @param string $iaNomtddetailsNomtdId
     * @return InterpolLogMv
     */
    public function setIaNomtddetailsNomtdId($iaNomtddetailsNomtdId)
    {
        $this->iaNomtddetailsNomtdId = $iaNomtddetailsNomtdId;
    
        return $this;
    }

    /**
     * Get iaNomtddetailsNomtdId
     *
     * @return string 
     */
    public function getIaNomtddetailsNomtdId()
    {
        return $this->iaNomtddetailsNomtdId;
    }

    /**
     * Set iaNomtdDin
     *
     * @param string $iaNomtdDin
     * @return InterpolLogMv
     */
    public function setIaNomtdDin($iaNomtdDin)
    {
        $this->iaNomtdDin = $iaNomtdDin;
    
        return $this;
    }

    /**
     * Get iaNomtdDin
     *
     * @return string 
     */
    public function getIaNomtdDin()
    {
        return $this->iaNomtdDin;
    }

    /**
     * Set iaNomtdTypeofdocument
     *
     * @param string $iaNomtdTypeofdocument
     * @return InterpolLogMv
     */
    public function setIaNomtdTypeofdocument($iaNomtdTypeofdocument)
    {
        $this->iaNomtdTypeofdocument = $iaNomtdTypeofdocument;
    
        return $this;
    }

    /**
     * Get iaNomtdTypeofdocument
     *
     * @return string 
     */
    public function getIaNomtdTypeofdocument()
    {
        return $this->iaNomtdTypeofdocument;
    }

    /**
     * Set iaObservaciones
     *
     * @param string $iaObservaciones
     * @return InterpolLogMv
     */
    public function setIaObservaciones($iaObservaciones)
    {
        $this->iaObservaciones = $iaObservaciones;
    
        return $this;
    }

    /**
     * Get iaObservaciones
     *
     * @return string 
     */
    public function getIaObservaciones()
    {
        return $this->iaObservaciones;
    }

    /**
     * Set iaQueryMode
     *
     * @param string $iaQueryMode
     * @return InterpolLogMv
     */
    public function setIaQueryMode($iaQueryMode)
    {
        $this->iaQueryMode = $iaQueryMode;
    
        return $this;
    }

    /**
     * Get iaQueryMode
     *
     * @return string 
     */
    public function getIaQueryMode()
    {
        return $this->iaQueryMode;
    }

    /**
     * Set iaRemoteAddress
     *
     * @param string $iaRemoteAddress
     * @return InterpolLogMv
     */
    public function setIaRemoteAddress($iaRemoteAddress)
    {
        $this->iaRemoteAddress = $iaRemoteAddress;
    
        return $this;
    }

    /**
     * Get iaRemoteAddress
     *
     * @return string 
     */
    public function getIaRemoteAddress()
    {
        return $this->iaRemoteAddress;
    }

    /**
     * Set iaResultCode
     *
     * @param string $iaResultCode
     * @return InterpolLogMv
     */
    public function setIaResultCode($iaResultCode)
    {
        $this->iaResultCode = $iaResultCode;
    
        return $this;
    }

    /**
     * Get iaResultCode
     *
     * @return string 
     */
    public function getIaResultCode()
    {
        return $this->iaResultCode;
    }


    /**
     * Set iaSistema
     *
     * @param string $iaSistema
     * @return InterpolLogMv
     */
    public function setIaSistema($iaSistema)
    {
        $this->iaSistema = $iaSistema;
    
        return $this;
    }

    /**
     * Get iaSistema
     *
     * @return string 
     */
    public function getIaSistema()
    {
        return $this->iaSistema;
    }

    /**
     * Set iaSltdCountryofregistration
     *
     * @param string $iaSltdCountryofregistration
     * @return InterpolLogMv
     */
    public function setIaSltdCountryofregistration($iaSltdCountryofregistration)
    {
        $this->iaSltdCountryofregistration = $iaSltdCountryofregistration;
    
        return $this;
    }

    /**
     * Get iaSltdCountryofregistration
     *
     * @return string 
     */
    public function getIaSltdCountryofregistration()
    {
        return $this->iaSltdCountryofregistration;
    }

    /**
     * Set iaSltddetailsId
     *
     * @param string $iaSltddetailsId
     * @return InterpolLogMv
     */
    public function setIaSltddetailsId($iaSltddetailsId)
    {
        $this->iaSltddetailsId = $iaSltddetailsId;
    
        return $this;
    }

    /**
     * Get iaSltddetailsId
     *
     * @return string 
     */
    public function getIaSltddetailsId()
    {
        return $this->iaSltddetailsId;
    }

    /**
     * Set iaSltdTypeofdocument
     *
     * @param string $iaSltdTypeofdocument
     * @return InterpolLogMv
     */
    public function setIaSltdTypeofdocument($iaSltdTypeofdocument)
    {
        $this->iaSltdTypeofdocument = $iaSltdTypeofdocument;
    
        return $this;
    }

    /**
     * Get iaSltdTypeofdocument
     *
     * @return string 
     */
    public function getIaSltdTypeofdocument()
    {
        return $this->iaSltdTypeofdocument;
    }

    /**
     * Set iaSvdetailsVinid
     *
     * @param string $iaSvdetailsVinid
     * @return InterpolLogMv
     */
    public function setIaSvdetailsVinid($iaSvdetailsVinid)
    {
        $this->iaSvdetailsVinid = $iaSvdetailsVinid;
    
        return $this;
    }

    /**
     * Get iaSvdetailsVinid
     *
     * @return string 
     */
    public function getIaSvdetailsVinid()
    {
        return $this->iaSvdetailsVinid;
    }

    /**
     * Set iaSvEnginenr
     *
     * @param string $iaSvEnginenr
     * @return InterpolLogMv
     */
    public function setIaSvEnginenr($iaSvEnginenr)
    {
        $this->iaSvEnginenr = $iaSvEnginenr;
    
        return $this;
    }

    /**
     * Get iaSvEnginenr
     *
     * @return string 
     */
    public function getIaSvEnginenr()
    {
        return $this->iaSvEnginenr;
    }

    /**
     * Set iaSvRegistrationmark
     *
     * @param string $iaSvRegistrationmark
     * @return InterpolLogMv
     */
    public function setIaSvRegistrationmark($iaSvRegistrationmark)
    {
        $this->iaSvRegistrationmark = $iaSvRegistrationmark;
    
        return $this;
    }

    /**
     * Get iaSvRegistrationmark
     *
     * @return string 
     */
    public function getIaSvRegistrationmark()
    {
        return $this->iaSvRegistrationmark;
    }

    /**
     * Set iaSvVin
     *
     * @param string $iaSvVin
     * @return InterpolLogMv
     */
    public function setIaSvVin($iaSvVin)
    {
        $this->iaSvVin = $iaSvVin;
    
        return $this;
    }

    /**
     * Get iaSvVin
     *
     * @return string 
     */
    public function getIaSvVin()
    {
        return $this->iaSvVin;
    }

    /**
     * Set iaTimeResponse
     *
     * @param integer $iaTimeResponse
     * @return InterpolLogMv
     */
    public function setIaTimeResponse($iaTimeResponse)
    {
        $this->iaTimeResponse = $iaTimeResponse;
    
        return $this;
    }

    /**
     * Get iaTimeResponse
     *
     * @return integer 
     */
    public function getIaTimeResponse()
    {
        return $this->iaTimeResponse;
    }

    /**
     * Set iaTimestamp
     *
     * @param \DateTime $iaTimestamp
     * @return InterpolLogMv
     */
    public function setIaTimestamp($iaTimestamp)
    {
        $this->iaTimestamp = $iaTimestamp;
    
        return $this;
    }

    /**
     * Get iaTimestamp
     *
     * @return \DateTime 
     */
    public function getIaTimestamp()
    {
        return $this->iaTimestamp;
    }

    /**
     * Set iaTipoProceso
     *
     * @param string $iaTipoProceso
     * @return InterpolLogMv
     */
    public function setIaTipoProceso($iaTipoProceso)
    {
        $this->iaTipoProceso = $iaTipoProceso;
    
        return $this;
    }

    /**
     * Get iaTipoProceso
     *
     * @return string 
     */
    public function getIaTipoProceso()
    {
        return $this->iaTipoProceso;
    }

    /**
     * Set iaTransito
     *
     * @param string $iaTransito
     * @return InterpolLogMv
     */
    public function setIaTransito($iaTransito)
    {
        $this->iaTransito = $iaTransito;
    
        return $this;
    }

    /**
     * Get iaTransito
     *
     * @return string 
     */
    public function getIaTransito()
    {
        return $this->iaTransito;
    }

    /**
     * Set iaUsuario
     *
     * @param string $iaUsuario
     * @return InterpolLogMv
     */
    public function setIaUsuario($iaUsuario)
    {
        $this->iaUsuario = $iaUsuario;
    
        return $this;
    }

    /**
     * Get iaUsuario
     *
     * @return string 
     */
    public function getIaUsuario()
    {
        return $this->iaUsuario;
    }

    /**
     * Set iaUsuarioApellido
     *
     * @param string $iaUsuarioApellido
     * @return InterpolLogMv
     */
    public function setIaUsuarioApellido($iaUsuarioApellido)
    {
        $this->iaUsuarioApellido = $iaUsuarioApellido;
    
        return $this;
    }

    /**
     * Get iaUsuarioApellido
     *
     * @return string 
     */
    public function getIaUsuarioApellido()
    {
        return $this->iaUsuarioApellido;
    }

    /**
     * Set iaUsuarioDependencia
     *
     * @param string $iaUsuarioDependencia
     * @return InterpolLogMv
     */
    public function setIaUsuarioDependencia($iaUsuarioDependencia)
    {
        $this->iaUsuarioDependencia = $iaUsuarioDependencia;
    
        return $this;
    }

    /**
     * Get iaUsuarioDependencia
     *
     * @return string 
     */
    public function getIaUsuarioDependencia()
    {
        return $this->iaUsuarioDependencia;
    }

    /**
     * Set iaUsuarioDependenciaId
     *
     * @param integer $iaUsuarioDependenciaId
     * @return InterpolLogMv
     */
    public function setIaUsuarioDependenciaId($iaUsuarioDependenciaId)
    {
        $this->iaUsuarioDependenciaId = $iaUsuarioDependenciaId;
    
        return $this;
    }

    /**
     * Get iaUsuarioDependenciaId
     *
     * @return integer 
     */
    public function getIaUsuarioDependenciaId()
    {
        return $this->iaUsuarioDependenciaId;
    }

    /**
     * Set iaUsuarioDependenciaIdStr
     *
     * @param string $iaUsuarioDependenciaIdStr
     * @return InterpolLogMv
     */
    public function setIaUsuarioDependenciaIdStr($iaUsuarioDependenciaIdStr)
    {
        $this->iaUsuarioDependenciaIdStr = $iaUsuarioDependenciaIdStr;
    
        return $this;
    }

    /**
     * Get iaUsuarioDependenciaIdStr
     *
     * @return string 
     */
    public function getIaUsuarioDependenciaIdStr()
    {
        return $this->iaUsuarioDependenciaIdStr;
    }

    /**
     * Set iaUsuarioDoc
     *
     * @param string $iaUsuarioDoc
     * @return InterpolLogMv
     */
    public function setIaUsuarioDoc($iaUsuarioDoc)
    {
        $this->iaUsuarioDoc = $iaUsuarioDoc;
    
        return $this;
    }

    /**
     * Get iaUsuarioDoc
     *
     * @return string 
     */
    public function getIaUsuarioDoc()
    {
        return $this->iaUsuarioDoc;
    }

    /**
     * Set iaUsuarioDocTipo
     *
     * @param string $iaUsuarioDocTipo
     * @return InterpolLogMv
     */
    public function setIaUsuarioDocTipo($iaUsuarioDocTipo)
    {
        $this->iaUsuarioDocTipo = $iaUsuarioDocTipo;
    
        return $this;
    }

    /**
     * Get iaUsuarioDocTipo
     *
     * @return string 
     */
    public function getIaUsuarioDocTipo()
    {
        return $this->iaUsuarioDocTipo;
    }

    /**
     * Set iaUsuarioIp
     *
     * @param string $iaUsuarioIp
     * @return InterpolLogMv
     */
    public function setIaUsuarioIp($iaUsuarioIp)
    {
        $this->iaUsuarioIp = $iaUsuarioIp;
    
        return $this;
    }

    /**
     * Get iaUsuarioIp
     *
     * @return string 
     */
    public function getIaUsuarioIp()
    {
        return $this->iaUsuarioIp;
    }

    /**
     * Set iaUsuarioJerarquia
     *
     * @param string $iaUsuarioJerarquia
     * @return InterpolLogMv
     */
    public function setIaUsuarioJerarquia($iaUsuarioJerarquia)
    {
        $this->iaUsuarioJerarquia = $iaUsuarioJerarquia;
    
        return $this;
    }

    /**
     * Get iaUsuarioJerarquia
     *
     * @return string 
     */
    public function getIaUsuarioJerarquia()
    {
        return $this->iaUsuarioJerarquia;
    }

    /**
     * Set iaUsuarioNombre
     *
     * @param string $iaUsuarioNombre
     * @return InterpolLogMv
     */
    public function setIaUsuarioNombre($iaUsuarioNombre)
    {
        $this->iaUsuarioNombre = $iaUsuarioNombre;
    
        return $this;
    }

    /**
     * Get iaUsuarioNombre
     *
     * @return string 
     */
    public function getIaUsuarioNombre()
    {
        return $this->iaUsuarioNombre;
    }
    
    
    
}