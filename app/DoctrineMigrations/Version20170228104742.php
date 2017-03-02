<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170228104742 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $myTable = $schema->createTable('albums');
        $myTable->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned ' => true]);
        $myTable->addColumn('vk_id', 'integer');
        $myTable->addColumn('user_id', 'integer', ['unsigned ' => true]);
        $myTable->addColumn('title', 'string');
        $myTable->addColumn('created', 'integer', ["unsigned" => true]);
        $myTable->setPrimaryKey(["id"]);
        $myTable->addUniqueIndex(["vk_id", "user_id"]);
        $myTable->addForeignKeyConstraint(
            'users',
            ['user_id'],
            ['id'],
            ['onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE']
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('albums');

    }
}
