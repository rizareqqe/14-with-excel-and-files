<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity]
#[Vich\Uploadable]
class Dish
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[Vich\UploadableField(mapping: 'dish_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function getPrice(): ?int
    {
        return $this->price;
    }
    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function setImageFile(?File $file = null): void
    {
        $this->imageFile = $file;
    }
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    public function setImageName(?string $name): void
    {
        $this->imageName = $name;
    }
}
