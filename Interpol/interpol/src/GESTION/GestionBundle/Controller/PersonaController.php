<?php
namespace GESTION\GestionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use GESTION\GestionBundle\Repository\InterpolRepository;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalle;
use GESTION\GestionBundle\Entity\ConsultaLoteDetalleRepository;

/**
 * Persona controller.
 *
 */
class PersonaController extends Controller
{

    /**
     * @var SessionManager
     * @DI\Inject("session.manager")
     */
    public $sessionManager;
    public function indexAction(Request $request)
    {
        $usuario = $this->getUser();
        return $this->render('GESTIONGestionBundle:Persona:index.html.twig', array(
            'usuario' => $usuario
        ));
    }

    /**
     * Muestra consulta realizada
     */
    public function showAction(Request $request, $id = 0)
    {

        $em = $this->getDoctrine()->getManager();

        $nombre = $request->get("txtNombre");
        $apellido = $request->get('txtApellido');
        $fechaNac = $request->get('txtFechaNac');
        $tipoCons = $request->get('lstTipoCons');

        $user = $this->getUser();
        $usuario = $user->getUsuario();
        $usuarioIp = $this->container->get('session')->get('ip');
        $usuarioApellido = $user->getApellido();
        $usuarioNombre = $user->getNombre();
        $usuarioDepen = $user->getDepenid()->getNombre();
        $usuarioDepenId = $user->getDepenid()->getCodigo();
        $legajo = "";
        $usuarioTipoDoc = $user->getTipodoc();
        $usuarioDoc = $user->getNumerodoc();
        $usuarioJerarquia = $user->getJerarquia();
        $version = "1.2"; //agregado

        $data = (object) array(
            "nombre" => $nombre,
            "apellido" => $apellido,
            "fechaNacimiento" => $fechaNac,
            "tipoCons" => $tipoCons,
            "usuario" => $usuario,
            "usuarioIp" => $usuarioIp,
            "usuarioApellido" => $usuarioApellido,
            "usuarioNombre" => $usuarioNombre,
            "usuarioDepen" => $usuarioDepen,
            "usuarioDepenId" => $usuarioDepenId,
            "legajo" => $legajo,
            "usuarioTipoDoc" => $usuarioTipoDoc,
            "usuarioDoc" => $usuarioDoc,
            "usuarioJerarquia" => $usuarioJerarquia,
            "version" => $version //agregado
        );


        $user->setCantPersona($user->getCantPersona() + 1);
        $em->flush();

        $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));

        if ($tipoCons == "AD") {

            $respuestaNominals = $interpolPersona->getNOMINALS();

            if (isset($respuestaNominals->datas) && !empty($respuestaNominals->datas)) {
                $nominals = $respuestaNominals->datas->search->origin->nominal;
            } else {
                $nominals = [];
            }

        } else {
            //  CONTROL FRONTERIZO (NOMINALSEXACT): LA RESPUESTA TRAE LA LISTA DE PERSONAS IGUAL QUE NOMINALS.
            $respuestaNominals = $interpolPersona->getNOMINALSEXACT();

            if (isset($respuestaNominals->datas->search->origin->nominal)) {
                $nominals = $respuestaNominals->datas->search->origin->nominal;
            } else {
                $nominals = [];
            }
        }

        //  LA LISTA DE PERSONAS DEBE SER PLANA (UN OBJETO POR FILA): SI VIENE UN SOLO OBJETO SE ENVUELVE EN UNA LISTA
        //  Y SI VIENE UNA LISTA DENTRO DE OTRA LISTA SE USA LA INTERNA.
        if (is_object($nominals)) {
            $nominals = [$nominals];
        } elseif (is_array($nominals) && isset($nominals[0]) && is_array($nominals[0])) {
            $nominals = $nominals[0];
        }


        foreach ($nominals as $n) {
            if (isset($n->date_of_birth)) {
                $n->date_of_birth = substr($n->date_of_birth, 6, 2) . '/' . substr($n->date_of_birth, 4, 2) . '/' . substr($n->date_of_birth, 0, 4);
            }
        }

        //  HACEMOS ESTE TRATAMIENTO PORQUE PODRÍA PASAR QUE EL CAMPO FORENAME VENGA CON UN OBJETO VACÍO EN LUGAR DE UN STRING
        //  ESTA SITUACIÓN HACE QUE NO SE REALICE EL RENDER DE LA PÁGINA, POR LO TANTO SOLO CAMBIAMOS EL OBJETO VACÍO POR EL
        //  VALOR "NO DATA", CONTEMPLANDO QUE ADEMÁS DE ESTE CASO PUDIERA INFORMARSE UN OBJETO CON NOMBRES.

        foreach ($nominals as $claveFila => $valorFila) {
            foreach ($valorFila as $clave => $valor) {
                if ($clave == "forename") {
                    if (is_object($valor)) {
                        if (!(array) $valor) {
                            $nominals[$claveFila]->forename = "NO DATA";
                        }

                        foreach ((array) $valor as $valorclave => $valorvalor) {
                            if ($valorclave == "formename") {
                                if (is_object($valorvalor)) {
                                    $nominals[$claveFila]->forename .= $valorvalor->value;
                                }
                                if (is_array($valorvalor)) {
                                    $nominals[$claveFila]->forename .= $valorvalor["value"];
                                }
                                if (is_string($valorvalor)) {
                                    $nominals[$claveFila]->forename .= $valorvalor;
                                }
                            }
                        }
                    }
                } //  if($clave=="forename"){
            }
        }

        //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIÓN.
        if (count($nominals) == 0 && isset($respuestaNominals->message) && $respuestaNominals->message != 'Sin resultados') {
            $nominals = ["ERROR" => $respuestaNominals->message];
        }

        return $this->render('GESTIONGestionBundle:Persona:show.html.twig', array('nominals' => $nominals));
    }

    public function showdetailsAction(Request $request, $id = 0)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $request->get("txtEntity");
        $nombre = $request->get("txtNombre");
        $apellido = $request->get('txtApellido');
        $fechaNac = $request->get('txtFechaNac');
        $tipoCons = $request->get('lstTipoCons');
        $idConsultaLoteDetalle = $request->get('idConsultaLoteDetalle');

        $user = $this->getUser();
        $usuario = $user->getUsuario();
        $usuarioIp = $this->container->get('session')->get('ip');
        $usuarioApellido = $user->getApellido();
        $usuarioNombre = $user->getNombre();
        $usuarioDepen = $user->getDepenid()->getNombre();
        $usuarioDepenId = $user->getDepenid()->getCodigo();
        $legajo = "";
        $usuarioTipoDoc = $user->getTipodoc();
        $usuarioDoc = $user->getNumerodoc();
        $usuarioJerarquia = $user->getJerarquia();

        $data = (object) array(
            "entity" => $entity,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "fechaNacimiento" => $fechaNac,
            "tipoCons" => $tipoCons,
            "usuario" => $usuario,
            "usuarioIp" => $usuarioIp,
            "usuarioApellido" => $usuarioApellido,
            "usuarioNombre" => $usuarioNombre,
            "usuarioDepen" => $usuarioDepen,
            "usuarioDepenId" => $usuarioDepenId,
            "legajo" => $legajo,
            "usuarioTipoDoc" => $usuarioTipoDoc,
            "usuarioDoc" => $usuarioDoc,
            "usuarioJerarquia" => $usuarioJerarquia,
            "version" => "1.2" //agregado
        );



        $user->setCantPersona($user->getCantPersona() + 1);
        $em->flush();




        $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));


        $respuestaNominals = $interpolPersona->getNOMINALSDETAILS($entity);
        /*  echo '<pre>';
         print_r($respuestaNominals);
         echo '</pre>';
  */
        $nominalDetails = [];




        if (isset($respuestaNominals->respuesta->datas->origin->nominal)) {
            $nominalDetails = $respuestaNominals->respuesta->datas->origin->nominal;
        } elseif (isset($respuestaNominals->datas->origin->nominal)) {
            $nominalDetails = $respuestaNominals->datas->origin->nominal;
        }

        if ($idConsultaLoteDetalle != '') { // Para la consulta por lotes graba la respuesta para futuras reconsultas
            $em = $this->getDoctrine()->getManager();
            $loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->find($idConsultaLoteDetalle);
            $loteDetalle->setRespuestaDetails(json_encode($nominalDetails));
            $loteDetalle->setEntityId($entity);
            $em->flush();
        }

        $detalle = json_decode(json_encode($nominalDetails), true);

        // Si la notificación viene dentro de event->product (esquema IPSG 2.7)
        if (!isset($detalle["notification"])) {
            if (isset($detalle["event"]["product"])) {
                $detalle["notification"] = $detalle["event"]["product"];
            } elseif (isset($detalle["event"][0]["product"])) {
                $detalle["notification"] = $detalle["event"][0]["product"];
            }
        }

        // El pedido de arresto viene dentro de la notificación: se muestra en su propio panel
        if (isset($detalle["notification"]["arrest_warrant"])) {
            $detalle["arrest_warrant"] = $detalle["notification"]["arrest_warrant"];
            unset($detalle["notification"]["arrest_warrant"]);
        }

        // Si la fecha de revisión viene en event
        if (!isset($detalle["db_review_date"])) {
            if (isset($detalle["event"]["db_review_date"])) {
                $detalle["db_review_date"] = $detalle["event"]["db_review_date"];
            } elseif (isset($detalle["event"][0]["db_review_date"])) {
                $detalle["db_review_date"] = $detalle["event"][0]["db_review_date"];
            }
        }

        $parametros = new \stdClass;
        $parametros->entityId = $entity;

        $arrayImg = array();
        $idx = 0;

        if (isset($detalle["file"][0])) {
            foreach ($detalle["file"] as $file) {
                $parametros->path = isset($file["path"]) ? $file["path"] : (isset($file["ref"]) ? $file["ref"] : '');

                if (isset($file["type_id"]) && $file["type_id"] != 'photo') {
                    continue; // ej. fingerprints: no es una imagen descargable
                }

                if ($parametros->path != '') {
                    $nominalImage = $interpolPersona->getNOMINALSIMAGE($parametros);

                    $imgRaw = null;
                    if (isset($nominalImage->imagen) && $nominalImage->imagen != false) {
                        $imgRaw = $nominalImage->imagen;
                    } elseif (isset($nominalImage->respuesta->imagen) && $nominalImage->respuesta->imagen != false) {
                        $imgRaw = $nominalImage->respuesta->imagen;
                    }

                    if ($imgRaw) {
                        $img = '';
                        $array = explode(",", $imgRaw);
                        for ($a = 0; $a < count($array); $a++) {
                            $img .= chr(intval($array[$a]));
                        }

                        $image = imagecreatefromstring($img);
                        if ($image !== false) {
                            ob_start();
                            imagejpeg($image);
                            $contents = ob_get_contents();
                            ob_end_clean();
                            $arrayImg[$idx] = "data:image/jpeg;base64," . base64_encode($contents);
                        }
                    }
                }
                $idx++;
            }
        } elseif (isset($detalle["file"])) {
            $parametros->path = isset($detalle["file"]["path"]) ? $detalle["file"]["path"] : (isset($detalle["file"]["ref"]) ? $detalle["file"]["ref"] : '');

            if ($parametros->path != '') {
                $nominalImage = $interpolPersona->getNOMINALSIMAGE($parametros);

                $imgRaw = null;
                if (isset($nominalImage->imagen) && $nominalImage->imagen != false) {
                    $imgRaw = $nominalImage->imagen;
                } elseif (isset($nominalImage->respuesta->imagen) && $nominalImage->respuesta->imagen != false) {
                    $imgRaw = $nominalImage->respuesta->imagen;
                }

                if ($imgRaw) {
                    $img = '';
                    $array = explode(",", $imgRaw);
                    for ($a = 0; $a < count($array); $a++) {
                        $img .= chr(intval($array[$a]));
                    }

                    $image = imagecreatefromstring($img);
                    if ($image !== false) {
                        ob_start();
                        imagejpeg($image);
                        $contents = ob_get_contents();
                        ob_end_clean();
                        $arrayImg[0] = "data:image/jpeg;base64," . base64_encode($contents);
                    }
                }
            }
        }


        $usuario = $this->getUser();

        //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIÓN.
        if (empty($nominalDetails) || (isset($respuestaNominals->status) && $respuestaNominals->status == 'error')) {
            $detalle = ["ERROR" => !empty($respuestaNominals->message) ? $respuestaNominals->message : "Sin resultados"];
        }

        return $this->render('GESTIONGestionBundle:Persona:showdetails.html.twig', array(
            'nominalDetails' => $detalle,
            'arrayImg' => $arrayImg,
            'titulos' => $interpolPersona->titulos,
            'encabezados' => $interpolPersona->encabezados,
            'colores' => $interpolPersona->colores,
            'paises' => $interpolPersona->paises,
            'Offences_Code' => $interpolPersona->Offences_Code,
            'documentosCod' => $interpolPersona->documentosCod,
            'cabello' => $interpolPersona->cabello,
            'ojos' => $interpolPersona->ojos,
            'complexion' => $interpolPersona->complexion,
            'propositos' => $interpolPersona->propositos,
            'estados' => $interpolPersona->estados,
            'Alias_Qualification' => $interpolPersona->Alias_Qualification,
            'caution' => $interpolPersona->Caution,
            'datosImpresion' => array(
                'usuario' => trim($usuarioApellido . ' ' . $usuarioNombre),
                'dependencia' => $usuarioDepen,
                'fecha' => date('d/m/Y H:i:s')
            )
        ));
    } //  public function showdetailsAction(Request $request, $id = 0) {

    /**
     * PDF original de la notificación (GETNOTICEPDFFILE).
     * Recibe por POST la ruta del archivo ("ref" del archivo de la notificación) en el campo pathToNotice
     * y devuelve el PDF para abrirlo en el navegador (desde el visor se puede descargar).
     */
    public function noticepdfAction(Request $request)
    {
        $pathToNotice = trim((string) $request->get('pathToNotice'));

        //  SOLO SE ACEPTAN RUTAS DE PDF: TERMINAN EN .PDF, SIN ".." Y CON CARACTERES DE RUTA NORMALES
        if ($pathToNotice === '' || strpos($pathToNotice, '..') !== false || !preg_match('/^[\w\-\.\/\\\\]+\.pdf$/i', $pathToNotice)) {
            return $this->noticepdfError('La ruta del documento solicitado no es válida.', 400);
        }

        $tipoCons = $request->get('lstTipoCons');
        if ($tipoCons != 'AD' && $tipoCons != 'BC') {
            $tipoCons = 'BC';
        }

        $user = $this->getUser();

        $data = (object) array(
            "tipoCons" => $tipoCons,
            "usuario" => $user->getUsuario(),
            "usuarioIp" => $this->container->get('session')->get('ip'),
            "usuarioApellido" => $user->getApellido(),
            "usuarioNombre" => $user->getNombre(),
            "usuarioDepen" => $user->getDepenid()->getNombre(),
            "usuarioDepenId" => $user->getDepenid()->getCodigo(),
            "legajo" => "",
            "usuarioTipoDoc" => $user->getTipodoc(),
            "usuarioDoc" => $user->getNumerodoc(),
            "usuarioJerarquia" => $user->getJerarquia(),
            "version" => "1.2"
        );

        $interpol = new InterpolRepository($this->container, $data, $this->container->get('session'));

        $respuesta = $interpol->getNOTICEPDFFILE($pathToNotice);

        //  EL PDF VIENE EN BASE64 EN "noticePDF", A VECES DENTRO DE UN NIVEL "respuesta"
        $base64 = null;
        if (is_object($respuesta)) {
            if (isset($respuesta->noticePDF)) {
                $base64 = $respuesta->noticePDF;
            } elseif (isset($respuesta->respuesta->noticePDF)) {
                $base64 = $respuesta->respuesta->noticePDF;
            }
        }

        if (empty($base64)) {
            $mensaje = (is_object($respuesta) && isset($respuesta->message) && is_string($respuesta->message))
                ? $respuesta->message
                : 'No se pudo obtener el PDF de la notificación.';

            return $this->noticepdfError($mensaje, 404);
        }

        $pdf = base64_decode($base64, true);
        if ($pdf === false || substr($pdf, 0, 4) !== '%PDF') {
            return $this->noticepdfError('El archivo recibido no es un PDF válido.', 502);
        }

        $nombre = preg_replace('/[^\w\-]/', '', pathinfo(str_replace('\\', '/', $pathToNotice), PATHINFO_FILENAME));
        if ($nombre == '') {
            $nombre = 'notificacion';
        }

        return new Response($pdf, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Notificacion_' . $nombre . '.pdf"',
            'Content-Length' => strlen($pdf)
        ));
    }

    //  PÁGINA SIMPLE CON EL MENSAJE DE ERROR PARA LA VENTANA NUEVA DEL PDF
    private function noticepdfError($mensaje, $codigo)
    {
        $html = '<!DOCTYPE html><html lang="es"><head><meta charset="utf-8"><title>PDF original</title></head>'
            . '<body style="font-family: Arial, sans-serif; margin: 40px; color: #333;">'
            . '<h3 style="color: #a12b20;">No se pudo abrir el PDF original</h3>'
            . '<p>' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</p>'
            . '<p><a href="#" onclick="window.close(); return false;">Cerrar esta ventana</a></p>'
            . '</body></html>';

        return new Response($html, $codigo, array('Content-Type' => 'text/html; charset=UTF-8'));
    }




}
?>