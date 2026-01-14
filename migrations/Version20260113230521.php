<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add dog breeds to the database
 */
final class Version20260113230521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add dog breeds';
    }

    public function up(Schema $schema): void
    {
        $catBreeds = [
            ['Abyssinian', false],
            ['American Bobtail', false],
            ['American Curl', false],
            ['American Shorthair', false],
            ['Balinese', false],
            ['Bengal', false],
            ['Birman', false],
            ['Bombay', false],
            ['British Shorthair', false],
            ['Burmese', false],
            ['Cornish Rex', false],
            ['Devon Rex', false],
            ['Egyptian Mau', false],
            ['Exotic Shorthair', false],
            ['Himalayan', false],
            ['Japanese Bobtail', false],
            ['Maine Coon', false],
            ['Norwegian Forest Cat', false],
            ['Persian', false],
            ['Ragdoll', false],
            ['Other', false],
            ['Unknown', false],
            ['Mixed', false],
        ];

        foreach ($catBreeds as $breed) {
            $this->addSql(
                "
                INSERT INTO breed (
                    type,
                    name,
                    is_dangerous,
                    date_created
                ) VALUES (?, ?, ?, NOW())
                ",
                [
                    'cat',
                    $breed[0],
                    $breed[1] ? 1 : 0,
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