<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216230000_insert_admin extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO user (username, email, password, roles, is_verified)
              VALUES ("Circus", "p@p.fr", "$2y$13$oDbxLnUn.vF5pIt9EHuPZ.qZamE2QJ6n7gGtdsFT0DgZ1sPRqH.Wu", \'["ROLE_ADMIN"]\', true)');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM user WHERE username LIKE "Circus"');
    }
}