<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126233324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE dish (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INTEGER NOT NULL, image_name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE order_document (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, file_name VARCHAR(255) DEFAULT NULL, uploaded_at DATETIME NOT NULL, order_id INTEGER NOT NULL, CONSTRAINT FK_399168C78D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_399168C78D9F6D38 ON order_document (order_id)');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_id INTEGER DEFAULT NULL, CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE9395C3F3 ON orders (customer_id)');
        $this->addSql('CREATE TABLE order_entity_dish (order_entity_id INTEGER NOT NULL, dish_id INTEGER NOT NULL, PRIMARY KEY (order_entity_id, dish_id), CONSTRAINT FK_C6034A153DA206A5 FOREIGN KEY (order_entity_id) REFERENCES orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C6034A15148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C6034A153DA206A5 ON order_entity_dish (order_entity_id)');
        $this->addSql('CREATE INDEX IDX_C6034A15148EB0CB ON order_entity_dish (dish_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE order_document');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE order_entity_dish');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
