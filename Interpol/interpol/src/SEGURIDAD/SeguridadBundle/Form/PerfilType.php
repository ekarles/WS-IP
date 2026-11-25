<?php

namespace SEGURIDAD\SeguridadBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PerfilType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('nombre')
            ->add('borrado', 'choice', [
                'label' => 'Borrado',
                'choices' => [
                    '0' => 'No',
                    '1' => 'Sí'
                ],
                'attr' => [ 'class' => 'selectpicker' ]
            ])
            ->add('permisoid', 'entity', [
                'class' => 'SEGURIDADSeguridadBundle:Permiso',
                'property' => 'permiso',
                'label' => 'Permiso',
                'required' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.permiso', 'ASC');
                },
                'attr' => [ 'class' => 'selectpicker',
                    'data-live-search' => 'true',
                    'data-none-selected-text' => 'Seleccionar permiso'
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
            'data_class' => 'SEGURIDAD\SeguridadBundle\Entity\Perfil'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seguridad_seguridadbundle_perfil';
    }
}
