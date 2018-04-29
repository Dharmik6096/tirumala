<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_rate_details_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $code
 * @property string $created_at
 * @property string $created_by
 * @property double $fat
 * @property integer $is_active
 * @property double $rtpl
 * @property double $snf
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $purchase_rate_code
 * @property integer $rate_type_code
 */
class TblPurchaseRateDetailsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_purchase_rate_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'code'], 'safe'],
            [['id', 'is_active', 'milk_quality_type_code', 'milk_type_code', 'purchase_rate_code', 'rate_type_code'], 'safe'],
            [['operation_type', 'code', 'created_by', 'updated_by'], 'safe'],
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['fat', 'rtpl', 'snf'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'fat' => Yii::t('app', 'Fat'),
            'is_active' => Yii::t('app', 'Is Active'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'snf' => Yii::t('app', 'Snf'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'rate_type_code' => Yii::t('app', 'Rate Type Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateDetailsHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblPurchaseRateDetailsHistoryQuery(get_called_class());
    }
}
