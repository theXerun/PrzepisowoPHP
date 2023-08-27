<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230827172556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fridge_ingredient DROP CONSTRAINT fk_44d72b8514a48e59');
        $this->addSql('ALTER TABLE fridge_ingredient DROP CONSTRAINT fk_44d72b85933fe08c');
        $this->addSql('DROP TABLE fridge_ingredient');
        $this->addSql('ALTER TABLE ingredient ADD fridge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787014A48E59 FOREIGN KEY (fridge_id) REFERENCES fridge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6BAF787014A48E59 ON ingredient (fridge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE fridge_ingredient (fridge_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(fridge_id, ingredient_id))');
        $this->addSql('CREATE INDEX idx_44d72b85933fe08c ON fridge_ingredient (ingredient_id)');
        $this->addSql('CREATE INDEX idx_44d72b8514a48e59 ON fridge_ingredient (fridge_id)');
        $this->addSql('ALTER TABLE fridge_ingredient ADD CONSTRAINT fk_44d72b8514a48e59 FOREIGN KEY (fridge_id) REFERENCES fridge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fridge_ingredient ADD CONSTRAINT fk_44d72b85933fe08c FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT FK_6BAF787014A48E59');
        $this->addSql('DROP INDEX IDX_6BAF787014A48E59');
        $this->addSql('ALTER TABLE ingredient DROP fridge_id');
    }
}
