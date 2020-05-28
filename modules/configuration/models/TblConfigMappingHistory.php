<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_config_mapping_history".
 *
 * @property integer $id
 * @property integer $config_mapping_code
 * @property integer $config_code
 * @property string $config_result
 * @property string $org_type
 * @property string $org_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblConfigMappingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_config_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_mapping_code', 'config_code', 'originating_type'], 'safe'],
            [['config_result', 'org_type', 'org_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'history_created_by'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_mapping_code' => Yii::t('app', 'Config Mapping Code'),
            'config_code' => Yii::t('app', 'Config Code'),
            'config_result' => Yii::t('app', 'Config Result'),
            'org_type' => Yii::t('app', 'Org Type'),
            'org_code' => Yii::t('app', 'Org Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
