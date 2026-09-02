<?php

namespace GESTION\GestionBundle\Repository;

/**
 * Description of Interpol
 *
 */
class InterpolRepository extends ConsultaCurl
{

    //Atributos
    public $titulos = array(
        'name' => 'Apellido/s',
        'document' => 'Documento',
        'nickname' => 'Alias',
        'forename' => 'Nombre/s',
        'sex_id' => 'Sexo',
        'sltdId' => 'Id Sltd',
        'date_of_birth' => 'Fecha de Nacimiento',
        'place_of_birth' => 'Lugar de Nacimiento',
        'country_of_birth_id' => 'Id País de Nacimiento',
        'db_last_updated_on' => 'Última Modificación de la Base de Datos',
        'db_review_date' => 'Fecha de Revisión de la Base de Datos',
        'type_id' => 'Id Tipo',
        'type_id_theft' => 'Id Tipo',
        'date' => 'Fecha',
        'origin' => 'Origen',
        'diffusion' => 'Difusión',
        'document item_id' => 'Documento Id Item',
        'item_id' => 'Id Item',
        'nr' => 'Número de Documento',
        'date_of_issuance' => 'Fecha de Publicación',
        'nominal item_id' => 'Nominal Id Item',
        'qualification_id' => 'Id Calificación',
        'nationalities' => 'Nacionalidades',
        'citizen_of' => 'Ciudadano de',
        'country_id' => 'Id País',
        'status_id' => 'Id Estado',
        'alias' => 'Alias',
        'icis' => 'INTERPOL Sistema de Información Criminal (ICIS)',
        'office_reference' => 'Oficina de Origen',
        'message_id' => 'Id Mensaje',
        'office_id' => 'Id Oficina',
        'criminal_history' => 'Historial Criminal',
        'offence_id' => 'Id Delito',
        'theft' => 'Robo',
        'db_insert_on' => 'Fecha de Alta en la Base de Datos',
        'investigation' => 'Investigación',
        'add_info' => 'Información Adicional',
        'value' => 'Valor',
        'country_of_issuance_id' => 'Id País de Emisión',
        'search' => 'Búsqueda',
        'origin item_id' => 'Origen Id Item',
        'place' => 'Lugar',
        'vin' => 'N° Identificador de Vehículo (VIN)',
        'make' => 'Marca',
        'vehicle item_id' => 'Vehículo Id Item',
        'reporting_country_id' => 'Id País Reportado',
        'model' => 'Modelo',
        'engine_nr' => 'N° de Motor',
        'gear_box_nr' => 'gear_box_nr',
        'registration_mark' => 'N° de Dominio',
        'registration_country_id' => 'Id País de Registración',
        'security_nr' => 'N° de Seguridad',
        'color_id' => 'Id Color',
        'registration_year' => 'A&ntilde;o de Registración',
        'name_ipsg' => 'Nombre',
        'identity_confirmed' => 'Identidad Confirmada,Si/No',
        'status' => 'Estado',
        'office_name' => 'Nombre de la Oficina de Origen',
        'caution' => 'Precaución',
        'control_nr' => 'Nro. de Control',
        'fingerprint_available' => 'Huellas Digitales Disponibles',
        'issued_by' => 'Autoridad Emisora',
        'issued_on' => 'Fecha de Emisión',
        'country_of_issue' => 'País de Emisión',
        'signatory' => 'Nombre del Firmante',
        'charge' => 'Cargos',
        'offence' => 'Delito',
        'expiry_date' => 'Fecha de Caducidad',
        'extradition_request' => 'Solicitud de Extradición,Si/No',
        'sentenced' => 'Fallo',
        'qualification' => 'Calificación',
        'language_id' => 'Id Lenguaje',
        'access_mode_id' => 'Modo de acceso',
        'entity_ident' => 'Identificador',
        'qualification_id' => 'Id Calificación',
        'caution_id' => 'Id Precaución',
        'path' => 'Ruta de Archivo',
        'cautions' => 'Precauciones',
        'notification' => 'Notificaciones',
        'father' => 'Padre',
        'mother' => 'Madre',
        'history' => 'Historial',
        'aliases' => 'Alias',
        'arrest_warrant' => 'órdenes de Detención o Sentencias Judiciales y Delitos Asociados',
        'country_of_issue_id' => 'Id País de Emisión',
        'name_at_birth' => 'Apellido de Nacimiento',
        'file' => 'Archivo',
        'type' => 'Tipo de documento',
        'owner_nationality' => 'Nacionalidad del propietario',
        'status_id' => 'Id de estado'
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
        "RED" => "ROJO",
        "ORANGE" => "NARANJA",
        "BLUE" => "AZUL",
        "YELLOW" => "AMARILLO",
        "GREEN" => "VERDE",
        "BLACK" => "NEGRO",
        "DIFUSION" => "BLANCO",
        "LIGHT BLUE" => "CELESTE",
        "UN" => "CELESTE",
        "RED DIFF" => "ROJO DIFERENCIAL",
        "ORANGE DIFF" => "NARANJA DIFERENCIAL",
        "BLUE DIFF" => "AZUL DIFERENCIAL",
        "YELOW DIFF" => "AMARILLO DIFERENCIAL",
        "GREEN DIFF" => "VERDE DIFERENCIAL",
        "BLACK DIFF" => "NEGRO DIFERENCIAL",
        "DIFUSION DIFF" => "BLANCO DIFERENCIAL",
        "UN DIFF" => "CELESTE DIFERENCIAL",
        "" => "INDEFINIDO"
    ];

    public $paises = array();
    public $documentos = array();
    public $Offences_Code = array();
    public $coloresId = array();

    //Constructor    
    function __construct($container, \stdClass $data, $sesion)
    {

        $Diccionario = new Diccionario();
        $this->paises = $Diccionario->getPaises();
        $this->documentos = $Diccionario->getDocumentos();
        $this->Offences_Code = $Diccionario->getOffencesCode();
        $this->coloresId = $Diccionario->getColoresId();

        $this->setUrl($container->getParameter('urlPFAINTIWS'));
        parent::__construct($container, $data, $sesion);

    }

    // Consulta de documento
    public function getSLTD()
    {

        $respuesta = $this->query('SLTD');

        return $respuesta;
    }

    // Consulta de documento Detalle
    public function getSLTDDETAILS($id)
    {
        $respuesta = $this->query('SLTDDETAILS', $id);
        return $respuesta;
    }

    // Consulta de vehiculo
    public function getSMV()
    {

        $respuesta = $this->query('SMV');
        return $respuesta;
    }

    // Consulta de vehiculo Detalle
    public function getSMVDETAILS($id)
    {

        $respuesta = $this->query('SMVDETAILS', $id);

        return $respuesta;
    }   //  public function getSMVDETAILS($id){

    public function getNOMINALS($params = null)
    {

        $respuesta = $this->query('NOMINALS', $params);

        return ($respuesta);
    }   //  public function getNOMINALS(){

    public function getNOMINALSEXACT($params = null)
    {

        //return json_decode('{"header":{"schema_version":"x.x.1.0.0.0","generator":"IPSG\/nominals.asmx\/search","generator_version":"1.1.8.bc","language_id":"120"},"datas":{"search":{"origin":{"nominal":[{"query_score":{"value":"10","reason":{}},"name":"YDONE CASTILLO","forename":"ALEX ROGER","date_of_birth":"19750817","entityId":"VPBSCRIDB101-31830096439:2025\/80826"},{"query_score":{"value":"10","reason":{}},"name":"YDONE CASTILLO","forename":"ALEX ROGER","date_of_birth":"19750817","entityId":"VPBSCRIDB101-31830096439:2019\/103164"}],"status_id":"NO_ERROR","name":"IPSG"},"query":"\/NAME=YDONE CASTILLO\/FORENAME=ALEX ROGER\/DOB=19750817\/"}}}');

        $respuesta = $this->query('NOMINALSEXACT', $params);

        if ($respuesta === FALSE) {
            $response = array('NOMINALSEXACT' => array("Error" => "Error de conexión con Interpol"));

        } else if ($respuesta == 'Sin resultados') {
            $response = array('NOMINALSEXACT' => array("Info" => "Sin datos para mostrar"));

        } else if (isset($respuesta->datas) && $respuesta->datas->search->origin->status_id != 'NO_ERROR') {
            $response = array('NOMINALSEXACT' => array("Error" => "Error de conexión con Interpol"));

        } else if (isset($respuesta->datas)) {
            $response = array('NOMINALSEXACT' => $respuesta->datas->search->origin->nominal);
            $id = $respuesta->datas->search->origin->nominal;

            if (is_array($id)) {
                $entityId = $id[0]->entityId;

            } else {
                $entityId = $id->entityId;

            }   //  if (is_array($id)) {
        }   //  if ($respuesta === FALSE) {

        return ($respuesta);

    }   //  public function getNOMINALSEXACT(){

    public function getNOMINALSDETAILS($id)
    {

        $respuesta = $this->query('NOMINALSDETAILS', $id);

        return ($respuesta);
    }

    public function getNOMINALSIMAGE($params)
    {

        $respuesta = $this->query('NOMINALSIMAGE', $params);
        return ($respuesta);
    }

    public function getResponse()
    {

        $respuesta = $this->query('NOMINALSEXACT');

        return $response;
    }

    private function query($accion, $parametro = null)
    {

        $this->setAccion($accion);

        $consulta = array(
            "accion" => $accion,
            "sistema" => "INTERPOL",
            "origen" => isset($parametro->origen) ? $parametro->origen : "INTERPOL",
            "token" => $this->getContainer()->getParameter('tokenPFAINTIWS'),
            "pass" => $this->getContainer()->getParameter('passGenerico'),
            "usuario" => $this->getData()->usuario,
            "usuarioIp" => $this->getData()->usuarioIp,
            "RemoteAddress" => "",
            "usuarioApellido" => $this->getData()->usuarioApellido,
            "usuarioNombre" => $this->getData()->usuarioNombre,
            "usuarioDepen" => $this->getData()->usuarioDepen,
            "legajo" => "",
            "usuarioTipoDoc" => $this->getData()->usuarioTipoDoc,
            "usuarioDoc" => $this->getData()->usuarioDoc,
            "usuarioJerarquia" => $this->getData()->usuarioJerarquia,
            "version" => "1.2", //agregado
            "modoConsulta" => $this->getData()->tipoCons,
            "usuarioDepenId" => $this->getData()->usuarioDepenId,
            "latitud" => "-34.614484",
            "longitud" => "-58.388816"
        );

        if ($accion == 'NOMINALSEXACT') {
            $consulta["fechaNacimiento"] = empty($this->getData()->fechaNacimiento) ? '' : substr($this->getData()->fechaNacimiento, 8, 2) . "/" . substr($this->getData()->fechaNacimiento, 5, 2) . "/" . substr($this->getData()->fechaNacimiento, 0, 4);
            $consulta["apellido"] = $this->getData()->apellido;
            $consulta["nombre"] = $this->getData()->nombre;
        }   //  if ($accion == 'NOMINALSEXACT') {

        if ($accion == 'NOMINALS') {
            $consulta["fechaNacimiento"] = empty($this->getData()->fechaNacimiento) ? '' : substr($this->getData()->fechaNacimiento, 8, 2) . "/" . substr($this->getData()->fechaNacimiento, 5, 2) . "/" . substr($this->getData()->fechaNacimiento, 0, 4);
            $consulta["apellido"] = $this->getData()->apellido;
            $consulta["nombre"] = $this->getData()->nombre;
            /* $consulta["nroDoc"] = $this->getData()->nroDoc; */
        }   //  if ($accion == 'NOMINALS') {

        if ($accion == 'NOMINALSDETAILS') {
            $consulta["entityId"] = $parametro;
        }   //  if ($accion == 'NOMINALSDETAILS') {

        if ($accion == 'NOMINALSIMAGE') {
            $consulta["entityId"] = $parametro->entityId;
            $consulta["imagePath"] = $parametro->path;
        }   //  if ($accion == 'NOMINALSIMAGE') {

        if ($accion == 'SLTD') {
            $consulta["tipoDoc"] = $this->getData()->tipoDoc;
            $consulta["nroDoc"] = $this->getData()->nroDoc;
            $consulta["pais"] = $this->getData()->pais;
            /*  $consulta["identity"] = $this->getData()->identity; */ // nuevo para SLTD
        }   //  if($accion=='SLTD'){

        if ($accion == 'SLTDDETAILS') {
            $consulta["sltdId"] = $this->getData()->sltdId;


        }   //  if($accion=='SLTDDETAILS'){

        if ($accion == 'SMV') {
            $consulta["vin"] = $this->getData()->vin;
            $consulta["RegistrationMark"] = $this->getData()->dominio;
            $consulta["nroMotor"] = $this->getData()->nroMotor;
        }   //  if($accion=='SLTD'){

        if ($accion == 'SMVDETAILS') {
            $consulta["vinId"] = $parametro;
        }   //  if($accion=='SLTDDETAILS'){

        $this->setConsulta(json_encode($consulta));
        return $this->consulta();
    }
}
