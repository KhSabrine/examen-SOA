<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230102103920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ligne_devis_id INTEGER DEFAULT NULL, num_article INTEGER NOT NULL, libelle VARCHAR(255) NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, qte_stock INTEGER NOT NULL, CONSTRAINT FK_23A0E661E44EBEC FOREIGN KEY (ligne_devis_id) REFERENCES ligne_devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_23A0E661E44EBEC ON article (ligne_devis_id)');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cin INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, tel INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE devis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, num_devis INTEGER NOT NULL, date_devis DATE NOT NULL, CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8B27C52B19EB6921 ON devis (client_id)');
        $this->addSql('CREATE TABLE ligne_devis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, devis_id INTEGER DEFAULT NULL, article_id INTEGER DEFAULT NULL, qte DOUBLE PRECISION NOT NULL, CONSTRAINT FK_888B2F1B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_888B2F1B7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_888B2F1B41DEFADA ON ligne_devis (devis_id)');
        $this->addSql('CREATE INDEX IDX_888B2F1B7294869C ON ligne_devis (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE ligne_devis');
    }
}
