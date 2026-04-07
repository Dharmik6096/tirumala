<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHead;
use app\modules\vsp\models\TblGeneralFormula;
use app\modules\vsp\models\TblVspBillHeadCriteriaApplicability;

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
class TblVspBillHeadCriteria extends \app\models\ChildModel {

    public $to_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_bill_head_criteria';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vsp_criteria_code', 'bill_head_code', 'general_formula_code', 'criteria_name'], 'required', 'on' => ['create']],
                [['bill_head_code', 'originating_type'], 'integer'],
                [['created_at', 'updated_at', 'criteria_type', 'criteria_code', 'to_date'], 'safe'],
                [['vsp_criteria_code', 'general_formula_code'], 'string', 'max' => 20],
                [['criteria_name'], 'string', 'max' => 100],
                [['union_code'], 'string', 'max' => 3],
                [['created_by', 'updated_by'], 'string', 'max' => 255],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                ['criteria_name', 'unique', 'targetAttribute' => ['criteria_name', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['create']],
                [['criteria_name'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => false, 'except' => ['androidsync']],
                [['criteria_type'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vsp_criteria_code' => Yii::t('app', 'Vsp Criteria Code'),
            'criteria_name' => Yii::t('app', 'Criteria Name'),
            'bill_head_code' => Yii::t('app', 'Bill Head'),
            'general_formula_code' => Yii::t('app', 'Formula'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'criteria_code' => Yii::t('app', 'Criteria Code'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBillHead() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getGeneralFormula() {
        return $this->hasOne(TblGeneralFormula::className(), ['general_formula_code' => 'general_formula_code']);
    }

    public function checkAllowDelete() {
        return Yii::$app->general->allowUpdateDelete($this);
    }

    public function getIsApplicability() {
        return $this->hasOne(TblVspBillHeadCriteriaApplicability::className(), ['vsp_criteria_code' => 'vsp_criteria_code']);
    }

}
