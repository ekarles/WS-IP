<?php

namespace ADMIN\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MapaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('descripcion',
            "text",
            [
                'label'=>'Descripcion',
                'required'=>true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('latitud',
                "text",
                [
                    'label'=>'Latitud',
                    'required'=>true,
                    'attr' => [
                        'autocomplete' => 'off'
                    ]
                ])
            ->add('longitud',
                "text",
                [
                    'label'=>'Longitud',
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
                    ->add('zoom',
                        "text",
                        [
                            'label'=>'Zoom',
                            'required'=>true,
                            'attr' => [
                                'autocomplete' => 'off'
                            ]
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
        return 'admin_adminbundle_mapa';
    }
}
