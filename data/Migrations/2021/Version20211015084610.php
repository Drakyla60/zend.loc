<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015084610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Створення indexes and foreign key constraints ';
    }

    public function up(Schema $schema): void
    {
        // Добавляем индекс к таблице post
        $table = $schema->getTable('post');
        $table->addIndex(['date_created'], 'date_created_index');

        // Добавляем индекс и внешний ключ к таблице comment
        $table = $schema->getTable('comment');
        $table->addIndex(['post_id'], 'post_id_index');
        $table->addForeignKeyConstraint('post', ['post_id'], ['id'], [], 'comment_post_id_fk');

        // Добавляем индексы и внешние ключи к таблице post_tag table
        $table = $schema->getTable('post_tag');
        $table->addIndex(['post_id'], 'post_id_index');
        $table->addIndex(['tag_id'], 'tag_id_index');
        $table->addForeignKeyConstraint('post', ['post_id'], ['id'], [], 'post_tag_post_id_fk');
        $table->addForeignKeyConstraint('tag', ['tag_id'], ['id'], [], 'post_tag_tag_id_fk');

    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('post_tag');
        $table->removeForeignKey('post_tag_post_id_fk');
        $table->removeForeignKey('post_tag_tag_id_fk');
        $table->dropIndex('post_id_index');
        $table->dropIndex('tag_id_index');

        $table = $schema->getTable('comment');
        $table->dropIndex('post_id_index');
        $table->removeForeignKey('comment_post_id_fk');

        $table = $schema->getTable('post');
        $table->dropIndex('date_created_index');

    }
}
