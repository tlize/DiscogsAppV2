<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511213442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE shipping_address shipping_address LONGTEXT NOT NULL, CHANGE buyer_extra buyer_extra LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item CHANGE shipping_address shipping_address LONGTEXT NOT NULL, CHANGE buyer_extra buyer_extra LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE shipping_address shipping_address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE buyer_extra buyer_extra TINYBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item CHANGE shipping_address shipping_address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE buyer_extra buyer_extra LONGBLOB DEFAULT NULL');
    }
}
