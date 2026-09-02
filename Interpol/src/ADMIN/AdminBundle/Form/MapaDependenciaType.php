<?php

namespace ADMIN\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MapaDependenciaType extends AbstractType
{
    private $dependencia;
    
    public function __construct($dependencia){
        $this->dependencia = $dependencia;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('dependencias', 'entity', [
                    'class' => 'SEGURIDADSeguridadBundle:Dependencia',
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                  ->andWhere(' upper(u.nombre) like upper(\'%'.$this->dependencia.'%\')')
                                  ->orderBy('u.nombre', 'ASC');
                        }
                  ])
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ADMIN\AdminBundle\Entity\Mapa'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_adminbundle_MapaDependencia';
    }
}
