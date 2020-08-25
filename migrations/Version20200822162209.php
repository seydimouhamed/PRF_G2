<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822162209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_partiels DROP FOREIGN KEY FK_F0370946757FABFF');
        $this->addSql('DROP INDEX IDX_F0370946757FABFF ON livrable_partiels');
        $this->addSql('ALTER TABLE livrable_partiels DROP brief_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_partiels ADD brief_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_partiels ADD CONSTRAINT FK_F0370946757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('CREATE INDEX IDX_F0370946757FABFF ON livrable_partiels (brief_id)');
    }
}
