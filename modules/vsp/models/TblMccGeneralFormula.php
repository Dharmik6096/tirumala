<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_mcc_general_formula".
 *
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
 */
class TblMccGeneralFormula extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_general_formula';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['general_formula_code', 'formula', 'formula_name', 'union_code'], 'required'],
            [['formula_name'], 'unique'],
            [['general_formula_code', 'formula', 'formula_name', 'formula_description', 'created_by', 'updated_by', 'union_code'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'default', 'value' => '1'],
            [['formula'], 'validateFormula'],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'general_formula_code' => Yii::t('app', 'General Formula Code'),
            'formula' => Yii::t('app', 'Formula'),
            'formula_name' => Yii::t('app', 'Formula Name'),
            'formula_description' => Yii::t('app', 'Formula Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function validateFormula($attribute, $params) {
        if (!empty($this->formula)) {
            $error = false;
            if (substr_count($this->formula, '(') != substr_count($this->formula, ')')) {
                $error = true;
            } else if (in_array(substr($this->formula, -1, 1), ['+', '-', '*', '/']) || in_array(substr($this->formula, 0, 1), ['+', '-', '*', '/'])) {
                $error = true;
            }
            if ($error) {
                $this->addError($attribute, Yii::t('app/validation', 'Incorrect Formula.'));
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
