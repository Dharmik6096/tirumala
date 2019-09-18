<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_union_config_result_history".
 *
 * @property integer $id
 * @property integer $config_txn_code
 * @property integer $config_code
 * @property string $config_name
 * @property string $config_key
 * @property integer $config_result_code
 * @property string $config_result_key
 * @property string $config_result
 * @property string $config_for
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblUnionConfigResultHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_union_config_result_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_txn_code', 'config_code', 'config_result_code', 'originating_type'], 'safe'],
            [['config_name', 'config_key', 'config_result_key', 'config_result', 'config_for', 'union_code', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_txn_code' => Yii::t('app', 'Config Txn Code'),
            'config_code' => Yii::t('app', 'Config Code'),
            'config_name' => Yii::t('app', 'Config Name'),
            'config_key' => Yii::t('app', 'Config Key'),
            'config_result_code' => Yii::t('app', 'Config Result Code'),
            'config_result_key' => Yii::t('app', 'Config Result Key'),
            'config_result' => Yii::t('app', 'Config Result'),
            'config_for' => Yii::t('app', 'Config For'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
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

}
