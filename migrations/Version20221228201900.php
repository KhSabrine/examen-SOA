<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228201900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, num_devis INTEGER NOT NULL, date_devis DATE NOT NULL)');
        $this->addSql('CREATE TABLE devis_article (devis_id INTEGER NOT NULL, article_id INTEGER NOT NULL, PRIMARY KEY(devis_id, article_id), CONSTRAINT FK_90D4953A41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_90D4953A7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_90D4953A41DEFADA ON devis_article (devis_id)');
        $this->addSql('CREATE INDEX IDX_90D4953A7294869C ON devis_article (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_article');
    }
}
