<?php

namespace ADMIN\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class PersonaObservadaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellido',
                "text",
                [
                    'label'=>'Apellido',
                    'required'=>true,
                    'attr' => [
                        'autocomplete' => 'off'
                    ]
                ])
                ->add('nombre',
                    "text",
                    [
                        'label'=>'Nombre',
                        'required'=>true,
                        'attr' => [
                            'autocomplete' => 'off'
                        ]
                    ])
                ->add('fechaNacimiento', 'date', array(
                        'input'  => 'datetime',
                        'required'=>true,
                        'widget' => 'single_text',
                        'label'=>'Fecha Nacimiento',
                        'mapped' => false
                    )
                )
                ->add('fecDesde', 'date', array(
                    'input'  => 'datetime',
                    'required'=>true,
                    'widget' => 'single_text',
                    'label'=>'Fecha Desde'
                    )
                )
                ->add('fecHasta', 'date', array(
                        'input'  => 'datetime',
                        'required'=>true,
                        'widget' => 'single_text',
                        'label'=>'Fecha Hasta'
                    )
                 )
                 ->add('mail',
                     'text',[
                         'label'=>'Mail (separar con "," para agregar más de una dirección)',
                         'required'=>false,
                         'attr' => [
                             'autocomplete' => 'off'
                         ]
                     ]
                 );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ADMIN\AdminBundle\Entity\PersonaObservada'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_adminbundle_personaobservada';
    }
}
