<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240503115731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adjusts forum_id in comment table and updates deleted_at in forum table.';
    }

    public function up(Schema $schema): void
    {
        // Check if the foreign key constraint already exists before adding it
        if (!$schema->getTable('comment')->hasForeignKey('FK_9474526C29CCBAD0')) {
            $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        }

        // Check if the index already exists before creating it
        if (!$schema->getTable('comment')->hasIndex('IDX_9474526C29CCBAD0')) {
            $this->addSql('CREATE INDEX IDX_9474526C29CCBAD0 ON comment (forum_id)');
        }

        // Update 'deleted_at' column modification in the 'forum' table
        $this->addSql('UPDATE forum SET deleted_at = NOW() WHERE deleted_at IS NULL');
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C29CCBAD0');
        $this->addSql('DROP INDEX IDX_9474526C29CCBAD0 ON comment');
        $this->addSql('ALTER TABLE comment DROP forum_id');

        // Restore 'deleted_at' column modification in the 'forum' table
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
