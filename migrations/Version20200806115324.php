<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806115324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant_groupes DROP FOREIGN KEY FK_881A12D9C5697D6D');
        $this->addSql('ALTER TABLE formateur_groupes DROP FOREIGN KEY FK_62FE1121155D8F51');
        $this->addSql('ALTER TABLE apprenant_groupes DROP FOREIGN KEY FK_881A12D9305371B');
        $this->addSql('ALTER TABLE formateur_groupes DROP FOREIGN KEY FK_62FE1121305371B');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E6409EF73');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EBF396750');
        $this->addSql('ALTER TABLE formateur DROP FOREIGN KEY FK_ED767E4FBF396750');
        $this->addSql('CREATE TABLE apprenants (id INT NOT NULL, profil_sorti_id INT DEFAULT NULL, genre VARCHAR(50) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, INDEX IDX_C71C2982B37C4ED5 (profil_sorti_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateurs (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, promotions_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, datecreation DATE DEFAULT NULL, statut VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_4B98C2110007789 (promotions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_apprenants (groupe_id INT NOT NULL, apprenants_id INT NOT NULL, INDEX IDX_7FF1185E7A45358C (groupe_id), INDEX IDX_7FF1185ED4B7C9BD (apprenants_id), PRIMARY KEY(groupe_id, apprenants_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_formateurs (groupe_id INT NOT NULL, formateurs_id INT NOT NULL, INDEX IDX_456DD4A87A45358C (groupe_id), INDEX IDX_456DD4A8FB0881C8 (formateurs_id), PRIMARY KEY(groupe_id, formateurs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_sorti (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, archivage TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, avatar LONGBLOB NOT NULL, archivage TINYINT(1) DEFAULT \'0\' NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_497B315EE7927C74 (email), INDEX IDX_497B315E275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenants ADD CONSTRAINT FK_C71C2982B37C4ED5 FOREIGN KEY (profil_sorti_id) REFERENCES profil_sorti (id)');
        $this->addSql('ALTER TABLE apprenants ADD CONSTRAINT FK_C71C2982BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateurs ADD CONSTRAINT FK_FD80E574BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C2110007789 FOREIGN KEY (promotions_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE groupe_apprenants ADD CONSTRAINT FK_7FF1185E7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenants ADD CONSTRAINT FK_7FF1185ED4B7C9BD FOREIGN KEY (apprenants_id) REFERENCES apprenants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateurs ADD CONSTRAINT FK_456DD4A87A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateurs ADD CONSTRAINT FK_456DD4A8FB0881C8 FOREIGN KEY (formateurs_id) REFERENCES formateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('DROP TABLE apprenant');
        $this->addSql('DROP TABLE apprenant_groupes');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('DROP TABLE formateur_groupes');
        $this->addSql('DROP TABLE groupes');
        $this->addSql('DROP TABLE profil_sortie');
        $this->addSql('DROP TABLE referentiel_groupe_competence');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE competence ADD libele VARCHAR(255) NOT NULL, DROP libelle, CHANGE descriptif descriptif VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE groupe_competence CHANGE lidelle libele VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE groupe_tag ADD libele VARCHAR(255) NOT NULL, DROP libelle');
        $this->addSql('ALTER TABLE niveau ADD libele VARCHAR(255) NOT NULL, DROP libelle');
        $this->addSql('ALTER TABLE profil DROP abbr');
        $this->addSql('ALTER TABLE promotion ADD groupes_id INT DEFAULT NULL, ADD date_fin_provisoire DATE NOT NULL, ADD statut VARCHAR(255) DEFAULT NULL, DROP date_fin_prvisoire, DROP status, CHANGE date_debut date_debut DATE NOT NULL, CHANGE fabrique fabrique VARCHAR(255) DEFAULT NULL, CHANGE date_fin_reelle date_fin_reelle DATE NOT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1305371B FOREIGN KEY (groupes_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD1305371B ON promotion (groupes_id)');
        $this->addSql('ALTER TABLE referentiel ADD critereevaluation VARCHAR(255) NOT NULL, DROP critere_evaluation, CHANGE presentation presentation LONGTEXT DEFAULT NULL, CHANGE libelle libele VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tag CHANGE libelle libele VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_apprenants DROP FOREIGN KEY FK_7FF1185ED4B7C9BD');
        $this->addSql('ALTER TABLE groupe_formateurs DROP FOREIGN KEY FK_456DD4A8FB0881C8');
        $this->addSql('ALTER TABLE groupe_apprenants DROP FOREIGN KEY FK_7FF1185E7A45358C');
        $this->addSql('ALTER TABLE groupe_formateurs DROP FOREIGN KEY FK_456DD4A87A45358C');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1305371B');
        $this->addSql('ALTER TABLE apprenants DROP FOREIGN KEY FK_C71C2982B37C4ED5');
        $this->addSql('ALTER TABLE apprenants DROP FOREIGN KEY FK_C71C2982BF396750');
        $this->addSql('ALTER TABLE formateurs DROP FOREIGN KEY FK_FD80E574BF396750');
        $this->addSql('CREATE TABLE apprenant (id INT NOT NULL, profil_sortie_id INT DEFAULT NULL, genre VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_C4EB462E6409EF73 (profil_sortie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE apprenant_groupes (apprenant_id INT NOT NULL, groupes_id INT NOT NULL, INDEX IDX_881A12D9C5697D6D (apprenant_id), INDEX IDX_881A12D9305371B (groupes_id), PRIMARY KEY(apprenant_id, groupes_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE formateur (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE formateur_groupes (formateur_id INT NOT NULL, groupes_id INT NOT NULL, INDEX IDX_62FE1121155D8F51 (formateur_id), INDEX IDX_62FE1121305371B (groupes_id), PRIMARY KEY(formateur_id, groupes_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupes (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_creation DATE DEFAULT NULL, statut VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_576366D9139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE profil_sortie (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, archivage TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE referentiel_groupe_competence (referentiel_id INT NOT NULL, groupe_competence_id INT NOT NULL, INDEX IDX_EC387D5B89034830 (groupe_competence_id), INDEX IDX_EC387D5B805DB139 (referentiel_id), PRIMARY KEY(referentiel_id, groupe_competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, fisrt_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, photo LONGBLOB DEFAULT NULL, archivage TINYINT(1) DEFAULT \'0\' NOT NULL, discr VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E6409EF73 FOREIGN KEY (profil_sortie_id) REFERENCES profil_sortie (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_groupes ADD CONSTRAINT FK_881A12D9305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_groupes ADD CONSTRAINT FK_881A12D9C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur ADD CONSTRAINT FK_ED767E4FBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur_groupes ADD CONSTRAINT FK_62FE1121155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur_groupes ADD CONSTRAINT FK_62FE1121305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D9139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE referentiel_groupe_competence ADD CONSTRAINT FK_EC387D5B805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_groupe_competence ADD CONSTRAINT FK_EC387D5B89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('DROP TABLE apprenants');
        $this->addSql('DROP TABLE formateurs');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_apprenants');
        $this->addSql('DROP TABLE groupe_formateurs');
        $this->addSql('DROP TABLE profil_sorti');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('ALTER TABLE competence ADD libelle VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP libele, CHANGE descriptif descriptif LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE groupe_competence CHANGE libele lidelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE groupe_tag ADD libelle VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP libele');
        $this->addSql('ALTER TABLE niveau ADD libelle VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP libele');
        $this->addSql('ALTER TABLE profil ADD abbr VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_C11D7DD1305371B ON promotion');
        $this->addSql('ALTER TABLE promotion ADD date_fin_prvisoire DATE DEFAULT NULL, ADD status VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP groupes_id, DROP date_fin_provisoire, DROP statut, CHANGE date_debut date_debut DATE DEFAULT NULL, CHANGE fabrique fabrique VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE date_fin_reelle date_fin_reelle DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE referentiel ADD libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD critere_evaluation LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP libele, DROP critereevaluation, CHANGE presentation presentation LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tag CHANGE libele libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
