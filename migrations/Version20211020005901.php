<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211020005901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B011CE312B');
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B016A2B381');
        $this->addSql('DROP INDEX UNIQ_55DBA8B016A2B381 ON borrow');
        $this->addSql('DROP INDEX UNIQ_55DBA8B011CE312B ON borrow');
        $this->addSql('ALTER TABLE borrow DROP borrower_id, DROP book_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow ADD borrower_id INT DEFAULT NULL, ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B011CE312B FOREIGN KEY (borrower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B016A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55DBA8B016A2B381 ON borrow (book_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55DBA8B011CE312B ON borrow (borrower_id)');
    }
}
