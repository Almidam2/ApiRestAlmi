<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104074301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alquiler (id SERIAL NOT NULL, usuario_id INT DEFAULT NULL, fecha_inicio VARCHAR(255) NOT NULL, fecha_fin DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_655BED39DB38439E ON alquiler (usuario_id)');
        $this->addSql('ALTER TABLE alquiler ADD CONSTRAINT FK_655BED39DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE alquiler DROP CONSTRAINT FK_655BED39DB38439E');
        $this->addSql('DROP TABLE alquiler');
    }
}
