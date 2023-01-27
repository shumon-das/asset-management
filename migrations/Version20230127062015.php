<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230127062015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assigning_assets CHANGE product product VARCHAR(255) NOT NULL, CHANGE vendor vendor INT NOT NULL, CHANGE asset_name asset_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE department CHANGE department_name department_name VARCHAR(255) NOT NULL, CHANGE contact_person contact_person VARCHAR(255) NOT NULL, CHANGE contact_person_email contact_person_email VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD1DE18AFC391953 ON department (department_name)');
        $this->addSql('ALTER TABLE location ADD office_name VARCHAR(255) NOT NULL, DROP offic_name, CHANGE country country VARCHAR(255) NOT NULL, CHANGE state state VARCHAR(255) NOT NULL, CHANGE city city VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assigning_assets CHANGE product product VARCHAR(255) DEFAULT NULL, CHANGE vendor vendor INT DEFAULT NULL, CHANGE asset_name asset_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_CD1DE18AFC391953 ON department');
        $this->addSql('ALTER TABLE department CHANGE department_name department_name VARCHAR(255) DEFAULT NULL, CHANGE contact_person contact_person VARCHAR(255) DEFAULT NULL, CHANGE contact_person_email contact_person_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD offic_name VARCHAR(255) DEFAULT NULL, DROP office_name, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE state state VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
    }
}
