<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223185515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664102C74C93D');
        $this->addSql('DROP INDEX IDX_FE8664102C74C93D ON facture');
        $this->addSql('ALTER TABLE facture CHANGE patient_name_id patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664106B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE8664106B899279 ON facture (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664106B899279');
        $this->addSql('DROP INDEX IDX_FE8664106B899279 ON facture');
        $this->addSql('ALTER TABLE facture CHANGE patient_id patient_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664102C74C93D FOREIGN KEY (patient_name_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE8664102C74C93D ON facture (patient_name_id)');
    }
}
