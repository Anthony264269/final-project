<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429123649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361093CB796C');
        $this->addSql('DROP INDEX IDX_8C9F361093CB796C ON file');
        $this->addSql('ALTER TABLE file DROP file_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361093CB796C FOREIGN KEY (file_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8C9F361093CB796C ON file (file_id)');
    }
}
