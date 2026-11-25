<?php

namespace SEGURIDAD\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UsuarioPerfilesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('perfilid', 'entity', [
                    'class' => 'SEGURIDADSeguridadBundle:Perfil',
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                  ->where('u.borrado=0')
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
