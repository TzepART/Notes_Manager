<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170525101741 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE layers ADD begin_radius DOUBLE PRECISION NOT NULL, ADD end_radius DOUBLE PRECISION NOT NULL, ADD color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sectors ADD begin_angle DOUBLE PRECISION NOT NULL, ADD end_angle DOUBLE PRECISION NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD parent_sector_id INT DEFAULT NULL, ADD color VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE layers DROP begin_radius, DROP end_radius, DROP color');
        $this->addSql('ALTER TABLE sectors DROP begin_angle, DROP end_angle, DROP name, DROP parent_sector_id, DROP color');
    }
}
