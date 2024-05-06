<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240503141131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Supprimer les lignes qui ajoutent la colonne forum_id
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // Ajoutez ici les instructions pour annuler les modifications, si nécessaire
        $this->addSql('ALTER TABLE file ADD forum_id INT');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361029CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('CREATE INDEX IDX_8C9F361029CCBAD0 ON file (forum_id)');
        $this->addSql('ALTER TABLE forum CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
