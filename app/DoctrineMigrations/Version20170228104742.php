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
        $myTable->addColumn('id', 'integer');
        $myTable->addColumn('owner_id', 'integer');
        $myTable->addColumn('title', 'string');
        $myTable->addColumn('created', 'integer', ["unsigned" => true]);
        $myTable->setPrimaryKey(["id", "owner_id"]);
        $myTable->addForeignKeyConstraint(
            'users',
            ['owner_id'],
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
