<?php

namespace App\Form;

use App\Entity\OrderEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer', EntityType::class, [
                'class' => \App\Entity\Customer::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите клиента',
            ])
            ->add('dishes', EntityType::class, [
                'class' => \App\Entity\Dish::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('documents', CollectionType::class, [
                'entry_type' => \App\Form\OrderDocumentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderEntity::class]);
    }
}
