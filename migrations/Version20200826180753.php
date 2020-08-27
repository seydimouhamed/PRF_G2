<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826180753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fil_discussion DROP FOREIGN KEY FK_C9EFF4FC671ED307');
        $this->addSql('DROP INDEX UNIQ_C9EFF4FC671ED307 ON fil_discussion');
        $this->addSql('ALTER TABLE fil_discussion DROP livrables_partiel_apprenant_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fil_discussion ADD livrables_partiel_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fil_discussion ADD CONSTRAINT FK_C9EFF4FC671ED307 FOREIGN KEY (livrables_partiel_apprenant_id) REFERENCES livrable_partiel_apprenant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C9EFF4FC671ED307 ON fil_discussion (livrables_partiel_apprenant_id)');
    }
}
