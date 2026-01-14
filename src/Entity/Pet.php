<?php

namespace App\Entity;

use App\Enum\GenderEnum;
use App\Enum\PetTypeEnum;
use App\Repository\PetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'pet_type', enumType: PetTypeEnum::class, nullable: false)]
    private PetTypeEnum $petType;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id')]
    private Breed $breed;

    #[ORM\Column(name: 'gender', enumType: GenderEnum::class, nullable: false)]
    private GenderEnum $gender;

    #[ORM\Column(name: 'date_created', type: Types::DATETIMETZ_IMMUTABLE, nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $dateCreated;

    #[ORM\Column(name: 'birth_date', type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTime $birthDate = null;

    #[ORM\Column(name: 'birth_date_is_exact', nullable: true)]
    private ?bool $birthDateIsExact = null;

    public function __construct(string $name, PetTypeEnum $petType, Breed $breed, GenderEnum $gender)
    {
        $this->name = $name;
        $this->petType = $petType;
        $this->breed = $breed;
        $this->gender = $gender;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getBreed(): ?Breed
    {
        return $this->breed;
    }

    public function setBreed(Breed $breed): static
    {
        $this->breed = $breed;

        return $this;
    }

    public function getGender(): GenderEnum
    {
        return $this->gender;
    }

    public function setGender(GenderEnum $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTime $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function isBirthDateIsExact(): ?bool
    {
        return $this->birthDateIsExact;
    }

    public function setBirthDateIsExact(?bool $birthDateIsExact): static
    {
        $this->birthDateIsExact = $birthDateIsExact;

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
