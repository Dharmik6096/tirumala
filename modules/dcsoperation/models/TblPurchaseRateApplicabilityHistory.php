<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_rate_applicability_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $rate_app_code
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $dcs_code
 * @property string $purchase_rate_code
 * @property integer $shift_code
 * @property string $union_code
 */
class TblPurchaseRateApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_purchase_rate_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active', 'shift_code', 'created_at', 'history_created_at', 'updated_at', 'wef_date', 'created_by', 'operation_type', 'rate_app_code', 'updated_by', 'dcs_code', 'purchase_rate_code', 'union_code', 'is_download', 'download_date_time'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'rate_app_code' => Yii::t('app', 'Rate App Code'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
