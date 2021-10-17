<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211017211829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE borrow (id INT AUTO_INCREMENT NOT NULL, borrower_id INT DEFAULT NULL, is_borrowed TINYINT(1) NOT NULL, is_borrowed_at DATETIME NOT NULL, is_returned_at DATETIME NOT NULL, INDEX IDX_55DBA8B011CE312B (borrower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B011CE312B FOREIGN KEY (borrower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE book ADD borrow_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331D4C006C8 FOREIGN KEY (borrow_id) REFERENCES borrow (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331D4C006C8 ON book (borrow_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331D4C006C8');
        $this->addSql('DROP TABLE borrow');
        $this->addSql('DROP INDEX IDX_CBE5A331D4C006C8 ON book');
        $this->addSql('ALTER TABLE book DROP borrow_id');
    }
}
