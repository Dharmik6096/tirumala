<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHead;
use app\modules\vsp\models\TblGeneralFormula;
use app\modules\vsp\models\TblMccBillHeadCriteriaApplicability;

/**
 * This is the model class for table "tbl_vsp_bill_head_criteria".
 *
 * @property string $vsp_criteria_code
 * @property string $criteria_name
 * @property integer $bill_head_code
 * @property string $general_formula_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $criteria_code
 */
class TblMccBillHeadCriteria extends \app\models\ChildModel {

    public $to_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_bill_head_criteria';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['mcc_bill_head_code', 'general_formula_code', 'criteria_name'], 'required', 'on' => ['create']],
                [['originating_type'], 'integer'],
                [['created_at', 'updated_at', 'to_date'], 'safe'],
                [['general_formula_code'], 'string', 'max' => 20],
                [['mcc_bill_head_code'], 'string', 'max' => 10],
                [['criteria_name'], 'string', 'max' => 100],
                [['union_code'], 'string', 'max' => 3],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                ['criteria_name', 'unique', 'targetAttribute' => ['criteria_name', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['create']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'criteria_code' => Yii::t('app', 'Criteria Code'),
            'criteria_name' => Yii::t('app', 'Criteria Name'),
            'mcc_bill_head_code' => Yii::t('app', 'Bill Head'),
            'general_formula_code' => Yii::t('app', 'Formula'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMccBillHead() {
        return $this->hasOne(TblMccBillHead::className(), ['mcc_bill_head_code' => 'mcc_bill_head_code']);
    }

    public function getGeneralFormula() {
        return $this->hasOne(TblMccGeneralFormula::className(), ['general_formula_code' => 'general_formula_code']);
    }

    public function checkAllowDelete() {
        return Yii::$app->general->allowUpdateDelete($this);
    }

     public function getIsApplicability() {
         return $this->hasOne(TblMccBillHeadCriteriaApplicability::className(), ['criteria_code' => 'criteria_code']);
     }

}
