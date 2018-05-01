<?php

namespace app\modules\stellapps\models;

use Yii;
use app\modules\collection\models\TblBmcCollection;
use app\modules\dcsoperation\models\TblShift;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\organisation\models\TblDcs;

class StellappsCollection extends TblBmcCollection {

    public function rules() {
        $rules = parent::rules();
        $array = [
            [['collection_type', 'milk_quality_type_code'], 'default', 'value' => 1],
            [['date'], 'date', 'format' => 'php:d-m-Y', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 01-12-2018'), 'skipOnEmpty' => true],
            [['dcs_code'], 'AddAutoData', 'skipOnEmpty' => true],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id']],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
            [['milk_quality_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type_code' => 'milk_quality_type_code']],
            [['qty_mode', 'qty_auto', 'qlty_auto'], 'in', 'range' => [0,1]]
        ];
        foreach ($rules as $row) {
            array_push($array, $row);
        }
        return $array;
    }

    public function AddAutoData($attribute, $params) {
        $this->date_time_of_collection = date('Y-m-d', strtotime($this->date)) . ' ' . $this->weigh_time;
        $this->date_time_of_recieve = date('Y-m-d h:i:s');
        $this->date_time_of_testing = date('Y-m-d', strtotime($this->date)) . ' ' . $this->testing_time;
        $this->village_code = $this->dcsCode->village_code;
    }

}
