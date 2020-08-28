<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827175811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etat_brief_groupe (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupes ADD etat_brief_groupe_id INT NOT NULL');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D97777C7A0 FOREIGN KEY (etat_brief_groupe_id) REFERENCES etat_brief_groupe (id)');
        $this->addSql('CREATE INDEX IDX_576366D97777C7A0 ON groupes (etat_brief_groupe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D97777C7A0');
        $this->addSql('DROP TABLE etat_brief_groupe');
        $this->addSql('DROP INDEX IDX_576366D97777C7A0 ON groupes');
        $this->addSql('ALTER TABLE groupes DROP etat_brief_groupe_id');
    }
}
