<?php

namespace GESTION\GestionBundle\Repository;

/**
 * Description of Interpol
 *
 * @author pfa27667140
 */
class InterpolRepository extends ConsultaCurl {

    //Atributos
    private $titulos = array('name' => 'Apellido/s',
        'document' => 'Documento',
        'nickname' => 'Sobrenombre',
        'forename' => 'Nombre/s',
        'sex_id' => 'Sexo',
        'sltdId' => 'Id Sltd',
        'date_of_birth' => 'Fecha de Nacimiento',
        'place_of_birth' => 'Lugar de Nacimiento',
        'country_of_birth_id' => 'Id Pa&iacute;s de Nacimiento',
        'db_last_updated_on' => '&Uacute;ltima Modificaci&oacute;n de la Base de Datos',
        'db_review_date' => 'Fecha de Revisi&oacute;n de la Base de Datos',
        'type_id' => 'Id Tipo',
        'type_id_theft' => 'Id Tipo',
        'date' => 'Fecha',
        'origin' => 'Origen',
        'diffusion' => 'Difusi&oacute;n',
        'document item_id' => 'Documento Id Item',
        'item_id' => 'Id Item',
        'nr' => 'N&uacute;mero',
        'date_of_issuance' => 'Fecha de Publicaci&oacute;n',
        'nominal item_id' => 'Nominal Id Item',
        'qualification_id' => 'Id Calificaci&oacute;n',
        'nationalities' => 'Nacionalidades',
        'citizen_of' => 'Ciudadano de',
        'country_id' => 'Id Pa&iacute;s',
        'status_id' => 'Id Estado',
        'alias' => 'Alias',
        'icis' => 'INTERPOL Sistema de Informaci&oacute;n Criminal (ICIS)',
        'office_reference' => 'Oficina de Origen',
        'message_id' => 'Id Mensaje',
        'office_id' => 'Id Oficina',
        'criminal_history' => 'Historial Criminal',
        'offence_id' => 'Id Delito',
        'theft' => 'Robo',
        'db_insert_on' => 'Fecha de Alta en la Base de Datos',
        'investigation' => 'Investigaci&oacute;n',
        'add_info' => 'Información Adicional',
        'value' => 'Valor',
        'country_of_issuance_id' => 'Id Pa&iacute;s de Emisi&oacute;n',
        'search' => 'B&uacute;squeda',
        'origin item_id' => 'Origen Id Item',
        'place' => 'Lugar',
        'vin' => 'N&ordm; Identificador de Veh&iacute;culo (VIN)',
        'make' => 'Marca',
        'vehicle item_id' => 'Veh&iacute;culo Id Item',
        'reporting_country_id' => 'Id Pa&iacute;s Reportado',
        'model' => 'Modelo',
        'engine_nr' => 'N&ordm; de Motor',
        'gear_box_nr' => 'gear_box_nr',
        'registration_mark' => 'N&ordm; de Dominio',
        'registration_country_id' => 'Id Pa&iacute;s de Registraci&oacute;n',
        'security_nr' => 'N&ordm; de Seguridad',
        'color_id' => 'Id Color',
        'registration_year' => 'A&ntilde;o de Registraci&oacute;n',
        'name_ipsg' => 'Nombre',
        'identity_confirmed' => 'Identidad Confirmada,Si/No',
        'status' => 'Estado',
        'office_name' => 'Nombre de la Oficina de Origen',
        'caution' => 'Precauci&oacute;n',
        'control_nr' => 'Nro. de Control',
        'fingerprint_available' => 'Huellas Digitales Disponibles,Si/No',
        'issued_by' => 'Autoridad Emisora',
        'issued_on' => 'Fecha de Emisi&oacute;n',
        'country_of_issue' => 'Pa&iacute;s de Emisi&oacute;n',
        'signatory' => 'Nombre del Firmante',
        'charge' => 'Cargos',
        'offence' => 'Delito',
        'expiry_date' => 'Fecha de Caducidad',
        'extradition_request' => 'Solicitud de Extradici&oacute;n,Si/No',
        'sentenced' => 'Fallo',
        'qualification' => 'Calificaci&oacute;n',
        'language_id' => 'Id Lenguaje',
        'access_mode_id' => 'Modo de acceso',
        'entity_ident' => 'Identificador',
        'qualification_id' => 'Id Calificaci&oacute;n',
        'caution_id' => 'Id Precauci&oacute;n',
        'path' => 'Ruta de Archivo',
        'cautions' => 'Precauciones',
        'notification' => 'Notificaciones',
        'father' => 'Padre',
        'mother' => 'Madre',
        'history' => 'Historial',
        'aliases' => 'Alias',
        'arrest_warrant' => '&Oacute;rdenes de Detenci&oacute;n o Sentencias Judiciales y Delitos Asociados',
        'country_of_issue_id' => 'Id Pa&iacute;s de Emisi&oacute;n',
        'name_at_birth' => 'Apellido de Nacimiento',
        'file' => 'Archivo');

    //Constructor    
    function __construct($container, \stdClass $persona, $sesion) {
        $this->setUrl($container->getParameter('urlPFAINTIWS'));
        parent::__construct($container, $persona, $sesion);
    }

    public function getResponse() {
        
        $respuesta = $this->query('NOMINALSEXACT');
        
        //var_dump($respuesta->datas->search->origin->nominal);
        //exit();

        if ($respuesta === FALSE) {
            $response = array('NOMINALSEXACT' => array("Error" => "Error de conexión con Interpol"));
        } else if ($respuesta == 'Sin resultados') {
            $response = array('NOMINALSEXACT' => array("Info" => "Sin datos para mostrar"));
        } else if ($respuesta->datas->search->origin->status_id != 'NO_ERROR') {
            $response = array('NOMINALSEXACT' => array("Error" => "Error de conexión con Interpol"));
        } else {
            $response = array('NOMINALSEXACT' => $respuesta->datas->search->origin->nominal);
            $id = $respuesta->datas->search->origin->nominal;
            if (is_array($id)) {
                $entityId = $id[0]->entityId;
            } else {
                $entityId = $id->entityId;
            }
            $nominalsDetails = $this->query('NOMINALSDETAILS', $entityId);

            $nD = array('origin' => array(
                    'status_id' => $nominalsDetails->datas->origin->status_id,
                    'nominal' => array(
                        $this->titulos["name"] => $nominalsDetails->datas->origin->nominal->name,
                        $this->titulos["forename"] => $nominalsDetails->datas->origin->nominal->forename,
                        $this->titulos["sex_id"] => $nominalsDetails->datas->origin->nominal->sex_id,
                        $this->titulos["date_of_birth"] => $nominalsDetails->datas->origin->nominal->date_of_birth,
                        $this->titulos["fingerprint_available"] => $nominalsDetails->datas->origin->nominal->fingerprint_available,
                        $this->titulos["db_last_updated_on"] => $nominalsDetails->datas->origin->nominal->db_last_updated_on,
                        $this->titulos["db_review_date"] => $nominalsDetails->datas->origin->nominal->db_review_date,
                        $this->titulos["qualification_id"] => $nominalsDetails->datas->origin->nominal->qualification_id,
                        $this->titulos["status_id"] => $nominalsDetails->datas->origin->nominal->status_id,
                    )
                )
            );

            if (property_exists($nominalsDetails->datas->origin->nominal, 'country_of_birth_id')) {
                $nD['origin']['nominal'][$this->titulos["country_of_birth_id"]] = $nominalsDetails->datas->origin->nominal->country_of_birth_id;
            }
            if (property_exists($nominalsDetails->datas->origin->nominal, 'place_of_birth')) {
                $nD['origin']['nominal'][$this->titulos["place_of_birth"]] = $nominalsDetails->datas->origin->nominal->place_of_birth;
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'name_at_birth')) {
                $nD['origin']['nominal'][$this->titulos["name_at_birth"]] = $nominalsDetails->datas->origin->nominal->name_at_birth;
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'father')) {
                $nD['origin']['nominal'][$this->titulos["father"]] = array('Nombre' => $nominalsDetails->datas->origin->nominal->father->name);
            }
            if (property_exists($nominalsDetails->datas->origin->nominal, 'mother')) {
                $nD['origin']['nominal'][$this->titulos["mother"]] = array('Nombre' => $nominalsDetails->datas->origin->nominal->mother->name);
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'nickname')) {
                $nD['origin']['nominal'][$this->titulos["nickname"]] = $nominalsDetails->datas->origin->nominal->nickname;
            }
            if (property_exists($nominalsDetails->datas->origin->nominal, 'notification')) {
                $nD['origin']['nominal'][$this->titulos["notification"]] = array(
                    $this->titulos["type_id"] => $nominalsDetails->datas->origin->nominal->notification->type_id,
                    $this->titulos["date"] => $nominalsDetails->datas->origin->nominal->notification->date
                );
            }
            if (property_exists($nominalsDetails->datas->origin->nominal, 'diffusion')) {
                $nD['origin']['nominal'][$this->titulos["diffusion"]] = array(
                    $this->titulos["type_id"] => $nominalsDetails->datas->origin->nominal->diffusion->type_id,
                    $this->titulos["date"] => $nominalsDetails->datas->origin->nominal->diffusion->date
                );
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'arrest_warrant')) {
                $nD['origin']['nominal'][$this->titulos["arrest_warrant"]] = array(
                    $this->titulos["nr"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->nr,
                    $this->titulos["issued_on"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->issued_on,
                    $this->titulos["issued_by"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->issued_by,
                    $this->titulos["country_of_issue_id"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->country_of_issue_id,
                    $this->titulos["sentenced"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->sentenced,
                    $this->titulos["expiry_date"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->expiry_date,
                    $this->titulos["charge"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->charge,
                    $this->titulos["extradition_request"] => $nominalsDetails->datas->origin->nominal->arrest_warrant->extradition_request
                );
                if (property_exists($nominalsDetails->datas->origin->nominal->arrest_warrant, 'signatory')) {
                    $nD['origin']['nominal'][$this->titulos["arrest_warrant"]][$this->titulos["signatory"]] = $nominalsDetails->datas->origin->nominal->arrest_warrant->signatory;
                }
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'document')) {
                $nD['origin']['nominal'][$this->titulos["document"]] = array(
                    $this->titulos["nr"] => $nominalsDetails->datas->origin->nominal->document->nr,
                    $this->titulos["type_id"] => $nominalsDetails->datas->origin->nominal->document->type_id,                   
                    $this->titulos["date_of_issuance"] => $nominalsDetails->datas->origin->nominal->document->date_of_issuance,
                    $this->titulos["sltdId"] => $nominalsDetails->datas->origin->nominal->document->sltdId,
                );
            }
            $countryOf = $nominalsDetails->datas->origin->nominal->document->country_of_issuance_id;
           
            if (!is_object($countryOf)) {
                  $nD['origin']['nominal'][$this->titulos["document"]][$this->titulos["country_of_issuance_id"]] = array(
                $this->titulos["country_of_issuance_id"] => $nominalsDetails->datas->origin->nominal->document->country_of_issuance_id
                          );
            } else {
                $arr = (array) $countryOf;
                if (!empty($arr)) {
                    //Ver que atributos vienen
                }
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'nationalities')) {
                $nacionalidades = $nominalsDetails->datas->origin->nominal->nationalities;
                if (is_array($nacionalidades)) {
                    foreach ($nacionalidades as $nacionalidad) {
                        $arrayNacionalidades[][$this->titulos["country_id"]] = $nacionalidad->citizen_of->country_id;
                    }
                } else {
                    $arrayNacionalidades[][$this->titulos["country_id"]] = $nacionalidades->citizen_of->country_id;
                }
                $nD['origin']['nominal'][$this->titulos["nationalities"]] = array(
                    $this->titulos["citizen_of"] => $arrayNacionalidades
                );
            }
            // var_dump($nD);
            // exit();


            if (property_exists($nominalsDetails->datas->origin->nominal, 'icis')) {
                $nD['origin']['nominal'][$this->titulos["icis"]] = array(
                    $this->titulos["office_reference"] => $nominalsDetails->datas->origin->nominal->icis->office_reference,
                    $this->titulos["message_id"] => $nominalsDetails->datas->origin->nominal->icis->message_id,
                    $this->titulos["office_id"] => $nominalsDetails->datas->origin->nominal->icis->office_id
                );
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'caution')) {

                $caution = $nominalsDetails->datas->origin->nominal->caution;

                $arrayCaution = array();
                if (is_array($caution)) {
                    foreach ($caution as $caut) {
                        $arrayCaution[][$this->titulos["caution"]] = $caut->caution_id;
                    }
                } else {
                    $arrayCaution[][$this->titulos["caution"]] = $caution->caution_id;
                }
                $nD['origin']['nominal'][$this->titulos["caution"]] = array(
                    $this->titulos["caution"] => $arrayCaution
                );
            }

            if (property_exists($nominalsDetails->datas->origin->nominal, 'criminal_history')) {

                $criminalHistory = $nominalsDetails->datas->origin->nominal->criminal_history;

                $arrayCH = array();
                if (is_array($criminalHistory)) {
                    foreach ($criminalHistory as $cHistory) {
                        $arrayCH[][$this->titulos["criminal_history"]] = $cHistory->offence_id;
                    }
                } else {
                    $arrayCH[][$this->titulos["criminal_history"]] = $nominalsDetails->datas->origin->nominal->criminal_history->offence_id;
                }
                $nD['origin']['nominal'][$this->titulos["criminal_history"]] = array(
                    $this->titulos["offence_id"] => $arrayCH
                );
            }
            if (property_exists($nominalsDetails->datas->origin->nominal, 'alias')) {
                $nD['origin']['nominal'][$this->titulos["alias"]] = array(
                    $this->titulos["name"] => $nominalsDetails->datas->origin->nominal->alias->name,
                    $this->titulos["forename"] => $nominalsDetails->datas->origin->nominal->alias->forename,
                    $this->titulos["qualification_id"] => $nominalsDetails->datas->origin->nominal->alias->qualification_id
                );
            }

            $response['NOMINALSDETAILS'] = $nD;

            if (property_exists($nominalsDetails->datas->origin->nominal, 'file')) {
                if (property_exists($nominalsDetails->datas->origin->nominal->file, 'path')) {
                    $path = $nominalsDetails->datas->origin->nominal->file->path;

                    $parametros = new \stdClass;

                    $parametros->entityId = $entityId;
                    $parametros->path = $path;
                    $nominalsImage = $this->query('NOMINALSIMAGE', $parametros);

                    $img = '';
                    $array = explode(",", $nominalsImage->imagen);
                    for ($a = 0; $a < count($array); $a++) {
                        $img .= chr(intval($array[$a]));
                    }

                    $image = imagecreatefromstring($img);
                    ob_start(); // Let's start output buffering.
                    imagejpeg($image); //This will normally output the image, but because of ob_start(), it won't.
                    $contents = ob_get_contents(); //Instead, output above is saved to $contents
                    ob_end_clean(); //End the output buffer.
                    $dataUri = "data:image/jpeg;base64," . base64_encode($contents);
                                        
                    $response['IMAGEDETAILS'] = $dataUri;
                }
            }
        }

        return $response;
    }

    private function query($accion, $parametro = null) {

        $this->setAccion($accion);

        $consulta = array(
            "sistema"          => "INTERPOL",
            "origen"           => $this->getSesion()->sistema,
            "token"            => $this->getContainer()->getParameter('tokenPFAINTIWS'),
            "pass"             => $this->getSesion()->pwdusupfa,
            "usuario"          => $this->getSesion()->usuario,
            "usuarioIp"        => $this->getData()->miIp,
            "RemoteAddress"    => "",
            "usuarioApellido"  => $this->getSesion()->apellido_usuario,
            "usuarioNombre"    => $this->getSesion()->nombre_usuario,
            "usuarioDepen"     => $this->getSesion()->desc_dep, //"COMPUTACION" 
            "legajo"           => $this->getSesion()->lp_usuario,
            "usuarioTipoDoc"   => $this->getSesion()->tipo_doc,
            "usuarioDoc"       => $this->getSesion()->nro_doc,
            "usuarioJerarquia" => $this->getSesion()->jerarquia_usuario, //  "OPERADOR",            
            "modoConsulta"     => "AD",
            "usuarioDepenId"   => $this->getSesion()->cod_dep, // "591",
            "nroDoc"           => $this->getData()->nroDocumento,
            "pais"             => $this->getData()->nacionalidad,
            "tipoDoc"          => $this->getData()->tipoDocumento,
            "fechaNacimiento"  => str_replace("-", "/", $this->getData()->fechaNacimiento),
            "apellido"         => $this->getData()->apellido,
            "nombre"           => $this->getData()->nombre,
            "latitud"          => "-34.614484",
            "longitud"         => "-58.388816"
        );
        
        if ($accion == 'NOMINALSEXACT') {
            $consulta["tipoDoc"] = "I";
            $consulta["fechaNacimiento"] = str_replace("-", "/", $this->getData()->fechaNacimiento);
            $consulta["apellido"] = $this->getData()->apellido;
            $consulta["nombre"] = $this->getData()->nombre;
        }
        if ($accion == 'NOMINALSDETAILS') {
            $consulta["entityId"] = $parametro;
        }
        if ($accion == 'NOMINALSIMAGE') {
            $consulta["entityId"] = $parametro->entityId;
            $consulta["imagePath"] = $parametro->path;
        }

        $this->setConsulta(json_encode($consulta));

        return $this->consulta();
    }

}
