<?php

namespace app\modules\payment\models;

use app\modules\general\models\TblProcessApproval;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_payment_transaction_approval".
 *
 * @property string $payment_transaction_approval_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_type
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $payment_date
 * @property string $qty
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $avg_rate
 * @property string $from_date
 * @property string $to_date
 * @property integer $total_count
 * @property string $approval_status
 * @property string $remarks
 * @property string $status_date
 * @property string $status_by
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
class TblPaymentTransactionApproval extends \app\models\ChildModel
{
    public $process_approval_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_payment_transaction_approval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_transaction_approval_code','union_code','plant_code','mcc_plant_code','bmc_code','customer_type','total_amount','total_deduction','final_amount','payment_date','qty','avg_fat','avg_snf','kg_fat','kg_snf','avg_rate','from_date','to_date','total_count','approval_status','remarks','status_date','status_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5','process_approval_code'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payment_transaction_approval_code' => Yii::t('app', 'Payment Transaction Approval Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant'),
            'bmc_code' => Yii::t('app', 'Bmc'),
            'customer_type' => Yii::t('app', 'Payment Type'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'qty' => Yii::t('app', 'Qty'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'total_count' => Yii::t('app', 'Total Count'),
            'approval_status' => Yii::t('app', 'Approval Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status_date' => Yii::t('app', 'Status Date'),
            'status_by' => Yii::t('app', 'Status By'),
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

    public function getProcessApprovalCode() {
        return $this->hasMany(TblProcessApproval::className(), ['process_code' => 'payment_transaction_approval_code']);
    }
}
