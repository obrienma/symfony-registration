<?php

namespace App\Twig\Components;

use App\Enum\PetTypeEnum;
use App\Repository\BreedRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Registration
{
    use DefaultActionTrait;

    public function __construct(
        private BreedRepository $breedRepository,
    ) {
    }

    #[LiveProp(writable: true)]
    public string $petName = '';

    #[LiveProp(writable: true)]
    public string $petType = '';

    #[LiveProp(writable: true)]
    public string $breed = '';

    #[LiveProp(writable: true)]
    public string $breedOption = '';

    #[LiveProp(writable: true)]
    public string $gender = '';

    #[LiveProp]
    public bool $submitted = false;

    /**
     * Get filtered breeds based on selected petType
     */
    public function getFilteredBreeds(): array
    {
        if (!$this->petType) {
            return [];
        }

        $petTypeEnum = PetTypeEnum::from($this->petType);
        return $this->breedRepository->findBy(['petType' => $petTypeEnum]);
    }

    /**
     * Get breeds matching search query
     */
    public function searchBreeds(string $query): array
    {
        if (!$this->petType || empty($query)) {
            return [];
        }

        $petTypeEnum = PetTypeEnum::from($this->petType);
        return $this->breedRepository->findByNameAndPetType($query, $petTypeEnum);
    }

    /**
     * Get special breeds (Other, Unknown, Mix) for when no match is found
     */
    public function getSpecialBreeds(): array
    {
        if (!$this->petType) {
            return [];
        }

        $petTypeEnum = PetTypeEnum::from($this->petType);
        $specialNames = ['Other', 'Unknown', 'Mix'];
        
        return $this->breedRepository->createQueryBuilder('b')
            ->where('b.name IN (:names)')
            ->andWhere('b.petType = :petType')
            ->setParameter('names', $specialNames)
            ->setParameter('petType', $petTypeEnum)
            ->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    #[LiveAction]
    public function submit(): void
    {
        $this->submitted = true;
        // Handle form submission here
    }
}
