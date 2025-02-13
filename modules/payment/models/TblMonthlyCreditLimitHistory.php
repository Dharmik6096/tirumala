<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_monthly_credit_limit_history".
 *
 * @property int $id
 * @property int $monthly_credit_limit_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $milk_amount
 * @property string $manual_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property int $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMonthlyCreditLimitHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_monthly_credit_limit_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['monthly_credit_limit_code', 'customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'final_amount', 'milk_amount', 'manual_amount', 'created_at', 'created_by', 'updated_at', 'updated_by', 'history_created_at', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'Id'),
            'monthly_credit_limit_code' => Yii::t('app', 'Monthly Credit Limit Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'MCC Plant Code'),
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'milk_amount' => Yii::t('app', 'Milk Amount'),
            'manual_amount' => Yii::t('app', 'Manual Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Column 1'),
            'x_col2' => Yii::t('app', 'X Column 2'),
            'x_col3' => Yii::t('app', 'X Column 3'),
            'x_col4' => Yii::t('app', 'X Column 4'),
            'x_col5' => Yii::t('app', 'X Column 5'),
        ];
    }

}
