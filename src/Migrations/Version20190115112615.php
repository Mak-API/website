<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190115112615 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE api_entity_relation ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_entity_relation ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_entity_field ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_entity_field ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_request ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_request ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_entity ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_entity ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE payment_plan ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE payment_plan ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_entity ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_entity ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_request ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_request ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_entity_relation ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_entity_relation ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE payment_plan ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE payment_plan ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_entity_field ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_entity_field ALTER updated_at SET DEFAULT \'now()\'');
    }
}
