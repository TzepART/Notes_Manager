<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161127225204 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, labels_id INT DEFAULT NULL, users_id INT DEFAULT NULL, name VARCHAR(1024) DEFAULT NULL, text LONGTEXT DEFAULT NULL, date_create DATETIME DEFAULT NULL, date_update DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_11BA68CB8478C02 (labels_id), INDEX fk_notes_users_idx (users_id), INDEX fk_notes_labels1_idx (labels_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created DATETIME NOT NULL, updated DATETIME NOT NULL, logged DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE labels (id INT AUTO_INCREMENT NOT NULL, sectors_id INT DEFAULT NULL, layers_id INT DEFAULT NULL, angle DOUBLE PRECISION DEFAULT NULL, radius DOUBLE PRECISION DEFAULT NULL, date_create DATETIME DEFAULT NULL, date_update DATETIME DEFAULT NULL, INDEX fk_labels_sectors1_idx (sectors_id), INDEX fk_labels_layers1_idx (layers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circle (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(256) NOT NULL, date_create DATETIME DEFAULT NULL, date_update DATETIME DEFAULT NULL, INDEX fk_circle_users1_idx (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sectors (id INT AUTO_INCREMENT NOT NULL, circle_id INT DEFAULT NULL, begin_angle DOUBLE PRECISION DEFAULT NULL, end_angle DOUBLE PRECISION DEFAULT NULL, name VARCHAR(45) DEFAULT NULL, parent_sector_id INT DEFAULT NULL, date_create DATETIME DEFAULT NULL, date_update DATETIME DEFAULT NULL, color VARCHAR(10) DEFAULT NULL, INDEX fk_sectors_circle1_idx (circle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE layers (id INT AUTO_INCREMENT NOT NULL, circle_id INT DEFAULT NULL, begin_radius DOUBLE PRECISION DEFAULT NULL, end_radius DOUBLE PRECISION DEFAULT NULL, date_create DATETIME DEFAULT NULL, date_update DATETIME DEFAULT NULL, color VARCHAR(10) DEFAULT NULL, INDEX fk_layers_circle1_idx (circle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CB8478C02 FOREIGN KEY (labels_id) REFERENCES labels (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE labels ADD CONSTRAINT FK_B5D102113479DC16 FOREIGN KEY (sectors_id) REFERENCES sectors (id)');
        $this->addSql('ALTER TABLE labels ADD CONSTRAINT FK_B5D1021124E6DD5 FOREIGN KEY (layers_id) REFERENCES layers (id)');
        $this->addSql('ALTER TABLE circle ADD CONSTRAINT FK_D4B76579A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sectors ADD CONSTRAINT FK_B594069870EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
        $this->addSql('ALTER TABLE layers ADD CONSTRAINT FK_E688ED5070EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C67B3B43D');
        $this->addSql('ALTER TABLE circle DROP FOREIGN KEY FK_D4B76579A76ED395');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CB8478C02');
        $this->addSql('ALTER TABLE sectors DROP FOREIGN KEY FK_B594069870EE2FF6');
        $this->addSql('ALTER TABLE layers DROP FOREIGN KEY FK_E688ED5070EE2FF6');
        $this->addSql('ALTER TABLE labels DROP FOREIGN KEY FK_B5D102113479DC16');
        $this->addSql('ALTER TABLE labels DROP FOREIGN KEY FK_B5D1021124E6DD5');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE labels');
        $this->addSql('DROP TABLE circle');
        $this->addSql('DROP TABLE sectors');
        $this->addSql('DROP TABLE layers');
    }
}
