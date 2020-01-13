<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200113092643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, no VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flight (id INT AUTO_INCREMENT NOT NULL, arrival_id INT NOT NULL, departure_id INT NOT NULL, number INT DEFAULT NULL, schedule TIME NOT NULL, price INT NOT NULL, reduction TINYINT(1) DEFAULT NULL, seat INT NOT NULL, INDEX IDX_C257E60E62789708 (arrival_id), INDEX IDX_C257E60E7704ED06 (departure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E62789708 FOREIGN KEY (arrival_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E7704ED06 FOREIGN KEY (departure_id) REFERENCES city (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E62789708');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E7704ED06');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE flight');
    }
}
