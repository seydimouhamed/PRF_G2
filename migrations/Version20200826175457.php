<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826175457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant (id INT NOT NULL, profil_sortie_id INT DEFAULT NULL, genre VARCHAR(30) NOT NULL, adresse LONGTEXT NOT NULL, telephone VARCHAR(50) NOT NULL, statut VARCHAR(50) DEFAULT \'attente\' NOT NULL, INDEX IDX_C4EB462E6409EF73 (profil_sortie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apprenant_groupes (apprenant_id INT NOT NULL, groupes_id INT NOT NULL, INDEX IDX_881A12D9C5697D6D (apprenant_id), INDEX IDX_881A12D9305371B (groupes_id), PRIMARY KEY(apprenant_id, groupes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief (id INT AUTO_INCREMENT NOT NULL, referentiel_id INT NOT NULL, formateur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, contexte LONGTEXT NOT NULL, date_poste DATE NOT NULL, date_limite DATE NOT NULL, liste_livrable LONGTEXT DEFAULT NULL, description_rapide LONGTEXT NOT NULL, modalite_pedagogique LONGTEXT NOT NULL, crictere_performance LONGTEXT NOT NULL, modalite_devaluation LONGTEXT NOT NULL, image_exemplaire LONGBLOB DEFAULT NULL, langue VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, archivage TINYINT(1) DEFAULT \'0\', INDEX IDX_1FBB1007805DB139 (referentiel_id), INDEX IDX_1FBB1007155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_promotion (brief_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_D897A312757FABFF (brief_id), INDEX IDX_D897A312139DF194 (promotion_id), PRIMARY KEY(brief_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_groupes (brief_id INT NOT NULL, groupes_id INT NOT NULL, INDEX IDX_DC8DF196757FABFF (brief_id), INDEX IDX_DC8DF196305371B (groupes_id), PRIMARY KEY(brief_id, groupes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_tag (brief_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_452A4F36757FABFF (brief_id), INDEX IDX_452A4F36BAD26311 (tag_id), PRIMARY KEY(brief_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_livrable_attendus (brief_id INT NOT NULL, livrable_attendus_id INT NOT NULL, INDEX IDX_2402D7A2757FABFF (brief_id), INDEX IDX_2402D7A275D62BB4 (livrable_attendus_id), PRIMARY KEY(brief_id, livrable_attendus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, fil_discussion_id INT DEFAULT NULL, commentaire LONGTEXT NOT NULL, INDEX IDX_D9BEC0C49AFA941D (fil_discussion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires_formateur (commentaires_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_945EF0C217C4B2B0 (commentaires_id), INDEX IDX_945EF0C2155D8F51 (formateur_id), PRIMARY KEY(commentaires_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE community_manager (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, descriptif LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fil_discussion (id INT AUTO_INCREMENT NOT NULL, livrables_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_C9EFF4FC96108872 (livrables_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateur (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateur_groupes (formateur_id INT NOT NULL, groupes_id INT NOT NULL, INDEX IDX_62FE1121155D8F51 (formateur_id), INDEX IDX_62FE1121305371B (groupes_id), PRIMARY KEY(formateur_id, groupes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_competence (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, archivage TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_competence_competence (groupe_competence_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_F64AE85C89034830 (groupe_competence_id), INDEX IDX_F64AE85C15761DAB (competence_id), PRIMARY KEY(groupe_competence_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag_tag (groupe_tag_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C430CACFD1EC9F2B (groupe_tag_id), INDEX IDX_C430CACFBAD26311 (tag_id), PRIMARY KEY(groupe_tag_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupes (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, date_creation DATE DEFAULT NULL, statut VARCHAR(50) DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, INDEX IDX_576366D9139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu_apprenant (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_attendu_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_BDB84E34C5697D6D (apprenant_id), INDEX IDX_BDB84E3475180ACC (livrable_attendu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendus (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, livrablepartiel_aprenant_id INT DEFAULT NULL, INDEX IDX_37F072C5F7034EA7 (livrablepartiel_aprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel_apprenant (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_partiel_id INT DEFAULT NULL, delais DATE NOT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_5B1B4613C5697D6D (apprenant_id), INDEX IDX_5B1B4613519178C4 (livrable_partiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiels (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, delai DATE NOT NULL, date_creation DATE NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, competence_id INT DEFAULT NULL, libelle VARCHAR(100) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, groupe_action VARCHAR(255) NOT NULL, INDEX IDX_4BDFF36B15761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_livrable_partiels (niveau_id INT NOT NULL, livrable_partiels_id INT NOT NULL, INDEX IDX_A56DC5AEB3E9C81 (niveau_id), INDEX IDX_A56DC5AE7B292AF4 (livrable_partiels_id), PRIMARY KEY(niveau_id, livrable_partiels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_brief (niveau_id INT NOT NULL, brief_id INT NOT NULL, INDEX IDX_FADCE261B3E9C81 (niveau_id), INDEX IDX_FADCE261757FABFF (brief_id), PRIMARY KEY(niveau_id, brief_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, abbr VARCHAR(255) NOT NULL, archivage TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_sortie (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(255) NOT NULL, archivage TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, referentiel_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, lieu VARCHAR(255) NOT NULL, date_debut DATE DEFAULT NULL, date_fin_prvisoire DATE DEFAULT NULL, fabrique VARCHAR(255) NOT NULL, date_fin_reelle DATE DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, avatar LONGBLOB DEFAULT NULL, INDEX IDX_C11D7DD1805DB139 (referentiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_formateur (promotion_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_9C01AF62139DF194 (promotion_id), INDEX IDX_9C01AF62155D8F51 (formateur_id), PRIMARY KEY(promotion_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation LONGTEXT NOT NULL, programme VARCHAR(255) NOT NULL, critere_admission LONGTEXT NOT NULL, critere_evaluation LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel_groupe_competence (referentiel_id INT NOT NULL, groupe_competence_id INT NOT NULL, INDEX IDX_EC387D5B805DB139 (referentiel_id), INDEX IDX_EC387D5B89034830 (groupe_competence_id), PRIMARY KEY(referentiel_id, groupe_competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, brief_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, piece_jointe LONGBLOB DEFAULT NULL, INDEX IDX_939F4544757FABFF (brief_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, fisrt_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, photo LONGBLOB DEFAULT NULL, archivage TINYINT(1) DEFAULT \'0\' NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_8D93D649275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E6409EF73 FOREIGN KEY (profil_sortie_id) REFERENCES profil_sortie (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_groupes ADD CONSTRAINT FK_881A12D9C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_groupes ADD CONSTRAINT FK_881A12D9305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('ALTER TABLE brief_promotion ADD CONSTRAINT FK_D897A312757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_promotion ADD CONSTRAINT FK_D897A312139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_groupes ADD CONSTRAINT FK_DC8DF196757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_groupes ADD CONSTRAINT FK_DC8DF196305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_livrable_attendus ADD CONSTRAINT FK_2402D7A2757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_livrable_attendus ADD CONSTRAINT FK_2402D7A275D62BB4 FOREIGN KEY (livrable_attendus_id) REFERENCES livrable_attendus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C49AFA941D FOREIGN KEY (fil_discussion_id) REFERENCES fil_discussion (id)');
        $this->addSql('ALTER TABLE commentaires_formateur ADD CONSTRAINT FK_945EF0C217C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_formateur ADD CONSTRAINT FK_945EF0C2155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE community_manager ADD CONSTRAINT FK_DEE14CEABF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fil_discussion ADD CONSTRAINT FK_C9EFF4FC96108872 FOREIGN KEY (livrables_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('ALTER TABLE formateur ADD CONSTRAINT FK_ED767E4FBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur_groupes ADD CONSTRAINT FK_62FE1121155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur_groupes ADD CONSTRAINT FK_62FE1121305371B FOREIGN KEY (groupes_id) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_competence_competence ADD CONSTRAINT FK_F64AE85C89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_competence_competence ADD CONSTRAINT FK_F64AE85C15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFD1EC9F2B FOREIGN KEY (groupe_tag_id) REFERENCES groupe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D9139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E34C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E3475180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendus (id)');
        $this->addSql('ALTER TABLE livrable_partiel ADD CONSTRAINT FK_37F072C5F7034EA7 FOREIGN KEY (livrablepartiel_aprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant ADD CONSTRAINT FK_5B1B4613C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant ADD CONSTRAINT FK_5B1B4613519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiels (id)');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE niveau_livrable_partiels ADD CONSTRAINT FK_A56DC5AEB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_livrable_partiels ADD CONSTRAINT FK_A56DC5AE7B292AF4 FOREIGN KEY (livrable_partiels_id) REFERENCES livrable_partiels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_brief ADD CONSTRAINT FK_FADCE261B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_brief ADD CONSTRAINT FK_FADCE261757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE promotion_formateur ADD CONSTRAINT FK_9C01AF62139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_formateur ADD CONSTRAINT FK_9C01AF62155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_groupe_competence ADD CONSTRAINT FK_EC387D5B805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_groupe_competence ADD CONSTRAINT FK_EC387D5B89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant_groupes DROP FOREIGN KEY FK_881A12D9C5697D6D');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E34C5697D6D');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant DROP FOREIGN KEY FK_5B1B4613C5697D6D');
        $this->addSql('ALTER TABLE brief_promotion DROP FOREIGN KEY FK_D897A312757FABFF');
        $this->addSql('ALTER TABLE brief_groupes DROP FOREIGN KEY FK_DC8DF196757FABFF');
        $this->addSql('ALTER TABLE brief_tag DROP FOREIGN KEY FK_452A4F36757FABFF');
        $this->addSql('ALTER TABLE brief_livrable_attendus DROP FOREIGN KEY FK_2402D7A2757FABFF');
        $this->addSql('ALTER TABLE niveau_brief DROP FOREIGN KEY FK_FADCE261757FABFF');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544757FABFF');
        $this->addSql('ALTER TABLE commentaires_formateur DROP FOREIGN KEY FK_945EF0C217C4B2B0');
        $this->addSql('ALTER TABLE groupe_competence_competence DROP FOREIGN KEY FK_F64AE85C15761DAB');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36B15761DAB');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C49AFA941D');
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB1007155D8F51');
        $this->addSql('ALTER TABLE commentaires_formateur DROP FOREIGN KEY FK_945EF0C2155D8F51');
        $this->addSql('ALTER TABLE formateur_groupes DROP FOREIGN KEY FK_62FE1121155D8F51');
        $this->addSql('ALTER TABLE promotion_formateur DROP FOREIGN KEY FK_9C01AF62155D8F51');
        $this->addSql('ALTER TABLE groupe_competence_competence DROP FOREIGN KEY FK_F64AE85C89034830');
        $this->addSql('ALTER TABLE referentiel_groupe_competence DROP FOREIGN KEY FK_EC387D5B89034830');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFD1EC9F2B');
        $this->addSql('ALTER TABLE apprenant_groupes DROP FOREIGN KEY FK_881A12D9305371B');
        $this->addSql('ALTER TABLE brief_groupes DROP FOREIGN KEY FK_DC8DF196305371B');
        $this->addSql('ALTER TABLE formateur_groupes DROP FOREIGN KEY FK_62FE1121305371B');
        $this->addSql('ALTER TABLE brief_livrable_attendus DROP FOREIGN KEY FK_2402D7A275D62BB4');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E3475180ACC');
        $this->addSql('ALTER TABLE fil_discussion DROP FOREIGN KEY FK_C9EFF4FC96108872');
        $this->addSql('ALTER TABLE livrable_partiel DROP FOREIGN KEY FK_37F072C5F7034EA7');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant DROP FOREIGN KEY FK_5B1B4613519178C4');
        $this->addSql('ALTER TABLE niveau_livrable_partiels DROP FOREIGN KEY FK_A56DC5AE7B292AF4');
        $this->addSql('ALTER TABLE niveau_livrable_partiels DROP FOREIGN KEY FK_A56DC5AEB3E9C81');
        $this->addSql('ALTER TABLE niveau_brief DROP FOREIGN KEY FK_FADCE261B3E9C81');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E6409EF73');
        $this->addSql('ALTER TABLE brief_promotion DROP FOREIGN KEY FK_D897A312139DF194');
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D9139DF194');
        $this->addSql('ALTER TABLE promotion_formateur DROP FOREIGN KEY FK_9C01AF62139DF194');
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB1007805DB139');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1805DB139');
        $this->addSql('ALTER TABLE referentiel_groupe_competence DROP FOREIGN KEY FK_EC387D5B805DB139');
        $this->addSql('ALTER TABLE brief_tag DROP FOREIGN KEY FK_452A4F36BAD26311');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFBAD26311');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EBF396750');
        $this->addSql('ALTER TABLE community_manager DROP FOREIGN KEY FK_DEE14CEABF396750');
        $this->addSql('ALTER TABLE formateur DROP FOREIGN KEY FK_ED767E4FBF396750');
        $this->addSql('DROP TABLE apprenant');
        $this->addSql('DROP TABLE apprenant_groupes');
        $this->addSql('DROP TABLE brief');
        $this->addSql('DROP TABLE brief_promotion');
        $this->addSql('DROP TABLE brief_groupes');
        $this->addSql('DROP TABLE brief_tag');
        $this->addSql('DROP TABLE brief_livrable_attendus');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE commentaires_formateur');
        $this->addSql('DROP TABLE community_manager');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE fil_discussion');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('DROP TABLE formateur_groupes');
        $this->addSql('DROP TABLE groupe_competence');
        $this->addSql('DROP TABLE groupe_competence_competence');
        $this->addSql('DROP TABLE groupe_tag');
        $this->addSql('DROP TABLE groupe_tag_tag');
        $this->addSql('DROP TABLE groupes');
        $this->addSql('DROP TABLE livrable_attendu_apprenant');
        $this->addSql('DROP TABLE livrable_attendus');
        $this->addSql('DROP TABLE livrable_partiel');
        $this->addSql('DROP TABLE livrable_partiel_apprenant');
        $this->addSql('DROP TABLE livrable_partiels');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE niveau_livrable_partiels');
        $this->addSql('DROP TABLE niveau_brief');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_sortie');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_formateur');
        $this->addSql('DROP TABLE referentiel');
        $this->addSql('DROP TABLE referentiel_groupe_competence');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
