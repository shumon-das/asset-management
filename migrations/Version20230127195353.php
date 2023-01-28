<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230127195353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admins (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assets (id INT AUTO_INCREMENT NOT NULL, product_category VARCHAR(255) DEFAULT NULL, product_type VARCHAR(255) DEFAULT NULL, product VARCHAR(255) NOT NULL, vendor INT NOT NULL, asset_name VARCHAR(255) NOT NULL, serial_number VARCHAR(255) DEFAULT NULL, price VARCHAR(255) DEFAULT NULL, description_type VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, purchase_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', warranty_expiry_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', purchase_type VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, useful_life VARCHAR(255) DEFAULT NULL, residual_value VARCHAR(255) DEFAULT NULL, rate VARCHAR(255) DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, deleted_by INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status TINYINT(1) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assigning_assets (id INT AUTO_INCREMENT NOT NULL, product_category VARCHAR(255) DEFAULT NULL, product_type VARCHAR(255) DEFAULT NULL, product VARCHAR(255) NOT NULL, vendor INT NOT NULL, location VARCHAR(255) DEFAULT NULL, asset_name VARCHAR(255) NOT NULL, department VARCHAR(255) DEFAULT NULL, assign_to INT NOT NULL, description LONGTEXT DEFAULT NULL, assign_component VARCHAR(255) DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, deleted_by INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status TINYINT(1) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, parent INT DEFAULT NULL, childes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', badge VARCHAR(255) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by INT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_by INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, department_name VARCHAR(255) NOT NULL, contact_person VARCHAR(255) NOT NULL, contact_person_email VARCHAR(255) NOT NULL, contact_person_phone VARCHAR(255) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by INT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_by INT DEFAULT NULL, UNIQUE INDEX UNIQ_CD1DE18AFC391953 (department_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT NULL, contact_no VARCHAR(255) DEFAULT NULL, location INT DEFAULT NULL, reporting_manager INT NOT NULL, department INT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT NOT NULL, deleted_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, office_name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) DEFAULT NULL, contact_person_name VARCHAR(255) DEFAULT NULL, address1 VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT NOT NULL, updated_by INT DEFAULT NULL, deleted_by INT DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, manufacturer VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, status TINYINT(1) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_by VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendors (id INT AUTO_INCREMENT NOT NULL, serial_number VARCHAR(255) DEFAULT NULL, vendor_name VARCHAR(255) DEFAULT NULL, contact_person VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, gstin_no VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', designation VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_by INT NOT NULL, deleted_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE admins');
        $this->addSql('DROP TABLE assets');
        $this->addSql('DROP TABLE assigning_assets');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE vendors');
    }
}
