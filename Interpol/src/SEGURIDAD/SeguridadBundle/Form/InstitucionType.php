<?php

namespace SEGURIDAD\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitucionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nombre', 
              "text",
              [
              'label'=>'Nombre',
              'required'=>true,
              'attr' => [
                'autocomplete' => 'off'
                        ]
              ])
        ->add('passgenerico', 
                  "text",
                  [
                  'label'=>'Pass. Generico',
                  'required'=>true,
                  'attr' => [
                      'autocomplete' => 'off'
                  ]])
        ->add('usuariogenerico',
                      "text",
                      [
                          'label'=>'Pass. Generico',
                          'required'=>true,
                          'attr' => [
                              'autocomplete' => 'off'
                          ]
               ]);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SEGURIDAD\SeguridadBundle\Entity\Institucion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seguridad_seguridadbundle_institucion';
    }
}
