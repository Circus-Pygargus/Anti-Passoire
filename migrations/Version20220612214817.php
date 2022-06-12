<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220612214817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anti_passoire_user (anti_passoire_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_195E61D68F8CBDBC (anti_passoire_id), INDEX IDX_195E61D6A76ED395 (user_id), PRIMARY KEY(anti_passoire_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE anti_passoire_user ADD CONSTRAINT FK_195E61D68F8CBDBC FOREIGN KEY (anti_passoire_id) REFERENCES anti_passoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE anti_passoire_user ADD CONSTRAINT FK_195E61D6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE anti_passoire ADD creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE anti_passoire ADD CONSTRAINT FK_6557D0CB61220EA6 FOREIGN KEY (creator_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_6557D0CB61220EA6 ON anti_passoire (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE anti_passoire_user');
        $this->addSql('ALTER TABLE anti_passoire DROP FOREIGN KEY FK_6557D0CB61220EA6');
        $this->addSql('DROP INDEX IDX_6557D0CB61220EA6 ON anti_passoire');
        $this->addSql('ALTER TABLE anti_passoire DROP creator_id');
    }
}
