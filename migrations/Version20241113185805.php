<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113185805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE micro_post (id SERIAL NOT NULL, author_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2AEFE017F675F31B ON micro_post (author_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, nombre VARCHAR(255) NOT NULL, apellido VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
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
        $this->addSql('ALTER TABLE micro_post ADD CONSTRAINT FK_2AEFE017F675F31B FOREIGN KEY (author_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alquiler ALTER usuario_id SET NOT NULL');
        $this->addSql('ALTER TABLE alquiler ALTER fecha_fin TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE alquiler ALTER fecha_inicio TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE producto ALTER imagen SET NOT NULL');
        $this->addSql('DROP INDEX IDX_E03211B85A921E97');
        $this->addSql('DROP INDEX IDX_E03211B87645698E');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E03211B85A921E97 ON ticket_alquiler (alquiler_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E03211B87645698E ON ticket_alquiler (producto_id)');
        $this->addSql('DROP INDEX IDX_E4835B34F2A5805D');
        $this->addSql('DROP INDEX IDX_E4835B347645698E');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4835B34F2A5805D ON ticket_compra (venta_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4835B347645698E ON ticket_compra (producto_id)');
        $this->addSql('DROP INDEX IDX_D482DCEFAB0096FA');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D482DCEFAB0096FA ON ticket_reparacion (reparacion_id)');
        $this->addSql('ALTER TABLE usuario ADD rol INT NOT NULL');
        $this->addSql('ALTER TABLE usuario ALTER imagen SET NOT NULL');
        $this->addSql('ALTER TABLE venta ALTER fecha TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE venta ALTER precio SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE micro_post DROP CONSTRAINT FK_2AEFE017F675F31B');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE alquiler ALTER usuario_id DROP NOT NULL');
        $this->addSql('ALTER TABLE alquiler ALTER fecha_inicio TYPE DATE');
        $this->addSql('ALTER TABLE alquiler ALTER fecha_fin TYPE DATE');
        $this->addSql('ALTER TABLE venta ALTER fecha TYPE DATE');
        $this->addSql('ALTER TABLE venta ALTER precio DROP NOT NULL');
        $this->addSql('ALTER TABLE usuario DROP rol');
        $this->addSql('ALTER TABLE usuario ALTER imagen DROP NOT NULL');
        $this->addSql('ALTER TABLE producto ALTER imagen DROP NOT NULL');
        $this->addSql('DROP INDEX UNIQ_E03211B85A921E97');
        $this->addSql('DROP INDEX UNIQ_E03211B87645698E');
        $this->addSql('CREATE INDEX IDX_E03211B85A921E97 ON ticket_alquiler (alquiler_id)');
        $this->addSql('CREATE INDEX IDX_E03211B87645698E ON ticket_alquiler (producto_id)');
        $this->addSql('DROP INDEX UNIQ_D482DCEFAB0096FA');
        $this->addSql('CREATE INDEX IDX_D482DCEFAB0096FA ON ticket_reparacion (reparacion_id)');
        $this->addSql('DROP INDEX UNIQ_E4835B34F2A5805D');
        $this->addSql('DROP INDEX UNIQ_E4835B347645698E');
        $this->addSql('CREATE INDEX IDX_E4835B34F2A5805D ON ticket_compra (venta_id)');
        $this->addSql('CREATE INDEX IDX_E4835B347645698E ON ticket_compra (producto_id)');
    }
}
