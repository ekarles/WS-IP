<?php

namespace ADMIN\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ADMIN\AdminBundle\Entity\Proceso;
use GESTION\GestionBundle\Repository\InterpolRepository;
use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;


class PersonaObservadaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:admin:persona_observada')
            ->setDescription('Consulta la tabla interpol log, buscando movimientos relacionados a las personas observadas.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        ini_set('max_execution_time', 600);
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $proceso = new Proceso();
        $resultadoMails = '';
        
        $proceso->setFechaIni(new \Datetime());
        $proceso->setNombre('admin:admin:persona_observada');
        
        $em->persist($proceso);
        $em->flush();
        
        $oResultado = $em->getRepository('ADMINAdminBundle:ConsultaPersonaObservada')->leerPersonasInterpolLog();
        
        if(isset($oResultado['salida']) && $oResultado['salida']!=''){
            $proceso->setResultado($oResultado['salida']);
            $proceso->setFechaFin(new \Datetime());
            $em->flush();
        }
        
        if(isset($oResultado['positivos']) && $oResultado['positivos']!=''){
            
            $idsConsultaPers = explode(',',$oResultado['positivos']);
            
            if(count($idsConsultaPers)>0){
                foreach ($idsConsultaPers as  $id){
                    $consultaPerObs = $em->getRepository('ADMINAdminBundle:ConsultaPersonaObservada')->find($id);
                    
                    if($consultaPerObs!==null){
                        
                        $from = $this->getContainer()->getParameter('mailer_from');
                        $to = explode(',',$this->getContainer()->getParameter('mailer_persona_obs_to'));
                        
                        if($consultaPerObs->getPersonaObservada()->getMail()!=''){
                            $mails = [];
                            $mails = explode(',',$consultaPerObs->getPersonaObservada()->getMail());
                            $to = array_merge($to,$mails);
                        }
                        
                        
                        if ($this->getContainer()->getParameter('ambiente') != 'PRODUCCION'){
                            $test = '[MAIL DE PRUEBA] ';
                        }else{
                            $test = '';
                        }
                        
                        
                        $message = \Swift_Message::newInstance()
                        ->setSubject($test.'Aviso de movimiento para '.$consultaPerObs->getPersonaObservada().'['.date('d/m/Y H:m:s').']')
                        ->setFrom([$from=>'[AVISO MOVIMIENTO]'])
                        ->setTo($to)
                        ->setBcc('computacion_interpol@policiafederal.gov.ar')
                        ->setBody(
                            $this->getContainer()->get('templating')->render('ADMINAdminBundle:PersonaObservada:avisoMovimiento.html.twig', array('consulta' => $consultaPerObs)),
                            'text/html'
                            );
                        
                        try {
                            $this->getContainer()->get('mailer')->send($message);
                            $resultadoMails .= "Se han enviado avisos de movimiento de ".$consultaPerObs->getPersonaObservada()." correctamente a <strong>".json_encode($to)."</strong>.\n<br>";
                        } catch (TransportExceptionInterface $e) {
                            $resultadoMails .= "Error al intentar enviar avisos de movimiento de ".$consultaPerObs->getPersonaObservada()." a <strong>".json_encode($to)."</strong>. [".$e->getMessage()."].\n<br>";
                        }
                    }else{
                        $oResultado["error"] = "Error al intentar obtener la consulta de Persona Observada [id=".$id."]\n<br>";
                    }
                    
                }
            }
        }
        
        $resultado["salida"] = str_replace('\n','\n<br>',$oResultado["salida"]);
                
        $resp = "<strong>Salida = </strong>".$resultado["salida"]."<br><br>";
        if($oResultado["positivos"]!=''){
            $resp .= "<strong>Positivos = </strong>".$oResultado["positivos"]."<br><br>";
        }
        $resp .= isset($oResultado["error"])?"<strong>Error = </strong>".$oResultado["error"]."<br><br>":"";
        if($resultadoMails!=''){
            $resp .= "<strong>Resultado Mails = </strong><br><br>".$resultadoMails;
        }
        
        $proceso->setResultado($resp);
        $proceso->setFechaFin(new \Datetime());
        $em->flush();
        
        echo $resp;
        
    }
 
}