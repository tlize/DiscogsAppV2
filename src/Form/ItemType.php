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
            ->add('listingId')
            ->add('artist', TextType::class, [
                'label' => 'Band or Artist'
            ])
            ->add('title')
            ->add('label')
            ->add('catno')
            ->add('format')
            ->add('releaseId')
//            ->add('status')
            ->add('price', IntegerType::class, [
                'attr' => [
                    'placeholder' => 666
                ]
            ])
//            ->add('listed')
//            ->add('comments')
            ->add('mediaCondition')
//            ->add('sleeveCondition')
//            ->add('acceptOffer')
//            ->add('externalId')
//            ->add('weight')
//            ->add('formatQuantity')
//            ->add('flatShipping')
//            ->add('location')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
