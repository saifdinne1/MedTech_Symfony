<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222153444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE866410A76ED395 ON facture (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497F2DEE08');
        $this->addSql('DROP INDEX IDX_8D93D6497F2DEE08 ON user');
        $this->addSql('ALTER TABLE user DROP facture_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A76ED395');
        $this->addSql('DROP INDEX IDX_FE866410A76ED395 ON facture');
        $this->addSql('ALTER TABLE facture DROP user_id');
        $this->addSql('ALTER TABLE user ADD facture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6497F2DEE08 ON user (facture_id)');
    }
}
