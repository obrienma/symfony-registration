<?php

namespace App\Entity;

use App\Enum\PetTypeEnum;
use App\Repository\BreedRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BreedRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Breed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'type', enumType: PetTypeEnum::class, nullable: false)]
    private PetTypeEnum $petType;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'is_dangerous', nullable: false, options: ['default' => false])]
    private bool $isDangerous = false;

    #[ORM\Column(name: 'is_fallback', nullable: false, options: ['default' => false])]
    private bool $isFallback = false;

    #[ORM\Column(name: 'date_created', type: Types::DATETIMETZ_IMMUTABLE, nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $dateCreated;

    public function __construct(PetTypeEnum $petType, string $name, bool $isDangerous = false, bool $isFallback = false)
    {
        $this->petType = $petType;
        $this->name = $name;
        $this->isDangerous = $isDangerous;
        $this->isFallback = $isFallback;
        $this->dateCreated = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        if (!isset($this->dateCreated)) {
            $this->dateCreated = new \DateTimeImmutable();
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPetType(): PetTypeEnum
    {
        return $this->petType;
    }

    public function setPetType(PetTypeEnum $petType): static
    {
        $this->petType = $petType;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIsDangerous(): bool
    {
        return $this->isDangerous;
    }

    public function setIsDangerous(bool $isDangerous): static
    {
        $this->isDangerous = $isDangerous;

        return $this;
    }
    public function getIsFallback(): bool
    {
        return $this->isFallback;
    }

    public function setIsFallback(bool $isFallback): static
    {
        $this->isFallback = $isFallback;

        return $this;
    }

    public function getDateCreated(): \DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeImmutable $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
}
