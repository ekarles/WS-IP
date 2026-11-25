<?php

namespace SEGURIDAD\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DependenciaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('codigo',
            'text',[
                'label'=>'Codigo',
                'required'=>true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ]
            )
            ->add('nombre',
                  'text',[
                  'label'=>'Nombre',
                  'required'=>true,
                  'attr' => [
                             'autocomplete' => 'off'
                            ]
                         ]
                )
            ->add('contacto')
            ->add('mail',
                'text',[
                    'label'=>'Mail (separar con "," para agregar más de una dirección)',
                    'required'=>false,
                    'attr' => [
                        'autocomplete' => 'off'
                    ]
                ]
                )
             ->add('direccion',
                    'text',[
                        'label'=>'Dirección',
                        'required'=>true,
                        'attr' => [
                            'autocomplete' => 'off'
                        ]
                    ]
                    )
            ->add('latitud','text',[
                'label'=>'Latitud',
                'required'=>true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('longitud', 'text' ,[
                'label'=>'Longitud',
                'required'=>true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('maximousuarios','integer',[
                'label'=>'Máximo Usuarios',
                'required'=>true,
                'attr' => [
                    'autocomplete' => 'off',
                    'min' => 0
                ]
            ])
            ->add('minaviso','integer',[
                'label'=>'Mínimo Aviso',
                'required'=>true,
                'attr' => [
                    'autocomplete' => 'off',
                    'min' => 0
                ]
            ])           
            ->add('observaciones', 'textarea', [
                'label' => 'Observaciones',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'resize:none',
                    'maxlength' => '1000'
                ]
            ])
            ->add('orden')
            ->add('tipo')
            ->add('institucionid', 'entity', [
                'class' => 'SEGURIDADSeguridadBundle:Institucion',
                'property' => 'nombre',
                'label' => 'Institución',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.nombre', 'ASC');
                },
                'attr' => [ 'class' => 'selectpicker',
                    'data-live-search' => 'true',
                    'data-none-selected-text' => 'Seleccionar institución'
                    ]
                ])
             ->add('borrado', 'choice', [
                    'label' => 'Borrado',
                    'choices' => [
                        '0' => 'No',
                        '1' => 'Sí'
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
            'data_class' => 'SEGURIDAD\SeguridadBundle\Entity\Dependencia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seguridad_seguridadbundle_dependencia';
    }
}
