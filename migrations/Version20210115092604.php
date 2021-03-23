<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115092604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(10) NOT NULL, l_name VARCHAR(100) NOT NULL, f_name VARCHAR(100) NOT NULL, state SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, info LONGTEXT NOT NULL, public_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_like_product (person_id_id INT NOT NULL, product_id_id INT NOT NULL, INDEX fk_person_like_product_product1_idx (product_id_id), PRIMARY KEY(person_id_id, product_id_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_like_product ADD CONSTRAINT fk_person_like_product_person FOREIGN KEY (person_id_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_like_product ADD CONSTRAINT fk_person_like_product_product1 FOREIGN KEY (product_id_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_like_product DROP FOREIGN KEY fk_person_like_product_person');
        $this->addSql('ALTER TABLE person_like_product DROP FOREIGN KEY fk_person_like_product_product1');
        $this->addSql('DROP TABLE person_like_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE person');
    }
}
