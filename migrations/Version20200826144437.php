<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826144437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable');
        $this->addSql('DROP TABLE livrable_attendu');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_attendu_id INT DEFAULT NULL, url LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9E78008C75180ACC (livrable_attendu_id), INDEX IDX_9E78008CC5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livrable_attendu (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE livrable ADD CONSTRAINT FK_9E78008C75180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendus (id)');
        $this->addSql('ALTER TABLE livrable ADD CONSTRAINT FK_9E78008CC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
    }
}
