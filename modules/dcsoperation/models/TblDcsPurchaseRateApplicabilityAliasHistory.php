<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_applicability_alias_history".
 *
 * @property integer $id
 * @property integer $rate_app_alias_code
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
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $error_desc
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblDcsPurchaseRateApplicabilityAliasHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_purchase_rate_applicability_alias_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_app_alias_code', 'is_active', 'purchase_rate_code', 'shift_code', 'rate_type', 'rate_gen_method_code', 'is_download'], 'integer'],
            [['created_at', 'updated_at', 'wef_date', 'download_date_time', 'history_created_at', 'status'], 'safe'],
            [['is_download'], 'required'],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['dcs_code'], 'string', 'max' => 12],
            [['union_code'], 'string', 'max' => 3],
            [['applicable_code'], 'string', 'max' => 20],
            [['applicable_for', 'operation_type'], 'string', 'max' => 10],
            [['error_desc'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'rate_app_alias_code' => Yii::t('app', 'Rate App Alias Code'),
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
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'error_desc' => Yii::t('app', 'Error Desc'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
