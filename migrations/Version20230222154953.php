<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222154953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilan ADD vente_id INT DEFAULT NULL, CHANGE charge cahrge VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE bilan ADD CONSTRAINT FK_F4DF4F447DC7170A FOREIGN KEY (vente_id) REFERENCES facture (id)');
        $this->addSql('CREATE INDEX IDX_F4DF4F447DC7170A ON bilan (vente_id)');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410705F7C57');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A76ED395');
        $this->addSql('DROP INDEX IDX_FE866410A76ED395 ON facture');
        $this->addSql('DROP INDEX IDX_FE866410705F7C57 ON facture');
        $this->addSql('ALTER TABLE facture ADD patient_id INT DEFAULT NULL, ADD numero_facture VARCHAR(20) NOT NULL, DROP bilan_id, DROP user_id, CHANGE designation designation VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664106B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE8664106B899279 ON facture (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilan DROP FOREIGN KEY FK_F4DF4F447DC7170A');
        $this->addSql('DROP INDEX IDX_F4DF4F447DC7170A ON bilan');
        $this->addSql('ALTER TABLE bilan DROP vente_id, CHANGE cahrge charge VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664106B899279');
        $this->addSql('DROP INDEX IDX_FE8664106B899279 ON facture');
        $this->addSql('ALTER TABLE facture ADD user_id INT DEFAULT NULL, DROP numero_facture, CHANGE designation designation VARCHAR(100) NOT NULL, CHANGE patient_id bilan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410705F7C57 FOREIGN KEY (bilan_id) REFERENCES bilan (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE866410A76ED395 ON facture (user_id)');
        $this->addSql('CREATE INDEX IDX_FE866410705F7C57 ON facture (bilan_id)');
    }
}
