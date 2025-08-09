<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250809162722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE session (id SERIAL NOT NULL, auth_user_id INT DEFAULT NULL, refresh_token VARCHAR(255) NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_agent VARCHAR(255) NOT NULL, ip_address VARCHAR(15) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D044D5D4E94AF366 ON session (auth_user_id)');
        $this->addSql('COMMENT ON COLUMN session.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4E94AF366 FOREIGN KEY (auth_user_id) REFERENCES auth_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT FK_D044D5D4E94AF366');
        $this->addSql('DROP TABLE session');
    }
}
