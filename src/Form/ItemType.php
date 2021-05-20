<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('listingId',
                null, [
                'label' => 'Listing Id',
            ])
            ->add('artist',
                null, [
                'label' => 'Band or Artist'
            ])
            ->add('title')
            ->add('label')
            ->add('catno', null, [
                'label' => 'Catalog #'
            ])
            ->add('format')
            ->add('releaseId',
                null, [
                'label' => 'Release Id'
            ])
            ->add('price', IntegerType::class)
            ->add('mediaCondition',
                ChoiceType::class, [
                'label' => 'Media Condition',
                'choices' => [
                    'Select Item Condition' => '',
                    'Mint (M)' => 'M',
                    'Near Mint (NM or M-)' => 'NM',
                    'Very Good Plus (VG+)' => 'VG+',
                    'Very Good (VG)' => 'VG',
                    'Good Plus (G+)' => 'G+',
                    'Good (G)' => 'G',
                    'Fair (F)' => 'F',
                    'Poor (P)' => 'P'
                    ],
                ]
            )
            ->add('sleeveCondition',
                ChoiceType::class, [
                    'label' => 'Sleeve Condition',
                    'choices' => [
                        'Select Item Condition' => '',
                        'Mint (M)' => 'M',
                        'Near Mint (NM or M-)' => 'NM',
                        'Very Good Plus (VG+)' => 'VG+',
                        'Very Good (VG)' => 'VG',
                        'Good Plus (G+)' => 'G+',
                        'Good (G)' => 'G',
                        'Fair (F)' => 'F',
                        'Poor (P)' => 'P'
                    ],
                    'required' => false
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
