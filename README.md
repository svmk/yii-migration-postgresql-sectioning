# Postgresql sectioning for Yii1 and Yii2

## EXAMPLE
DateTime partitiong by week of year on Yii1:
```php 
<?php
use svmk\yiiMigrationSectioningPostgres\PostgresqlMigrationHelper;
use svmk\yiiMigrationSectioningPostgres\DateTimeWeekOfYearGrouping;
class m151019_123058_create_table_page_stat extends CDbMigration
{

	protected function getHelper() {
		$helper = new PostgresqlMigrationHelper(
			$this,
			'page_stat',
			array(
				'id' => 'bigserial NOT NULL PRIMARY KEY',
				'data_id' => 'BIGINT',
				'event_date' => 'TIMESTAMP',
				'region_id'  => 'INT',
				'city_id'    => 'INT',
				'views'		 => 'INT',
			)
		);
		return $helper->grouping(
			new DateTimeWeekOfYearGrouping('id','event_date')
		);
	}
	public function getDbConnection()
	{
		return Yii::app()->statisticsDb;
	}
	public function safeUp()
	{
		$this->getHelper()->up(
			function($migration,$tableName,$columns,$config,$isMainTable){
				$migration->createTable(
					$tableName,
					$columns,
					$config
				);
				if (!$isMainTable) {
					$migration->createIndex($tableName.'_data_id',$tableName,'data_id');
					$migration->createIndex($tableName.'_event_date',$tableName,'event_date');
				}
			}
		);
	}

	public function safeDown()
	{
		$this->getHelper()->down(function($migration,$tableName,$isMainTable){			
			$migration->dropTable($tableName);
		});
	}
}
```

## FEATURES
For sectioning use these classes:
* ```svmk\yiiMigrationSectioningPostgres\DateTimeDayOfYearGrouping``` - group by timestamp field day of year
* ```svmk\yiiMigrationSectioningPostgres\DateTimeWeekOfYearGrouping``` - group by timestamp field week of year
* ```svmk\yiiMigrationSectioningPostgres\IndexReminderOfDivisonGrouping``` - group by integer field - division with remainder

## REQUIREMENTS

PHP 5.3

## INSTALLATION

Require this package in your composer.json and run composer update:

		"svmk/yii-migration-postgresql-sectioning": "*"
