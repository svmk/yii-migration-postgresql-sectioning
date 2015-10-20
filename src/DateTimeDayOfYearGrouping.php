<?php
namespace svmk\yiiMigrationSectioningPostgres;
class DateTimeDayOfYearGrouping extends AbstractGrouping {	
	/**
     * generateSequences generates grouping sequences
     * 
     * @access public
     *
     * @return array
     */
	public function generateSequences() {
		return range(1,366);
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
		return ' EXTRACT(DOY FROM '.$column.') = '.$sequenceItem.' ';
	}
}