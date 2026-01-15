<?php

namespace App\DataFixtures;

use App\Entity\Breed;
use App\Enum\PetTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BreedFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Cat breeds
        $persian = new Breed(PetTypeEnum::cat, 'Persian');
        $manager->persist($persian);

        $siamese = new Breed(PetTypeEnum::cat, 'Siamese');
        $manager->persist($siamese);

        $maineCoon = new Breed(PetTypeEnum::cat, 'Maine Coon');
        $manager->persist($maineCoon);

        $unknownCat = new Breed(PetTypeEnum::cat, 'Unknown Cat Breed', false, true);
        $manager->persist($unknownCat);

        // Dog breeds
        $labrador = new Breed(PetTypeEnum::dog, 'Labrador Retriever');
        $manager->persist($labrador);

        $germanShepherd = new Breed(PetTypeEnum::dog, 'German Shepherd');
        $manager->persist($germanShepherd);

        $pitBull = new Breed(PetTypeEnum::dog, 'Pit Bull', true, false);
        $manager->persist($pitBull);

        $rottweiler = new Breed(PetTypeEnum::dog, 'Rottweiler', true, false);
        $manager->persist($rottweiler);

        $unknownDog = new Breed(PetTypeEnum::dog, 'Unknown Dog Breed', false, true);
        $manager->persist($unknownDog);

        $manager->flush();
    }
}
