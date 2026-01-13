<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260113061959 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pet (uuid BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, pet_type VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, birth_date DATETIME DEFAULT NULL, birth_date_is_exact TINYINT DEFAULT NULL, breed_id BINARY(16) NOT NULL, INDEX IDX_E4529B85A8B4A30F (breed_id), PRIMARY KEY (uuid)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B85A8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (uuid)');
        $this->addSql('ALTER TABLE breed CHANGE is_dangerous is_dangerous TINYINT DEFAULT 0 NOT NULL, CHANGE date_created date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
