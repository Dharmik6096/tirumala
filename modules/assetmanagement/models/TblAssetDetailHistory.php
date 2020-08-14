<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_detail_history".
 *
 * @property integer $id
 * @property string $asset_detail_code
 * @property string $asset_group_code
 * @property string $asset_code
 * @property string $store_location_code
 * @property string $serial_number
 * @property string $manufacturer_code
 * @property string $capacity
 * @property string $purchase_date
 * @property string $put_to_use_date
 * @property string $warranty_period
 * @property string $maintanance_duration_in_days
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblAssetDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_detail_code', 'asset_group_code', 'asset_code', 'store_location_code', 'serial_number', 'manufacturer_code', 'capacity', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['purchase_date', 'put_to_use_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['warranty_period', 'maintanance_duration_in_days', 'union_code'], 'safe'],
            [['is_active', 'qty', 'make'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_detail_code' => Yii::t('app', 'Asset Detail Code'),
            'asset_group_code' => Yii::t('app', 'Asset Group Code'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'store_location_code' => Yii::t('app', 'Store Location Code'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'manufacturer_code' => Yii::t('app', 'Manufacturer Code'),
            'capacity' => Yii::t('app', 'Capacity'),
            'purchase_date' => Yii::t('app', 'Purchase Date'),
            'put_to_use_date' => Yii::t('app', 'Put To Use Date'),
            'warranty_period' => Yii::t('app', 'Warranty Period'),
            'maintanance_duration_in_days' => Yii::t('app', 'Maintanance Duration In Days'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
