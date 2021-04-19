<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_collection_penalty_rate_applicability".
 *
 * @property string $penalty_rate_applicability_code
 * @property string $penalty_rate_code
 * @property string $penalty_rate
 * @property string $penalty_type
 * @property string $wef_date
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
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
class TblCollectionPenaltyRateApplicability extends \app\models\ChildModel {

    public $dcs_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_penalty_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicable_code', 'wef_date'], 'required', 'except' => ['androidsync']],
            [['penalty_rate_code'], 'validateApplicability', 'skipOnEmpty' => false, 'except' => ['androidsync']],
            [['wef_date', 'created_at', 'updated_at', 'penalty_rate_applicability_code', 'penalty_rate', 'originating_type', 'penalty_rate_code'], 'safe'],
            [['penalty_type', 'created_by', 'updated_by', 'applicable_code', 'applicable_for', 'applicable_type'], 'safe'],
            [['bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
//            [['applicable_code', 'applicable_for', 'penalty_type', 'wef_date'], 'unique', 'targetAttribute' => ['applicable_code', 'applicable_for', 'penalty_type', 'wef_date'], 'message' => 'The combination of Penalty Type, Wef Date, Applicable Code and Applicable For has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'penalty_rate_applicability_code' => Yii::t('app', 'Penalty Rate Applicability Code'),
            'penalty_rate_code' => Yii::t('app', 'Penalty Rate Code'),
            'penalty_rate' => Yii::t('app', 'Penalty Rate'),
            'penalty_type' => Yii::t('app', 'Penalty Type'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
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

    public function validateApplicability($attribute, $params) {
        $this->penalty_rate_applicability_code = Yii::$app->general->getCodeAutoIncrement($this);
        $this->bmc_code = Yii::$app->general->getCustomer($this, $this->applicable_for, FALSE, TRUE);
        $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
        $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        $this->applicable_type = 'BMC';
    }

    public function getCustomerTypeFor() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getName($applicableFor) {
        if ($applicableFor == 'PLANT') {
            return Yii::$app->general->getforeignkey($this->plantCode, 'name');
        } else if ($applicableFor == 'MCC') {
            return Yii::$app->general->getforeignkey($this->mccPlantCode, 'name');
        } else if ($applicableFor == 'BMC') {
            return Yii::$app->general->getforeignkey($this->bmcCode, 'bmc_name');
        } else if ($applicableFor == 'DCS') {
            return Yii::$app->general->getforeignkey($this->dcsCode, 'dcs_name');
        } else {
            return Yii::$app->general->getforeignkey($this->mainCustomerCode, 'customer_name');
        }
    }

}
