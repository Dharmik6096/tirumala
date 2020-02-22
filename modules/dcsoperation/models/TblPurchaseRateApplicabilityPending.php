<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_rate_applicability_pending".
 *
 * @property integer $rate_app_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $dcs_code
 * @property integer $purchase_rate_code
 * @property integer $shift_code
 * @property string $union_code
 * @property integer $rate_type
 * @property integer $rate_gen_method_code
 * @property string $download_date_time
 * @property integer $is_download
 */
class TblPurchaseRateApplicabilityPending extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_purchase_rate_applicability_pending';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'wef_date', 'download_date_time'], 'safe'],
            [['created_by', 'updated_by', 'dcs_code', 'union_code'], 'string'],
            [['is_active', 'purchase_rate_code', 'shift_code', 'rate_type', 'rate_gen_method_code', 'is_download'], 'integer'],
            [['is_download'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rate_app_code' => Yii::t('app', 'Rate App Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'rate_type' => Yii::t('app', 'Rate Type'),
            'rate_gen_method_code' => Yii::t('app', 'Rate Gen Method Code'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
            'is_download' => Yii::t('app', 'Is Download'),
        ];
    }
}
