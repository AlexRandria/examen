<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121090751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code_postal INT NOT NULL, population INT NOT NULL, image VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_43C3D9C3CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3CCF9E01E');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE ville');
    }
}
