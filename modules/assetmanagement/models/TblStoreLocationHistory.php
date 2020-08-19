<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_store_location_history".
 *
 * @property integer $id
 * @property string $store_location_code
 * @property string $store_location_name
 * @property string $store_location_type
 * @property string $reference_code
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
class TblStoreLocationHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_store_location_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_location_code', 'store_location_name', 'store_location_type', 'reference_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by', 'local_name'], 'safe'],
            [['is_active'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'store_location_code' => Yii::t('app', 'Store Location Code'),
            'store_location_name' => Yii::t('app', 'Store Location Name'),
            'store_location_type' => Yii::t('app', 'Store Location Type'),
            'reference_code' => Yii::t('app', 'Reference Code'),
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
