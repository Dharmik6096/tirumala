<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_send_sms_count".
 *
 * @property integer $send_sms_count_code
 * @property integer $count
 * @property string $type
 * @property integer $from_device
 * @property string $date_time_of_send
 * @property integer $shift_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblSendSmsCount extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_send_sms_count';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['send_sms_count_code'], 'required', 'except' => 'androidsync'],
            [['send_sms_count_code', 'count', 'from_device', 'shift_code', 'date_time_of_send', 'type'], 'safe'],
            [['dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'send_sms_count_code' => Yii::t('app', 'Send Sms Count Code'),
            'count' => Yii::t('app', 'Count'),
            'type' => Yii::t('app', 'Type'),
            'from_device' => Yii::t('app', 'From Device'),
            'date_time_of_send' => Yii::t('app', 'Send Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
