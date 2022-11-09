<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_vsp_bill_head_criteria_applicability".
 *
 * @property integer $bill_head_criteria_applicability_code
 * @property string $vsp_criteria_code
 * @property string $wef_date
 * @property string $bill_head_code
 * @property string $union_code
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $bmc_code
 * @property string $bill_head_for
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblVspBillHeadCriteriaApplicability extends \app\models\ChildModel {

    public $dcs_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_bill_head_criteria_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['applicable_code', 'from_date', 'to_date'], 'required', 'except' => ['androidsync']],
                [['applicable_code'], 'setBMCCode'],
                [['from_date', 'to_date', 'wef_date', 'created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'safe'],
                [['vsp_criteria_code', 'applicable_code', 'applicable_for', 'bill_head_for'], 'safe'],
                [['bill_head_code'], 'safe'],
                [['union_code'], 'safe'],
                [['bmc_code'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
//            [['applicable_code', 'applicable_for', 'bill_head_code', 'wef_date'], 'unique', 'targetAttribute' => ['applicable_code', 'applicable_for', 'bill_head_code', 'wef_date'], 'message' => 'The combination of Wef Date, Bill Head Code, Applicable Code and Applicable For has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_criteria_applicability_code' => Yii::t('app', 'Bill Head Criteria Applicability Code'),
            'vsp_criteria_code' => Yii::t('app', 'Vsp Criteria Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'bill_head_for' => Yii::t('app', 'Bill Head For'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
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

    public function setBMCCode($attribute, $params) {
        $this->bmc_code = Yii::$app->general->getCustomer($this, $this->applicable_for, FALSE, TRUE);
    }

}
