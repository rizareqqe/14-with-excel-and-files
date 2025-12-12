<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
 public function buildForm(FormBuilderInterface $builder, array $options): void
 {
  $builder
   ->add('name', TextType::class, ['label' => 'Имя'])
   ->add('phone', TextType::class, ['label' => 'Телефон'])
   ->add('plainPassword', RepeatedType::class, [
    'type' => PasswordType::class,
    'first_options' => ['label' => 'Пароль'],
    'second_options' => ['label' => 'Повторите пароль'],
    'mapped' => false,
    'constraints' => [
     new NotBlank(),
     new Length(['min' => 6]),
    ],
   ]);
 }

 public function configureOptions(OptionsResolver $resolver): void
 {
  $resolver->setDefaults([
   'data_class' => Customer::class,
  ]);
 }
}
