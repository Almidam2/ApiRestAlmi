<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104074826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket_reparacion (id SERIAL NOT NULL, reparacion_id INT NOT NULL, producto_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D482DCEFAB0096FA ON ticket_reparacion (reparacion_id)');
        $this->addSql('CREATE INDEX IDX_D482DCEF7645698E ON ticket_reparacion (producto_id)');
        $this->addSql('ALTER TABLE ticket_reparacion ADD CONSTRAINT FK_D482DCEFAB0096FA FOREIGN KEY (reparacion_id) REFERENCES reparacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_reparacion ADD CONSTRAINT FK_D482DCEF7645698E FOREIGN KEY (producto_id) REFERENCES producto (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ticket_reparacion DROP CONSTRAINT FK_D482DCEFAB0096FA');
        $this->addSql('ALTER TABLE ticket_reparacion DROP CONSTRAINT FK_D482DCEF7645698E');
        $this->addSql('DROP TABLE ticket_reparacion');
    }
}
