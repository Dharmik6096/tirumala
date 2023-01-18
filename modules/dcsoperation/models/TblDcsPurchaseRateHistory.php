<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_history".
 *
 * @property integer $id
 * @property string $purchase_rate_code
 * @property string $wef_date
 * @property string $description
 * @property integer $is_excel
 * @property string $originating_org_type
 * @property string $originating_org_id
 * @property string $rate_method
 * @property integer $rate_type
 * @property string $created_at
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property integer $is_default
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $operation_type
 * @property integer $shift_applicability
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblDcsPurchaseRateMaster $purchaseRateCode
 * @property User $updatedBy
 */
class TblDcsPurchaseRateHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_purchase_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['purchase_rate_code'], 'safe'],
                [['wef_date', 'created_at', 'deleted_at', 'history_created_at', 'sync_timestamp', 'updated_at', 'is_default'], 'safe'],
                [['is_excel', 'rate_type', 'is_active', 'is_delete', 'shift_applicability'], 'safe'],
                [['purchase_rate_code', 'description', 'rate_method'], 'safe'],
                [['originating_org_type'], 'safe'],
                [['originating_org_id'], 'safe'],
                [['flg_sentbox_entry', 'sync_status'], 'safe'],
                [['operation_type'], 'safe'],
                [['created_by', 'is_delete', 'originating_org_code', 'updated_by', 'rate_gen_method_code', 'shift_id', 'originating_type', 'union_code', 'qty_mode', 'reference_code', 'is_process', 'for_member', 'ts_rate', 'rate_type', 'rate_value'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'description' => Yii::t('app', 'Description'),
            'is_excel' => Yii::t('app', 'Is Excel'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_id' => Yii::t('app', 'Originating Org ID'),
            'rate_method' => Yii::t('app', 'Rate Method'),
            'rate_type' => Yii::t('app', 'Rate Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'shift_applicability' => Yii::t('app', 'Shift Applicability'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy() {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRateCode() {
        return $this->hasOne(TblDcsPurchaseRateMaster::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsPurchaseRateHistoryQuery(get_called_class());
    }

}
