<?php
namespace GESTION\GestionBundle\Repository;

/**
 * Description of Interpol
 *
 */
class InterpolRepositoryiArms extends ConsultaCurl {

    //Atributos
    public $titulos = array (
        'name'                    => 'Apellido/s',
        'document'                => 'Documento',
        'nickname'                => 'Alias',
        'forename'                => 'Nombre/s',
        'sex_id'                  => 'Sexo',
        'sltdId'                  => 'Id Sltd',
        'date_of_birth'           => 'Fecha de Nacimiento',
        'place_of_birth'          => 'Lugar de Nacimiento',
        'country_of_birth_id'     => 'Id País de Nacimiento',
        'db_last_updated_on'      => 'Última Modificación de la Base de Datos',
        'db_review_date'          => 'Fecha de Revisión de la Base de Datos',
        'type_id'                 => 'Id Tipo',
        'type_id_theft'           => 'Id Tipo',
        'date'                    => 'Fecha',
        'origin'                  => 'Origen',
        'diffusion'               => 'Difusión',
        'document item_id'        => 'Documento Id Item',
        'item_id'                 => 'Id Item',
        'nr'                      => 'Número de Documento',
        'date_of_issuance'        => 'Fecha de Publicación',
        'nominal item_id'         => 'Nominal Id Item',
        'qualification_id'        => 'Id Calificación',
        'nationalities'           => 'Nacionalidades',
        'citizen_of'              => 'Ciudadano de',
        'country_id'              => 'Id País',
        'status_id'               => 'Id Estado',
        'alias'                   => 'Alias',
        'icis'                    => 'INTERPOL Sistema de Información Criminal (ICIS)',
        'office_reference'        => 'Oficina de Origen',
        'message_id'              => 'Id Mensaje',
        'office_id'               => 'Id Oficina',
        'criminal_history'        => 'Historial Criminal',
        'offence_id'              => 'Id Delito',
        'theft'                   => 'Robo',
        'db_insert_on'            => 'Fecha de Alta en la Base de Datos',
        'investigation'           => 'Investigación',
        'add_info'                => 'Información Adicional',
        'value'                   => 'Valor',
        'country_of_issuance_id'  => 'Id País de Emisión',
        'search'                  => 'Búsqueda',
        'origin item_id'          => 'Origen Id Item',
        'place'                   => 'Lugar',
        'vin'                     => 'N° Identificador de Vehículo (VIN)',
        'make'                    => 'Marca',
        'vehicle item_id'         => 'Vehículo Id Item',
        'reporting_country_id'    => 'Id País Reportado',
        'model'                   => 'Modelo',
        'engine_nr'               => 'N° de Motor',
        'gear_box_nr'             => 'gear_box_nr',
        'registration_mark'       => 'N° de Dominio',
        'registration_country_id' => 'Id País de Registración',
        'security_nr'             => 'N° de Seguridad',
        'color_id'                => 'Id Color',
        'registration_year'       => 'A&ntilde;o de Registración',
        'name_ipsg'               => 'Nombre',
        'identity_confirmed'      => 'Identidad Confirmada,Si/No',
        'status'                  => 'Estado',
        'office_name'             => 'Nombre de la Oficina de Origen',
        'caution'                 => 'Precaución',
        'control_nr'              => 'Nro. de Control',
        'fingerprint_available'   => 'Huellas Digitales Disponibles',
        'issued_by'               => 'Autoridad Emisora',
        'issued_on'               => 'Fecha de Emisión',
        'country_of_issue'        => 'País de Emisión',
        'signatory'               => 'Nombre del Firmante',
        'charge'                  => 'Cargos',
        'offence'                 => 'Delito',
        'expiry_date'             => 'Fecha de Caducidad',
        'extradition_request'     => 'Solicitud de Extradición,Si/No',
        'sentenced'               => 'Fallo',
        'qualification'           => 'Calificación',
        'language_id'             => 'Id Lenguaje',
        'access_mode_id'          => 'Modo de acceso',
        'entity_ident'            => 'Identificador',
        'qualification_id'        => 'Id Calificación',
        'caution_id'              => 'Id Precaución',
        'path'                    => 'Ruta de Archivo',
        'cautions'                => 'Precauciones',
        'notification'            => 'Notificaciones',
        'father'                  => 'Padre',
        'mother'                  => 'Madre',
        'history'                 => 'Historial',
        'aliases'                 => 'Alias',
        'arrest_warrant'          => 'órdenes de Detención o Sentencias Judiciales y Delitos Asociados',
        'country_of_issue_id'     => 'Id País de Emisión',
        'name_at_birth'           => 'Apellido de Nacimiento',
        'file'                    => 'Archivo',
        'type'                    => 'Tipo de documento',
        'owner_nationality'       => 'Nacionalidad del propietario',
        'status_id'               => 'Id de estado'
     );
    
    public $encabezados = array(        
        "leyenda_A" => "Por favor, comuníquese con el Dpto. INTERPOL",
        "leyenda_B" => "POC (Push on call): 15-3197-1632 / 15-3197-0914",
        "leyenda_C" => "Telefono de línea: 4346-5753 (ROTATIVO)",
        "leyenda_D" => "CONMUTADOR POLICIA FEDERAL ARGENTINA: 4370-5800",
        "leyenda_E" => "INTERNOS: 5753 / 6165",
        "leyenda_F" => "T.O.: 5753 / 6165"
    );
    
    
    public $colores = [
        "RED"             => "ROJO"    ,
        "ORANGE"          => "NARANJA" ,
        "BLUE"            => "AZUL"    ,
        "YELLOW"          => "AMARILLO",
        "GREEN"           => "VERDE"   ,
        "BLACK"           => "NEGRO"   ,
        "DIFUSION"        => "BLANCO"  ,
        "LIGHT BLUE"      => "CELESTE" ,
        "UN"              => "CELESTE" ,
        "RED DIFF"        => "ROJO DIFERENCIAL",
        "ORANGE DIFF"     => "NARANJA DIFERENCIAL",
        "BLUE DIFF"       => "AZUL DIFERENCIAL",
        "YELOW DIFF"      => "AMARILLO DIFERENCIAL",
        "GREEN DIFF"      => "VERDE DIFERENCIAL",
        "BLACK DIFF"      => "NEGRO DIFERENCIAL",
        "DIFUSION DIFF"   => "BLANCO DIFERENCIAL",
        "UN DIFF"         => "CELESTE DIFERENCIAL",
        ""                => "INDEFINIDO"
    ];
    
    public $paises     = Array();
    public $documentos = Array();
    public $Offences_Code = Array();
    public $coloresId = Array();
    
    //Constructor    
    function __construct($container, \stdClass $data, $sesion) {

        $Diccionario  = new Diccionario();
        $this->paises     = $Diccionario->getPaises();
        $this->documentos = $Diccionario->getDocumentos();
        $this->Offences_Code = $Diccionario->getOffencesCode();
        $this->coloresId = $Diccionario->getColoresId();
        
        $this->setUrl(PFA_IARMSWS_WEB);
        parent::__construct($container, $data, $sesion);
        
    }
    
    
    public function getIARMS($params){
        
        $respuesta = $this->query('firearmsSearch', $params);
        return( $respuesta );
    }
    
    public function getResponse() {

        $respuesta = $this->query('NOMINALSEXACT');
        
        return $response;
    }

    private function query($accion, $parametro = null) {

        $this->setAccion($accion);
        
        $consulta = array(
            "metodo"           =>$accion,  // Juan hice un cambio en el cual el campo accion es reservado a un parámetro de búsqueda (corresponde al campo "action" que figura en la documentación)
            "sistema"          => "IARMS",
            "origen"           => isset($parametro->origen)?$parametro->origen:"INTERPOL",
            "token"            => IARMS_TOKEN,
            "pass"             => IARMS_BACKEND_PASS,
            "usuario"          => $this->getData()->usuario,
            //"user"             => $this->getData()->usuario,
            "usuarioIp"        => $this->getData()->usuarioIp,
            "RemoteAddress"    => "",
            "usuarioApellido"  => $this->getData()->usuarioApellido,
            "usuarioNombre"    => $this->getData()->usuarioNombre,
            "usuarioDepen"     =>  $this->getData()->usuarioDepen,
            "legajo"           => "",
            "usuarioTipoDoc"   => $this->getData()->usuarioTipoDoc,
            "usuarioDoc"       => $this->getData()->usuarioDoc,
            "usuarioJerarquia" => $this->getData()->usuarioJerarquia,
            //"modoConsulta"     =>  $this->getData()->tipoCons,
            "usuarioDepenId"   => $this->getData()->usuarioDepenId,
            "latitud"          => "-34.614484",
            "longitud"         => "-58.388816",
            "nroSerie"         => $this->getData()->nroSerie,
            "marca"            => $this->getData()->marca,
            "modelo"           => $this->getData()->modelo,
            "calibre"          => $this->getData()->calibre,
            "fabricante"       => $this->getData()->fabricante,
            "tipo"             => $this->getData()->tipo,
            "fechaDesde"       => $this->getData()->fechaDesde,
            "fechaHasta"       => $this->getData()->fechaHasta
            
        );
        
        $this->setConsulta(json_encode($consulta));
        
        echo "<pre>";
        echo "Acción: " . $accion."<BR>";
        print_r($consulta);
        echo "</pre>";
        
        
        return $this->consulta();
    }
}

?>