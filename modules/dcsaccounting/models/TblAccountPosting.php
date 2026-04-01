<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsaccounting\models\TblEvent;

/**
 * This is the model class for table "tbl_account_posting".
 *
 * @property string $account_posting_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $from_shift
 * @property integer $to_shift
 * @property integer $posting_type
 * @property integer $status
 * @property integer $event_type
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
class TblAccountPosting extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_account_posting';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['account_posting_code'], 'required', 'on' => 'androidsync'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'created_at', 'updated_at', 'from_shift', 'to_shift', 'posting_type', 'status', 'event_type', 'originating_type', 'account_posting_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'account_posting_code' => Yii::t('app', 'Account Posting Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'posting_type' => Yii::t('app', 'Posting Type'),
            'status' => Yii::t('app', 'Status'),
            'event_type' => Yii::t('app', 'Event Type'),
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getFromShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

    public function getEventCode() {
        return $this->hasOne(TblEvent::className(), ['event_code_default' => 'event_type']);
    }

}
