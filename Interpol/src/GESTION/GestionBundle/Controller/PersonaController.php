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


        //DEPURACION DE DATOS MANDADOS A SHOW
        echo "<pre>";
        echo "estos son los datos enviados " . json_encode($data);
        echo "</pre>";
        //FIN DEPURACION DE DATOS MANDADOS

        $user->setCantPersona($user->getCantPersona() + 1);
        $em->flush();

        $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));

        if ($tipoCons == "AD") {

            $respuestaNominals = $interpolPersona->getNOMINALS();

            if (isset($respuestaNominals->datas)) {
                $n = $respuestaNominals->datas->search->origin->nominal;
                if (is_array($n) && count($n) > 1) {
                    $nominals = $n;
                } else {
                    $nominals[0] = $n;
                }
            } else {
                $nominals = [];
            }

        } else {
            //  EN CASO DE NOMINALSEXACT RETORNO UN ARRAY PARA LA POSICIÓN 0, SOLO PARA QUE EL RESTO DE LA INTERFAZ SE COMPORTE EXACTAMENTE IGUAL AL RESULTADO DE NOMINALS.
            $respuestaNominals = $interpolPersona->getNOMINALSEXACT();

            if (isset($respuestaNominals->datas->search->origin->nominal)) {
                $nominals[0] = $respuestaNominals->datas->search->origin->nominal;
                if (is_array($nominals[0]) && count($nominals[0]) == 0) {
                    $nominals = [];
                }
            } else {
                $nominals = [];
            }
        }

        //DEPURACION DE NOMINALS
        echo "<pre>";
        echo "estos son los nominals " . json_encode($respuestaNominals);
        echo "</pre>";
        //FIN DEPURACION DE NOMINALS

        foreach ($nominals as $n) {
            if (isset($n->date_of_birth)) {
                $n->date_of_birth = substr($n->date_of_birth, 6, 2) . '/' . substr($n->date_of_birth, 4, 2) . '/' . substr($n->date_of_birth, 0, 4);
            }
        }

        //  HACEMOS ESTE TRATAMIENTO PORQUE PODRÍA PASAR QUE EL CAMPO FORENAME VENGA CON UN OBJETO VACÍO EN LUGAR DE UN STRING
        //  ESTA SITUACIÓN HACE QUE NO SE REALICE EL RENDER DE LA PÁGINA, POR LO TANTO SOLO CAMBIAMOS EL OBJETO VACÍO POR EL
        //  VALOR "NO DATA", CONTEMPLANDO QUE ADEMÁS DE ESTE CASO PUDIERA INFORMARSE UN OBJETO CON NOMBRES.

        foreach ($nominals as $claveFila => $valorFila) {

            if (is_array($nominals[0]) && count($nominals[0]) > 1) {
                $nominals[0] = $nominals[0][0];
            }

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

        echo "<pre>";
        print_r($data);
        echo "</pre>";


        $interpolPersona = new InterpolRepository($this->container, $data, $this->container->get('session'));


        $respuestaNominals = $interpolPersona->getNOMINALSDETAILS($entity);

        $nominalDetails = [];
        if (isset($respuestaNominals->datas->origin->nominal))
            $nominalDetails = $respuestaNominals->datas->origin->nominal;
        if ($idConsultaLoteDetalle != '') { // Para la consulta por lotes graba la respuesta para futuras reconsultas
            $em = $this->getDoctrine()->getManager();
            $loteDetalle = $em->getRepository('GESTIONGestionBundle:ConsultaLoteDetalle')->find($idConsultaLoteDetalle);
            $loteDetalle->setRespuestaDetails(json_encode($nominalDetails));
            $loteDetalle->setEntityId($entity);
            $em->flush();
        }

        $detalle = json_decode(json_encode($nominalDetails), true);

        $parametros = new \stdClass;
        $parametros->entityId = $entity;

        $arrayImg = array();
        $idx = 0;

        if (isset($detalle["file"][0])) {
            foreach ($detalle["file"] as $file) {
                $parametros->path = isset($file["path"]) ? $file["path"] : '';

                if ($parametros->path != '') {
                    $nominalImage = $interpolPersona->getNOMINALSIMAGE($parametros);

                    if (isset($nominalImage->imagen) && $nominalImage->imagen != false) {
                        $img = '';
                        $array = explode(",", $nominalImage->imagen);
                        for ($a = 0; $a < count($array); $a++) {
                            $img .= chr(intval($array[$a]));
                        }

                        $image = imagecreatefromstring($img);
                        ob_start();
                        imagejpeg($image);
                        $contents = ob_get_contents();
                        ob_end_clean();
                        $arrayImg[$idx] = "data:image/jpeg;base64," . base64_encode($contents);
                    }
                }
                $idx++;
            }
        } else {
            $parametros->path = isset($detalle["file"]["path"]) ? $detalle["file"]["path"] : '';

            if ($parametros->path != '') {
                $nominalImage = $interpolPersona->getNOMINALSIMAGE($parametros);

                if (isset($nominalImage->imagen) && $nominalImage->imagen != false) {
                    $img = '';
                    $array = explode(",", $nominalImage->imagen);
                    for ($a = 0; $a < count($array); $a++) {
                        $img .= chr(intval($array[$a]));
                    }

                    $image = imagecreatefromstring($img);
                    ob_start();
                    imagejpeg($image);
                    $contents = ob_get_contents();
                    ob_end_clean();
                    $arrayImg[0] = "data:image/jpeg;base64," . base64_encode($contents);
                }
            }
        }


        $usuario = $this->getUser();

        //  VERIFICAMOS QUE NO HAYA HABIDO ERROR DE COMUNICACIÓN.
        if (!isset($nominalDetails) || (isset($respuestaNominals->message) && $respuestaNominals->message != "Sin resultados")) {
            $detalle = ["ERROR" => $respuestaNominals->message];
        }

        return $this->render('GESTIONGestionBundle:Persona:showdetails.html.twig', array(
            'nominalDetails' => $detalle,
            'arrayImg' => $arrayImg,
            'titulos' => $interpolPersona->titulos,
            'encabezados' => $interpolPersona->encabezados,
            'colores' => $interpolPersona->colores,
            'paises' => $interpolPersona->paises,
            'Offences_Code' => $interpolPersona->Offences_Code
        ));
    } //  public function showdetailsAction(Request $request, $id = 0) {




}
?>