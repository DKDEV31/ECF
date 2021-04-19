<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210411145450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banker (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, phone INT NOT NULL, birth_date DATE NOT NULL, UNIQUE INDEX UNIQ_69567A0CE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, phone INT NOT NULL, birth_date DATE NOT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4A76ED395');
        $this->addSql('DROP INDEX IDX_7D3656A4A76ED395 ON account');
        $this->addSql('ALTER TABLE account CHANGE user_id client_id INT NOT NULL');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_7D3656A419EB6921 ON account (client_id)');
        $this->addSql('ALTER TABLE request_account DROP FOREIGN KEY FK_571224F338835980');
        $this->addSql('ALTER TABLE request_account ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE request_account ADD CONSTRAINT FK_571224F319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE request_account ADD CONSTRAINT FK_571224F338835980 FOREIGN KEY (banker_id) REFERENCES banker (id)');
        $this->addSql('CREATE INDEX IDX_571224F319EB6921 ON request_account (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request_account DROP FOREIGN KEY FK_571224F338835980');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A419EB6921');
        $this->addSql('ALTER TABLE request_account DROP FOREIGN KEY FK_571224F319EB6921');
        $this->addSql('DROP TABLE banker');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP INDEX IDX_7D3656A419EB6921 ON account');
        $this->addSql('ALTER TABLE account CHANGE client_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7D3656A4A76ED395 ON account (user_id)');
        $this->addSql('ALTER TABLE request_account DROP FOREIGN KEY FK_571224F338835980');
        $this->addSql('DROP INDEX IDX_571224F319EB6921 ON request_account');
        $this->addSql('ALTER TABLE request_account DROP client_id');
        $this->addSql('ALTER TABLE request_account ADD CONSTRAINT FK_571224F338835980 FOREIGN KEY (banker_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
