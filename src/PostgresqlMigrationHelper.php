<?php
namespace svmk\yiiMigrationSectioningPostgres;
use Exception;
class PostgresqlMigrationHelper {
	protected $migration;
	protected $tableName;
	protected $params;
	protected $config; 
	protected $grouping;

    /**
     * getMigration returns migration
     * 
     * @access public
     *
     * @return mixed
     */
	public function getMigration() {
		return $this->migration;
	}

    /**
     * getTableName returns table name
     * 
     * @access public
     *
     * @return string
     */
	public function getTableName() {
		return $this->tableName;
	}

    /**
     * getParams returns params
     * 
     * @access public
     *
     * @return array
     */
	public function getParams() {
		return $this->params;
	}

    /**
     * getConfig returns config
     * 
     * @access public
     *
     * @return string
     */
	public function getConfig() {
		return $this->config;
	}

    /**
     * getGrouping returns grouping
     * 
     * @access public
     *
     * @return IGrouping
     */
	public function getGrouping() {
		return $this->grouping;
	}

    /**
     * grouping grouping field
     * 
     * @param IGrouping $grouping grouping
     *
     * @access public
     * @return this
     */
	public function grouping($grouping) {
		$this->grouping = $grouping;
		if (!($this->grouping instanceof IGrouping)) {
			throw new Exception('Need to implement interface IGrouping in class '.get_class($this->grouping));			
		}
		$this->grouping->setHelper($this);
		return $this;
	}

    /**
     * checkConfig checking config
     * 
     * @access protected
     *
     */
	protected function checkConfig() {		
		if ($this->grouping === null) {
			throw new Exception('Need to configure grouping field.');
		}
	}

    /**
     * up migrate up 
     * 
     * @param Closure function($migration,$tableName,$columns,$config,$isMainTable)
     *
     * @access public
     * @throws Exception when migration is't confured
     */
	public function up($up) {
		$this->checkConfig();
		$up($this->migration,$this->tableName,$this->params,$this->config,true);
		foreach ($this->grouping->generateSequences() as $item) {
			$up(
				$this->migration,
				$this->grouping->getTableName($item),
				$this->grouping->getTableParams($item),
				$this->grouping->getTableConfig($item),
				true
			);			
		}
		$this->grouping->up();
	}

    /**
     * down migrate down
     * 
     * @param Closure function($migration,$tableName,$isMainTable)
     *
     * @access public
     * @throws Exception when migration is't confured
     */
	public function down($down) {
		$this->checkConfig();
		foreach ($this->grouping->generateSequences() as $item) {
			$down(
				$this->migration,
				$this->grouping->getTableName($item),
                false
			);
		}
		$down($this->migration,$this->tableName,true);
		$this->grouping->down();
	}

    /**
     * __construct
     * 
     * @param CDbMigration|Migration  $migration migration class
     * @param string       			  $tableName string
     * @param array   				  $params    params
     * @param string 				  $config    config
     *
     * @access public
     *
     */
	function __construct($migration,$tableName,$params,$config = '') {
		$this->migration = $migration;
		$this->tableName = $tableName;
		$this->params    = $params;
		$this->config    = $config;
	}
}