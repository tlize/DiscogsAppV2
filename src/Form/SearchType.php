<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => '',
                'attr' => [
                    'placeholder' => 'What is your name ?'
                ]
            ])
            ->add('quest', ChoiceType::class, [
                'label' => '',
                'choices' => [
                    'What is your quest ?' => '',
                    'Band / Artist' => 'artist',
                    'Title' => 'title',
                    'Label' => 'label'
                ],
            ])
            ->add('color', ChoiceType::class, [
                'label' => '',
                'choices' => [
                    'What is your favorite color ?' => '',
                    'Sold' => 'sold',
                    'For Sale' => 'forsale',
                    'All' => 'all'
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
