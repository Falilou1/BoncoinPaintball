<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923074235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96C5A3D550 FOREIGN KEY (adverts_id) REFERENCES adverts (id)');
        $this->addSql('CREATE INDEX IDX_DB021E96C5A3D550 ON messages (adverts_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96C5A3D550');
        $this->addSql('DROP INDEX IDX_DB021E96C5A3D550 ON messages');
        $this->addSql('ALTER TABLE messages DROP adverts_id');
    }
}
