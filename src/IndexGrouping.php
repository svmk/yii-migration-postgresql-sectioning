<?php
namespace svmk\yiiMigrationSectioningPostgres;
class IndexGrouping extends AbstractGrouping {	
     protected $min = null;
     protected $max = null;
	/**
     * generateSequences generates grouping sequences
     * 
     * @access public
     *
     * @return array
     */
	public function generateSequences() {
		return range($this->min,$this->max);
	}

    /**
     * getExpression returns expression
     * 
     * @param string $sequenceItem sequence
     * @param string $column       столбец
     *
     * @access protected
     *
     * @return string
     */
	protected function getExpression($sequenceItem,$column) {
		return ' EXTRACT('.$column.') = '.$sequenceItem.' ';
	}

     function __construct($primaryKey,$column,$min,$max) {
          if ($min <= 0 || $max <= 0 || $min >= $max) {
               throw new Exception("Configuration error.");               
          }
          parent::__construct($primaryKey,$column);
     }
}