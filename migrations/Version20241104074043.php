<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104074043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE venta ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE venta ADD fecha VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE venta ADD CONSTRAINT FK_8FE7EE55DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8FE7EE55DB38439E ON venta (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE venta DROP CONSTRAINT FK_8FE7EE55DB38439E');
        $this->addSql('DROP INDEX IDX_8FE7EE55DB38439E');
        $this->addSql('ALTER TABLE venta DROP usuario_id');
        $this->addSql('ALTER TABLE venta DROP fecha');
    }
}
