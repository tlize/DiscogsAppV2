<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502201329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, buyer VARCHAR(50) NOT NULL, order_date DATETIME NOT NULL, order_num VARCHAR(20) NOT NULL, status VARCHAR(50) NOT NULL, order_total INT NOT NULL, order_fee INT DEFAULT NULL, item_id INT NOT NULL, item_price NUMERIC(6, 2) NOT NULL, item_fee INT NOT NULL, description VARCHAR(255) NOT NULL, release_id INT NOT NULL, media_condition VARCHAR(20) NOT NULL, sleeve_condition VARCHAR(20) DEFAULT NULL, comments VARCHAR(255) DEFAULT NULL, external_id VARCHAR(20) DEFAULT NULL, item_removed VARCHAR(20) DEFAULT NULL, shipping INT DEFAULT NULL, invoice VARCHAR(20) DEFAULT NULL, rating_of_buyer VARCHAR(20) DEFAULT NULL, rating_of_seller VARCHAR(20) DEFAULT NULL, rating_of_buyer_date DATETIME DEFAULT NULL, rating_of_seller_date DATETIME DEFAULT NULL, comment_about_buyer VARCHAR(255) DEFAULT NULL, comment_about_seller VARCHAR(255) DEFAULT NULL, archived VARCHAR(20) NOT NULL, shipping_address VARCHAR(255) NOT NULL, buyer_extra VARCHAR(255) DEFAULT NULL, last_activity DATETIME DEFAULT NULL, currency VARCHAR(20) DEFAULT NULL, from_offer VARCHAR(20) DEFAULT NULL, offer_original_price NUMERIC(6, 2) DEFAULT NULL, shipping_method VARCHAR(50) NOT NULL, location VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_item');
    }
}
