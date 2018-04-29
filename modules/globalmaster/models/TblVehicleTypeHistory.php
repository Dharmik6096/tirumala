<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_type_history".
 *
 * @property integer $id
 * @property integer $vehicle_type_code
 * @property string $vehicle_type_name
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 */
class TblVehicleTypeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_type_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [           
            [['id', 'vehicle_type_code', 'is_active'], 'safe'],
            [['vehicle_type_name', 'created_by', 'operation_type', 'updated_by', 'local_name'], 'safe'],
            [['created_at', 'history_created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vehicle_type_code' => Yii::t('app', 'Vehicle Type Code'),
            'vehicle_type_name' => Yii::t('app', 'Vehicle Type Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVehicleTypeHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblVehicleTypeHistoryQuery(get_called_class());
    }
}
