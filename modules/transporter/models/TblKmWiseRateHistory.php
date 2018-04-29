<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_km_wise_rate_history".
 *
 * @property integer $id
 * @property integer $km_code
 * @property string $rate
 * @property string $from_km
 * @property string $to_km
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblKmWiseRateHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_km_wise_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['km_code'], 'safe'],
            [['rate', 'from_km', 'to_km'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at','vehicle_code'], 'safe'],
            [['created_by', 'updated_by', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'km_code' => Yii::t('app', 'Km Code'),
            'rate' => Yii::t('app', 'Rate'),
            'from_km' => Yii::t('app', 'From Km'),
            'to_km' => Yii::t('app', 'To Km'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
