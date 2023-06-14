<?php

/**
 * @link https://github.com/ruskid/yii2-csv-importer#README
 */

namespace ruskid\csvimporter;

use Yii;

/**
 * Update from CSV. This will create|update rows of an ActiveRecord table.
 * A csv line is considered a new record if its key does not match any AR table row key.
 */
class MultipleUpdateStrategy extends BaseUpdateStrategy{

	/**
	 * @inheritdoc
	 */
	protected function importNewRecords(&$data) {
		$strategy = new MultipleImportStrategy([
			'tableName' => $this->tableName,
			'configs' => $this->configs,
			'skipImport' => $this->skipImport,
		]);
		return $strategy->import($data);
	}

	/**
	 * @inheritdoc
	 */
	protected function updateRecord($row, $values) {
		return Yii::$app->db->createCommand()->update($this->tableName, $values, $row)->execute();
	}
}
