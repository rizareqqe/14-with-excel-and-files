<?php

namespace App\Form;

use App\Entity\Dish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Название'])
            ->add('price', IntegerType::class, ['label' => 'Цена'])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Фото (JPG/PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Удалить',
                'download_uri' => true,
                'image_uri' => false,
                'constraints' => [new File(['maxSize' => '2m', 'mimeTypes' => ['image/jpeg', 'image/png']])],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Dish::class]);
    }
}
