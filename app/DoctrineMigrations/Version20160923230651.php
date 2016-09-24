<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160923230651 extends AbstractMigration
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
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C5414E7AF8F FOREIGN KEY (gallery_id) REFERENCES media__gallery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C541EA9FDD75 FOREIGN KEY (media_id) REFERENCES app__images (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36727ACA70 FOREIGN KEY (parent_id) REFERENCES classification__category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36E25D857E FOREIGN KEY (context) REFERENCES classification__context (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AE25D857E FOREIGN KEY (context) REFERENCES classification__context (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classification__tag ADD CONSTRAINT FK_CA57A1C7E25D857E FOREIGN KEY (context) REFERENCES classification__context (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app__images ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE app__images ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD enabled BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE app__images ADD provider_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE app__images ADD provider_status INT NOT NULL');
        $this->addSql('ALTER TABLE app__images ADD provider_reference VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE app__images ADD provider_metadata TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD width INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD height INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD length NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD content_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD content_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD copyright VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD author_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD context VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD cdn_is_flushable BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD cdn_flush_identifier VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD cdn_flush_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD cdn_status INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__images ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE app__images ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN app__images.provider_metadata IS \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE app__images ADD CONSTRAINT FK_F471D9A412469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F471D9A412469DE2 ON app__images (category_id)');
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
        $this->addSql('DROP SEQUENCE media__gallery_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media__gallery_media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media__media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE classification__category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE classification__collection_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE classification__tag_id_seq CASCADE');
        $this->addSql('DROP TABLE media__gallery');
        $this->addSql('DROP TABLE media__gallery_media');
        $this->addSql('DROP TABLE media__media');
        $this->addSql('DROP TABLE classification__category');
        $this->addSql('DROP TABLE classification__collection');
        $this->addSql('DROP TABLE classification__context');
        $this->addSql('DROP TABLE classification__tag');
        $this->addSql('DROP INDEX IDX_F471D9A412469DE2');
        $this->addSql('ALTER TABLE app__images DROP category_id');
        $this->addSql('ALTER TABLE app__images DROP name');
        $this->addSql('ALTER TABLE app__images DROP description');
        $this->addSql('ALTER TABLE app__images DROP enabled');
        $this->addSql('ALTER TABLE app__images DROP provider_name');
        $this->addSql('ALTER TABLE app__images DROP provider_status');
        $this->addSql('ALTER TABLE app__images DROP provider_reference');
        $this->addSql('ALTER TABLE app__images DROP provider_metadata');
        $this->addSql('ALTER TABLE app__images DROP width');
        $this->addSql('ALTER TABLE app__images DROP height');
        $this->addSql('ALTER TABLE app__images DROP length');
        $this->addSql('ALTER TABLE app__images DROP content_type');
        $this->addSql('ALTER TABLE app__images DROP content_size');
        $this->addSql('ALTER TABLE app__images DROP copyright');
        $this->addSql('ALTER TABLE app__images DROP author_name');
        $this->addSql('ALTER TABLE app__images DROP context');
        $this->addSql('ALTER TABLE app__images DROP cdn_is_flushable');
        $this->addSql('ALTER TABLE app__images DROP cdn_flush_identifier');
        $this->addSql('ALTER TABLE app__images DROP cdn_flush_at');
        $this->addSql('ALTER TABLE app__images DROP cdn_status');
        $this->addSql('ALTER TABLE app__images DROP updated_at');
        $this->addSql('ALTER TABLE app__images DROP created_at');
    }
}
