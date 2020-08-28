<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827162324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etatbrief_groupe (id INT AUTO_INCREMENT NOT NULL, briefs_id INT DEFAULT NULL, groupe_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, brief VARCHAR(255) NOT NULL, INDEX IDX_675F3352CA062D03 (briefs_id), INDEX IDX_675F33527A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etatbrief_groupe ADD CONSTRAINT FK_675F3352CA062D03 FOREIGN KEY (briefs_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE etatbrief_groupe ADD CONSTRAINT FK_675F33527A45358C FOREIGN KEY (groupe_id) REFERENCES groupes (id)');
        $this->addSql('DROP TABLE brief_groupes');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brief_groupes (brief_id INT NOT NULL, groupes_id INT NOT NULL, INDEX IDX_DC8DF196305371B (groupes_id), INDEX IDX_DC8DF196757FABFF (brief_id), PRIMARY KEY(brief_id, groupes_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE brief_groupes ADD CONSTRAINT FK_DC8DF196305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_groupes ADD CONSTRAINT FK_DC8DF196757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE etatbrief_groupe');
    }
}
