<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_general_formula_history".
 *
 * @property integer $id
 * @property string $general_formula_code
 * @property string $formula
 * @property string $formula_name
 * @property string $formula_description
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMccGeneralFormulaHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_general_formula_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'general_formula_code', 'formula', 'formula_name', 'formula_description', 'is_active'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'created_by', 'updated_by', 'history_created_by', 'originating_type', 'operation_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'general_formula_code' => 'General Formula Code',
            'formula' => 'Formula',
            'formula_name' => 'Formula Name',
            'formula_description' => 'Formula Description',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'union_code' => 'Union Code',
            'history_created_at' => 'History Created At',
            'operation_type' => 'Operation Type',
            'history_created_by' => 'History Created By',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
        ];
    }

}
