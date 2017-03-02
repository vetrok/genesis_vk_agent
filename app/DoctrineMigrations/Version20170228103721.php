<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170228103721 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $myTable = $schema->createTable('users');
        $myTable->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned ' => true]);
        $myTable->addColumn('vk_id', 'integer');
        $myTable->addColumn('first_name', 'string');
        $myTable->addColumn('last_name', 'string');
        $myTable->addColumn('screen_name', 'string');
        $myTable->addUniqueIndex(["vk_id"]);
        $myTable->setPrimaryKey(["id"]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('users');

    }
}
