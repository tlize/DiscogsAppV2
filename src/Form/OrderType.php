<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('buyer', TextType::class, [
                'attr'=>[
                    'placeholder'=>'Buyer Username'
                ]
            ])
            ->add('orderNum', IntegerType::class, [
                'label'=>'Order #',
                'attr'=>[
                    'placeholder'=>'number after the dash'
                ]

            ])
            ->add('shippingAddress',
                TextareaType::class, [
                    'label' => 'Buyer Info',
                    'attr'=>[
                        'placeholder'=>'Buyer shipping address, email...'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
