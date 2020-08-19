<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_set_history".
 *
 * @property integer $id
 * @property integer $asset_set_code
 * @property string $sap_code
 * @property string $sloc_code
 * @property string $store_location_type
 * @property string $reference_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $status
 * @property integer $is_active
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblAssetSetHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_set_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_set_code', 'status', 'is_active'], 'integer'],
            [['sap_code', 'sloc_code', 'store_location_type', 'reference_code', 'union_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'string'],
            [['created_at', 'updated_at', 'history_created_at', 'store_location_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_set_code' => Yii::t('app', 'Asset Set Code'),
            'sap_code' => Yii::t('app', 'Sap Code'),
            'sloc_code' => Yii::t('app', 'Sloc Code'),
            'store_location_type' => Yii::t('app', 'Store Location Type'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
