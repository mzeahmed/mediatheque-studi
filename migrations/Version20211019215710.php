<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019215710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD is_published_at DATETIME DEFAULT NULL, ADD is_borrowed_at DATETIME DEFAULT NULL, ADD is_returned_at DATETIME DEFAULT NULL, CHANGE is_vailable is_borrowed TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE borrow DROP is_borrowed, DROP is_borrowed_at, DROP is_returned_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP is_published_at, DROP is_borrowed_at, DROP is_returned_at, CHANGE is_borrowed is_vailable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE borrow ADD is_borrowed TINYINT(1) NOT NULL, ADD is_borrowed_at DATETIME NOT NULL, ADD is_returned_at DATETIME NOT NULL');
    }
}
