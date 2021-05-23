<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523130204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(2) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, listing_id INT NOT NULL, artist VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, catno VARCHAR(50) NOT NULL, format VARCHAR(100) NOT NULL, release_id INT NOT NULL, status VARCHAR(20) NOT NULL, price NUMERIC(6, 2) NOT NULL, listed DATETIME NOT NULL, comments VARCHAR(255) DEFAULT NULL, media_condition VARCHAR(20) NOT NULL, sleeve_condition VARCHAR(20) DEFAULT NULL, accept_offer VARCHAR(1) DEFAULT NULL, external_id VARCHAR(20) DEFAULT NULL, weight INT DEFAULT NULL, format_quantity INT DEFAULT NULL, flat_shipping INT DEFAULT NULL, location VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, buyer VARCHAR(50) NOT NULL, order_num VARCHAR(20) NOT NULL, order_date DATETIME NOT NULL, status VARCHAR(50) DEFAULT NULL, total NUMERIC(6, 2) NOT NULL, shipping VARCHAR(20) DEFAULT NULL, fee VARCHAR(20) DEFAULT NULL, tax VARCHAR(20) DEFAULT NULL, taxed_amount VARCHAR(20) DEFAULT NULL, tax_jurisdiction VARCHAR(20) DEFAULT NULL, tax_responsible_party VARCHAR(20) DEFAULT NULL, invoice VARCHAR(20) DEFAULT NULL, rating_of_buyer VARCHAR(20) DEFAULT NULL, rating_of_seller VARCHAR(20) DEFAULT NULL, rating_of_buyer_date DATETIME DEFAULT NULL, rating_of_seller_date DATETIME DEFAULT NULL, comment_about_buyer VARCHAR(255) DEFAULT NULL, comment_about_seller VARCHAR(255) DEFAULT NULL, archived VARCHAR(20) DEFAULT NULL, shipping_address LONGTEXT NOT NULL, buyer_extra LONGTEXT DEFAULT NULL, last_activity DATETIME DEFAULT NULL, currency VARCHAR(20) DEFAULT NULL, from_offer VARCHAR(20) DEFAULT NULL, offer_original_price VARCHAR(20) DEFAULT NULL, shipping_method VARCHAR(20) DEFAULT NULL, country VARCHAR(255) NOT NULL, nb_items INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, buyer VARCHAR(50) NOT NULL, order_date DATETIME NOT NULL, order_num VARCHAR(20) NOT NULL, status VARCHAR(50) NOT NULL, order_total INT NOT NULL, order_fee INT DEFAULT NULL, item_id INT NOT NULL, item_price NUMERIC(6, 2) NOT NULL, item_fee INT NOT NULL, description VARCHAR(255) NOT NULL, release_id INT NOT NULL, media_condition VARCHAR(20) NOT NULL, sleeve_condition VARCHAR(20) DEFAULT NULL, comments VARCHAR(255) DEFAULT NULL, external_id VARCHAR(20) DEFAULT NULL, item_removed VARCHAR(20) DEFAULT NULL, shipping INT DEFAULT NULL, invoice VARCHAR(20) DEFAULT NULL, rating_of_buyer VARCHAR(20) DEFAULT NULL, rating_of_seller VARCHAR(20) DEFAULT NULL, rating_of_buyer_date DATETIME DEFAULT NULL, rating_of_seller_date DATETIME DEFAULT NULL, comment_about_buyer VARCHAR(255) DEFAULT NULL, comment_about_seller VARCHAR(255) DEFAULT NULL, archived VARCHAR(20) DEFAULT NULL, shipping_address LONGTEXT NOT NULL, buyer_extra LONGTEXT DEFAULT NULL, last_activity DATETIME DEFAULT NULL, currency VARCHAR(20) DEFAULT NULL, from_offer VARCHAR(20) DEFAULT NULL, offer_original_price NUMERIC(6, 2) DEFAULT NULL, shipping_method VARCHAR(50) DEFAULT NULL, location VARCHAR(20) DEFAULT NULL, INDEX IDX_52EA1F098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_item (id INT AUTO_INCREMENT NOT NULL, artist VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_order (id INT AUTO_INCREMENT NOT NULL, order_num VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, register_date DATETIME NOT NULL, is_admin TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE test_item');
        $this->addSql('DROP TABLE test_order');
        $this->addSql('DROP TABLE user');
    }
}
