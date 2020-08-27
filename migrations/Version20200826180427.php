<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826180427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fil_discussion DROP FOREIGN KEY FK_C9EFF4FC96108872');
        $this->addSql('DROP INDEX IDX_C9EFF4FC96108872 ON fil_discussion');
        $this->addSql('ALTER TABLE fil_discussion CHANGE livrables_id livrables_partiel_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fil_discussion ADD CONSTRAINT FK_C9EFF4FC671ED307 FOREIGN KEY (livrables_partiel_apprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C9EFF4FC671ED307 ON fil_discussion (livrables_partiel_apprenant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fil_discussion DROP FOREIGN KEY FK_C9EFF4FC671ED307');
        $this->addSql('DROP INDEX UNIQ_C9EFF4FC671ED307 ON fil_discussion');
        $this->addSql('ALTER TABLE fil_discussion CHANGE livrables_partiel_apprenant_id livrables_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fil_discussion ADD CONSTRAINT FK_C9EFF4FC96108872 FOREIGN KEY (livrables_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_C9EFF4FC96108872 ON fil_discussion (livrables_id)');
    }
}
