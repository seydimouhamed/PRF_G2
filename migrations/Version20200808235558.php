<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200808235558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D9139DF194');
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D99C5A55D4');
        $this->addSql('DROP INDEX IDX_576366D9139DF194 ON groupes');
        $this->addSql('DROP INDEX IDX_576366D99C5A55D4 ON groupes');
        $this->addSql('ALTER TABLE groupes ADD promotions_id INT DEFAULT NULL, DROP promotion_id, DROP promogroupe_id');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D910007789 FOREIGN KEY (promotions_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_576366D910007789 ON groupes (promotions_id)');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1B8F4689C');
        $this->addSql('DROP INDEX IDX_C11D7DD1B8F4689C ON promotion');
        $this->addSql('ALTER TABLE promotion DROP referentiels_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D910007789');
        $this->addSql('DROP INDEX IDX_576366D910007789 ON groupes');
        $this->addSql('ALTER TABLE groupes ADD promogroupe_id INT DEFAULT NULL, CHANGE promotions_id promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D9139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D99C5A55D4 FOREIGN KEY (promogroupe_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_576366D9139DF194 ON groupes (promotion_id)');
        $this->addSql('CREATE INDEX IDX_576366D99C5A55D4 ON groupes (promogroupe_id)');
        $this->addSql('ALTER TABLE promotion ADD referentiels_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1B8F4689C FOREIGN KEY (referentiels_id) REFERENCES referentiel (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD1B8F4689C ON promotion (referentiels_id)');
    }
}
