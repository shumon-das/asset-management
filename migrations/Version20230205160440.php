<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205160440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, entity_name VARCHAR(255) NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_deleted TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CBB3CCD50A ON location (office_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE upload');
        $this->addSql('DROP INDEX UNIQ_5E9E89CBB3CCD50A ON location');
    }
}
