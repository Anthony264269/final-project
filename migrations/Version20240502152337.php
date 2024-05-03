<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502152337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D22AEFBC1');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D22AEFBC1 FOREIGN KEY (my_garage_id) REFERENCES my_garage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D22AEFBC1');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D22AEFBC1 FOREIGN KEY (my_garage_id) REFERENCES my_garage (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
