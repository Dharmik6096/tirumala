<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_routes_history".
 *
 * @property integer $id
 * @property integer $capacity
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $return_time
 * @property string $route_code
 * @property string $route_length_kms
 * @property string $route_name
 * @property string $local_name
 * @property string $start_time
 * @property string $updated_at
 * @property string $created_by
 * @property string $union_code
 * @property string $updated_by
 * @property integer $vehicle_type_code
 *
 * @property TblVehicleTypes $vehicleType
 * @property TblUnions $unionCode
 * @property TblUsers $updatedBy
 * @property TblUsers $deletedBy
 * @property TblUsers $createdBy
 */
class TblRoutesHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_routes_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at', 'is_active', 'bmc_code', 'sap_vendor_code'], 'safe'],
                [['route_length_kms', 'capacity', 'vehicle_type_code', 'return_time', 'route_code', 'vehicle_type_code', 'route_name', 'start_time', 'union_code', 'local_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'capacity' => Yii::t('app', 'Capacity'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'is_active' => Yii::t('app', 'Is Active'),
                  'operation_type' => Yii::t('app', 'Operation Type'),
                  'return_time' => Yii::t('app', 'Return Time'),
                  'route_code' => Yii::t('app', 'Route Code'),
                  'route_length_kms' => Yii::t('app', 'Route Length Kms'),
                  'route_name' => Yii::t('app', 'Route Name'),
                  'start_time' => Yii::t('app', 'Start Time'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'union_code' => Yii::t('app', 'Union Code'),
                  'updated_by' => Yii::t('app', 'Updated By'),
                  'vehical_type_code' => Yii::t('app', 'Vehical Type'), */
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleType() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*   public function getUpdatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
      } */

    /**
     * @return \yii\db\ActiveQuery
     */
    /*   public function getDeletedBy()
      {
      return $this->hasOne(TblUsers::className());
      } */

    /**
     * @return \yii\db\ActiveQuery
     */
    /*    public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
      }
     */

    /**
     * @inheritdoc
     * @return TblRoutesHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblRoutesHistoryQuery(get_called_class());
    }

}
