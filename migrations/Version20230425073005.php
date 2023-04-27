<?php

declare(strict_types=1);

namespace LemisoftSyliusInvoiceRequestPlugin;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230425073005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding nip field and GUS configuration table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE lemisoft_gus_configuration (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) DEFAULT NULL, is_test TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_channel ADD gus_configuration_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_channel ADD CONSTRAINT FK_16C8119EB7E20174 FOREIGN KEY (gus_configuration_id) REFERENCES lemisoft_gus_configuration (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16C8119EB7E20174 ON sylius_channel (gus_configuration_id)');
        $this->addSql('ALTER TABLE sylius_order ADD nip VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_channel DROP FOREIGN KEY FK_16C8119EB7E20174');
        $this->addSql('DROP TABLE lemisoft_gus_configuration');
        $this->addSql('DROP INDEX UNIQ_16C8119EB7E20174 ON sylius_channel');
        $this->addSql('ALTER TABLE sylius_channel DROP gus_configuration_id');
        $this->addSql('ALTER TABLE sylius_order DROP nip');
    }
}
