<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_master".
 *
 * @property string $vehicle_code
 * @property integer $vehicle_type_code
 * @property integer $capacity_code
 * @property string $registration_no
 * @property string $applicable_rto
 * @property string $driver_name
 * @property string $driver_contact_no
 * @property string $wef_date
 * @property string $driving_license_number
 * @property string $transporter_code
 * @property string $mapped_route
 * @property integer $pollution_certificate
 * @property integer $insurance
 * @property string $rc_book_no
 * @property string $expiry_date
 * @property integer $rent
 * @property string $average
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $delete_at
 * @property string $delete_by
 * @property integer $is_active
 */
class TblVehicleMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_type_code', 'capacity_code','registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no','transporter_code', 'mapped_route','wef_date', 'union_code'], 'required'],
            [['registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no', 'driving_license_number', 'transporter_code', 'mapped_route', 'rc_book_no', 'average', 'union_code', 'created_by', 'updated_by', 'delete_by'], 'string'],
            [['vehicle_type_code', 'capacity_code', 'pollution_certificate', 'insurance', 'is_active'], 'integer'],
            [['wef_date', 'expiry_date', 'created_at', 'updated_at', 'delete_at','vehicle_code'], 'safe'],
            [['rent'], 'number'],
            [['driver_contact_no'], function ($attribute, $params) {
                Yii::$app->general->vaildatePhoneNumbers($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['driver_name'], function ($attribute, $params) {
                Yii::$app->general->validateName($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['registration_no','driving_license_number','rc_book_no'], function ($attribute, $params) {
                Yii::$app->general->validateAlphaNumber($this, $attribute,$params);
            },'skipOnEmpty'=> false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'vehicle_type_code' => Yii::t('app', 'Vehicle Type'),
            'capacity_code' => Yii::t('app', 'Capacity (LPD)'),
            'registration_no' => Yii::t('app', 'Registration No'),
            'applicable_rto' => Yii::t('app', 'Applicable RTO'),
            'driver_name' => Yii::t('app', 'Driver'),
            'driver_contact_no' => Yii::t('app', 'Driver Contact No'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'driving_license_number' => Yii::t('app', 'Driving License Number'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'mapped_route' => Yii::t('app', 'Mapped Route'),
            'pollution_certificate' => Yii::t('app', 'Pollution Certificate'),
            'insurance' => Yii::t('app', 'Insurance'),
            'rc_book_no' => Yii::t('app', 'RC Book No'),
            'expiry_date' => Yii::t('app', 'Expiry Date'),
            'rent' => Yii::t('app', 'Rent'),
            'average' => Yii::t('app', 'Average'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'delete_at' => Yii::t('app', 'Delete At'),
            'delete_by' => Yii::t('app', 'Delete By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVehicleMasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblVehicleMasterQuery(get_called_class());
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapacity()
    {
        return $this->hasOne(TblCapacity::className(), ['capacity_code' => 'capacity_code']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransporter()
    {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }
    
    public function getVehicleType()
    {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }
    
}
