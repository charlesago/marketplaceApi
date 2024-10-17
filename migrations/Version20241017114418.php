<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017114418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE created_api (id SERIAL NOT NULL, creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, request_amount_per_sale INT NOT NULL, api_key TEXT NOT NULL, doc_link VARCHAR(255) NOT NULL, link_to_api_user VARCHAR(255) NOT NULL, link_to_api_request VARCHAR(255) NOT NULL, link_to_api_user_delete VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B73ED50B61220EA6 ON created_api (creator_id)');
        $this->addSql('CREATE TABLE "order" (id SERIAL NOT NULL, of_profile_id INT NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F529939825AED32D ON "order" (of_profile_id)');
        $this->addSql('CREATE TABLE order_created_api (order_id INT NOT NULL, created_api_id INT NOT NULL, PRIMARY KEY(order_id, created_api_id))');
        $this->addSql('CREATE INDEX IDX_AC8CAA8D8D9F6D38 ON order_created_api (order_id)');
        $this->addSql('CREATE INDEX IDX_AC8CAA8D573A0697 ON order_created_api (created_api_id)');
        $this->addSql('CREATE TABLE profile (id SERIAL NOT NULL, of_user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0F5A1B2224 ON profile (of_user_id)');
        $this->addSql('CREATE TABLE purchased_api (id SERIAL NOT NULL, link_to_profile_id INT NOT NULL, link_api_id INT NOT NULL, is_api_key_generated BOOLEAN NOT NULL, remaining_requests INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_462C80019E51FCD9 ON purchased_api (link_to_profile_id)');
        $this->addSql('CREATE INDEX IDX_462C800135527DB7 ON purchased_api (link_api_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE created_api ADD CONSTRAINT FK_B73ED50B61220EA6 FOREIGN KEY (creator_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939825AED32D FOREIGN KEY (of_profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_created_api ADD CONSTRAINT FK_AC8CAA8D8D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_created_api ADD CONSTRAINT FK_AC8CAA8D573A0697 FOREIGN KEY (created_api_id) REFERENCES created_api (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F5A1B2224 FOREIGN KEY (of_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchased_api ADD CONSTRAINT FK_462C80019E51FCD9 FOREIGN KEY (link_to_profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchased_api ADD CONSTRAINT FK_462C800135527DB7 FOREIGN KEY (link_api_id) REFERENCES created_api (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE created_api DROP CONSTRAINT FK_B73ED50B61220EA6');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939825AED32D');
        $this->addSql('ALTER TABLE order_created_api DROP CONSTRAINT FK_AC8CAA8D8D9F6D38');
        $this->addSql('ALTER TABLE order_created_api DROP CONSTRAINT FK_AC8CAA8D573A0697');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0F5A1B2224');
        $this->addSql('ALTER TABLE purchased_api DROP CONSTRAINT FK_462C80019E51FCD9');
        $this->addSql('ALTER TABLE purchased_api DROP CONSTRAINT FK_462C800135527DB7');
        $this->addSql('DROP TABLE created_api');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_created_api');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE purchased_api');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
