<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_ledger_mapping_bill_head_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $ledger_mapping_bill_head_code
 * @property integer $type
 * @property integer $has_sub_ledger
 * @property integer $credit_debit
 * @property string $ledger_code
 * @property string $bill_head_code
 * @property string $bill_criteria_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblLedgerMappingBillHeadHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledger_mapping_bill_head_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ledger_mapping_bill_head_code', 'bmc_code', 'dcs_code', 'plant_code', 'mcc_plant_code', 'ledger_code', 'bill_head_code', 'bill_criteria_code', 'history_created_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'union_code', 'type', 'has_sub_ledger', 'credit_debit', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'history_created_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'ledger_mapping_bill_head_code' => Yii::t('app', 'Ledger Mapping Bill Head Code'),
            'type' => Yii::t('app', 'Type'),
            'has_sub_ledger' => Yii::t('app', 'Has Sub Ledger'),
            'credit_debit' => Yii::t('app', 'Credit Debit'),
            'ledger_code' => Yii::t('app', 'Ledger Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_criteria_code' => Yii::t('app', 'Bill Criteria Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
