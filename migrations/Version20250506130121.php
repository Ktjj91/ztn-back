<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506130121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE note_key (id SERIAL NOT NULL, note_id INT NOT NULL, recipient UUID NOT NULL, enc_key BYTEA NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FA638B4926ED0855 ON note_key (note_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note_key ADD CONSTRAINT FK_FA638B4926ED0855 FOREIGN KEY (note_id) REFERENCES note (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note_key DROP CONSTRAINT FK_FA638B4926ED0855
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE note_key
        SQL);
    }
}
