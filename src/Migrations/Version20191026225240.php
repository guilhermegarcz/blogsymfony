<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191026225240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_23A0E66A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, user_id, title, text, date FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL COLLATE BINARY, text VARCHAR(280) NOT NULL COLLATE BINARY, date DATETIME NOT NULL, thumbnail VARCHAR(255) NOT NULL, CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, user_id, title, text, date) SELECT id, user_id, title, text, date FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('DROP INDEX IDX_10FA0198BAD26311');
        $this->addSql('DROP INDEX IDX_10FA01987294869C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articleTags AS SELECT article_id, tag_id FROM articleTags');
        $this->addSql('DROP TABLE articleTags');
        $this->addSql('CREATE TABLE articleTags (article_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(article_id, tag_id), CONSTRAINT FK_10FA01987294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_10FA0198BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO articleTags (article_id, tag_id) SELECT article_id, tag_id FROM __temp__articleTags');
        $this->addSql('DROP TABLE __temp__articleTags');
        $this->addSql('CREATE INDEX IDX_10FA0198BAD26311 ON articleTags (tag_id)');
        $this->addSql('CREATE INDEX IDX_10FA01987294869C ON articleTags (article_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_23A0E66A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, user_id, title, text, date FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL, text VARCHAR(280) NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO article (id, user_id, title, text, date) SELECT id, user_id, title, text, date FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('DROP INDEX IDX_10FA01987294869C');
        $this->addSql('DROP INDEX IDX_10FA0198BAD26311');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articleTags AS SELECT article_id, tag_id FROM articleTags');
        $this->addSql('DROP TABLE articleTags');
        $this->addSql('CREATE TABLE articleTags (article_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(article_id, tag_id))');
        $this->addSql('INSERT INTO articleTags (article_id, tag_id) SELECT article_id, tag_id FROM __temp__articleTags');
        $this->addSql('DROP TABLE __temp__articleTags');
        $this->addSql('CREATE INDEX IDX_10FA01987294869C ON articleTags (article_id)');
        $this->addSql('CREATE INDEX IDX_10FA0198BAD26311 ON articleTags (tag_id)');
    }
}
