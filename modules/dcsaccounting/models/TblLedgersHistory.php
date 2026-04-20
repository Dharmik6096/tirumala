<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_ledgers_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $ledger_code
 * @property string $ledger_name
 * @property string $local_name
 * @property integer $has_sub_ledger
 * @property integer $ledger_group_code
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
class TblLedgersHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledgers_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'history_created_by', 'operation_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ledger_name', 'ledger_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'has_sub_ledger', 'ledger_group_code', 'is_active', 'originating_type', 'created_at', 'updated_at', 'local_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ref_code'], 'safe'],
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
            'ledger_code' => Yii::t('app', 'Ledger Code'),
            'ledger_name' => Yii::t('app', 'Ledger Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'has_sub_ledger' => Yii::t('app', 'Has Sub Ledger'),
            'ledger_group_code' => Yii::t('app', 'Ledger Group Code'),
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
