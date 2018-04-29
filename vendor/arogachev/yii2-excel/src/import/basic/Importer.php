<?php

namespace arogachev\excel\import\basic;

use arogachev\excel\helpers\PHPExcelHelper;
use arogachev\excel\import\BaseImporter;
use arogachev\excel\import\exceptions\RowException;
use Yii;

class Importer extends BaseImporter
{
    /**
     * @inheritdoc
     */

    protected function fillModels($rows)
    {
        $c = 1;
       
        foreach ($rows as $row) {
            if (PHPExcelHelper::isRowEmpty($row)) {
                break;
            }

            if ($c == 1) {
                if (!$this->_standardModels[0]->parseAttributeNames($row)) {
                    throw new RowException($row, 'Attribute names must be placed in first filled row.');
                }
            } else {
                $this->_models[] = new Model([
                    'row' => $row,
                    'standardModel' => $this->_standardModels[0],
                ]);
            }

            $c++;
        }
    }

    /**
     * @inheritdoc
     */
    protected function safeRun()
    {
        parent::safeRun();

        $this->fillModels($this->_phpExcel->getActiveSheet()->getRowIterator());

        $this->processedRow = 0;
        foreach ($this->_models as $model) {
            $model->load();
            $va = $model->validate();
            if(!empty($va))
                $this->processedRow += 1;
        }
         
        Yii::$app->db->transaction(function () {

            /** add default values to model after saving **/
            $defaultValues = $this->_standardModels[0]->defalutValues;
            foreach ($this->_models as $model) {

                $model->save();
                $data = $model->getInstance();
                foreach($defaultValues as $key=>$row){
                    $data->$key=$row;
                }
                $data->save();
            }
        });
        $this->trigger(self::EVENT_RUN);
    }
}
