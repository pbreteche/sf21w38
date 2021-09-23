<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PostType extends AbstractType
{
    private const TITLE_HELP = ' Attention, modifier le titre impacte le référencement.';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'help' => 'Ne pas utiliser le caractère "$".'.($options['warn_seo'] ? self::TITLE_HELP: null),
                'constraints' => [new Length(['min' => 3])], // À éviter, préférer la configuration des DataClasses
            ])
            ->add('body')
            ->add('isPublished')
            ->add('writtenBy', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'nickname'
            ])
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
