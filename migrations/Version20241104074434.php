<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104074434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket_compra (id SERIAL NOT NULL, venta_id INT NOT NULL, producto_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4835B34F2A5805D ON ticket_compra (venta_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4835B347645698E ON ticket_compra (producto_id)');
        $this->addSql('ALTER TABLE ticket_compra ADD CONSTRAINT FK_E4835B34F2A5805D FOREIGN KEY (venta_id) REFERENCES venta (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_compra ADD CONSTRAINT FK_E4835B347645698E FOREIGN KEY (producto_id) REFERENCES producto (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ticket_compra DROP CONSTRAINT FK_E4835B34F2A5805D');
        $this->addSql('ALTER TABLE ticket_compra DROP CONSTRAINT FK_E4835B347645698E');
        $this->addSql('DROP TABLE ticket_compra');
    }
}
