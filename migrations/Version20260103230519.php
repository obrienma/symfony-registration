<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260103230519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE breed (
                id INT AUTO_INCREMENT NOT NULL,
                type VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                is_dangerous TINYINT DEFAULT 0 NOT NULL,
                is_fallback TINYINT DEFAULT 0 NOT NULL,
                date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        ");

        $this->addSql("
            CREATE TABLE pet (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                pet_type VARCHAR(255) NOT NULL,
                gender VARCHAR(255) NOT NULL,
                date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                birth_date DATETIME DEFAULT NULL,
                birth_date_is_exact TINYINT DEFAULT NULL,
                breed_id INT NOT NULL,
                INDEX IDX_E4529B85A8B4A30F (breed_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        ");

        $this->addSql("
            CREATE TABLE messenger_messages (
                id BIGINT AUTO_INCREMENT NOT NULL,
                body LONGTEXT NOT NULL,
                headers LONGTEXT NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at DATETIME NOT NULL,
                available_at DATETIME NOT NULL,
                delivered_at DATETIME DEFAULT NULL,
                INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (
                    queue_name,
                    available_at,
                    delivered_at,
                    id
                ),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        ");

        $this->addSql("
            ALTER TABLE pet
            ADD CONSTRAINT FK_E4529B85A8B4A30F
            FOREIGN KEY (breed_id) REFERENCES breed (id)
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B85A8B4A30F');
        $this->addSql('DROP TABLE breed');
        $this->addSql('DROP TABLE pet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
