<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add dog breeds to the database
 */
final class Version20260113230520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add dog breeds';
    }

    public function up(Schema $schema): void
    {
        $dogBreeds = [
            ['Beagle', false, false],
            ['Belgian Malinois', true, false],
            ['Boxer', true, false],
            ['Bulldog', false, false],
            ['Bullmastiff', true, false],
            ['Cavalier King Charles Spaniel', false, false],
            ['Chihuahua', false, false],
            ['Dachshund', false, false],
            ['Dalmatian', true, false],
            ['German Shepherd', true, false],
            ['German Shorthaired Pointer', false, false],
            ['Golden Retriever', false, false],
            ['Husky', true, false],
            ['Labrador Retriever', false, false],
            ['Maltese', false, false],
            ['Pomeranian', false, false],
            ['Poodle', false, false],
            ['Rottweiler', true, false],
            ['Shih Tzu', false, false],
            ['Yorkshire Terrier', false, false],
            ['Other', false, true],
            ['Unknown', false, true],
            ['Mixed', false, true],
        ];

        foreach ($dogBreeds as $breed) {
            $this->addSql(
                "
                INSERT INTO breed (
                    type,
                    name,
                    is_dangerous,
                    is_fallback,
                    date_created
                ) VALUES (?, ?, ?, ?, NOW())
                ",
                [
                    'dog',
                    $breed[0],
                    $breed[1] ? 1 : 0,
                    $breed[2] ? 1 : 0,
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'DELETE FROM breed WHERE type = ?',
            ['dog']
        );
    }
}