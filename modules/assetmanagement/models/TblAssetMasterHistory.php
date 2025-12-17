<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_master_history".
 *
 * @property integer $id
 * @property string $asset_code
 * @property string $asset_group_code
 * @property string $asset_name
 * @property integer $is_serial_number
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $local_name
 */
class TblAssetMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_code', 'asset_group_code', 'asset_name', 'created_by', 'updated_by', 'operation_type', 'history_created_by', 'local_name', 'asset_type_code'], 'safe'],
            [['is_serial_number', 'is_active', 'cmpl_product_code'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['ref_code', 'is_spare'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'asset_group_code' => Yii::t('app', 'Asset Group Code'),
            'asset_name' => Yii::t('app', 'Asset Name'),
            'is_serial_number' => Yii::t('app', 'Is Serial Number'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'local_name' => Yii::t('app', 'Local Name'),
            'ref_code' => Yii::t('app', 'Reference Code'),
            'is_spare' => Yii::t('app', 'Is Spare'),
        ];
    }

}
