<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503080148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum ADD forum_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD14721E40 FOREIGN KEY (forum_category_id) REFERENCES forum_category (id)');
        $this->addSql('CREATE INDEX IDX_852BBECD14721E40 ON forum (forum_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD14721E40');
        $this->addSql('DROP INDEX IDX_852BBECD14721E40 ON forum');
        $this->addSql('ALTER TABLE forum DROP forum_category_id');
    }
}
