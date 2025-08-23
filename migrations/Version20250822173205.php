<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250822173205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ALTER title DROP NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_a DROP NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_b DROP NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_c DROP NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_d DROP NOT NULL');
        $this->addSql('ALTER TABLE question ALTER correct_answer DROP NOT NULL');
        $this->addSql('ALTER TABLE question ALTER difficulty DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE question ALTER title SET NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_a SET NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_b SET NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_c SET NOT NULL');
        $this->addSql('ALTER TABLE question ALTER option_d SET NOT NULL');
        $this->addSql('ALTER TABLE question ALTER correct_answer SET NOT NULL');
        $this->addSql('ALTER TABLE question ALTER difficulty SET NOT NULL');
    }
}
