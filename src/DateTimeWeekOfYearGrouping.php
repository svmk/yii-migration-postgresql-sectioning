<?php
namespace svmk\yiiMigrationSectioningPostgres;
class DateTimeWeekOfYearGrouping extends AbstractGrouping {	
	/**
     * generateSequences generates grouping sequences
     * 
     * @access public
     *
     * @return array
     */
	public function generateSequences() {
		return range(1,54);
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
		return ' EXTRACT(WOY FROM '.$this->column.') = '.$sequenceItem.' ';
	}
}