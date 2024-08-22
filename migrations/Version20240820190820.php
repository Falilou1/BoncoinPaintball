<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240820190820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adverts_user (adverts_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A8FC7E73C5A3D550 (adverts_id), INDEX IDX_A8FC7E73A76ED395 (user_id), PRIMARY KEY(adverts_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adverts_user ADD CONSTRAINT FK_A8FC7E73C5A3D550 FOREIGN KEY (adverts_id) REFERENCES adverts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adverts_user ADD CONSTRAINT FK_A8FC7E73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adverts_user DROP FOREIGN KEY FK_A8FC7E73C5A3D550');
        $this->addSql('ALTER TABLE adverts_user DROP FOREIGN KEY FK_A8FC7E73A76ED395');
        $this->addSql('DROP TABLE adverts_user');
    }
}
