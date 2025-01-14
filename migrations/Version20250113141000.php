<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113141000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rob_id INT NOT NULL, description LONGTEXT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_8A8E26E9A76ED395 (user_id), INDEX IDX_8A8E26E9E465CE5F (rob_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rob (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_E9ED1230A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9E465CE5F FOREIGN KEY (rob_id) REFERENCES rob (id)');
        $this->addSql('ALTER TABLE rob ADD CONSTRAINT FK_E9ED1230A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD user_message_id INT NOT NULL, ADD rob_id INT NOT NULL, ADD conversation_id INT NOT NULL, ADD time_stamp DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD readed TINYINT(1) NOT NULL, ADD sent_by_human TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF41DD5C5 FOREIGN KEY (user_message_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE465CE5F FOREIGN KEY (rob_id) REFERENCES rob (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF41DD5C5 ON message (user_message_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FE465CE5F ON message (rob_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE465CE5F');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9A76ED395');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9E465CE5F');
        $this->addSql('ALTER TABLE rob DROP FOREIGN KEY FK_E9ED1230A76ED395');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE rob');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF41DD5C5');
        $this->addSql('DROP INDEX IDX_B6BD307FF41DD5C5 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FE465CE5F ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F9AC0396 ON message');
        $this->addSql('ALTER TABLE message DROP user_message_id, DROP rob_id, DROP conversation_id, DROP time_stamp, DROP readed, DROP sent_by_human');
    }
}
