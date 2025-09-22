<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_event_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $event_code
 * @property integer $ledger_credit
 * @property integer $ledger_debit
 * @property string $description
 * @property string $event_name
 * @property integer $event_code_default
 * @property integer $sub_ledger_credit
 * @property integer $sub_ledger_debit
 * @property integer $is_active
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
class TblEventHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_event_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'created_at', 'updated_at', 'event_code', 'ledger_credit', 'ledger_debit', 'event_code_default', 'sub_ledger_credit', 'sub_ledger_debit', 'is_active', 'originating_type', 'operation_type', 'history_created_by', 'created_by', 'updated_by', 'description', 'event_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'originating_org_code', 'originating_org_type'], 'safe']
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
            'event_code' => Yii::t('app', 'Event Code'),
            'ledger_credit' => Yii::t('app', 'Ledger Credit'),
            'ledger_debit' => Yii::t('app', 'Ledger Debit'),
            'description' => Yii::t('app', 'Description'),
            'event_name' => Yii::t('app', 'Event Name'),
            'event_code_default' => Yii::t('app', 'Event Code Default'),
            'sub_ledger_credit' => Yii::t('app', 'Sub Ledger Credit'),
            'sub_ledger_debit' => Yii::t('app', 'Sub Ledger Debit'),
            'is_active' => Yii::t('app', 'Is Active'),
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
