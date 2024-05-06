<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506071815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9474526C93CB796C ON comment (file_id)');
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C93CB796C');
        $this->addSql('DROP INDEX UNIQ_9474526C93CB796C ON comment');
        $this->addSql('ALTER TABLE comment DROP file_id');
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
