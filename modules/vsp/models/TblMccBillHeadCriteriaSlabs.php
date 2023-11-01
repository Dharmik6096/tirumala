<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_bill_head_criteria_slabs".
 *
 * @property string $vsp_slab_code
 * @property string $vsp_criteria_code
 * @property integer $bill_head_code
 * @property string $from_val
 * @property string $to_val
 * @property string $general_formula_code
 * @property string $formula_with_val
 * @property string $for_what
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMccBillHeadCriteriaSlabs extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_bill_head_criteria_slabs';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_val', 'to_val', 'formula_with_val'], 'required', 'on' => ['create']],
            [['originating_type','criteria_code','criteria_slab_code'], 'integer'],
            [['from_val', 'to_val'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['general_formula_code', 'for_what'], 'string', 'max' => 20],
            [['mcc_bill_head_code'], 'string', 'max' => 10],
            [['formula_with_val'], 'string', 'max' => 100],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['for_what'], 'default', 'value' => 'qty'],
            [['from_val'], 'validateRange', 'on' => ['create']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'criteria_slab_code' => Yii::t('app', 'Criteria Slab Code'),
            'criteria_code' => Yii::t('app', 'Vsp Criteria Code'),
            'mcc_bill_head_code' => Yii::t('app', 'Mcc Bill Head Code'),
            'from_val' => Yii::t('app', 'From Val'),
            'to_val' => Yii::t('app', 'To Val'),
            'general_formula_code' => Yii::t('app', 'Formula'),
            'formula_with_val' => Yii::t('app', 'Formula Val.'),
            'for_what' => Yii::t('app', 'For What'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function validateRange($attribute, $params) {
        if ($this->from_val >= $this->to_val) {
            $this->addError($attribute, Yii::t('app/validation', 'To Val Must Greater than From Val'));
            return false;
        }

        $dateData = $this->find()
                ->where('criteria_code = \'' . $this->criteria_code . '\'')
                ->andWhere('((\'' . $this->from_val . '\' between from_val  and to_val) OR (\'' . $this->to_val . '\' between from_val  and to_val) OR  (from_val between \'' . $this->from_val . '\' and  \'' . $this->to_val . '\') OR (to_val between \'' . $this->from_val . '\' and \'' . $this->to_val . '\'))')
                ->andfilterWhere(['!=', 'criteria_slab_code', $this->criteria_slab_code])
                ->all();
        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Slab Range is invalid'));
            return false;
        }
    }

    public function checkAllowDelete() {
        return Yii::$app->general->allowUpdateDelete($this);
    }

}
