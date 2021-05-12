<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511233030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE table 10');
        $this->addSql('DROP TABLE table 7');
        $this->addSql('DROP TABLE table 8');
        $this->addSql('DROP TABLE table 9');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE table 10 (buyer VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_num VARCHAR(11) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, status VARCHAR(31) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, total INT DEFAULT NULL, shipping VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, fee INT DEFAULT NULL, tax INT DEFAULT NULL, taxed amount INT DEFAULT NULL, tax jurisdiction VARCHAR(2) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, tax responsible party VARCHAR(7) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, invoice VARCHAR(16) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_buyer VARCHAR(78) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_seller VARCHAR(219) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, archived VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, shipping_address VARCHAR(177) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, buyer_extra VARCHAR(414) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, last_activity VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, currency VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, from_offer VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, offer_original_price INT DEFAULT NULL, shipping_method VARCHAR(13) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE table 7 (buyer VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_num VARCHAR(11) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, status VARCHAR(31) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, total INT DEFAULT NULL, shipping VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, fee INT DEFAULT NULL, tax INT DEFAULT NULL, taxed amount INT DEFAULT NULL, tax jurisdiction VARCHAR(2) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, tax responsible party VARCHAR(7) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, invoice VARCHAR(16) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_buyer VARCHAR(78) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_seller VARCHAR(219) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, archived VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, shipping_address VARCHAR(177) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, buyer_extra VARCHAR(414) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, last_activity VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, currency VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, from_offer VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, offer_original_price INT DEFAULT NULL, shipping_method VARCHAR(13) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE table 8 (buyer VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_num VARCHAR(11) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, status VARCHAR(31) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, total INT DEFAULT NULL, shipping VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, fee INT DEFAULT NULL, tax INT DEFAULT NULL, taxed amount INT DEFAULT NULL, tax jurisdiction VARCHAR(2) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, tax responsible party VARCHAR(7) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, invoice VARCHAR(16) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_buyer VARCHAR(78) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_seller VARCHAR(219) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, archived VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, shipping_address VARCHAR(177) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, buyer_extra VARCHAR(414) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, last_activity VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, currency VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, from_offer VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, offer_original_price INT DEFAULT NULL, shipping_method VARCHAR(13) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE table 9 (buyer VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_num VARCHAR(11) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, order_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, status VARCHAR(31) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, total INT DEFAULT NULL, shipping VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, fee INT DEFAULT NULL, tax INT DEFAULT NULL, taxed amount INT DEFAULT NULL, tax jurisdiction VARCHAR(2) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, tax responsible party VARCHAR(7) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, invoice VARCHAR(16) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_buyer_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rating_of_seller_date VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_buyer VARCHAR(78) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, comment_about_seller VARCHAR(219) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, archived VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, shipping_address VARCHAR(177) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, buyer_extra VARCHAR(414) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, last_activity VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, currency VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, from_offer VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, offer_original_price INT DEFAULT NULL, shipping_method VARCHAR(13) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
    }
}
