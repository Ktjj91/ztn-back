<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['note:read']],
    denormalizationContext: ['groups' => ['note:write']],
)]
class Note
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue()]
    #[Groups('note:read')]
    private ?int $id =null;

    #[ORM\Column(type: Types::BINARY)]
    private  $cipherText;

    #[ORM\Column(type: Types::BINARY)]
    private  $iv;

    #[ORM\Column(options: ['default' => 1])]
    private ?int $currentVer = 1;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['note:write'])]
    private ?User $owner = null;

    /**
     * @var Collection<int, NoteKey>
     */
    #[ORM\OneToMany(targetEntity: NoteKey::class, mappedBy: 'note',cascade: ['persist', 'remove'],orphanRemoval: true)]
    private Collection $noteKeys;

    /**
     * @var Collection<int, Share>
     */
    #[ORM\OneToMany(targetEntity: Share::class, mappedBy: 'note',cascade: ["persist", "remove"],orphanRemoval: true)]
    private Collection $shares;

    public function __construct()
    {
        $this->noteKeys = new ArrayCollection();
        $this->shares = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCipherText()
    {
        return $this->cipherText;
    }

    public function setCipherText($cipherText): static
    {
        $this->cipherText = $cipherText;

        return $this;
    }

    public function getIv()
    {
        return $this->iv;
    }

    public function setIv($iv): static
    {
        $this->iv = $iv;

        return $this;
    }

    public function getCurrentVer(): ?int
    {
        return $this->currentVer;
    }

    public function setCurrentVer(int $currentVer): static
    {
        $this->currentVer = $currentVer;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, NoteKey>
     */
    public function getNoteKeys(): Collection
    {
        return $this->noteKeys;
    }

    public function addNoteKey(NoteKey $noteKey): static
    {
        if (!$this->noteKeys->contains($noteKey)) {
            $this->noteKeys->add($noteKey);
            $noteKey->setNote($this);
        }

        return $this;
    }

    public function removeNoteKey(NoteKey $noteKey): static
    {
        if ($this->noteKeys->removeElement($noteKey)) {
            // set the owning side to null (unless already changed)
            if ($noteKey->getNote() === $this) {
                $noteKey->setNote(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Share>
     */
    public function getShares(): Collection
    {
        return $this->shares;
    }

    public function addShare(Share $share): static
    {
        if (!$this->shares->contains($share)) {
            $this->shares->add($share);
            $share->setNote($this);
        }

        return $this;
    }

    public function removeShare(Share $share): static
    {
        if ($this->shares->removeElement($share)) {
            // set the owning side to null (unless already changed)
            if ($share->getNote() === $this) {
                $share->setNote(null);
            }
        }

        return $this;
    }

    #[Groups(['note:read'])]
    public function getCipherTextBase64(): ?string
    {
        if (is_resource($this->cipherText)) {
            return base64_encode(stream_get_contents($this->cipherText));
        }

        return null;
    }

    #[Groups(['note:read'])]
    public function getIvBase64(): ?string
    {
        if (is_resource($this->iv)) {
            return base64_encode(stream_get_contents($this->iv));
        }

        return null;
    }
}
