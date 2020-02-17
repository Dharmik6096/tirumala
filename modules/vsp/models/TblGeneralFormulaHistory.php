<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_general_formula_history".
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
class TblGeneralFormulaHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_general_formula_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['general_formula_code', 'formula', 'formula_name', 'formula_description', 'created_by', 'updated_by', 'union_code', 'operation_type', 'history_created_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['is_active', 'originating_type'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'general_formula_code' => Yii::t('app', 'General Formula Code'),
            'formula' => Yii::t('app', 'Formula'),
            'formula_name' => Yii::t('app', 'Formula Name'),
            'formula_description' => Yii::t('app', 'Formula Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
