<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('listingId',
                IntegerType::class, [
                'label' => 'Listing Id'
            ])
            ->add('artist',
                TextType::class, [
                'label' => 'Band or Artist'
            ])
            ->add('title')
            ->add('label')
            ->add('catno')
            ->add('format')
            ->add('releaseId',
                IntegerType::class, [
                'label' => 'Release Id'
            ])
            ->add('price'
                , IntegerType::class
            )
            ->add('mediaCondition',
                TextType::class, [
                'label' => 'Media Condition'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
