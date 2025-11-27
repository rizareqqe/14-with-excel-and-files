<?php

namespace App\Form;

use App\Entity\OrderDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints\File;

class OrderDocumentType extends AbstractType
{
 public function buildForm(FormBuilderInterface $builder, array $options): void
 {
  $builder->add('file', VichFileType::class, [
   'label' => false,
   'required' => false,
   'allow_delete' => true,
   'download_uri' => true,
   'constraints' => [new File([
    'maxSize' => '10m',
    'mimeTypes' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'image/jpeg', 'image/png'],
   ])],
  ]);
 }

 public function configureOptions(OptionsResolver $resolver): void
 {
  $resolver->setDefaults(['data_class' => OrderDocument::class]);
 }
}
