<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328091927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscriber ADD forum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscriber ADD CONSTRAINT FK_AD005B6929CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('CREATE INDEX IDX_AD005B6929CCBAD0 ON subscriber (forum_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscriber DROP FOREIGN KEY FK_AD005B6929CCBAD0');
        $this->addSql('DROP INDEX IDX_AD005B6929CCBAD0 ON subscriber');
        $this->addSql('ALTER TABLE subscriber DROP forum_id');
    }
}
