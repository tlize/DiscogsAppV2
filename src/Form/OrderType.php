<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('buyer')
            ->add('orderNum')
            ->add('orderDate')
            ->add('status')
            ->add('total')
            ->add('shipping')
            ->add('fee')
            ->add('tax')
            ->add('taxedAmount')
            ->add('taxJurisdiction')
            ->add('taxResponsibleParty')
            ->add('invoice')
            ->add('ratingOfBuyer')
            ->add('ratingOfSeller')
            ->add('ratingOfBuyerDate')
            ->add('ratingOfSellerDate')
            ->add('commentAboutBuyer')
            ->add('commentAboutSeller')
            ->add('archived')
            ->add('shippingAddress')
            ->add('buyerExtra')
            ->add('lastActivity')
            ->add('currency')
            ->add('fromOffer')
            ->add('offerOriginalPrice')
            ->add('shippingMethod')
            ->add('country')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
