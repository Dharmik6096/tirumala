<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_route_mapping_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $route_code
 * @property integer $capacity
 * @property string $morning_start_time
 * @property string $morning_end_time
 * @property double $route_length_kms
 * @property string $route_name
 * @property string $union_code
 * @property integer $vehicle_type_code
 * @property string $local_name
 * @property string $evening_start_time
 * @property string $evening_end_time
 * @property string $route_type
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 */
class TblRouteMappingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_route_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // [['id'], 'required'],
            [['id', 'capacity', 'vehicle_type_code', 'is_active'], 'safe'],
            [['history_created_at', 'created_at', 'updated_at', 'valid_from'], 'safe'],
            [['operation_type', 'route_code', 'morning_start_time', 'morning_end_time', 'route_name', 'union_code', 'local_name', 'evening_start_time', 'evening_end_time', 'route_type', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'safe'],
            [['route_length_kms'], 'safe'],
            [['route_code_ex', 'ref_code', 'vendor_code', 'auto_code'], 'safe'],
            [['data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'history_created_by'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'route_code' => Yii::t('app', 'Route Code'),
            'capacity' => Yii::t('app', 'Capacity'),
            'morning_start_time' => Yii::t('app', 'Morning Start Time'),
            'morning_end_time' => Yii::t('app', 'Morning End Time'),
            'route_length_kms' => Yii::t('app', 'Route Length Kms'),
            'route_name' => Yii::t('app', 'Route Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'vehicle_type_code' => Yii::t('app', 'Vehicle Type Code'),
            'local_name' => Yii::t('app', 'Local Name'),
            'evening_start_time' => Yii::t('app', 'Evening Start Time'),
            'evening_end_time' => Yii::t('app', 'Evening End Time'),
            'route_type' => Yii::t('app', 'Route Type'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

}
