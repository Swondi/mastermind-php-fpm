<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250811030757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auth_user ADD data_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE auth_user ADD CONSTRAINT FK_A3B536FDC3658564 FOREIGN KEY (data_user_id) REFERENCES data_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A3B536FDC3658564 ON auth_user (data_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auth_user DROP CONSTRAINT FK_A3B536FDC3658564');
        $this->addSql('DROP INDEX UNIQ_A3B536FDC3658564');
        $this->addSql('ALTER TABLE auth_user DROP data_user_id');
    }
}
