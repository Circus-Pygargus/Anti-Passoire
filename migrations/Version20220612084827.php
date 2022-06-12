<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220612084827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anti_passoire (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, text LONGTEXT NOT NULL, seo_keywords VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, display_nb INT NOT NULL, last_display DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6557D0CB2B36786B (title), UNIQUE INDEX UNIQ_6557D0CB989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE anti_passoire_category (anti_passoire_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_AA48FEF88F8CBDBC (anti_passoire_id), INDEX IDX_AA48FEF812469DE2 (category_id), PRIMARY KEY(anti_passoire_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE anti_passoire_category ADD CONSTRAINT FK_AA48FEF88F8CBDBC FOREIGN KEY (anti_passoire_id) REFERENCES anti_passoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE anti_passoire_category ADD CONSTRAINT FK_AA48FEF812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE anti_passoire_category DROP FOREIGN KEY FK_AA48FEF88F8CBDBC');
        $this->addSql('DROP TABLE anti_passoire');
        $this->addSql('DROP TABLE anti_passoire_category');
    }
}
