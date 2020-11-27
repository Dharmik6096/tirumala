<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_group_history".
 *
 * @property integer $id
 * @property string $asset_group_code
 * @property string $asset_group_name
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
class TblAssetGroupHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_group_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_group_code', 'asset_group_name', 'created_by', 'updated_by', 'operation_type', 'history_created_by', 'local_name'], 'safe'],
            [['is_active'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'reference_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_group_code' => Yii::t('app', 'Asset Group Code'),
            'asset_group_name' => Yii::t('app', 'Asset Group Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'local_name' => Yii::t('app', 'Local Name'),
        ];
    }

}
