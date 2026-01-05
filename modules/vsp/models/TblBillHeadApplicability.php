<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHead;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_bill_head_applicability".
 *
 * @property integer $bill_head_applicabilty_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $dcs_code
 * @property string $bill_head_code
 * @property string $union_code
 */
class TblBillHeadApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'updated_at', 'wef_date', 'from_date', 'to_date'], 'safe'],
                [['applicable_code'], 'required'],
                [['to_date'], 'required', 'on' => ['updateToDate']],
                [['from_date'], 'setToDate', 'except' => ['updateToDate']],
                [['from_date'], 'required', 'except' => ['updateToDate']],
                [['created_by', 'updated_by', 'dcs_code', 'bill_head_code', 'union_code'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
                [['applicable_code', 'applicable_for', 'bmc_code', 'bill_head_for'], 'safe'],
                [['applicable_code'], 'setBMCCode', 'except' => ['updateToDate']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_applicabilty_code' => Yii::t('app', 'Bill Head Applicabilty Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'union_code' => Yii::t('app', 'Union'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'mcc_name' => Yii::t('app', 'Applicable Name'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function getDcsName() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function setBMCCode($attribute, $params) {
        if (empty($this->bmc_code) && !is_array($this->applicable_code)) {
            $this->bmc_code = Yii::$app->general->getCustomer($this, $this->applicable_for, FALSE, TRUE);
        }
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function getEditRecord() {
        return $this->find()->where(['bill_head_code' => $this->bill_head_code, 'applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for])
                        ->andWhere(['<=', 'from_date', $this->to_date])->all();
    }

    public function setToDate(){
        if(empty($this->to_date)){
            $this->to_date = '2099-01-01';
        }
    }

}
