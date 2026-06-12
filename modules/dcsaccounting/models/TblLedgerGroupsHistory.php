<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_ledger_groups_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $ledger_group_code
 * @property string $ledger_group_name
 * @property string $local_name
 * @property integer $ledger_type_code
 * @property integer $is_active
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
class TblLedgerGroupsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledger_groups_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ledger_group_name', 'ledger_group_code', 'ledger_type_code', 'is_active', 'local_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'history_created_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'originating_type', 'history_created_at', 'created_at', 'updated_at', 'ref_code', 'is_cash'], 'safe'],
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
            'ledger_group_code' => Yii::t('app', 'Ledger Group Code'),
            'ledger_group_name' => Yii::t('app', 'Ledger Group Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'ledger_type_code' => Yii::t('app', 'Ledger Type Code'),
            'is_active' => Yii::t('app', 'Is Active'),
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
