<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_coupon_issue_history".
 *
 * @property integer $id
 * @property string $coupon_issue_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $balance
 * @property string $consumer_code
 * @property integer $consumer_type
 * @property integer $milk_type_code
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $issue_date
 * @property string $voucher_code
 * @property integer $payment_mode
 * @property string $bank_code
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblCouponIssueHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_coupon_issue_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['coupon_issue_code', 'originating_org_code', 'originating_org_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'consumer_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'voucher_code', 'bank_code', 'created_by', 'updated_by', 'history_created_by', 'operation_type', 'balance', 'consumer_type', 'milk_type_code', 'is_active', 'is_delete', 'payment_mode', 'originating_type', 'issue_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'coupon_issue_code' => Yii::t('app', 'Coupon Issue Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'balance' => Yii::t('app', 'Balance'),
            'consumer_code' => Yii::t('app', 'Consumer Code'),
            'consumer_type' => Yii::t('app', 'Consumer Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'issue_date' => Yii::t('app', 'Issue Date'),
            'voucher_code' => Yii::t('app', 'Voucher Code'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'bank_code' => Yii::t('app', 'Bank Code'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
