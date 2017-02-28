<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170228110440 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $myTable = $schema->createTable('photos');
        $myTable->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned ' => true]);
        $myTable->addColumn('vk_id', 'integer');
        $myTable->addColumn('album_id', 'integer');
        $myTable->addColumn('link', 'string');
        $myTable->addColumn('created', 'integer', ['unsigned' => true]);
        //Add unique index and additional column with owner_id
        //integrity of DB, when one user can't have 2 photos with same photo_id
        $myTable->addColumn('owner_id', 'integer');
        $myTable->addUniqueIndex(["vk_id", "owner_id"]);
        $myTable->addForeignKeyConstraint(
            'users',
            ['owner_id'],
            ['id'],
            ['onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE']
        );

        $myTable->setPrimaryKey(['id']);
        $myTable->addForeignKeyConstraint(
            'albums',
            ['album_id'],
            ['id'],
            ['onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE']
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('photos');

    }
}
