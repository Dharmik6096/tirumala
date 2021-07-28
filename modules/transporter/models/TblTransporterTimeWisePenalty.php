<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;

/**
 * This is the model class for table "tbl_transporter_time_wise_penalty".
 *
 * @property integer $penalty_code
 * @property string $union_code
 * @property string $penalty_amount
 * @property string $minute_limit
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblTransporterTimeWisePenalty extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_time_wise_penalty';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['penalty_amount', 'minute_limit'], 'number'],
            [['created_at', 'updated_at', 'mcc_plant_code', 'plant_code', 'wef_date'], 'safe'],
            [['originating_type'], 'integer'],
            [['mcc_plant_code'], 'setImport', 'on' => ['importCsv']],
            [['penalty_amount', 'minute_limit', 'mcc_plant_code', 'wef_date'], 'required'],
            [['minute_limit', 'penalty_amount'], 'number', 'min' => 0],
            [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
            [['minute_limit'], 'unique', 'targetAttribute' => ['minute_limit', 'wef_date', 'mcc_plant_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
            [['plant_code', 'union_code'], 'required', 'except' => 'importCsv']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'penalty_code' => Yii::t('app', 'Penalty Code'),
            'union_code' => Yii::t('app', 'Union'),
            'penalty_amount' => Yii::t('app', 'Penalty Amount(Per Minute)'),
            'minute_limit' => Yii::t('app', 'Late Minute(>=)'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
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

    public function setImport($attribute, $params) {
        $this->plant_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'plant_code');
        $this->union_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'union_code');
    }

}
