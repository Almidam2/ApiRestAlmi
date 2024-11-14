<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104074542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket_alquiler (id SERIAL NOT NULL, alquiler_id INT NOT NULL, producto_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E03211B85A921E97 ON ticket_alquiler (alquiler_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E03211B87645698E ON ticket_alquiler (producto_id)');
        $this->addSql('ALTER TABLE ticket_alquiler ADD CONSTRAINT FK_E03211B85A921E97 FOREIGN KEY (alquiler_id) REFERENCES alquiler (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_alquiler ADD CONSTRAINT FK_E03211B87645698E FOREIGN KEY (producto_id) REFERENCES producto (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ticket_alquiler DROP CONSTRAINT FK_E03211B85A921E97');
        $this->addSql('ALTER TABLE ticket_alquiler DROP CONSTRAINT FK_E03211B87645698E');
        $this->addSql('DROP TABLE ticket_alquiler');
    }
}
