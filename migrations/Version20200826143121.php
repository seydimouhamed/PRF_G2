<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826143121 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E5467F92D');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EEE63D1F4');
        $this->addSql('DROP INDEX IDX_C4EB462EEE63D1F4 ON apprenant');
        $this->addSql('DROP INDEX IDX_C4EB462E5467F92D ON apprenant');
        $this->addSql('ALTER TABLE apprenant DROP livrable_attendu_apprenant_id, DROP livrable_partiel_apprenant_id');
        $this->addSql('ALTER TABLE livrable_attendus DROP FOREIGN KEY FK_F90C2D23EE63D1F4');
        $this->addSql('DROP INDEX IDX_F90C2D23EE63D1F4 ON livrable_attendus');
        $this->addSql('ALTER TABLE livrable_attendus DROP livrable_attendu_apprenant_id');
        $this->addSql('ALTER TABLE livrable_partiels DROP FOREIGN KEY FK_F03709465467F92D');
        $this->addSql('DROP INDEX IDX_F03709465467F92D ON livrable_partiels');
        $this->addSql('ALTER TABLE livrable_partiels DROP livrable_partiel_apprenant_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant ADD livrable_attendu_apprenant_id INT DEFAULT NULL, ADD livrable_partiel_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E5467F92D FOREIGN KEY (livrable_partiel_apprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EEE63D1F4 FOREIGN KEY (livrable_attendu_apprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_C4EB462EEE63D1F4 ON apprenant (livrable_attendu_apprenant_id)');
        $this->addSql('CREATE INDEX IDX_C4EB462E5467F92D ON apprenant (livrable_partiel_apprenant_id)');
        $this->addSql('ALTER TABLE livrable_attendus ADD livrable_attendu_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendus ADD CONSTRAINT FK_F90C2D23EE63D1F4 FOREIGN KEY (livrable_attendu_apprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_F90C2D23EE63D1F4 ON livrable_attendus (livrable_attendu_apprenant_id)');
        $this->addSql('ALTER TABLE livrable_partiels ADD livrable_partiel_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_partiels ADD CONSTRAINT FK_F03709465467F92D FOREIGN KEY (livrable_partiel_apprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_F03709465467F92D ON livrable_partiels (livrable_partiel_apprenant_id)');
    }
}
