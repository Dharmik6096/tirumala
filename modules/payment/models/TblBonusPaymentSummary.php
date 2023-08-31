<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_bonus_payment_summary".
 *
 * @property integer $bonus_payment_summary_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $payment_type
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $qty
 * @property string $amount
 * @property string $addition
 * @property string $deduction
 * @property string $net_payable
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblBonusPaymentSummary extends \app\models\ChildModel {

    public $customer_name, $customer_ex_code, $payment_cycle_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bonus_payment_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_datetime', 'to_datetime'], 'required', 'on' => 'process'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'payment_cycle_code'], 'required', 'on' => 'disburse'],
                [['from_datetime', 'to_datetime', 'created_at', 'updated_at'], 'safe'],
                [['from_shift', 'to_shift', 'originating_type'], 'safe'],
                [['kg_fat', 'kg_snf', 'avg_fat', 'avg_snf', 'qty', 'amount', 'addition', 'deduction', 'net_payable'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'safe'],
                [['customer_type', 'customer_code', 'payment_type'], 'safe'],
                [['status'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'payment_cycle_code'], 'safe'],
                [['from_datetime'], 'validateDate', 'on' => 'process'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bonus_payment_summary_code' => Yii::t('app', 'Bonus Payment Summary Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'customer_type' => Yii::t('app', 'Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'payment_type' => Yii::t('app', 'Payment Type'),
            'from_datetime' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'kg_fat' => Yii::t('app', 'KgFAT'),
            'kg_snf' => Yii::t('app', 'KgSNF'),
            'avg_fat' => Yii::t('app', 'AvgFat'),
            'avg_snf' => Yii::t('app', 'AvgSnf'),
            'qty' => Yii::t('app', 'Qty'),
            'amount' => Yii::t('app', 'Milk Amount'),
            'addition' => Yii::t('app', 'Addition'),
            'deduction' => Yii::t('app', 'Deduction'),
            'net_payable' => Yii::t('app', 'Net Payable'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
        ];
    }

    public function validateDate() {
        if (strtotime($this->from_datetime) > strtotime($this->to_datetime)) {
            $this->addError('from_datetime', 'From Date must not be grater than To Date.');
        }
    }

    public function getRecords() {
        return $this->find()->where(['union_code' => $this->union_code,
                    'from_datetime' => $this->from_datetime,
                    'to_datetime' => $this->to_datetime,
                    'bmc_code' => $this->bmc_code,
                    'payment_type' => $this->payment_type,
                    'customer_type' => $this->customer_type,
                    'status' => ['generated', 'processed']
                ])->orderBy(['bmc_code' => SORT_ASC, 'customer_code' => SORT_ASC]);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function PaymentCycleList($union_code, $bmc_code) {
        $data = $this->find()->select(['from_datetime', 'to_datetime'])
                        ->where(['union_code' => $union_code, 'bmc_code' => $bmc_code])
                        ->andWhere(['in', 'status', ['locked']])->asArray()->all();

        return ArrayHelper::map($data, function($data) {
                    return $data['from_datetime'] . '#' . $data['to_datetime'];
                }, function($data) {
                    return date('d-m-Y', strtotime($data['from_datetime'])) . ' To ' . date('d-m-Y', strtotime($data['to_datetime']));
                });
    }

}
