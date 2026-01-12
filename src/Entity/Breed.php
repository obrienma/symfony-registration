<?php

namespace App\Entity;

use App\Enum\PetTypeEnum;
use App\Repository\BreedRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: BreedRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Breed
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'type', enumType: PetTypeEnum::class, nullable: false)]
    private PetTypeEnum $petType;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'is_dangerous', nullable: false, options: ['default' => false])]
    private bool $isDangerous = false;

    #[ORM\Column(name: 'date_created', type: Types::DATETIMETZ_IMMUTABLE, nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $dateCreated;

    public function __construct(PetTypeEnum $petType, string $name, bool $isDangerous = false)
    {
        $this->petType = $petType;
        $this->name = $name;
        $this->isDangerous = $isDangerous;
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

    public function getUUID(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUUID(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
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

    public function isDangerous(): bool
    {
        return $this->isDangerous;
    }

    public function setIsDangerous(bool $isDangerous): static
    {
        $this->isDangerous = $isDangerous;

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
