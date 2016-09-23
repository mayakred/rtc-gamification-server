<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160923093426 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE app__departments (id SERIAL NOT NULL, code VARCHAR(255) CHECK(code IN (\'department_type.service\', \'department_type.federal_clients\', \'department_type.active_sales\')) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A9D10AB77153098 ON app__departments (code)');
        $this->addSql('COMMENT ON COLUMN app__departments.code IS \'(DC2Type:DepartmentType)\'');
        $this->addSql('ALTER TABLE app__users ADD department_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__users ADD CONSTRAINT FK_7BB824F5AE80F5DF FOREIGN KEY (department_id) REFERENCES app__departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7BB824F5AE80F5DF ON app__users (department_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app__users DROP CONSTRAINT FK_7BB824F5AE80F5DF');
        $this->addSql('DROP TABLE app__departments');
        $this->addSql('DROP INDEX IDX_7BB824F5AE80F5DF');
        $this->addSql('ALTER TABLE app__users DROP department_id');
    }
}
