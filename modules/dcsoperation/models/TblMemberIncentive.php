<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;

/**
 * This is the model class for table "tbl_member_incentive".
 *
 * @property integer $member_incentive_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $member_vendor_code
 * @property string $from_date
 * @property string $to_date
 * @property string $total_qty
 * @property integer $pouring_days
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $milk_amount
 * @property string $bonus_criteria
 * @property string $incentive_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMemberIncentive extends \app\models\ChildModel {

    public $import_eipl_code, $import_union_code, $import_key_pattern;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_incentive';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'total_qty', 'avg_fat', 'avg_snf', 'milk_amount', 'incentive_amount', 'from_date', 'pouring_days', 'originating_type', 'to_date', 'created_at', 'updated_at', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'member_vendor_code', 'created_by', 'originating_org_code', 'originating_org_type', 'updated_by', 'bonus_criteria'], 'safe'],
                [['total_qty', 'avg_fat', 'avg_snf', 'milk_amount', 'incentive_amount', 'pouring_days'], 'number'],
                [['from_date', 'to_date'], 'convertDate', 'on' => ['importCsv']],
                [['to_date'], 'validateToDate', 'on' => ['importCsv']],
                [['member_vendor_code'], 'setImport', 'on' => ['importCsv']],];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_incentive_code' => Yii::t('app', 'Member Incentive Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_code' => Yii::t('app', 'Member'),
            'member_vendor_code' => Yii::t('app', 'Member Vendor Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'pouring_days' => Yii::t('app', 'Pouring Days'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'milk_amount' => Yii::t('app', 'Milk Amount'),
            'bonus_criteria' => Yii::t('app', 'Bonus Criteria'),
            'incentive_amount' => Yii::t('app', 'Incentive Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
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

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['sap_farmer_code' => 'member_vendor_code']);
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->to_date) && !empty($this->from_date) && ($this->from_date > $this->to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than From Date.'));
            return false;
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->from_date = !empty($this->from_date) ? Yii::$app->controls->view_date($this->from_date, 'php:Y-m-d') : NULL;
            $this->to_date = !empty($this->to_date) ? Yii::$app->controls->view_date($this->to_date, 'php:Y-m-d') : NULL;
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $member = new TblMember();
            $member_data = $member->memberVendorData($this->member_vendor_code);
            if (empty($member_data)) {
                $this->addError('member_vendor_code', 'Member Vendor Code is invalid');
                return false;
            } else {
                $this->member_code = $member_data->member_code;
                $this->dcs_code = $member_data->dcs_code;
                $this->union_code = $member_data->union_code;
                $dcs_code = $this->dcsCode;
                $this->bmc_code = $dcs_code->bmc_code;
                $this->plant_code = $dcs_code->plant_code;
                $this->mcc_plant_code = $dcs_code->mcc_plant_code;
            }
        }
    }

}
