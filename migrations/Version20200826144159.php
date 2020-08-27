<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826144159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable_attendu');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EB17D6C42');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EF13C18DD');
        $this->addSql('DROP INDEX IDX_C4EB462EF13C18DD ON apprenant');
        $this->addSql('DROP INDEX IDX_C4EB462EB17D6C42 ON apprenant');
        $this->addSql('ALTER TABLE apprenant DROP livrableattenduapprenant_id, DROP livrable_partielapprenant_id');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD apprenant_id INT DEFAULT NULL, ADD livrable_attendu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E34C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E3475180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendus (id)');
        $this->addSql('CREATE INDEX IDX_BDB84E34C5697D6D ON livrable_attendu_apprenant (apprenant_id)');
        $this->addSql('CREATE INDEX IDX_BDB84E3475180ACC ON livrable_attendu_apprenant (livrable_attendu_id)');
        $this->addSql('ALTER TABLE livrable_attendus DROP FOREIGN KEY FK_F90C2D23B17D6C42');
        $this->addSql('DROP INDEX IDX_F90C2D23B17D6C42 ON livrable_attendus');
        $this->addSql('ALTER TABLE livrable_attendus DROP livrableattenduapprenant_id');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant ADD apprenant_id INT DEFAULT NULL, ADD livrable_partiel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant ADD CONSTRAINT FK_5B1B4613C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant ADD CONSTRAINT FK_5B1B4613519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiels (id)');
        $this->addSql('CREATE INDEX IDX_5B1B4613C5697D6D ON livrable_partiel_apprenant (apprenant_id)');
        $this->addSql('CREATE INDEX IDX_5B1B4613519178C4 ON livrable_partiel_apprenant (livrable_partiel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable_attendu (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE apprenant ADD livrableattenduapprenant_id INT DEFAULT NULL, ADD livrable_partielapprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EB17D6C42 FOREIGN KEY (livrableattenduapprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EF13C18DD FOREIGN KEY (livrable_partielapprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_C4EB462EF13C18DD ON apprenant (livrable_partielapprenant_id)');
        $this->addSql('CREATE INDEX IDX_C4EB462EB17D6C42 ON apprenant (livrableattenduapprenant_id)');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E34C5697D6D');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E3475180ACC');
        $this->addSql('DROP INDEX IDX_BDB84E34C5697D6D ON livrable_attendu_apprenant');
        $this->addSql('DROP INDEX IDX_BDB84E3475180ACC ON livrable_attendu_apprenant');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP apprenant_id, DROP livrable_attendu_id');
        $this->addSql('ALTER TABLE livrable_attendus ADD livrableattenduapprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendus ADD CONSTRAINT FK_F90C2D23B17D6C42 FOREIGN KEY (livrableattenduapprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_F90C2D23B17D6C42 ON livrable_attendus (livrableattenduapprenant_id)');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant DROP FOREIGN KEY FK_5B1B4613C5697D6D');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant DROP FOREIGN KEY FK_5B1B4613519178C4');
        $this->addSql('DROP INDEX IDX_5B1B4613C5697D6D ON livrable_partiel_apprenant');
        $this->addSql('DROP INDEX IDX_5B1B4613519178C4 ON livrable_partiel_apprenant');
        $this->addSql('ALTER TABLE livrable_partiel_apprenant DROP apprenant_id, DROP livrable_partiel_id');
    }
}
