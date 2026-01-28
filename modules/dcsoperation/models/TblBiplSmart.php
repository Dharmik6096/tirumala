<?php

namespace app\modules\dcsoperation\models;

use Yii;

class TblBiplSmart extends \app\models\ChildModel {

    public static function tableName() {
        return 'tbl_member';
    }

    public function rules() {
        return [
        ];
    }

    public function attributeLabels() {
        return [
            'f_plant_code' => Yii::t('app', 'Plant'),
            'f_mcc_plant_code' => Yii::t('app', 'MCC'),
            'f_bmc_code' => Yii::t('app', 'BMC'),
        ];
    }

}
