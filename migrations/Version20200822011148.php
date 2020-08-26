<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822011148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aprenant_livrable_partiel (id INT AUTO_INCREMENT NOT NULL, etat VARCHAR(50) NOT NULL, delai DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_apprenant (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL, pieces_jointe LONGBLOB DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, creat_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences_valide (id INT AUTO_INCREMENT NOT NULL, competence_id INT DEFAULT NULL, apprenant_id INT DEFAULT NULL, niveau1 LONGTEXT NOT NULL, niveau2 LONGTEXT NOT NULL, niveau3 LONGTEXT NOT NULL, INDEX IDX_81F6BD5215761DAB (competence_id), INDEX IDX_81F6BD52C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competences_valide ADD CONSTRAINT FK_81F6BD5215761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE competences_valide ADD CONSTRAINT FK_81F6BD52C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE brief ADD statut VARCHAR(50) NOT NULL, ADD archivage TINYINT(1) NOT NULL, ADD etat VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE livrable_partiels ADD etat VARCHAR(50) NOT NULL, ADD type VARCHAR(50) NOT NULL, ADD nbre_rendu INT DEFAULT NULL, ADD nbre_corriger INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD type VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE aprenant_livrable_partiel');
        $this->addSql('DROP TABLE brief_apprenant');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE competences_valide');
        $this->addSql('ALTER TABLE brief DROP statut, DROP archivage, DROP etat');
        $this->addSql('ALTER TABLE livrable_partiels DROP etat, DROP type, DROP nbre_rendu, DROP nbre_corriger');
        $this->addSql('ALTER TABLE ressource DROP type');
    }
}
