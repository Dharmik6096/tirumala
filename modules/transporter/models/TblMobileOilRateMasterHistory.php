<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_mobile_oil_rate_master_history".
 *
 * @property integer $id
 * @property integer $mobile_oil_rate_master_code
 * @property string $rate
 * @property string $wef_date
 * @property integer $vehicle_code
 * @property string $km_info
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 */
class TblMobileOilRateMasterHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mobile_oil_rate_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile_oil_rate_master_code', 'vehicle_code'], 'safe'],
            [['rate'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['km_info', 'created_by', 'updated_by', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mobile_oil_rate_master_code' => Yii::t('app', 'Mobile Oil Rate Master Code'),
            'rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'km_info' => Yii::t('app', 'Km Info'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }
}
