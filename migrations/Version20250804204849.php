<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250804204849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contest (id UUID NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, total_questions INT NOT NULL, allowed_jokers JSON NOT NULL, difficulty_curve JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN contest.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE game (id UUID NOT NULL, contest_id UUID NOT NULL, public_token UUID NOT NULL, presenter_token UUID NOT NULL, finished BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_232B318C1CD0F0DE ON game (contest_id)');
        $this->addSql('COMMENT ON COLUMN game.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.contest_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.public_token IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.presenter_token IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN game.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE question (id UUID NOT NULL, contest_id_id UUID NOT NULL, title VARCHAR(255) NOT NULL, option_a VARCHAR(255) NOT NULL, option_b VARCHAR(255) NOT NULL, option_c VARCHAR(255) NOT NULL, option_d VARCHAR(255) NOT NULL, correct_answer VARCHAR(255) NOT NULL, difficulty VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6F7494E7339EE7F ON question (contest_id_id)');
        $this->addSql('COMMENT ON COLUMN question.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN question.contest_id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE round (id UUID NOT NULL, game_id UUID NOT NULL, question_id UUID NOT NULL, question_number INT NOT NULL, shown_answers JSON NOT NULL, preselected_answer VARCHAR(255) DEFAULT NULL, confirmed_answer VARCHAR(255) DEFAULT NULL, is_correct BOOLEAN DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5EEEA34E48FD905 ON round (game_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C5EEEA341E27F6BF ON round (question_id)');
        $this->addSql('COMMENT ON COLUMN round.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN round.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN round.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN round.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN round.finished_at IS \'(DC2Type:datetime_immutable)\'');
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
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C1CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E7339EE7F FOREIGN KEY (contest_id_id) REFERENCES contest (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA34E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA341E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C1CD0F0DE');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E7339EE7F');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA34E48FD905');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA341E27F6BF');
        $this->addSql('DROP TABLE contest');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE round');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
