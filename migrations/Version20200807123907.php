<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807123907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenants (id INT NOT NULL, profil_sorti_id INT DEFAULT NULL, genre VARCHAR(50) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, INDEX IDX_C71C2982B37C4ED5 (profil_sorti_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, niveau_id INT DEFAULT NULL, libele VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, INDEX IDX_94D4687FB3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateurs (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, promotions_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, datecreation DATE DEFAULT NULL, statut VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_4B98C2110007789 (promotions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_apprenants (groupe_id INT NOT NULL, apprenants_id INT NOT NULL, INDEX IDX_7FF1185E7A45358C (groupe_id), INDEX IDX_7FF1185ED4B7C9BD (apprenants_id), PRIMARY KEY(groupe_id, apprenants_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_formateurs (groupe_id INT NOT NULL, formateurs_id INT NOT NULL, INDEX IDX_456DD4A87A45358C (groupe_id), INDEX IDX_456DD4A8FB0881C8 (formateurs_id), PRIMARY KEY(groupe_id, formateurs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_competence (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_competence_competence (groupe_competence_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_F64AE85C89034830 (groupe_competence_id), INDEX IDX_F64AE85C15761DAB (competence_id), PRIMARY KEY(groupe_competence_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag_tag (groupe_tag_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C430CACFD1EC9F2B (groupe_tag_id), INDEX IDX_C430CACFBAD26311 (tag_id), PRIMARY KEY(groupe_tag_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, groupe_action VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archivage TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_sorti (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, archivage TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, groupes_id INT DEFAULT NULL, referentiel_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, lieu VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin_provisoire DATE NOT NULL, fabrique VARCHAR(255) DEFAULT NULL, date_fin_reelle DATE NOT NULL, statut VARCHAR(255) DEFAULT NULL, INDEX IDX_C11D7DD1305371B (groupes_id), INDEX IDX_C11D7DD1805DB139 (referentiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, presentation LONGTEXT DEFAULT NULL, programme VARCHAR(255) NOT NULL, critere_admission LONGTEXT NOT NULL, critereevaluation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_groupe_competence (tag_id INT NOT NULL, groupe_competence_id INT NOT NULL, INDEX IDX_976641D7BAD26311 (tag_id), INDEX IDX_976641D789034830 (groupe_competence_id), PRIMARY KEY(tag_id, groupe_competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, avatar LONGBLOB NOT NULL, archivage TINYINT(1) DEFAULT \'0\' NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_497B315EE7927C74 (email), INDEX IDX_497B315E275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenants ADD CONSTRAINT FK_C71C2982B37C4ED5 FOREIGN KEY (profil_sorti_id) REFERENCES profil_sorti (id)');
        $this->addSql('ALTER TABLE apprenants ADD CONSTRAINT FK_C71C2982BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687FB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE formateurs ADD CONSTRAINT FK_FD80E574BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C2110007789 FOREIGN KEY (promotions_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE groupe_apprenants ADD CONSTRAINT FK_7FF1185E7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenants ADD CONSTRAINT FK_7FF1185ED4B7C9BD FOREIGN KEY (apprenants_id) REFERENCES apprenants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateurs ADD CONSTRAINT FK_456DD4A87A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateurs ADD CONSTRAINT FK_456DD4A8FB0881C8 FOREIGN KEY (formateurs_id) REFERENCES formateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_competence_competence ADD CONSTRAINT FK_F64AE85C89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_competence_competence ADD CONSTRAINT FK_F64AE85C15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFD1EC9F2B FOREIGN KEY (groupe_tag_id) REFERENCES groupe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1305371B FOREIGN KEY (groupes_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE tag_groupe_competence ADD CONSTRAINT FK_976641D7BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_groupe_competence ADD CONSTRAINT FK_976641D789034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_apprenants DROP FOREIGN KEY FK_7FF1185ED4B7C9BD');
        $this->addSql('ALTER TABLE groupe_competence_competence DROP FOREIGN KEY FK_F64AE85C15761DAB');
        $this->addSql('ALTER TABLE groupe_formateurs DROP FOREIGN KEY FK_456DD4A8FB0881C8');
        $this->addSql('ALTER TABLE groupe_apprenants DROP FOREIGN KEY FK_7FF1185E7A45358C');
        $this->addSql('ALTER TABLE groupe_formateurs DROP FOREIGN KEY FK_456DD4A87A45358C');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1305371B');
        $this->addSql('ALTER TABLE groupe_competence_competence DROP FOREIGN KEY FK_F64AE85C89034830');
        $this->addSql('ALTER TABLE tag_groupe_competence DROP FOREIGN KEY FK_976641D789034830');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFD1EC9F2B');
        $this->addSql('ALTER TABLE competence DROP FOREIGN KEY FK_94D4687FB3E9C81');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315E275ED078');
        $this->addSql('ALTER TABLE apprenants DROP FOREIGN KEY FK_C71C2982B37C4ED5');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C2110007789');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1805DB139');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFBAD26311');
        $this->addSql('ALTER TABLE tag_groupe_competence DROP FOREIGN KEY FK_976641D7BAD26311');
        $this->addSql('ALTER TABLE apprenants DROP FOREIGN KEY FK_C71C2982BF396750');
        $this->addSql('ALTER TABLE formateurs DROP FOREIGN KEY FK_FD80E574BF396750');
        $this->addSql('DROP TABLE apprenants');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE formateurs');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_apprenants');
        $this->addSql('DROP TABLE groupe_formateurs');
        $this->addSql('DROP TABLE groupe_competence');
        $this->addSql('DROP TABLE groupe_competence_competence');
        $this->addSql('DROP TABLE groupe_tag');
        $this->addSql('DROP TABLE groupe_tag_tag');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_sorti');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE referentiel');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_groupe_competence');
        $this->addSql('DROP TABLE utilisateurs');
    }
}
