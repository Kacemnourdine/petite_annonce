<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201228183926 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces ADD categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_CB988C6FA21214B7 ON annonces (categories_id)');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF346684C2885D7');
        $this->addSql('DROP INDEX IDX_3AF346684C2885D7 ON categories');
        $this->addSql('ALTER TABLE categories DROP annonces_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA21214B7');
        $this->addSql('DROP INDEX IDX_CB988C6FA21214B7 ON annonces');
        $this->addSql('ALTER TABLE annonces DROP categories_id');
        $this->addSql('ALTER TABLE categories ADD annonces_id INT NOT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346684C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
        $this->addSql('CREATE INDEX IDX_3AF346684C2885D7 ON categories (annonces_id)');
    }
}
