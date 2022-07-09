<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220709081521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_group (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_85F30B8CEA750E8 (label), UNIQUE INDEX UNIQ_85F30B8C989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_category_group (user_id INT NOT NULL, category_group_id INT NOT NULL, INDEX IDX_EE76D4D5A76ED395 (user_id), INDEX IDX_EE76D4D5492E5D3C (category_group_id), PRIMARY KEY(user_id, category_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_category_group ADD CONSTRAINT FK_EE76D4D5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_category_group ADD CONSTRAINT FK_EE76D4D5492E5D3C FOREIGN KEY (category_group_id) REFERENCES category_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD category_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1492E5D3C FOREIGN KEY (category_group_id) REFERENCES category_group (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1492E5D3C ON category (category_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1492E5D3C');
        $this->addSql('ALTER TABLE user_category_group DROP FOREIGN KEY FK_EE76D4D5492E5D3C');
        $this->addSql('DROP TABLE category_group');
        $this->addSql('DROP TABLE user_category_group');
        $this->addSql('DROP INDEX IDX_64C19C1492E5D3C ON category');
        $this->addSql('ALTER TABLE category DROP category_group_id');
    }
}
