<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190228161957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cron_tasks (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, command VARCHAR(200) NOT NULL, options VARCHAR(255) DEFAULT NULL, arguments VARCHAR(255) DEFAULT NULL, expression VARCHAR(255) NOT NULL, last_execution DATETIME DEFAULT NULL, last_return_code INT DEFAULT NULL, log_file VARCHAR(255) DEFAULT NULL, priority INT NOT NULL, execute_immediately TINYINT(1) DEFAULT NULL, disabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, is_published TINYINT(1) NOT NULL, INDEX IDX_1DD3995061220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD3995061220EA6 FOREIGN KEY (creator_id) REFERENCES user_account (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE cron_tasks');
        $this->addSql('DROP TABLE news');
    }
}
