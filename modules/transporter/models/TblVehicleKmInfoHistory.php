<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_km_info_history".
 *
 * @property integer $id
 * @property string $km_info_code
 * @property string $vehicle_code
 * @property string $route_code
 * @property string $transporter_code
 * @property string $wef_date
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $extra_kms
 * @property string $total_kms
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property integer $is_active
 */
class TblVehicleKmInfoHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_km_info_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_active', 'data_lock'], 'safe'],
            [['km_info_code', 'vehicle_code', 'route_code', 'transporter_code', 'created_by', 'updated_by', 'operation_type'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'shift_code'], 'safe'],
            [['morning_kms', 'evening_kms', 'extra_kms', 'total_kms','union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'km_info_code' => Yii::t('app', 'Km Info Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'morning_kms' => Yii::t('app', 'Morning Kms'),
            'evening_kms' => Yii::t('app', 'Evening Kms'),
            'extra_kms' => Yii::t('app', 'Extra Kms'),
            'total_kms' => Yii::t('app', 'Total Kms'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

}
