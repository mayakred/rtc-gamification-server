<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160923202847 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE events__events_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE events__sales_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE events__events (id INT NOT NULL, user_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) CHECK(type IN (\'sale\', \'call\', \'meeting\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FCD5C9D3A76ED395 ON events__events (user_id)');
        $this->addSql('COMMENT ON COLUMN events__events.type IS \'(DC2Type:EventType)\'');
        $this->addSql('CREATE TABLE events__meetings (id INT NOT NULL, result BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE events__calls (id INT NOT NULL, call_type VARCHAR(255) CHECK(call_type IN (\'cold\', \'hot\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN events__calls.call_type IS \'(DC2Type:CallType)\'');
        $this->addSql('CREATE TABLE events__sales (id INT NOT NULL, total INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE events__sales_items (id INT NOT NULL, sale_event_id INT NOT NULL, service VARCHAR(255) NOT NULL, amount INT NOT NULL, cost INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D074D1F56F830421 ON events__sales_items (sale_event_id)');
        $this->addSql('ALTER TABLE events__events ADD CONSTRAINT FK_FCD5C9D3A76ED395 FOREIGN KEY (user_id) REFERENCES app__users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__meetings ADD CONSTRAINT FK_F3CA72A0BF396750 FOREIGN KEY (id) REFERENCES events__events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__calls ADD CONSTRAINT FK_82268926BF396750 FOREIGN KEY (id) REFERENCES events__events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__sales ADD CONSTRAINT FK_3304A5EDBF396750 FOREIGN KEY (id) REFERENCES events__events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__sales_items ADD CONSTRAINT FK_D074D1F56F830421 FOREIGN KEY (sale_event_id) REFERENCES events__sales (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE events__meetings DROP CONSTRAINT FK_F3CA72A0BF396750');
        $this->addSql('ALTER TABLE events__calls DROP CONSTRAINT FK_82268926BF396750');
        $this->addSql('ALTER TABLE events__sales DROP CONSTRAINT FK_3304A5EDBF396750');
        $this->addSql('ALTER TABLE events__sales_items DROP CONSTRAINT FK_D074D1F56F830421');
        $this->addSql('DROP SEQUENCE events__events_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE events__sales_items_id_seq CASCADE');
        $this->addSql('DROP TABLE events__events');
        $this->addSql('DROP TABLE events__meetings');
        $this->addSql('DROP TABLE events__calls');
        $this->addSql('DROP TABLE events__sales');
        $this->addSql('DROP TABLE events__sales_items');
    }
}
