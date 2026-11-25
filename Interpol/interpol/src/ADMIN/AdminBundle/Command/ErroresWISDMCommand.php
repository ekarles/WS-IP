<?php

namespace ADMIN\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ADMIN\AdminBundle\Entity\LoteDocumentoRepository;
use ADMIN\AdminBundle\Entity\Proceso;

class ErroresWISDMCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:admin:errores_wisdm')
            ->setDescription('Envía por correo electrónico los registros WISDM con errores de las últimas 24 hs.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $proceso = new Proceso();
        
        $proceso->setFechaIni(new \Datetime());
        $proceso->setNombre('admin:admin:errores_wisdm');
        
        $em->persist($proceso);
        $em->flush();

        $errores = $em->getRepository('ADMINAdminBundle:LoteDocumento')->obtenerErrores24();
       
        $from = $this->getContainer()->getParameter('mailer_from');
        $to = explode(',',$this->getContainer()->getParameter('mailer_wisdm_to'));
        
        
        $message = \Swift_Message::newInstance()
            ->setSubject('Errores WISDM '.date('d/m/Y'))
            ->setFrom([$from=>'[ERRORES WISDM]'])
            ->setTo($to)
            ->setBody(
                    $this->getContainer()->get('templating')->render('ADMINAdminBundle:LoteDocumento:errores24.html.twig', array('errores' => $errores)),
                    'text/html'
                );
        
        try {
            $this->getContainer()->get('mailer')->send($message);
            $result = "Se han enviado las novedades correctamente a ".$this->getContainer()->getParameter('mailer_wisdm_to')." (".count($errores)." errores)";            
        } catch (TransportExceptionInterface $e) {
            $result = "Error al intentar enviar las novedades a ".$this->getContainer()->getParameter('mailer_wisdm_to')." (".count($errores)." errores) [".$e->getMessage()."]";
        }
        
        
        $proceso->setResultado($result);
        
        $proceso->setFechaFin(new \Datetime());
        $em->flush();
        
        echo $result;
    }
}