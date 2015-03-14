<?php

namespace Autoformation\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageArticleType extends ImageType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('date');
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'autoformation_blogbundle_image_article';
    }
}
