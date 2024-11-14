<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104074718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reparacion (id SERIAL NOT NULL, usuario_id INT NOT NULL, fecha DATE NOT NULL, descripcion VARCHAR(500) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42114EBBDB38439E ON reparacion (usuario_id)');
        $this->addSql('ALTER TABLE reparacion ADD CONSTRAINT FK_42114EBBDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reparacion DROP CONSTRAINT FK_42114EBBDB38439E');
        $this->addSql('DROP TABLE reparacion');
    }
}
