<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826143539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable_attendu (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, livrablepartiel_aprenant_id INT DEFAULT NULL, INDEX IDX_37F072C5F7034EA7 (livrablepartiel_aprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livrable_partiel ADD CONSTRAINT FK_37F072C5F7034EA7 FOREIGN KEY (livrablepartiel_aprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E5467F92D');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EEE63D1F4');
        $this->addSql('DROP INDEX IDX_C4EB462EEE63D1F4 ON apprenant');
        $this->addSql('DROP INDEX IDX_C4EB462E5467F92D ON apprenant');
        $this->addSql('ALTER TABLE apprenant ADD livrableattenduapprenant_id INT DEFAULT NULL, ADD livrable_partielapprenant_id INT DEFAULT NULL, DROP livrable_attendu_apprenant_id, DROP livrable_partiel_apprenant_id');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EB17D6C42 FOREIGN KEY (livrableattenduapprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EF13C18DD FOREIGN KEY (livrable_partielapprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_C4EB462EB17D6C42 ON apprenant (livrableattenduapprenant_id)');
        $this->addSql('CREATE INDEX IDX_C4EB462EF13C18DD ON apprenant (livrable_partielapprenant_id)');
        $this->addSql('ALTER TABLE livrable_attendus DROP FOREIGN KEY FK_F90C2D23EE63D1F4');
        $this->addSql('DROP INDEX IDX_F90C2D23EE63D1F4 ON livrable_attendus');
        $this->addSql('ALTER TABLE livrable_attendus CHANGE livrable_attendu_apprenant_id livrableattenduapprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendus ADD CONSTRAINT FK_F90C2D23B17D6C42 FOREIGN KEY (livrableattenduapprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_F90C2D23B17D6C42 ON livrable_attendus (livrableattenduapprenant_id)');
        $this->addSql('ALTER TABLE livrable_partiels DROP FOREIGN KEY FK_F03709465467F92D');
        $this->addSql('DROP INDEX IDX_F03709465467F92D ON livrable_partiels');
        $this->addSql('ALTER TABLE livrable_partiels DROP livrable_partiel_apprenant_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable_attendu');
        $this->addSql('DROP TABLE livrable_partiel');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EB17D6C42');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EF13C18DD');
        $this->addSql('DROP INDEX IDX_C4EB462EB17D6C42 ON apprenant');
        $this->addSql('DROP INDEX IDX_C4EB462EF13C18DD ON apprenant');
        $this->addSql('ALTER TABLE apprenant ADD livrable_attendu_apprenant_id INT DEFAULT NULL, ADD livrable_partiel_apprenant_id INT DEFAULT NULL, DROP livrableattenduapprenant_id, DROP livrable_partielapprenant_id');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E5467F92D FOREIGN KEY (livrable_partiel_apprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EEE63D1F4 FOREIGN KEY (livrable_attendu_apprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_C4EB462EEE63D1F4 ON apprenant (livrable_attendu_apprenant_id)');
        $this->addSql('CREATE INDEX IDX_C4EB462E5467F92D ON apprenant (livrable_partiel_apprenant_id)');
        $this->addSql('ALTER TABLE livrable_attendus DROP FOREIGN KEY FK_F90C2D23B17D6C42');
        $this->addSql('DROP INDEX IDX_F90C2D23B17D6C42 ON livrable_attendus');
        $this->addSql('ALTER TABLE livrable_attendus CHANGE livrableattenduapprenant_id livrable_attendu_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendus ADD CONSTRAINT FK_F90C2D23EE63D1F4 FOREIGN KEY (livrable_attendu_apprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_F90C2D23EE63D1F4 ON livrable_attendus (livrable_attendu_apprenant_id)');
        $this->addSql('ALTER TABLE livrable_partiels ADD livrable_partiel_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_partiels ADD CONSTRAINT FK_F03709465467F92D FOREIGN KEY (livrable_partiel_apprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_F03709465467F92D ON livrable_partiels (livrable_partiel_apprenant_id)');
    }
}
