<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826181203 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable_partiel');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, livrablepartiel_aprenant_id INT DEFAULT NULL, INDEX IDX_37F072C5F7034EA7 (livrablepartiel_aprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE livrable_partiel ADD CONSTRAINT FK_37F072C5F7034EA7 FOREIGN KEY (livrablepartiel_aprenant_id) REFERENCES livrable_partiel_apprenant (id)');
    }
}
