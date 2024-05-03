<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503142112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD file_id INT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64993CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64993CB796C ON user (file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64993CB796C');
        $this->addSql('DROP INDEX UNIQ_8D93D64993CB796C ON user');
        $this->addSql('ALTER TABLE user DROP file_id');
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
