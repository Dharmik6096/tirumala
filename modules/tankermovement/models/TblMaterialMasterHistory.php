<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_material_master_history".
 *
 * @property integer $id
 * @property integer $material_code
 * @property string $material_name
 * @property string $local_name
 * @property string $union_code
 * @property string $ref_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMaterialMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_material_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'ref_code', 'created_at', 'updated_at', 'history_created_at', 'material_name', 'local_name', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_by', 'material_code', 'is_active', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'material_code' => Yii::t('app', 'Material Code'),
            'material_name' => Yii::t('app', 'Material Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
