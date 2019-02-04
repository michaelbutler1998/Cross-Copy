<?php
/**
 * Cross Copy plugin for Craft CMS 3.x
 *
 * Copy a matrix from one project to another
 *
 * @link      michael@ipopdigital.com
 * @copyright Copyright (c) 2019 Michael Butler
 */

namespace crosscopy\crosscopy\migrations;

use crosscopy\crosscopy\CrossCopy;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * Cross Copy Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    Michael Butler
 * @package   CrossCopy
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

    // crosscopy_crosscopyrecord table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%crosscopy_crosscopyrecord}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%crosscopy_crosscopyrecord}}',
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                // Custom columns in the table
                    'siteId' => $this->integer()->notNull(),
                    'structureName' => foreignKey(),
                    // 'structureName' => $this->addForeignKeys(),
                    // 'handle',
                    // 'sectionType',
                    // 'Url',
                    // 'template'
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * Creates the indexes needed for the Records used by the plugin
     *
     * @return void
     */
    protected function createIndexes()
    {
    // crosscopy_crosscopyrecord table
        $this->createIndex(
            $this->db->getIndexName(
                '{{%crosscopy_crosscopyrecord}}',
                'some_field',
                true
            ),
            '{{%crosscopy_crosscopyrecord}}',
            'some_field',
            true
        );
        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
    // crosscopy_crosscopyrecord table
        // $this->addForeignKey(
        //     $this->db->getForeignKeyName('{{%crosscopy_crosscopyrecord}}', 'siteId'),
        //     '{{%crosscopy_crosscopyrecord}}',
        //     'siteId',
        //     '{{%sites}}',
        //     'id',
        //     'CASCADE',
        //     'CASCADE'
        // );
        //public void addForeignKey ( $name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null )
    
        $this->db->addForeignKey( 
            'structureName', //Name of foreing key constraint
            '{{%crosscopy_crosscopyrecord}}', //Table
            'structureName',  //Columns to add to 
            'sections', //Ref to the foreign key table
            'id', //ref to the foreign key column
            'CASCADE', //on delete..
            'CASCADE'   //On update
        );
    }

    /**
     * Populates the DB with the default data.
     *
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
    // crosscopy_crosscopyrecord table
        $this->dropTableIfExists('{{%crosscopy_crosscopyrecord}}');
    }
}
