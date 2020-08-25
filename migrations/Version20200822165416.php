<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822165416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaires_formateur (commentaires_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_945EF0C217C4B2B0 (commentaires_id), INDEX IDX_945EF0C2155D8F51 (formateur_id), PRIMARY KEY(commentaires_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires_formateur ADD CONSTRAINT FK_945EF0C217C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_formateur ADD CONSTRAINT FK_945EF0C2155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commentaires_formateur');
    }
}
