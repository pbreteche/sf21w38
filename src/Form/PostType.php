<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PostType extends AbstractType
{
    private const TITLE_HELP = 'post.form.title_help';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'help' => self::TITLE_HELP,
                'help_translation_parameters' => ['seo' => $options['warn_seo'] ? 'with_seo': 'without_seo'],
                'constraints' => [new Length(['min' => 3])], // À éviter, préférer la configuration des DataClasses
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
            'label_format' => 'post.fields.%name%'
        ]);
    }
}
