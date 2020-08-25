<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822144845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrable_partiels (apprenant_id INT NOT NULL, livrable_partiels_id INT NOT NULL, INDEX IDX_B3EC6288C5697D6D (apprenant_id), INDEX IDX_B3EC62887B292AF4 (livrable_partiels_id), PRIMARY KEY(apprenant_id, livrable_partiels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_livrable_partiels ADD CONSTRAINT FK_B3EC6288C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_livrable_partiels ADD CONSTRAINT FK_B3EC62887B292AF4 FOREIGN KEY (livrable_partiels_id) REFERENCES livrable_partiels (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE apprenant_livrable_partiels');
    }
}
