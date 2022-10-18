<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018200343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assets DROP FOREIGN KEY FK_79D17D8EBE6903FD');
        $this->addSql('DROP INDEX IDX_79D17D8EBE6903FD ON assets');
        $this->addSql('ALTER TABLE assets ADD product_category VARCHAR(255) DEFAULT NULL, ADD product_type VARCHAR(255) DEFAULT NULL, ADD product VARCHAR(255) DEFAULT NULL, ADD asset_name VARCHAR(255) DEFAULT NULL, ADD seriul_number VARCHAR(255) DEFAULT NULL, ADD price VARCHAR(255) DEFAULT NULL, ADD description_type VARCHAR(255) DEFAULT NULL, ADD location VARCHAR(255) DEFAULT NULL, ADD purchase_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD warranty_expiry_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD purchase_type VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD useful_life VARCHAR(255) DEFAULT NULL, ADD residual_value VARCHAR(255) DEFAULT NULL, ADD rate VARCHAR(255) DEFAULT NULL, ADD created_by INT DEFAULT NULL, ADD updated_by INT DEFAULT NULL, ADD deleted_by INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD status TINYINT(1) DEFAULT NULL, ADD is_deleted TINYINT(1) DEFAULT NULL, CHANGE product_category_id vendor INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assets ADD product_category_id INT DEFAULT NULL, DROP product_category, DROP product_type, DROP product, DROP vendor, DROP asset_name, DROP seriul_number, DROP price, DROP description_type, DROP location, DROP purchase_date, DROP warranty_expiry_date, DROP purchase_type, DROP description, DROP useful_life, DROP residual_value, DROP rate, DROP created_by, DROP updated_by, DROP deleted_by, DROP created_at, DROP updated_at, DROP deleted_at, DROP status, DROP is_deleted');
        $this->addSql('ALTER TABLE assets ADD CONSTRAINT FK_79D17D8EBE6903FD FOREIGN KEY (product_category_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_79D17D8EBE6903FD ON assets (product_category_id)');
    }
}
