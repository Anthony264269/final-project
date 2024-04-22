<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417060247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule ADD image_url_id INT DEFAULT NULL, DROP image_url');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D2ED2842F FOREIGN KEY (image_url_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_292FFF1D2ED2842F ON vehicule (image_url_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D2ED2842F');
        $this->addSql('DROP INDEX UNIQ_292FFF1D2ED2842F ON vehicule');
        $this->addSql('ALTER TABLE vehicule ADD image_url VARCHAR(255) DEFAULT NULL, DROP image_url_id');
    }
}
