<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220709181212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_group ADD creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category_group ADD CONSTRAINT FK_85F30B8C61220EA6 FOREIGN KEY (creator_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_85F30B8C61220EA6 ON category_group (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_group DROP FOREIGN KEY FK_85F30B8C61220EA6');
        $this->addSql('DROP INDEX IDX_85F30B8C61220EA6 ON category_group');
        $this->addSql('ALTER TABLE category_group DROP creator_id');
    }
}
