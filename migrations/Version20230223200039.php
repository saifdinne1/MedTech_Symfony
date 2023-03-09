<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223200039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chambre ADD service_affecter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF7EC569D9 FOREIGN KEY (service_affecter_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_C509E4FF7EC569D9 ON chambre (service_affecter_id)');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD29B177F54');
        $this->addSql('DROP INDEX IDX_E19D9AD29B177F54 ON service');
        $this->addSql('ALTER TABLE service DROP chambre_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF7EC569D9');
        $this->addSql('DROP INDEX IDX_C509E4FF7EC569D9 ON chambre');
        $this->addSql('ALTER TABLE chambre DROP service_affecter_id');
        $this->addSql('ALTER TABLE service ADD chambre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD29B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD29B177F54 ON service (chambre_id)');
    }
}
