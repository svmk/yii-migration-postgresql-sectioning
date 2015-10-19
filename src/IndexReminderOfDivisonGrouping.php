<?php
namespace svmk\yiiMigrationSectioningPostgres;
use Exception;
class IndexReminderOfDivisonGrouping extends AbstractGrouping {	
     protected $divider;
	/**
     * generateSequences generates grouping sequences
     * 
     * @access public
     *
     * @return array
     */
	public function generateSequences() {
		return range(0,$this->divider);
	}

    /**
     * getExpression returns expression
     * 
     * @param string $sequenceItem sequence
     *
     * @access protected
     *
     * @return string
     */
	protected function getExpression($sequenceItem) {
		return ' ('.$this->column.' % '.$this->divider.') = '.$sequenceItem.' ';
	}

     function __construct($primaryKey,$column,$divider) {
          $this->divider = $divider;
          if ($divider <= 0) {
               throw new Exception("Divider shall be more or equal 1.");               
          }
          parent::__construct($primaryKey,$column);
     }
}