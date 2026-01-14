<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add cat breeds to the database
 */
final class Version20260112140001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cat breeds';
    }

    public function up(Schema $schema): void
    {
        $catBreeds = [
            ['Abyssinian', false, false],
            ['American Bobtail', false, false],
            ['American Curl', false, false],
            ['American Shorthair', false, false],
            ['Balinese', false, false],
            ['Bengal', false, false],
            ['Birman', false, false],
            ['Bombay', false, false],
            ['British Shorthair', false, false],
            ['Burmese', false, false],
            ['Cornish Rex', false, false],
            ['Devon Rex', false, false],
            ['Egyptian Mau', false, false],
            ['Exotic Shorthair', false, false],
            ['Himalayan', false, false],
            ['Japanese Bobtail', false, false],
            ['Maine Coon', false, false],
            ['Norwegian Forest Cat', false, false],
            ['Persian', false, false],
            ['Ragdoll', false, false],
            ['Other', false, true],      // fallback
            ['Unknown', false, true],    // fallback
            ['Mixed', false, true],      // fallback
        ];

        foreach ($catBreeds as $breed) {
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
                    'cat',
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
            ['cat']
        );
    }
}
