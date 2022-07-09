<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220709081900_insert_CategoryGroup_default_values extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO category_group (label, slug) VALUES ('Public', 'public')");
        $this->addSql("INSERT INTO category_group (label, slug) VALUES ('Privé', 'prive')");
        $this->addSql("UPDATE category SET category_group_id=(
            SELECT id FROM category_group WHERE slug LIKE 'public'
        )");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE category SET category_group_id=null WHERE category_group_id=(
            SELECT id FROM category_group WHERE slug LIKE 'public'
        )");
        $this->addSql("UPDATE category SET category_group_id=null WHERE category_group_id=(
            SELECT id FROM category_group WHERE slug LIKE 'prive'
        )");
        $this->addSql("DELETE FROM category_group WHERE slug like 'public'");
        $this->addSql("DELETE FROM category_group WHERE slug like 'prive'");
    }
}
