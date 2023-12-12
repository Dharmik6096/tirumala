<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_rate_history".
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
 * @property string $history_created_at
 * @property integer $is_default
 * @property integer $is_active
 * @property string $operation_type
 * @property integer $shift_applicability
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblPurchaseRateMaster $purchaseRateCode
 * @property User $updatedBy
 */
class TblPurchaseRateHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_purchase_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['purchase_rate_code', 'description', 'rate_method', 'originating_org_id', 'originating_org_type', 'is_excel', 'rate_type', 'flg_sentbox_entry', 'sync_status', 'operation_type', 'is_active', 'is_delete', 'shift_applicability', 'wef_date', 'created_at', 'deleted_at', 'history_created_at', 'sync_timestamp', 'updated_at', 'is_default', 'union_code', 'reference_code'], 'safe'],
//            [['purchase_rate_code'], 'required'],
//            [['wef_date', 'created_at', 'deleted_at', 'history_created_at', 'sync_timestamp', 'updated_at','is_default'], 'safe'],
//            [['is_excel', 'rate_type', 'is_active', 'is_delete', 'shift_applicability'], 'integer'],
//            [['purchase_rate_code', 'description', 'rate_method'], 'string', 'max' => 255],
//            [['originating_org_type'], 'string', 'max' => 50],
//            [['originating_org_id'], 'string', 'max' => 25],
//            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
//            [['operation_type'], 'string', 'max' => 10],
//            [['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRateMaster::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code']],
            [['originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_by', 'originating_org_code', 'updated_by', 'rate_gen_method_code', 'rate_type', 'shift_id', 'reference_code', 'rate_category', 'is_process', 'ts_rate', 'kg_fat_rate', 'kg_snf_rate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
//            'wef_date' => Yii::t('app', 'Wef Date'),
//            'description' => Yii::t('app', 'Description'),
//            'is_excel' => Yii::t('app', 'Is Excel'),
//            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
//            'originating_org_id' => Yii::t('app', 'Originating Org ID'),
//            'rate_method' => Yii::t('app', 'Rate Method'),
//            'rate_type' => Yii::t('app', 'Rate Type'),
//            'created_at' => Yii::t('app', 'Created At'),
//            'deleted_at' => Yii::t('app', 'Deleted At'),
//            'history_created_at' => Yii::t('app', 'History Created At'),
//            'is_active' => Yii::t('app', 'Is Active'),
//            'is_delete' => Yii::t('app', 'Is Delete'),
//            'operation_type' => Yii::t('app', 'Operation Type'),
//            'shift_applicability' => Yii::t('app', 'Shift Applicability'),
//            'updated_at' => Yii::t('app', 'Updated At'),
//            'created_by' => Yii::t('app', 'Created By'),
//            'deleted_by' => Yii::t('app', 'Deleted By'),
//            'updated_by' => Yii::t('app', 'Updated By'),
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
        return $this->hasOne(TblPurchaseRateMaster::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblPurchaseRateHistoryQuery(get_called_class());
    }

}
