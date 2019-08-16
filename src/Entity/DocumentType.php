<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity()
 * @ORM\Table(name="public.document_type") 
 */
class DocumentType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
  
    /**
    * @ORM\Column(type="text")
    */
    private $form;
  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
  
    public function getForm(): string {
      return $this->form;
    }
  
    public function setForm(string $form): self {
      $this->form = $form;
      return self;
    }
  
  public function __toString() {
      return "ID: " . $this->id . " / " . $this->getName();
    }
  
}