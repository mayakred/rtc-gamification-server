<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160924035340 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE achievements__achievements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE achievements__achievements (id INT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, max_value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FC6757763DA5256D ON achievements__achievements (image_id)');
        $this->addSql('CREATE TABLE achievements__achievements_users (achievement_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(achievement_id, user_id))');
        $this->addSql('CREATE INDEX IDX_D3DCF17CB3EC99FE ON achievements__achievements_users (achievement_id)');
        $this->addSql('CREATE INDEX IDX_D3DCF17CA76ED395 ON achievements__achievements_users (user_id)');
        $this->addSql('ALTER TABLE achievements__achievements ADD CONSTRAINT FK_FC6757763DA5256D FOREIGN KEY (image_id) REFERENCES app__images (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE achievements__achievements_users ADD CONSTRAINT FK_D3DCF17CB3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievements__achievements (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE achievements__achievements_users ADD CONSTRAINT FK_D3DCF17CA76ED395 FOREIGN KEY (user_id) REFERENCES app__users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE achievements__achievements_users DROP CONSTRAINT FK_D3DCF17CB3EC99FE');
        $this->addSql('DROP SEQUENCE achievements__achievements_id_seq CASCADE');
        $this->addSql('DROP TABLE achievements__achievements');
        $this->addSql('DROP TABLE achievements__achievements_users');
    }
}
