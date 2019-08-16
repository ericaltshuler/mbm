<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Lists;
use App\Entity\DocumentType;

/**
 * @ApiResource(attributes={"order"={"position": "ASC"}})
 * @ORM\Entity()
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Lists", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $listId;
  
    /**
     * @ORM\ManyToOne(targetEntity="DocumentType")
     */
    public $typeId;
  
    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    public $userId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;
  
    /**
    * @ORM\Column(type="string")
    */
    private $name;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $position;
  
    /**
     * @ORM\Column(type="string")
     */
    private $fileLink;
    
    /**
     * @ORM\Column(type="text")
     */
    private $form;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListId(): Lists
    {
        return $this->listId;
    }

    public function setListId(Lists $listId): self
    {
        $this->listId = $listId;
        return $this;
    }
  
    public function getTypeId(): DocumentType
    {
        return $this->typeId;
    }

    public function setTypeId(DocumentType $typeId): self
    {
        $this->typeId = $typeId;
        return $this;
    }
  
    public function getUserId(): User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): self
    {
        $this->userId = $userId;
        return $this;
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

    public function getFileLink(): string
    {
        return $this->fileLink;
    }

    public function setFileLink(string $link): self
    {
        $this->fileLink = $link;
        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }
  
    public function getForm(): string {
      return $this->form;
    }
  
    public function setForm(string $form): self {
      $this->form = $form;
      return $this;
    }
  
  public function __toString() {
      return "ID: " . $this->id . " / " . $this->getName();
    }
}
