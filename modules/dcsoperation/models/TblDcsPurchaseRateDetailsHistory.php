<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_details_history".
 *
 * @property integer $id
 * @property string $code
 * @property double $fat
 * @property double $rtpl
 * @property double $snf
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $purchase_rate_code
 * @property integer $rate_type_code
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblDcsPurchaseRateDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_purchase_rate_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code'], 'safe'],
            [['fat', 'rtpl', 'snf'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'rate_type_code'], 'safe'],
            [['history_created_at'], 'safe'],
            [['purchase_rate_code'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'fat' => Yii::t('app', 'Fat'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'snf' => Yii::t('app', 'Snf'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'rate_type_code' => Yii::t('app', 'Rate Type Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateDetailsHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsPurchaseRateDetailsHistoryQuery(get_called_class());
    }

}
