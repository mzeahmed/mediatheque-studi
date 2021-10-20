<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019223330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331D4C006C8');
        $this->addSql('DROP INDEX IDX_CBE5A331D4C006C8 ON book');
        $this->addSql('ALTER TABLE book ADD is_reserved TINYINT(1) NOT NULL, ADD is_reserved_at DATETIME DEFAULT NULL, ADD is_returned TINYINT(1) NOT NULL, DROP borrow_id');
        $this->addSql('ALTER TABLE borrow DROP INDEX IDX_55DBA8B011CE312B, ADD UNIQUE INDEX UNIQ_55DBA8B011CE312B (borrower_id)');
        $this->addSql('ALTER TABLE borrow ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B016A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55DBA8B016A2B381 ON borrow (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD borrow_id INT DEFAULT NULL, DROP is_reserved, DROP is_reserved_at, DROP is_returned');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331D4C006C8 FOREIGN KEY (borrow_id) REFERENCES borrow (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331D4C006C8 ON book (borrow_id)');
        $this->addSql('ALTER TABLE borrow DROP INDEX UNIQ_55DBA8B011CE312B, ADD INDEX IDX_55DBA8B011CE312B (borrower_id)');
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B016A2B381');
        $this->addSql('DROP INDEX UNIQ_55DBA8B016A2B381 ON borrow');
        $this->addSql('ALTER TABLE borrow DROP book_id');
    }
}
