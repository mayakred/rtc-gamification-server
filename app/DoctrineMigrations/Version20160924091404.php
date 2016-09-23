<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160924091404 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tournaments__tournaments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournaments__teams_results_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournaments__teams_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tournaments__tournaments (id INT NOT NULL, name VARCHAR(255) NOT NULL, startDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, endDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) CHECK(type IN (\'team\', \'individual\')) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN tournaments__tournaments.type IS \'(DC2Type:TournamentType)\'');
        $this->addSql('CREATE TABLE tournaments__teams_results (id INT NOT NULL, team_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1C559BD6296CD8AE ON tournaments__teams_results (team_id)');
        $this->addSql('CREATE TABLE tournaments__teams (id INT NOT NULL, department_id INT DEFAULT NULL, tournament_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_201FA554AE80F5DF ON tournaments__teams (department_id)');
        $this->addSql('CREATE INDEX IDX_201FA55433D1A3E7 ON tournaments__teams (tournament_id)');
        $this->addSql('ALTER TABLE tournaments__teams_results ADD CONSTRAINT FK_1C559BD6296CD8AE FOREIGN KEY (team_id) REFERENCES tournaments__teams (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams ADD CONSTRAINT FK_201FA554AE80F5DF FOREIGN KEY (department_id) REFERENCES app__departments (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams ADD CONSTRAINT FK_201FA55433D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments__tournaments (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tournaments__teams DROP CONSTRAINT FK_201FA55433D1A3E7');
        $this->addSql('ALTER TABLE tournaments__teams_results DROP CONSTRAINT FK_1C559BD6296CD8AE');
        $this->addSql('DROP SEQUENCE tournaments__tournaments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournaments__teams_results_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournaments__teams_id_seq CASCADE');
        $this->addSql('DROP TABLE tournaments__tournaments');
        $this->addSql('DROP TABLE tournaments__teams_results');
        $this->addSql('DROP TABLE tournaments__teams');
    }
}
