<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_tanker_rate_based_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property integer $deduction_type
 * @property double $end_range
 * @property double $fixed_point
 * @property string $history_created_at
 * @property integer $is_active
 * @property double $kg_rate
 * @property string $operation_type
 * @property string $rate_based_code
 * @property integer $ref_type
 * @property double $start_range
 * @property double $step
 * @property string $updated_at
 * @property string $updated_by
 * @property double $value
 * @property string $formula_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $purchase_rate_code
 * @property integer $quality_param_code
 */
class TblTankerRateBasedHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tanker_rate_based_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['created_at', 'history_created_at', 'updated_at'], 'safe'],
//            [['created_by', 'operation_type', 'rate_based_code', 'updated_by', 'formula_code'], 'string'],
//            [['deduction_type', 'is_active', 'ref_type', 'milk_quality_type_code', 'milk_type_code', 'purchase_rate_code', 'quality_param_code'], 'integer'],
//            [['end_range', 'fixed_point', 'kg_rate', 'start_range', 'step', 'value'], 'number'],
       ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deduction_type' => Yii::t('app', 'Deduction Type'),
            'end_range' => Yii::t('app', 'End Range'),
            'fixed_point' => Yii::t('app', 'Fixed Point'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'kg_rate' => Yii::t('app', 'Kg Rate'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'rate_based_code' => Yii::t('app', 'Rate Based Code'),
            'ref_type' => Yii::t('app', 'Ref Type'),
            'start_range' => Yii::t('app', 'Start Range'),
            'step' => Yii::t('app', 'Step'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'value' => Yii::t('app', 'Value'),
            'formula_code' => Yii::t('app', 'Formula Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'quality_param_code' => Yii::t('app', 'Quality Param Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblTankerRateBasedHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblTankerRateBasedHistoryQuery(get_called_class());
    }
}
