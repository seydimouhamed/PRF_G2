<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809045618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promotion_referentiel');
        $this->addSql('ALTER TABLE promotion ADD referentiel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD1805DB139 ON promotion (referentiel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion_referentiel (promotion_id INT NOT NULL, referentiel_id INT NOT NULL, INDEX IDX_6692E9F3139DF194 (promotion_id), INDEX IDX_6692E9F3805DB139 (referentiel_id), PRIMARY KEY(promotion_id, referentiel_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE promotion_referentiel ADD CONSTRAINT FK_6692E9F3139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_referentiel ADD CONSTRAINT FK_6692E9F3805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1805DB139');
        $this->addSql('DROP INDEX IDX_C11D7DD1805DB139 ON promotion');
        $this->addSql('ALTER TABLE promotion DROP referentiel_id');
    }
}
