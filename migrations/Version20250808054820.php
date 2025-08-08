<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250808054820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id SERIAL NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN customer.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE metric (id SERIAL NOT NULL, node_id INT NOT NULL, cpu_usage DOUBLE PRECISION NOT NULL, ram DOUBLE PRECISION NOT NULL, disk DOUBLE PRECISION NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_87D62EE3460D9FD7 ON metric (node_id)');
        $this->addSql('COMMENT ON COLUMN metric.timestamp IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE node (id SERIAL NOT NULL, workspace_id INT NOT NULL, name VARCHAR(32) NOT NULL, url VARCHAR(32) NOT NULL, api_key VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_857FE84582D40A1F ON node (workspace_id)');
        $this->addSql('CREATE TABLE task (id VARCHAR(255) NOT NULL, node_id INT NOT NULL, type VARCHAR(255) NOT NULL, payload JSON NOT NULL, status VARCHAR(255) NOT NULL, error JSON DEFAULT NULL, output JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25460D9FD7 ON task (node_id)');
        $this->addSql('COMMENT ON COLUMN task.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE workspace (id SERIAL NOT NULL, owner_id INT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D9400197E3C61F9 ON workspace (owner_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE metric ADD CONSTRAINT FK_87D62EE3460D9FD7 FOREIGN KEY (node_id) REFERENCES node (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE node ADD CONSTRAINT FK_857FE84582D40A1F FOREIGN KEY (workspace_id) REFERENCES workspace (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25460D9FD7 FOREIGN KEY (node_id) REFERENCES node (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE workspace ADD CONSTRAINT FK_8D9400197E3C61F9 FOREIGN KEY (owner_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE metric DROP CONSTRAINT FK_87D62EE3460D9FD7');
        $this->addSql('ALTER TABLE node DROP CONSTRAINT FK_857FE84582D40A1F');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25460D9FD7');
        $this->addSql('ALTER TABLE workspace DROP CONSTRAINT FK_8D9400197E3C61F9');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE metric');
        $this->addSql('DROP TABLE node');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE workspace');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
