<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160924035228 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE media__gallery_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media__gallery_media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media__media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE classification__category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE classification__collection_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE classification__tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE events__events_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE events__sales_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournaments__tournaments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournaments__metrics_conditions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournaments__teams_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournaments__teams_participants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournaments__teams_results_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media__gallery (id INT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media__gallery_media (id INT NOT NULL, gallery_id INT DEFAULT NULL, media_id INT DEFAULT NULL, position INT NOT NULL, enabled BOOLEAN NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_80D4C5414E7AF8F ON media__gallery_media (gallery_id)');
        $this->addSql('CREATE INDEX IDX_80D4C541EA9FDD75 ON media__gallery_media (media_id)');
        $this->addSql('CREATE TABLE media__media (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled BOOLEAN NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata TEXT DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable BOOLEAN DEFAULT NULL, cdn_flush_identifier VARCHAR(64) DEFAULT NULL, cdn_flush_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN media__media.provider_metadata IS \'(DC2Type:json)\'');
        $this->addSql('CREATE TABLE classification__category (id INT NOT NULL, parent_id INT DEFAULT NULL, context VARCHAR(255) DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_43629B36727ACA70 ON classification__category (parent_id)');
        $this->addSql('CREATE INDEX IDX_43629B36E25D857E ON classification__category (context)');
        $this->addSql('CREATE INDEX IDX_43629B36EA9FDD75 ON classification__category (media_id)');
        $this->addSql('CREATE TABLE classification__collection (id INT NOT NULL, context VARCHAR(255) DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A406B56AE25D857E ON classification__collection (context)');
        $this->addSql('CREATE INDEX IDX_A406B56AEA9FDD75 ON classification__collection (media_id)');
        $this->addSql('CREATE UNIQUE INDEX tag_collection ON classification__collection (slug, context)');
        $this->addSql('CREATE TABLE classification__context (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE classification__tag (id INT NOT NULL, context VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, slug VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CA57A1C7E25D857E ON classification__tag (context)');
        $this->addSql('CREATE UNIQUE INDEX tag_context ON classification__tag (slug, context)');
        $this->addSql('CREATE TABLE app__access_tokens (id SERIAL NOT NULL, device_id INT DEFAULT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, player_id VARCHAR(400) DEFAULT NULL, since TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_287B566C5F37A13B ON app__access_tokens (token)');
        $this->addSql('CREATE INDEX IDX_287B566C94A4C7D4 ON app__access_tokens (device_id)');
        $this->addSql('CREATE INDEX IDX_287B566CA76ED395 ON app__access_tokens (user_id)');
        $this->addSql('CREATE TABLE events__events (id INT NOT NULL, user_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) CHECK(type IN (\'sale\', \'call\', \'meeting\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FCD5C9D3A76ED395 ON events__events (user_id)');
        $this->addSql('COMMENT ON COLUMN events__events.type IS \'(DC2Type:EventType)\'');
        $this->addSql('CREATE TABLE events__calls (id INT NOT NULL, call_type VARCHAR(255) CHECK(call_type IN (\'cold\', \'hot\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN events__calls.call_type IS \'(DC2Type:CallType)\'');
        $this->addSql('CREATE TABLE app__departments (id SERIAL NOT NULL, code VARCHAR(255) CHECK(code IN (\'department_type.service\', \'department_type.federal_clients\', \'department_type.active_sales\')) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A9D10AB77153098 ON app__departments (code)');
        $this->addSql('COMMENT ON COLUMN app__departments.code IS \'(DC2Type:DepartmentType)\'');
        $this->addSql('CREATE TABLE app__devices (id SERIAL NOT NULL, device_id VARCHAR(255) NOT NULL, platform VARCHAR(255) CHECK(platform IN (\'android\', \'ios\')) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DCFCF4A94A4C7D4 ON app__devices (device_id)');
        $this->addSql('COMMENT ON COLUMN app__devices.platform IS \'(DC2Type:PlatformType)\'');
        $this->addSql('CREATE TABLE app__duels (id SERIAL NOT NULL, victim_id INT NOT NULL, initiator_id INT NOT NULL, metric_id INT NOT NULL, victim_value DOUBLE PRECISION NOT NULL, initiator_value DOUBLE PRECISION NOT NULL, status VARCHAR(255) CHECK(status IN (\'duel_status_type.waiting_victim\', \'duel_status_type.in_progress\', \'duel_status_type.victim_win\', \'duel_status_type.initiator_win\', \'duel_status_type.draw\', \'duel_status_type.rejected_by_victim\')) NOT NULL, since TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D712FAC444972A0E ON app__duels (victim_id)');
        $this->addSql('CREATE INDEX IDX_D712FAC47DB3B714 ON app__duels (initiator_id)');
        $this->addSql('CREATE INDEX IDX_D712FAC4A952D583 ON app__duels (metric_id)');
        $this->addSql('COMMENT ON COLUMN app__duels.status IS \'(DC2Type:DuelStatusType)\'');
        $this->addSql('CREATE TABLE app__images (id SERIAL NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled BOOLEAN NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata TEXT DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable BOOLEAN DEFAULT NULL, cdn_flush_identifier VARCHAR(64) DEFAULT NULL, cdn_flush_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, fixture BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F471D9A412469DE2 ON app__images (category_id)');
        $this->addSql('COMMENT ON COLUMN app__images.provider_metadata IS \'(DC2Type:json)\'');
        $this->addSql('CREATE TABLE events__meetings (id INT NOT NULL, result BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app__metrics (id SERIAL NOT NULL, code VARCHAR(255) NOT NULL, unit_type VARCHAR(255) CHECK(unit_type IN (\'unit_type.percent\', \'unit_type.rubles\', \'unit_type.units\')) NOT NULL, name VARCHAR(255) NOT NULL, available_for_individual_tournaments BOOLEAN NOT NULL, available_for_team_tournaments BOOLEAN NOT NULL, available_for_duel BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E422B3777153098 ON app__metrics (code)');
        $this->addSql('COMMENT ON COLUMN app__metrics.unit_type IS \'(DC2Type:UnitType)\'');
        $this->addSql('CREATE TABLE app__phones (id SERIAL NOT NULL, user_id INT DEFAULT NULL, phone VARCHAR(255) NOT NULL, since TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F746493BA76ED395 ON app__phones (user_id)');
        $this->addSql('CREATE TABLE events__sales (id INT NOT NULL, total INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE events__sales_items (id INT NOT NULL, sale_event_id INT NOT NULL, service VARCHAR(255) NOT NULL, amount INT NOT NULL, cost INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D074D1F56F830421 ON events__sales_items (sale_event_id)');
        $this->addSql('CREATE TABLE tournaments__tournaments (id INT NOT NULL, name VARCHAR(255) NOT NULL, startDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, endDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) CHECK(type IN (\'team\', \'individual\')) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN tournaments__tournaments.type IS \'(DC2Type:TournamentType)\'');
        $this->addSql('CREATE TABLE tournaments__metrics_conditions (id INT NOT NULL, tournament_id INT NOT NULL, metric_id INT NOT NULL, department_id INT NOT NULL, amount_limit INT NOT NULL, money_limit INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A6B75B4133D1A3E7 ON tournaments__metrics_conditions (tournament_id)');
        $this->addSql('CREATE INDEX IDX_A6B75B41A952D583 ON tournaments__metrics_conditions (metric_id)');
        $this->addSql('CREATE INDEX IDX_A6B75B41AE80F5DF ON tournaments__metrics_conditions (department_id)');
        $this->addSql('CREATE TABLE tournaments__teams (id INT NOT NULL, department_id INT DEFAULT NULL, tournament_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_201FA554AE80F5DF ON tournaments__teams (department_id)');
        $this->addSql('CREATE INDEX IDX_201FA55433D1A3E7 ON tournaments__teams (tournament_id)');
        $this->addSql('CREATE TABLE tournaments__teams_participants (id INT NOT NULL, team_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7007BF09296CD8AE ON tournaments__teams_participants (team_id)');
        $this->addSql('CREATE INDEX IDX_7007BF09A76ED395 ON tournaments__teams_participants (user_id)');
        $this->addSql('CREATE TABLE tournaments__teams_results (id INT NOT NULL, team_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1C559BD6296CD8AE ON tournaments__teams_results (team_id)');
        $this->addSql('CREATE TABLE app__users (id SERIAL NOT NULL, department_id INT DEFAULT NULL, avatar_id INT DEFAULT NULL, secret VARCHAR(255) DEFAULT NULL, sms_code VARCHAR(255) DEFAULT NULL, sms_code_dt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, second_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) CHECK(gender IN (\'male\', \'female\')) NOT NULL, rating INT NOT NULL, top_position INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7BB824F5AE80F5DF ON app__users (department_id)');
        $this->addSql('CREATE INDEX IDX_7BB824F586383B10 ON app__users (avatar_id)');
        $this->addSql('COMMENT ON COLUMN app__users.gender IS \'(DC2Type:GenderType)\'');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C5414E7AF8F FOREIGN KEY (gallery_id) REFERENCES media__gallery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C541EA9FDD75 FOREIGN KEY (media_id) REFERENCES app__images (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36727ACA70 FOREIGN KEY (parent_id) REFERENCES classification__category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36E25D857E FOREIGN KEY (context) REFERENCES classification__context (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AE25D857E FOREIGN KEY (context) REFERENCES classification__context (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__tag ADD CONSTRAINT FK_CA57A1C7E25D857E FOREIGN KEY (context) REFERENCES classification__context (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__access_tokens ADD CONSTRAINT FK_287B566C94A4C7D4 FOREIGN KEY (device_id) REFERENCES app__devices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__access_tokens ADD CONSTRAINT FK_287B566CA76ED395 FOREIGN KEY (user_id) REFERENCES app__users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__events ADD CONSTRAINT FK_FCD5C9D3A76ED395 FOREIGN KEY (user_id) REFERENCES app__users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__calls ADD CONSTRAINT FK_82268926BF396750 FOREIGN KEY (id) REFERENCES events__events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__duels ADD CONSTRAINT FK_D712FAC444972A0E FOREIGN KEY (victim_id) REFERENCES app__users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__duels ADD CONSTRAINT FK_D712FAC47DB3B714 FOREIGN KEY (initiator_id) REFERENCES app__users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__duels ADD CONSTRAINT FK_D712FAC4A952D583 FOREIGN KEY (metric_id) REFERENCES app__metrics (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__images ADD CONSTRAINT FK_F471D9A412469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__meetings ADD CONSTRAINT FK_F3CA72A0BF396750 FOREIGN KEY (id) REFERENCES events__events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__phones ADD CONSTRAINT FK_F746493BA76ED395 FOREIGN KEY (user_id) REFERENCES app__users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__sales ADD CONSTRAINT FK_3304A5EDBF396750 FOREIGN KEY (id) REFERENCES events__events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events__sales_items ADD CONSTRAINT FK_D074D1F56F830421 FOREIGN KEY (sale_event_id) REFERENCES events__sales (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__metrics_conditions ADD CONSTRAINT FK_A6B75B4133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments__tournaments (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__metrics_conditions ADD CONSTRAINT FK_A6B75B41A952D583 FOREIGN KEY (metric_id) REFERENCES app__metrics (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__metrics_conditions ADD CONSTRAINT FK_A6B75B41AE80F5DF FOREIGN KEY (department_id) REFERENCES app__departments (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams ADD CONSTRAINT FK_201FA554AE80F5DF FOREIGN KEY (department_id) REFERENCES app__departments (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams ADD CONSTRAINT FK_201FA55433D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments__tournaments (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams_participants ADD CONSTRAINT FK_7007BF09296CD8AE FOREIGN KEY (team_id) REFERENCES tournaments__teams (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams_participants ADD CONSTRAINT FK_7007BF09A76ED395 FOREIGN KEY (user_id) REFERENCES app__users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournaments__teams_results ADD CONSTRAINT FK_1C559BD6296CD8AE FOREIGN KEY (team_id) REFERENCES tournaments__teams (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__users ADD CONSTRAINT FK_7BB824F5AE80F5DF FOREIGN KEY (department_id) REFERENCES app__departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__users ADD CONSTRAINT FK_7BB824F586383B10 FOREIGN KEY (avatar_id) REFERENCES app__images (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media__gallery_media DROP CONSTRAINT FK_80D4C5414E7AF8F');
        $this->addSql('ALTER TABLE classification__category DROP CONSTRAINT FK_43629B36EA9FDD75');
        $this->addSql('ALTER TABLE classification__collection DROP CONSTRAINT FK_A406B56AEA9FDD75');
        $this->addSql('ALTER TABLE classification__category DROP CONSTRAINT FK_43629B36727ACA70');
        $this->addSql('ALTER TABLE app__images DROP CONSTRAINT FK_F471D9A412469DE2');
        $this->addSql('ALTER TABLE classification__category DROP CONSTRAINT FK_43629B36E25D857E');
        $this->addSql('ALTER TABLE classification__collection DROP CONSTRAINT FK_A406B56AE25D857E');
        $this->addSql('ALTER TABLE classification__tag DROP CONSTRAINT FK_CA57A1C7E25D857E');
        $this->addSql('ALTER TABLE events__calls DROP CONSTRAINT FK_82268926BF396750');
        $this->addSql('ALTER TABLE events__meetings DROP CONSTRAINT FK_F3CA72A0BF396750');
        $this->addSql('ALTER TABLE events__sales DROP CONSTRAINT FK_3304A5EDBF396750');
        $this->addSql('ALTER TABLE tournaments__metrics_conditions DROP CONSTRAINT FK_A6B75B41AE80F5DF');
        $this->addSql('ALTER TABLE tournaments__teams DROP CONSTRAINT FK_201FA554AE80F5DF');
        $this->addSql('ALTER TABLE app__users DROP CONSTRAINT FK_7BB824F5AE80F5DF');
        $this->addSql('ALTER TABLE app__access_tokens DROP CONSTRAINT FK_287B566C94A4C7D4');
        $this->addSql('ALTER TABLE media__gallery_media DROP CONSTRAINT FK_80D4C541EA9FDD75');
        $this->addSql('ALTER TABLE app__users DROP CONSTRAINT FK_7BB824F586383B10');
        $this->addSql('ALTER TABLE app__duels DROP CONSTRAINT FK_D712FAC4A952D583');
        $this->addSql('ALTER TABLE tournaments__metrics_conditions DROP CONSTRAINT FK_A6B75B41A952D583');
        $this->addSql('ALTER TABLE events__sales_items DROP CONSTRAINT FK_D074D1F56F830421');
        $this->addSql('ALTER TABLE tournaments__metrics_conditions DROP CONSTRAINT FK_A6B75B4133D1A3E7');
        $this->addSql('ALTER TABLE tournaments__teams DROP CONSTRAINT FK_201FA55433D1A3E7');
        $this->addSql('ALTER TABLE tournaments__teams_participants DROP CONSTRAINT FK_7007BF09296CD8AE');
        $this->addSql('ALTER TABLE tournaments__teams_results DROP CONSTRAINT FK_1C559BD6296CD8AE');
        $this->addSql('ALTER TABLE app__access_tokens DROP CONSTRAINT FK_287B566CA76ED395');
        $this->addSql('ALTER TABLE events__events DROP CONSTRAINT FK_FCD5C9D3A76ED395');
        $this->addSql('ALTER TABLE app__duels DROP CONSTRAINT FK_D712FAC444972A0E');
        $this->addSql('ALTER TABLE app__duels DROP CONSTRAINT FK_D712FAC47DB3B714');
        $this->addSql('ALTER TABLE app__phones DROP CONSTRAINT FK_F746493BA76ED395');
        $this->addSql('ALTER TABLE tournaments__teams_participants DROP CONSTRAINT FK_7007BF09A76ED395');
        $this->addSql('DROP SEQUENCE media__gallery_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media__gallery_media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media__media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE classification__category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE classification__collection_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE classification__tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE events__events_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE events__sales_items_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournaments__tournaments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournaments__metrics_conditions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournaments__teams_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournaments__teams_participants_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournaments__teams_results_id_seq CASCADE');
        $this->addSql('DROP TABLE media__gallery');
        $this->addSql('DROP TABLE media__gallery_media');
        $this->addSql('DROP TABLE media__media');
        $this->addSql('DROP TABLE classification__category');
        $this->addSql('DROP TABLE classification__collection');
        $this->addSql('DROP TABLE classification__context');
        $this->addSql('DROP TABLE classification__tag');
        $this->addSql('DROP TABLE app__access_tokens');
        $this->addSql('DROP TABLE events__events');
        $this->addSql('DROP TABLE events__calls');
        $this->addSql('DROP TABLE app__departments');
        $this->addSql('DROP TABLE app__devices');
        $this->addSql('DROP TABLE app__duels');
        $this->addSql('DROP TABLE app__images');
        $this->addSql('DROP TABLE events__meetings');
        $this->addSql('DROP TABLE app__metrics');
        $this->addSql('DROP TABLE app__phones');
        $this->addSql('DROP TABLE events__sales');
        $this->addSql('DROP TABLE events__sales_items');
        $this->addSql('DROP TABLE tournaments__tournaments');
        $this->addSql('DROP TABLE tournaments__metrics_conditions');
        $this->addSql('DROP TABLE tournaments__teams');
        $this->addSql('DROP TABLE tournaments__teams_participants');
        $this->addSql('DROP TABLE tournaments__teams_results');
        $this->addSql('DROP TABLE app__users');
    }
}
