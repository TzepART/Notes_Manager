<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170525091254 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE circle DROP date_create, DROP date_update');
        $this->addSql('ALTER TABLE user DROP created, DROP updated, CHANGE logged logged DATETIME NOT NULL');
        $this->addSql('ALTER TABLE notes DROP date_create, DROP date_update');
        $this->addSql('ALTER TABLE labels DROP date_create, DROP date_update, CHANGE angle angle DOUBLE PRECISION NOT NULL, CHANGE radius radius DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE sectors DROP FOREIGN KEY FK_B594069870EE2FF6');
        $this->addSql('ALTER TABLE sectors DROP begin_angle, DROP end_angle, DROP name, DROP parent_sector_id, DROP date_create, DROP date_update, DROP color');
        $this->addSql('DROP INDEX fk_sectors_circle1_idx ON sectors');
        $this->addSql('CREATE INDEX IDX_B594069870EE2FF6 ON sectors (circle_id)');
        $this->addSql('ALTER TABLE sectors ADD CONSTRAINT FK_B594069870EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
        $this->addSql('ALTER TABLE layers DROP FOREIGN KEY FK_E688ED5070EE2FF6');
        $this->addSql('ALTER TABLE layers DROP begin_radius, DROP end_radius, DROP date_create, DROP date_update, DROP color');
        $this->addSql('DROP INDEX fk_layers_circle1_idx ON layers');
        $this->addSql('CREATE INDEX IDX_E688ED5070EE2FF6 ON layers (circle_id)');
        $this->addSql('ALTER TABLE layers ADD CONSTRAINT FK_E688ED5070EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE circle ADD date_create DATETIME DEFAULT NULL, ADD date_update DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE labels ADD date_create DATETIME DEFAULT NULL, ADD date_update DATETIME DEFAULT NULL, CHANGE radius radius DOUBLE PRECISION DEFAULT NULL, CHANGE angle angle DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE layers DROP FOREIGN KEY FK_E688ED5070EE2FF6');
        $this->addSql('ALTER TABLE layers ADD begin_radius DOUBLE PRECISION DEFAULT NULL, ADD end_radius DOUBLE PRECISION DEFAULT NULL, ADD date_create DATETIME DEFAULT NULL, ADD date_update DATETIME DEFAULT NULL, ADD color VARCHAR(10) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX idx_e688ed5070ee2ff6 ON layers');
        $this->addSql('CREATE INDEX fk_layers_circle1_idx ON layers (circle_id)');
        $this->addSql('ALTER TABLE layers ADD CONSTRAINT FK_E688ED5070EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
        $this->addSql('ALTER TABLE notes ADD date_create DATETIME DEFAULT NULL, ADD date_update DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sectors DROP FOREIGN KEY FK_B594069870EE2FF6');
        $this->addSql('ALTER TABLE sectors ADD begin_angle DOUBLE PRECISION DEFAULT NULL, ADD end_angle DOUBLE PRECISION DEFAULT NULL, ADD name VARCHAR(45) DEFAULT NULL COLLATE utf8_unicode_ci, ADD parent_sector_id INT DEFAULT NULL, ADD date_create DATETIME DEFAULT NULL, ADD date_update DATETIME DEFAULT NULL, ADD color VARCHAR(10) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX idx_b594069870ee2ff6 ON sectors');
        $this->addSql('CREATE INDEX fk_sectors_circle1_idx ON sectors (circle_id)');
        $this->addSql('ALTER TABLE sectors ADD CONSTRAINT FK_B594069870EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
        $this->addSql('ALTER TABLE user ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL, CHANGE logged logged DATETIME DEFAULT NULL');
    }
}
