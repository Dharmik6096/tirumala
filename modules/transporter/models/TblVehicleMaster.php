<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblCapacity;
use app\modules\organisation\models\TblVehicleType;
use app\modules\syncutility\models\TblSentbox;

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
 * @property string $fuel_type
 * @property integer $is_active
 */
class TblVehicleMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $billing_type, $remarks;

    public static function tableName() {
        return 'tbl_vehicle_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pollution_certificate'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => 'importCsv'],
            [['insurance'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => 'importCsv'],
            [['billing_method'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'billing_method');
                }, 'on' => 'importCsv'],
            [['fuel_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'fuel_type_code');
                }, 'on' => 'importCsv'],
            [['vehicle_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'vehicle_type_code');
                }, 'on' => 'importCsv'],
            [['vehicle_type_code', 'capacity_code', 'registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no', 'transporter_code', 'wef_date', 'fuel_type_code', 'parsing_no', 'average', 'rent', 'billing_method'], 'required'],
            [['union_code'], 'required', 'except' => ['importCsv']],
            [['registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no', 'driving_license_number', 'transporter_code', 'mapped_route', 'rc_book_no', 'average', 'union_code', 'created_by', 'updated_by'], 'string'],
            [['vehicle_type_code', 'capacity_code', 'pollution_certificate', 'insurance', 'is_active'], 'integer'],
            [['wef_date', 'expiry_date', 'created_at', 'updated_at', 'vehicle_code', 'licence_expiry_date', 'bmc_code', 'billing_method'], 'safe'],
            [['rent', 'average'], 'number', 'min' => 0],
            [['driver_contact_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['driver_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['registration_no', 'driving_license_number', 'rc_book_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['parsing_no', 'rc_book_no'], 'unique'],
            [['is_active'], 'default', 'value' => 1],
            [['transporter_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblTransporter::className(), 'targetAttribute' => ['transporter_code' => 'transporter_code'], 'on' => ['importCsv']],
            [['vehicle_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleType::className(), 'targetAttribute' => ['vehicle_type_code' => 'vehicle_type_code'], 'on' => ['importCsv']],
            [['fuel_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblFuelTypeMaster::className(), 'targetAttribute' => ['fuel_type_code' => 'fuel_type_code'], 'on' => ['importCsv']],
            [['capacity_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblCapacity::className(), 'targetAttribute' => ['capacity_code' => 'capacity_code'], 'on' => ['importCsv']],
            [['wef_date', 'expiry_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date', 'expiry_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date', 'expiry_date'], 'convertDate', 'on' => ['importCsv']],
            [['transporter_code'], 'importFieldSet', 'on' => ['importCsv']],
//            [['parsing_no'], function ($attribute, $params) {
//                    Yii::$app->general->validVehicleNumber($this, $attribute, $params);
//                }],
//            [['parsing_no'], 'string', 'min' => 8, 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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
            'expiry_date' => Yii::t('app', 'Certificate Expiry Date'),
            'licence_expiry_date' => Yii::t('app', 'Licence Expiry Date'),
            'rent' => Yii::t('app', 'Rent'),
            'average' => Yii::t('app', 'Average'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'fuel_type_code' => Yii::t('app', 'Fuel Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'billing_method' => Yii::t('app', 'Billing Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVehicleMasterQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblVehicleMasterQuery(get_called_class());
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
    public function getCapacityCode() {
        return $this->hasOne(TblCapacity::className(), ['capacity_code' => 'capacity_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransporter() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getVehicleType() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }

    public function getFuelType() {
        return $this->hasOne(TblFuelTypeMaster::className(), ['fuel_type_code' => 'fuel_type_code']);
    }

    public function getVehicleBillingType() {
        return $this->hasOne(TblVehicleBillingType::className(), ['vehicle_code' => 'vehicle_code'])->orderBy('wef_date desc');
    }

    public function getVehicleKmInfo() {
        return $this->hasOne(TblVehicleKmInfo::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function vehicle($km_base) {
        $data = $this->find()->all();
//        if($km_base == true){
//            $vehicle_billing_model = new TblVehicleBillingType();
//            $km_based_vehicle = $vehicle_billing_model->find()
//                    ->select(['max(vehicle_billing_code) As vehicle_billing_code'])
//                    ->andFilterWhere(['<=','wef_date',date('Y-m-d')])
//                    ->groupBy('vehicle_code')->all();
//            $sub_query = $vehicle_billing_model->find()->select(['vehicle_code'])->where(['vehicle_billing_code' => $km_based_vehicle])->andFilterWhere(['=','billing_type_code',2])->all();
//            $data = $this->find()
//                ->where(['vehicle_code'=>$sub_query])
//                ->all();
//        }
        $array = \yii\helpers\ArrayHelper::map($data, 'vehicle_code', function($data) {
                    return $data->parsing_no . '/' . $data->vehicleType->vehicle_type_name;
                });
        return $array;
    }

    public function allVehicle($parents = '') {
        $rows = $this->find()->where(['transporter_code' => $parents])->all();
        $vehicles = [];
        foreach ($rows as $value) {
            $vehicles[] = array('id' => $value->vehicle_code,
                'name' => $value->parsing_no . '/' . $value->vehicleType->vehicle_type_name);
        }
        return $vehicles;
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
        try {
            $this->expiry_date = Yii::$app->controls->view_date($this->expiry_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->expiry_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
            $this->expiry_date = !empty($this->expiry_date) ? Yii::$app->controls->view_date($this->expiry_date, 'php:Y-m-d') : NULL;
        }
    }

    public function importFieldSet($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->transporter, 'union_code');
            $this->parsing_no = strtoupper($this->parsing_no);
        }
    }

}
