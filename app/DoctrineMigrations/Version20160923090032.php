<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160923090032 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE app__users ADD first_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__users ADD second_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__users ADD middle_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__users ADD gender VARCHAR(255) CHECK(gender IN (\'male\', \'female\')) NOT NULL');
        $this->addSql('ALTER TABLE app__users ADD rating INT NOT NULL');
        $this->addSql('COMMENT ON COLUMN app__users.gender IS \'(DC2Type:GenderType)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app__users DROP first_name');
        $this->addSql('ALTER TABLE app__users DROP second_name');
        $this->addSql('ALTER TABLE app__users DROP middle_name');
        $this->addSql('ALTER TABLE app__users DROP gender');
        $this->addSql('ALTER TABLE app__users DROP rating');
    }
}
