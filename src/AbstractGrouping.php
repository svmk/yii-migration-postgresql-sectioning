<?php
namespace svmk\yiiMigrationSectioningPostgres;
abstract class AbstractGrouping implements IGrouping {
	
	protected $column;
	protected $primaryKey;
	protected $helper;
	/**
     * generateSequences generates grouping sequences
     * 
     * @access public
     *
     * @return array
     */
	abstract public function generateSequences();

	/**
     * getExpression returns expression
     * 
     * @param string $sequenceItem sequence
     *
     * @access protected
     *
     * @return string
     */
	abstract protected function getExpression($sequenceItem);

    /**
     * getTableName returns table name by sequence
     * 
     * @param mixed $sequenceItem 
     *
     * @access public
     *
     * @return string table name
     */
	public function getTableName($sequenceItem) {
		return $this->helper->getTableName().'_'.$sequenceItem;
	}    

    /**
     * getTableParams return table columns and keys
     * 
     * @param mixed $sequenceItem
     *
     * @access public
     *
     * @return array
     */
	public function getTableParams($sequenceItem) {
		$param = $this->helper->getParams();
		if (!isset($param[$this->primaryKey])) {
			throw new Exception('Primary key '.$this->primaryKey.' not found');			
		}
		return array(
			$this->primaryKey => $param[$this->primaryKey],
			'CHECK ( '.$this->getExpression($sequenceItem).' ) ',
		);
	}

    /**
     * getTableConfig return table config
     * 
     * @param mixed $sequenceItem 
     *
     * @access public
     *
     * @return string
     */
	public function getTableConfig($sequenceItem) {
		return ' INHERITS ('.$this->helper->getTableName().') '.$this->helper->getConfig();
	}

	/**
     * up creates something
     * 
     *
     * @access public
     *
     */
	public function up() {
		$tableName = $this->helper->getTableName();
		$triggerName = $tableName.'_woy_sect_insert_trigger';
		$column      = $this->column;
		$conditions = array();
		foreach ($this->generateSequences() as $sequenceItem) {
			$sqlItem = $this->getExpression($sequenceItem);
			$tableNameItem = '('.$this->getTableName($sequenceItem).')';
			$sqlItem .= " THEN INSERT INTO $tableNameItem VALUES (NEW.*); ";
			$conditions[] = $sqlItem;
		}
		$conditions = implode("\n ELSIF ",$conditions);
		$sql = <<<SQL
CREATE OR REPLACE FUNCTION $tableName.$triggerName()
RETURNS TRIGGER AS $$
BEGIN
  IF $conditions
  ELSE 
    RAISE EXCEPTION 'Date % is out of range. Fix $tableName.$triggerName', NEW.$column;
  END IF;
  RETURN NULL;
END;
$$
LANGUAGE plpgsql;
SQL;
		$this->helper->getMigration()->execute($sql);
		$this->helper->getMigration()->execute("CREATE TRIGGER $tableName_$triggerName
  BEFORE INSERT ON $tableName.$column
  FOR EACH ROW EXECUTE PROCEDURE $tableName.$triggerName();");
	}

    /**
     * down drops something
     * 
     *
     * @access public
     *
     */
	public function down() {	
	}

	/**
     * setHelper sets helper
     * 
     * @param PostgresqlMigrationHelper $helper helper
     *
     * @access public
     *
     */
	public function setHelper($helper) {
		$this->helper = $helper;
	}

	function __construct($primaryKey,$column) {
		if ($column === null) {
			throw new Exception('You need to fill column parameter.');			
		}
		if ($primaryKey === null) {
			throw new Exception('You need to fill primaryKey parameter.');			
		}
		$this->column = $column;
		$this->primaryKey = $primaryKey;
	}
}