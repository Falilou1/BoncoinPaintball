<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204230737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, reference VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E52FFDEEAEA34913 (reference), INDEX IDX_E52FFDEE67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_details (adverts_id INT NOT NULL, orders_id INT NOT NULL, quantity INT NOT NULL, price INT NOT NULL, INDEX IDX_835379F1C5A3D550 (adverts_id), INDEX IDX_835379F1CFFE9AD6 (orders_id), PRIMARY KEY(adverts_id, orders_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1C5A3D550 FOREIGN KEY (adverts_id) REFERENCES adverts (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96C5A3D550 FOREIGN KEY (adverts_id) REFERENCES adverts (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE67B3B43D');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1C5A3D550');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1CFFE9AD6');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_details');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96C5A3D550');
    }
}
