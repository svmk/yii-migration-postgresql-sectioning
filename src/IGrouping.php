<?php
namespace svmk\yiiMigrationSectioningPostgres;
interface IGrouping {
    /**
     * generateSequences generates grouping sequences
     * 
     * @access public
     *
     * @return array
     */
	public function generateSequences();

    /**
     * getTableName returns table name by sequence
     * 
     * @param mixed $sequenceItem 
     *
     * @access public
     *
     * @return string table name
     */
	public function getTableName($sequenceItem);

    /**
     * getTableParams return table columns and keys
     * 
     * @param mixed $sequenceItem
     *
     * @access public
     *
     * @return array
     */
	public function getTableParams($sequenceItem);

    /**
     * getTableConfig return table config
     * 
     * @param mixed $sequenceItem 
     *
     * @access public
     *
     * @return string
     */
	public function getTableConfig($sequenceItem);

    /**
     * up creates something
     * 
     *
     * @access public
     *
     */
	public function up();

    /**
     * down drops something
     * 
     *
     * @access public
     *
     */
	public function down();

    /**
     * setHelper sets helper
     * 
     * @param PostgresqlMigrationHelper $helper helper
     *
     * @access public
     *
     */
	public function setHelper($helper);
}