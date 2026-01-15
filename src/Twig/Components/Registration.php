<?php

namespace App\Twig\Components;

use App\Entity\Pet;
use App\Enum\GenderEnum;
use App\Enum\PetTypeEnum;
use App\Repository\BreedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

#[AsLiveComponent]
final class Registration
{
    use DefaultActionTrait;

    public function __construct(
        private BreedRepository $breedRepository,
        private EntityManagerInterface $entityManager,
        private RouterInterface $router,
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

    #[LiveProp(writable: true)]
    public string $ageMode = 'approximate'; // 'exact' or 'approximate'

    #[LiveProp(writable: true)]
    public string $birthDate = ''; // For exact DOB input

    #[LiveProp(writable: true)]
    public string $approximateAge = ''; // For approximate age dropdown

    #[LiveProp]
    public array $errors = [];

    private ?Pet $savedPet = null;

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
     * Get breeds ordered by fallback status (fallback breeds first)
     */
    public function getBreedsOrderedByFallback(): array
    {
        if (!$this->petType) {
            return [];
        }

        $petTypeEnum = PetTypeEnum::from($this->petType);
        return $this->breedRepository->findByPetTypeWithFallbackFirst($petTypeEnum);
    }

    /**
     * Get the selected breed entity
     */
    public function getSelectedBreed(): ?object
    {
        if (!$this->breed || !$this->petType) {
            return null;
        }

        $petTypeEnum = PetTypeEnum::from($this->petType);
        return $this->breedRepository->findOneBy([
            'name' => $this->breed,
            'petType' => $petTypeEnum,
        ]);
    }

    /**
     * Get age options for the dropdown
     */
    public function getAgeOptions(): array
    {
        $options = ['0.5' => 'Under 1 year'];
        for ($i = 1; $i <= 20; $i++) {
            $options[(string)$i] = $i === 1 ? '1 year' : "$i years";
        }
        return $options;
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
    public function submit()
    {
        // Clear previous errors
        $this->errors = [];

        // Validate all required fields
        if (empty($this->petName)) {
            $this->errors['petName'] = 'Pet name is required';
        }

        if (empty($this->petType)) {
            $this->errors['petType'] = 'Pet type is required';
        }

        if (empty($this->breed)) {
            $this->errors['breed'] = 'Breed is required';
        }

        if (empty($this->gender)) {
            $this->errors['gender'] = 'Gender is required';
        }

        // Validate age field based on mode
        if ($this->ageMode === 'exact' && empty($this->birthDate)) {
            $this->errors['birthDate'] = 'Birth date is required';
        }
        if ($this->ageMode === 'approximate' && empty($this->approximateAge)) {
            $this->errors['approximateAge'] = 'Age is required';
        }

        // If there are errors, stop here
        if (!empty($this->errors)) {
            return;
        }

        // Get breed entity
        $breedEntity = $this->getSelectedBreed();
        if (!$breedEntity) {
            $this->errors['breed'] = 'Invalid breed selected';
            return;
        }

        // Calculate birth_date based on age mode
        $birthDate = new \DateTime();
        $birthDateIsExact = false;

        if ($this->ageMode === 'exact') {
            $birthDate = new \DateTime($this->birthDate);
            $birthDateIsExact = true;
        } else {
            // Approximate: subtract years from current date
            $years = (float) $this->approximateAge;
            $birthDate->modify('-' . $years . ' years');
        }

        // Create and save pet
        $pet = new Pet(
            $this->petName,
            PetTypeEnum::from($this->petType),
            $breedEntity,
            GenderEnum::from($this->gender)
        );
        $pet->setBirthDate($birthDate);
        $pet->setBirthDateIsExact($birthDateIsExact);

        $this->entityManager->persist($pet);
        $this->entityManager->flush();

        // Redirect to confirmation page instead of trying to update state
        $url = $this->router->generate('app_confirmation', ['id' => $pet->getId()]);
        return new RedirectResponse($url);
    }

    #[LiveAction]
    public function reset(): void
    {
        $this->petName = '';
        $this->petType = '';
        $this->breed = '';
        $this->gender = '';
        $this->ageMode = 'approximate';
        $this->birthDate = '';
        $this->approximateAge = '';
    }
}
