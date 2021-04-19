<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413100923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request_benefit ADD client_id INT NOT NULL, ADD account_id INT NOT NULL');
        $this->addSql('ALTER TABLE request_benefit ADD CONSTRAINT FK_76AF724819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE request_benefit ADD CONSTRAINT FK_76AF72489B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_76AF724819EB6921 ON request_benefit (client_id)');
        $this->addSql('CREATE INDEX IDX_76AF72489B6B5FBA ON request_benefit (account_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request_benefit DROP FOREIGN KEY FK_76AF724819EB6921');
        $this->addSql('ALTER TABLE request_benefit DROP FOREIGN KEY FK_76AF72489B6B5FBA');
        $this->addSql('DROP INDEX IDX_76AF724819EB6921 ON request_benefit');
        $this->addSql('DROP INDEX IDX_76AF72489B6B5FBA ON request_benefit');
        $this->addSql('ALTER TABLE request_benefit DROP client_id, DROP account_id');
    }
}
