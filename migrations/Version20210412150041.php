<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412150041 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE request_delete (id INT AUTO_INCREMENT NOT NULL, banker_id INT NOT NULL, client_id INT NOT NULL, account_id INT NOT NULL, state VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, close_request VARCHAR(255) NOT NULL, INDEX IDX_714BBF7C38835980 (banker_id), INDEX IDX_714BBF7C19EB6921 (client_id), UNIQUE INDEX UNIQ_714BBF7C9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE request_delete ADD CONSTRAINT FK_714BBF7C38835980 FOREIGN KEY (banker_id) REFERENCES banker (id)');
        $this->addSql('ALTER TABLE request_delete ADD CONSTRAINT FK_714BBF7C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE request_delete ADD CONSTRAINT FK_714BBF7C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE request_delete');
    }
}
