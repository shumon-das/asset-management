<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220164811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assets CHANGE seriul_number serial_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE department ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD created_by INT NOT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_by INT DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD deleted_by INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assets CHANGE serial_number seriul_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE department DROP created_at, DROP created_by, DROP updated_at, DROP updated_by, DROP deleted_at, DROP deleted_by');
    }
}
