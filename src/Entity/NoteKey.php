<?php

namespace App\Entity;

use App\Repository\NoteKeyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteKeyRepository::class)]
class NoteKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $recipient = null;

    #[ORM\Column(type: Types::BINARY,length: 256)]
    private string $encKey;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'noteKeys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Note $note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getEncKey()
    {
        return $this->encKey;
    }

    public function setEncKey($encKey): static
    {
        $this->encKey = $encKey;

        return $this;
    }

    public function getNote(): ?Note
    {
        return $this->note;
    }

    public function setNote(?Note $note): static
    {
        $this->note = $note;

        return $this;
    }
}
