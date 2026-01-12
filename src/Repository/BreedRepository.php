<?php

namespace App\Repository;

use App\Entity\Breed;
use App\Enum\PetTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Breed>
 */
class BreedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Breed::class);
    }

    /**
     * Find breeds by name and pet type for search functionality
     *
     * @return Breed[] Returns an array of Breed objects
     */
    public function findByNameAndPetType(string $name, PetTypeEnum $petType): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('LOWER(b.name) LIKE LOWER(:name)') // NOTE: not necessary for mysql, but database/collation agnostic
            ->andWhere('b.petType = :petType')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('petType', $petType)
            ->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
