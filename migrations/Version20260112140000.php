<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add dog breeds to the database
 */
final class Version20260112140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add dog breeds';
    }

    public function up(Schema $schema): void
    {
        $dogBreeds = [
            ['Beagle', false],
            ['Belgian Malinois', true],
            ['Boxer', true],
            ['Bulldog', false],
            ['Bullmastiff', true],
            ['Cavalier King Charles Spaniel', false],
            ['Chihuahua', false],
            ['Dachshund', false],
            ['Dalmatian', true],
            ['German Shepherd', true],
            ['German Shorthaired Pointer', false],
            ['Golden Retriever', false],
            ['Husky', true],
            ['Labrador Retriever', false],
            ['Maltese', false],
            ['Pomeranian', false],
            ['Poodle', false],
            ['Rottweiler', true],
            ['Shih Tzu', false],
            ['Yorkshire Terrier', false],
            ['Other', false],
            ['Unknown', false],
            ['Mixed', false],
        ];

        foreach ($dogBreeds as $breed) {
            $this->addSql(
                'INSERT INTO breed (uuid, type, name, is_dangerous, date_created) VALUES (UNHEX(REPLACE(UUID(), \'-\', \'\')), ?, ?, ?, NOW())',
                [
                    'dog',
                    $breed[0],
                    $breed[1] ? 1 : 0,
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM breed WHERE type = ?', ['dog']);
    }
}
