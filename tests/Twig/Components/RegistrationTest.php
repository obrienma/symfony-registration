<?php

namespace App\Tests\Twig\Components;

use App\DataFixtures\BreedFixtures;
use App\Entity\Breed;
use App\Entity\Pet;
use App\Enum\GenderEnum;
use App\Enum\PetTypeEnum;
use App\Repository\BreedRepository;
use App\Twig\Components\Registration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RegistrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private BreedRepository $breedRepository;
    private RouterInterface $router;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->breedRepository = $container->get(BreedRepository::class);
        $this->router = $container->get(RouterInterface::class);

        $this->loadFixtures();
    }

    private function loadFixtures(): void
    {
        $fixtures = new BreedFixtures();
        $fixtures->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        $this->entityManager->createQuery('DELETE FROM App\Entity\Pet')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Breed')->execute();
        $this->entityManager->close();

        parent::tearDown();
    }

    private function createRegistrationComponent(): Registration
    {
        return new Registration(
            $this->breedRepository,
            $this->entityManager,
            $this->router
        );
    }

    public function testGetFilteredBreedsReturnsCatBreeds(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petType = 'cat';

        $breeds = $component->getFilteredBreeds();

        $this->assertNotEmpty($breeds);
        foreach ($breeds as $breed) {
            $this->assertInstanceOf(Breed::class, $breed);
            $this->assertSame(PetTypeEnum::cat, $breed->getPetType());
        }
    }

    public function testGetFilteredBreedsReturnsDogBreeds(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petType = 'dog';

        $breeds = $component->getFilteredBreeds();

        $this->assertNotEmpty($breeds);
        foreach ($breeds as $breed) {
            $this->assertInstanceOf(Breed::class, $breed);
            $this->assertSame(PetTypeEnum::dog, $breed->getPetType());
        }
    }

    public function testGetFilteredBreedsReturnsEmptyWhenNoPetType(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petType = '';

        $breeds = $component->getFilteredBreeds();

        $this->assertEmpty($breeds);
    }

    public function testGetBreedsOrderedByFallbackPutsFallbackFirst(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petType = 'cat';

        $breeds = $component->getBreedsOrderedByFallback();

        $this->assertNotEmpty($breeds);
        // First breed should be the fallback
        $firstBreed = $breeds[0];
        $this->assertTrue($firstBreed->getIsFallback());
    }

    public function testValidationFailsWithEmptyPetName(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = '';
        $component->petType = 'cat';
        $component->breed = 'Persian';
        $component->gender = 'female';
        $component->ageMode = 'approximate';
        $component->approximateAge = '2';

        $result = $component->submit();

        $this->assertNull($result);
        $this->assertArrayHasKey('petName', $component->errors);
        $this->assertSame('Pet name is required', $component->errors['petName']);
    }

    public function testValidationFailsWithEmptyPetType(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = 'Fluffy';
        $component->petType = '';
        $component->breed = 'Persian';
        $component->gender = 'female';
        $component->ageMode = 'approximate';
        $component->approximateAge = '2';

        $result = $component->submit();

        $this->assertNull($result);
        $this->assertArrayHasKey('petType', $component->errors);
    }

    public function testValidationFailsWithEmptyBreed(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = 'Fluffy';
        $component->petType = 'cat';
        $component->breed = '';
        $component->gender = 'female';
        $component->ageMode = 'approximate';
        $component->approximateAge = '2';

        $result = $component->submit();

        $this->assertNull($result);
        $this->assertArrayHasKey('breed', $component->errors);
    }

    public function testValidationFailsWithEmptyGender(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = 'Fluffy';
        $component->petType = 'cat';
        $component->breed = 'Persian';
        $component->gender = '';
        $component->ageMode = 'approximate';
        $component->approximateAge = '2';

        $result = $component->submit();

        $this->assertNull($result);
        $this->assertArrayHasKey('gender', $component->errors);
    }

    public function testValidationFailsWithEmptyApproximateAge(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = 'Fluffy';
        $component->petType = 'cat';
        $component->breed = 'Persian';
        $component->gender = 'female';
        $component->ageMode = 'approximate';
        $component->approximateAge = '';

        $result = $component->submit();

        $this->assertNull($result);
        $this->assertArrayHasKey('approximateAge', $component->errors);
    }

    public function testValidationFailsWithEmptyBirthDate(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = 'Fluffy';
        $component->petType = 'cat';
        $component->breed = 'Persian';
        $component->gender = 'female';
        $component->ageMode = 'exact';
        $component->birthDate = '';

        $result = $component->submit();

        $this->assertNull($result);
        $this->assertArrayHasKey('birthDate', $component->errors);
    }

    public function testSuccessfulSubmissionWithApproximateAge(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = 'Fluffy';
        $component->petType = 'cat';
        $component->breed = 'Persian';
        $component->gender = 'female';
        $component->ageMode = 'approximate';
        $component->approximateAge = '2';

        $result = $component->submit();

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEmpty($component->errors);

        // Verify pet was saved
        $pets = $this->entityManager->getRepository(Pet::class)->findAll();
        $this->assertCount(1, $pets);
        $this->assertSame('Fluffy', $pets[0]->getName());
        $this->assertFalse($pets[0]->isBirthDateIsExact());
    }

    public function testSuccessfulSubmissionWithExactBirthDate(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petName = 'Max';
        $component->petType = 'dog';
        $component->breed = 'Labrador Retriever';
        $component->gender = 'male';
        $component->ageMode = 'exact';
        $component->birthDate = '2020-05-15';

        $result = $component->submit();

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEmpty($component->errors);

        // Verify pet was saved
        $pets = $this->entityManager->getRepository(Pet::class)->findAll();
        $this->assertCount(1, $pets);
        $this->assertSame('Max', $pets[0]->getName());
        $this->assertTrue($pets[0]->isBirthDateIsExact());
        $this->assertSame('2020-05-15', $pets[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testGetSelectedBreedReturnsCorrectBreed(): void
    {
        $component = $this->createRegistrationComponent();
        $component->petType = 'dog';
        $component->breed = 'Pit Bull';

        $breed = $component->getSelectedBreed();

        $this->assertInstanceOf(Breed::class, $breed);
        $this->assertSame('Pit Bull', $breed->getName());
        $this->assertTrue($breed->getIsDangerous());
    }

    public function testGetAgeOptionsReturnsCorrectStructure(): void
    {
        $component = $this->createRegistrationComponent();
        $options = $component->getAgeOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('0.5', $options);
        $this->assertArrayHasKey('1', $options);
        $this->assertArrayHasKey('10', $options);
        $this->assertSame('Under 1 year', $options['0.5']);
        $this->assertSame('1 year', $options['1']);
    }
}
