<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502195233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, listing_id INT NOT NULL, artist VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, catno VARCHAR(50) NOT NULL, format VARCHAR(100) NOT NULL, release_id INT NOT NULL, status VARCHAR(20) NOT NULL, price NUMERIC(6, 2) NOT NULL, listed DATETIME NOT NULL, comments VARCHAR(255) DEFAULT NULL, media_condition VARCHAR(20) NOT NULL, sleeve_condition VARCHAR(20) DEFAULT NULL, accept_offer TINYINT(1) DEFAULT NULL, external_id VARCHAR(20) DEFAULT NULL, weight INT DEFAULT NULL, format_quantity INT DEFAULT NULL, flat_shipping INT DEFAULT NULL, location VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE item');
    }
}
