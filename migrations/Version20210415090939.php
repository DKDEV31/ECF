<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415090939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request_delete DROP FOREIGN KEY FK_714BBF7C9B6B5FBA');
        $this->addSql('DROP INDEX UNIQ_714BBF7C9B6B5FBA ON request_delete');
        $this->addSql('ALTER TABLE request_delete DROP account_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request_delete ADD account_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE request_delete ADD CONSTRAINT FK_714BBF7C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_714BBF7C9B6B5FBA ON request_delete (account_id)');
    }
}
