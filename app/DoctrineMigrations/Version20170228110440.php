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
        $myTable->addColumn('album_id', 'integer', ['unsigned ' => true]);
        $myTable->addColumn('created', 'integer', ['unsigned' => true]);

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
