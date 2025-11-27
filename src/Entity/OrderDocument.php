<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity]
#[Vich\Uploadable]
class OrderDocument
{
 #[ORM\Id, ORM\GeneratedValue, ORM\Column]
 private ?int $id = null;

 #[ORM\ManyToOne(targetEntity: OrderEntity::class, inversedBy: 'documents')]
 #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
 private ?OrderEntity $order = null;

 #[Vich\UploadableField(mapping: 'order_document', fileNameProperty: 'fileName')]
 private ?File $file = null;

 #[ORM\Column(nullable: true)]
 private ?string $fileName = null;

 #[ORM\Column(type: 'datetime_immutable')]
 private ?\DateTimeImmutable $uploadedAt = null;

 public function getId(): ?int
 {
  return $this->id;
 }
 public function getOrder(): ?OrderEntity
 {
  return $this->order;
 }
 public function setOrder(?OrderEntity $o): self
 {
  $this->order = $o;
  return $this;
 }

 public function setFile(?File $f = null): void
 {
  $this->file = $f;
  if ($f) $this->uploadedAt = new \DateTimeImmutable();
 }
 public function getFile(): ?File
 {
  return $this->file;
 }

 public function getFileName(): ?string
 {
  return $this->fileName;
 }
 public function setFileName(?string $n): void
 {
  $this->fileName = $n;
 }

 public function getUploadedAt(): ?\DateTimeImmutable
 {
  return $this->uploadedAt;
 }
}
