<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_config_txn_result_history".
 *
 * @property integer $id
 * @property string $config_txn_result_code
 * @property integer $config_code
 * @property string $config_result
 * @property string $config_for
 * @property string $ref_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $x_col6
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblConfigTxnResultHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_config_txn_result_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ref_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_by', 'config_code', 'originating_type', 'created_at', 'updated_at', 'history_created_at', 'config_txn_result_code', 'config_result', 'config_for', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'x_col6'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_txn_result_code' => Yii::t('app', 'Config Txn Result Code'),
            'config_code' => Yii::t('app', 'Config Code'),
            'config_result' => Yii::t('app', 'Config Result'),
            'config_for' => Yii::t('app', 'Config For'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'x_col6' => Yii::t('app', 'X Col6'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
