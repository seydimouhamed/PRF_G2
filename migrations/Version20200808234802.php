<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200808234802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion_formateur (promotion_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_9C01AF62139DF194 (promotion_id), INDEX IDX_9C01AF62155D8F51 (formateur_id), PRIMARY KEY(promotion_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion_formateur ADD CONSTRAINT FK_9C01AF62139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_formateur ADD CONSTRAINT FK_9C01AF62155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes ADD promogroupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D99C5A55D4 FOREIGN KEY (promogroupe_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_576366D99C5A55D4 ON groupes (promogroupe_id)');
        $this->addSql('ALTER TABLE promotion ADD users_id INT DEFAULT NULL, ADD referentiels_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1B8F4689C FOREIGN KEY (referentiels_id) REFERENCES referentiel (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD167B3B43D ON promotion (users_id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD1B8F4689C ON promotion (referentiels_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promotion_formateur');
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D99C5A55D4');
        $this->addSql('DROP INDEX IDX_576366D99C5A55D4 ON groupes');
        $this->addSql('ALTER TABLE groupes DROP promogroupe_id');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD167B3B43D');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1B8F4689C');
        $this->addSql('DROP INDEX IDX_C11D7DD167B3B43D ON promotion');
        $this->addSql('DROP INDEX IDX_C11D7DD1B8F4689C ON promotion');
        $this->addSql('ALTER TABLE promotion DROP users_id, DROP referentiels_id');
    }
}
