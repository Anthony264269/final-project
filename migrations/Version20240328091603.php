<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328091603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD subscriber_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7808B1AD FOREIGN KEY (subscriber_id) REFERENCES subscriber (id)');
        $this->addSql('CREATE INDEX IDX_9474526C7808B1AD ON comment (subscriber_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7808B1AD');
        $this->addSql('DROP INDEX IDX_9474526C7808B1AD ON comment');
        $this->addSql('ALTER TABLE comment DROP subscriber_id');
    }
}
