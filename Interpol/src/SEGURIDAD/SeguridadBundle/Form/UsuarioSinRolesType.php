<?php

namespace SEGURIDAD\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UsuarioSinRolesType extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellido','text',[
                'label'=>'Apellido *',
                'required'=>true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('estado', 'choice', [
                'label' => 'Estado',
                'mapped' => false,
                'choices' => [
                    'A' => 'Activo',
                    'I' => 'Inactivo',
                    'B' => 'Borrado'
                ],
                'attr' => [ 'class' => 'selectpicker' ]
            ])
            ->add('password','password', [
                'label'=>'Password *',
                'required' => false,
                'always_empty' => false
            ])
            ->add('expiracionpassword','date', [
                'label'=>'Expiración de clave *',
                'widget' => 'single_text',
                'attr' => [ 'class' => 'form-control auto-complete-off',
                    'autocomplete' => 'off'
                ]
            ])
            ->add('iphabilitada','text',[
                'label'=>'IP habilitada',
                'required'=>false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('jerarquia','text',[
                'label'=>'Jerarquía',
                'required'=>false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('nombre','text', [
                'label'=>'Nombre *',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('numerodoc','text',[
                'label'=>'Nro.Doc. *',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('tipodoc','choice', [
                'choices' => [
                    'DNI' => 'DNI',
                    'LC' => 'LC',
                    'PAS' => 'PAS'
                ],
                'choices_as_values' => true,
                'label' => 'Tipo Doc.'
            ])
            ->add('usuario','text', [
                'label'=>'Usuario *',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'auto-complete-off'
                ]
            ])
            ->add('depenid', 'entity', [
                'class' => 'SEGURIDADSeguridadBundle:Dependencia',
                'property' => 'nombre',
                'label' => 'Dependencia *',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')->where('u.borrado=0')
                ->orderBy('u.nombre', 'ASC');
                },
                'attr' => [ 'class' => 'selectpicker',
                    'data-live-search' => 'true',
                    'data-none-selected-text' => 'Seleccionar dependencia'
                ]
            ])
            ->add('perfilid', 'entity', [
                'class' => 'SEGURIDADSeguridadBundle:Perfil',
                'property' => 'nombre',
                'label' => 'Perfiles *',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.nombre', 'ASC');
                },
                'attr' => [
                    'class' => 'selectpicker'
                ]
            ])
            ->add('observacion', 'textarea', [
                'label' => 'Observaciones',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'resize:none',
                    'maxlength' => '255'
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
            'data_class' => 'SEGURIDAD\SeguridadBundle\Entity\Usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seguridad_seguridadbundle_usuario';
    }
}
