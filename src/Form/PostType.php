<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private const TITLE_HELP = ' Attention, modifier le titre impacte le référencement.';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'help' => 'Ne pas utiliser le caractère "$".'.($options['warn_seo'] ? self::TITLE_HELP: null),
            ])
            ->add('body')
            ->add('isPublished')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'warn_seo' => false,
        ]);
    }
}
