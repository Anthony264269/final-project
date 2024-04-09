<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328091106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscriber ADD my_garage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscriber ADD CONSTRAINT FK_AD005B6922AEFBC1 FOREIGN KEY (my_garage_id) REFERENCES my_garage (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD005B6922AEFBC1 ON subscriber (my_garage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscriber DROP FOREIGN KEY FK_AD005B6922AEFBC1');
        $this->addSql('DROP INDEX UNIQ_AD005B6922AEFBC1 ON subscriber');
        $this->addSql('ALTER TABLE subscriber DROP my_garage_id');
    }
}
