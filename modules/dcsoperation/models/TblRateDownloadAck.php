<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_download_ack".
 *
 * @property integer $ack_id
 * @property integer $rate_app_code
 * @property integer $purchase_rate_code
 * @property string $wef_date
 * @property integer $shift_code
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $device_id
 * @property string $hash_key
 * @property string $union_code
 * @property string $download_date_time
 */
class TblRateDownloadAck extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rate_download_ack';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate_app_code', 'purchase_rate_code', 'shift_code'], 'integer'],
            [['wef_date', 'download_date_time'], 'safe'],
            [['applicable_code', 'applicable_for', 'device_id', 'hash_key', 'union_code'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ack_id' => Yii::t('app', 'Ack ID'),
            'rate_app_code' => Yii::t('app', 'Rate App Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'device_id' => Yii::t('app', 'Device ID'),
            'hash_key' => Yii::t('app', 'Hash Key'),
            'union_code' => Yii::t('app', 'Union Code'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
        ];
    }
}
