<?php

namespace App\Tests\Entity;

use App\Entity\Breed;
use App\Entity\Pet;
use App\Enum\GenderEnum;
use App\Enum\PetTypeEnum;
use PHPUnit\Framework\TestCase;

class PetTest extends TestCase
{
    public function testPetCreation(): void
    {
        $breed = new Breed(PetTypeEnum::cat, 'Persian');
        $pet = new Pet('Fluffy', PetTypeEnum::cat, $breed, GenderEnum::female);

        $this->assertSame('Fluffy', $pet->getName());
        $this->assertSame(PetTypeEnum::cat, $pet->getPetType());
        $this->assertSame($breed, $pet->getBreed());
        $this->assertSame(GenderEnum::female, $pet->getGender());
        $this->assertInstanceOf(\DateTimeImmutable::class, $pet->getDateCreated());
    }

    public function testSetBirthDate(): void
    {
        $breed = new Breed(PetTypeEnum::dog, 'Labrador');
        $pet = new Pet('Max', PetTypeEnum::dog, $breed, GenderEnum::male);

        $birthDate = new \DateTime('2020-01-15');
        $pet->setBirthDate($birthDate);
        $pet->setBirthDateIsExact(true);

        $this->assertSame($birthDate, $pet->getBirthDate());
        $this->assertTrue($pet->isBirthDateIsExact());
    }

    public function testApproximateBirthDate(): void
    {
        $breed = new Breed(PetTypeEnum::cat, 'Siamese');
        $pet = new Pet('Whiskers', PetTypeEnum::cat, $breed, GenderEnum::male);

        $approximateBirthDate = new \DateTime('-2 years');
        $pet->setBirthDate($approximateBirthDate);
        $pet->setBirthDateIsExact(false);

        $this->assertFalse($pet->isBirthDateIsExact());
        $this->assertInstanceOf(\DateTime::class, $pet->getBirthDate());
    }
}
