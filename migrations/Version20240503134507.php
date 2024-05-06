<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240503134507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ($schema->getTable('file')->hasColumn('user_id')) {
            $this->addSql('ALTER TABLE file DROP user_id');
        }
        // Ajoutez ici d'autres instructions pour la migration
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // Ajoutez ici les instructions pour restaurer la colonne user_id
    }
}
