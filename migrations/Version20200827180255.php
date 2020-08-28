<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827180255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief ADD etat_brief_groupe_id INT NOT NULL');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB10077777C7A0 FOREIGN KEY (etat_brief_groupe_id) REFERENCES etat_brief_groupe (id)');
        $this->addSql('CREATE INDEX IDX_1FBB10077777C7A0 ON brief (etat_brief_groupe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB10077777C7A0');
        $this->addSql('DROP INDEX IDX_1FBB10077777C7A0 ON brief');
        $this->addSql('ALTER TABLE brief DROP etat_brief_groupe_id');
    }
}
