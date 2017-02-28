<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170228115301 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $myTable = $schema->createTable('photo_sizes');
        $myTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $myTable->addColumn('photo_id', 'integer');
        $myTable->addColumn('size', 'string', ['length' => 32]);
        $myTable->addColumn('link', 'string');
        $myTable->setPrimaryKey(['id']);
        $myTable->addUniqueIndex(["photo_id", "size"]);
        $myTable->addForeignKeyConstraint(
            'photos',
            ['photo_id'],
            ['id'],
            ['onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE']
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('photo_sizes');
    }
}
