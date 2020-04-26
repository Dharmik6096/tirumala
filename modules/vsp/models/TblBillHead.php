<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHeadDefault;

/**
 * This is the model class for table "tbl_bill_head".
 *
 * @property string $bill_head_code
 * @property string $bill_head_name
 * @property integer $is_default
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property integer $is_disburse_allowed
 * @property integer $bill_head_type
 * @property string $general_formula_code
 */
class TblBillHead extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bill_head_code', 'bill_head_name', 'bill_head_type', 'union_code', 'sequence_no', 'bill_head_for'], 'required'],
            [['bill_head_code', 'bill_head_name', 'created_by', 'updated_by', 'union_code', 'general_formula_code'], 'string'],
            [['is_default', 'is_active', 'is_disburse_allowed', 'bill_head_type', 'sequence_no'], 'integer'],
            [['created_at', 'updated_at', 'general_formula', 'default_bill_head_code'], 'safe'],
            [['is_active'], 'default', 'value' => '1'],
            [['is_disburse_allowed'], 'default', 'value' => '1'],
            [['is_default'], 'default', 'value' => '0'],
            ['default_bill_head_code', 'unique', 'targetAttribute' => ['default_bill_head_code', 'union_code', 'bill_head_for'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Default Bill Head Type has already been taken.')],
            ['default_bill_head_code', 'required', 'when' => function ($model) {
                    return $model->is_default == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblbillhead-is_default').is(':checked'); 
          }"],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'bill_head_for'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_head_name' => Yii::t('app', 'Head Name'),
            'is_default' => Yii::t('app', 'Is Default'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
            'is_disburse_allowed' => Yii::t('app', 'Is Disburse Allowed'),
            'bill_head_type' => Yii::t('app', 'Bill Head Type'),
            'default_bill_head_code' => Yii::t('app', 'Default Bill Head Type'),
            'general_formula_code' => Yii::t('app', 'Formula'),
            'sequence_no' => Yii::t('app', 'Sequence No.'),
            'bill_head_for' => Yii::t('app', 'Head For'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblBillHeadQuery the active query used by this AR class.
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHeadApplicability::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getDefaultBillHeadCode() {
        return $this->hasOne(TblBillHeadDefault::className(), ['default_bill_head_code' => 'default_bill_head_code']);
    }

    public function getAllBillHead($society = '', $union = '') {
        $query = $this->find()->select('tbl_bill_head.bill_head_code,bill_head_name')->where(['is_active' => 1, 'is_default' => 0]);
        $query->andWhere(['or', ['general_formula_code' => ''], ['general_formula_code' => null]]);
        if (!empty($union)) {
            $query->andWhere(['union_code' => $union]);
        }
        if (!empty($society)) {
            $query->innerJoinWith('billHeadCode')->andWhere(['dcs_code' => $society]);
        }
        return $query->all();
    }

    public function billHeadData($society = [], $union = []) {
        $list = $this->getAllBillHead($society, $union);
        return \yii\helpers\ArrayHelper::map($list, 'bill_head_code', 'bill_head_name');
    }

    public function billHeadType($bill_head_code) {
        return $this->find()->select(['bill_head_type'])->where(['bill_head_code' => $bill_head_code])->one();
    }

    public function billHeadTypeWise($union, $type, $code, $headFor) {
        $query = $this->find()
                ->innerJoinWith('billHeadCode as apl')
                ->where(['is_active' => 1, 'is_default' => 0, 'tbl_bill_head.bill_head_for' => $headFor, 'apl.union_code' => $union, 'apl.applicable_for' => $type, 'apl.applicable_code' => $code])
                ->andWhere(['or', ['general_formula_code' => ''], ['general_formula_code' => null]]);
        $list = $query->all();

        return \yii\helpers\ArrayHelper::map($list, 'bill_head_code', function($data) {
                    return isset($data->bill_head_type) ? $data->bill_head_name . ' (' . Yii::$app->dropdown->getRecords('calc_type')['data'][$data->bill_head_type] . ')' : $data->bill_head_name;
                });
    }

}
