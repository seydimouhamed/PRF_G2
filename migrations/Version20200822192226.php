<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822192226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_attendu_id INT DEFAULT NULL, url LONGTEXT NOT NULL, INDEX IDX_9E78008CC5697D6D (apprenant_id), INDEX IDX_9E78008C75180ACC (livrable_attendu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livrable ADD CONSTRAINT FK_9E78008CC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable ADD CONSTRAINT FK_9E78008C75180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendus (id)');
        $this->addSql('DROP TABLE apprenant_livrable_attendus');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrable_attendus (apprenant_id INT NOT NULL, livrable_attendus_id INT NOT NULL, INDEX IDX_BAD746EDC5697D6D (apprenant_id), INDEX IDX_BAD746ED75D62BB4 (livrable_attendus_id), PRIMARY KEY(apprenant_id, livrable_attendus_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE apprenant_livrable_attendus ADD CONSTRAINT FK_BAD746ED75D62BB4 FOREIGN KEY (livrable_attendus_id) REFERENCES livrable_attendus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_livrable_attendus ADD CONSTRAINT FK_BAD746EDC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE livrable');
    }
}
