<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_mcc_wise_transportation_cost".
 *
 * @property integer $tpt_cost_code
 * @property string $union_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $primary_tpt_cost
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMccWiseTransportationCost extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_wise_transportation_cost';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'mcc_plant_code', 'plant_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['primary_tpt_cost'], 'number'],
            [['wef_date', 'created_at', 'updated_at', 'bmc_code'], 'safe'],
            [['originating_type'], 'integer'],
            [['mcc_plant_code'], 'setImport', 'on' => ['importCsv']],
            [['mcc_plant_code', 'bmc_code', 'wef_date', 'primary_tpt_cost'], 'required'],
            [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
            [['wef_date'], 'unique', 'targetAttribute' => ['wef_date', 'mcc_plant_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
            [['primary_tpt_cost'], 'number', 'min' => 0],
            [['plant_code', 'union_code'], 'required', 'except' => 'importCsv']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tpt_cost_code' => Yii::t('app', 'Tpt Cost Code'),
            'union_code' => Yii::t('app', 'Union'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'primary_tpt_cost' => Yii::t('app', 'Primary Transportation'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'bmc_code' => Yii::t('app', 'BMC'),
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
    public function setImport($attribute, $params) {
        $this->plant_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'plant_code');
        $this->union_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'union_code');
    }

}
