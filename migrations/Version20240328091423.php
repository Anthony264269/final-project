<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328091423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE my_garage ADD subscriber_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE my_garage ADD CONSTRAINT FK_343A1107808B1AD FOREIGN KEY (subscriber_id) REFERENCES subscriber (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_343A1107808B1AD ON my_garage (subscriber_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE my_garage DROP FOREIGN KEY FK_343A1107808B1AD');
        $this->addSql('DROP INDEX UNIQ_343A1107808B1AD ON my_garage');
        $this->addSql('ALTER TABLE my_garage DROP subscriber_id');
    }
}
