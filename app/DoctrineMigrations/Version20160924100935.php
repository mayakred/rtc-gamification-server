<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160924100935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tournaments__teams_participants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tournaments__teams_participants (id INT NOT NULL, team_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7007BF09296CD8AE ON tournaments__teams_participants (team_id)');
        $this->addSql('CREATE INDEX IDX_7007BF09A76ED395 ON tournaments__teams_participants (user_id)');
        $this->addSql('ALTER TABLE tournaments__teams_participants ADD CONSTRAINT FK_7007BF09296CD8AE FOREIGN KEY (team_id) REFERENCES tournaments__teams (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams_participants ADD CONSTRAINT FK_7007BF09A76ED395 FOREIGN KEY (user_id) REFERENCES app__users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tournaments__teams_participants_id_seq CASCADE');
        $this->addSql('DROP TABLE tournaments__teams_participants');
    }
}
