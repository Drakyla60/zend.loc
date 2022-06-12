<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015084353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Створення головних таблиць';
    }

    public function up(Schema $schema): void
    {
        // Создаем таблицу 'post'
        $table = $schema->createTable('post');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('title', 'text', ['notnull'=>true]);
        $table->addColumn('content', 'text', ['notnull'=>true]);
        $table->addColumn('status', 'integer', ['notnull'=>true]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        // Создаем таблицу 'comment'
        $table = $schema->createTable('comment');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('post_id', 'integer', ['notnull'=>true]);
        $table->addColumn('content', 'text', ['notnull'=>true]);
        $table->addColumn('author', 'string', ['notnull'=>true, 'lenght'=>128]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        // Создаем таблицу 'tag'
        $table = $schema->createTable('tag');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'lenght'=>128]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        // Создаем таблицу 'post_tag'
        $table = $schema->createTable('post_tag');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('post_id', 'integer', ['notnull'=>true]);
        $table->addColumn('tag_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('post_tag');
        $schema->dropTable('tag');
        $schema->dropTable('comment');
        $schema->dropTable('post');
    }
}
