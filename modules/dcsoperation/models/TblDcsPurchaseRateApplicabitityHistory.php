<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_applicabitity_history".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property boolean $is_active
 * @property boolean $is_delete
 * @property string $operation_type
 * @property string $rate_app_code
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $dcs_code
 * @property string $purchase_rate_code
 * @property integer $shift_code
 * @property string $union_code
 */
class TblDcsPurchaseRateApplicabitityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_purchase_rate_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'deleted_at', 'history_created_at', 'sync_timestamp', 'updated_at', 'wef_date'], 'safe'],
            [['is_active', 'is_delete'], 'safe'],
            [['shift_code'], 'safe'],
            [['created_by', 'deleted_by', 'updated_by'], 'safe'],
            [['flg_sentbox_entry', 'sync_status'], 'safe'],
            [['operation_type'], 'safe'],
            [['rate_app_code'], 'safe'],
            [['dcs_code'], 'safe'],
            [['purchase_rate_code'], 'safe'],
            [['union_code', 'applicable_for', 'applicable_code','rate_type','rate_gen_method_code','download_date_time','is_download','history_created_by'], 'safe'],
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
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'rate_app_code' => Yii::t('app', 'Rate App Code'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateApplicabitityHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsPurchaseRateApplicabitityHistoryQuery(get_called_class());
    }

}
