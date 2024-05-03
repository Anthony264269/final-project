<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503141131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file ADD forum_id INT');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361029CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('CREATE INDEX IDX_8C9F361029CCBAD0 ON file (forum_id)');
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361029CCBAD0');
        $this->addSql('DROP INDEX IDX_8C9F361029CCBAD0 ON file');
        $this->addSql('ALTER TABLE file DROP forum_id');
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
