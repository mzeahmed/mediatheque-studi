<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019231438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD is_updated_at DATETIME DEFAULT NULL, DROP is_borrowed, DROP updated_at, DROP is_borrowed_at, DROP is_returned_at, DROP is_reserved_at, DROP is_returned');
        $this->addSql('ALTER TABLE borrow ADD is_created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD is_borrowed TINYINT(1) NOT NULL, ADD is_borrowed_at DATETIME DEFAULT NULL, ADD is_returned_at DATETIME DEFAULT NULL, ADD is_reserved_at DATETIME DEFAULT NULL, ADD is_returned TINYINT(1) NOT NULL, CHANGE is_updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE borrow DROP is_created_at');
    }
}
