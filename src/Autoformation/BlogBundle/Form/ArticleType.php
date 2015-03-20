<?php

namespace Autoformation\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date')
            ->add('titre', 'text', array('required' => false))
            ->add('auteur', 'text')
            ->add('contenu', 'textarea', array())
            ->add('publication', 'checkbox', array('required' => false))
            ->add('image', new ImageArticleType())
            ->add('categories', 'entity', array('class' => 'AutoformationBlogBundle:Categorie',
                                                'property' => 'nom',
                                                'multiple' => true,
                                                'expanded' => true))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoformation\BlogBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'autoformation_blogbundle_article';
    }
}
