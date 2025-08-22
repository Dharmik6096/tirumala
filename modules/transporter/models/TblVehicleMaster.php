<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblCapacity;
use app\modules\organisation\models\TblVehicleType;
use app\modules\syncutility\models\TblSentbox;
use app\modules\tankermovement\models\TblVehicleQaInspection;
use app\modules\transporter\models\TblTransporter;
use app\modules\transporter\models\TblFuelTypeMaster;
use app\modules\transporter\models\TblBillingType;
use yii\base\UserException;
use yii\helpers\ArrayHelper;

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
    public $billing_type, $remarks, $vendor_code, $flag_wef_date, $billing_qty_flag;

    public static function tableName() {
        return 'tbl_vehicle_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['billing_with_capacity'], 'default', 'value' => 0],
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
                }, 'on' => ['importCsv', 'customImport']],
                [['vehicle_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'vehicle_type_code');
                }, 'on' => ['importCsv', 'customImport']],
                [['vehicle_use_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'vehicle_use_type');
                }, 'on' => ['importCsv', 'customImport']],
                [['billing_qty_flag'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'billing_qty_flag');
                }, 'on' => ['importCsv', 'vehicleWiseFlag']],
                [['transporter_code'], 'fieldValidate', 'on' => 'importCsv'],
                [['driver_name', 'transporter_code', 'wef_date', 'union_code', 'parsing_no', 'billing_method', 'vehicle_use_type'], 'required', 'except' => ['importCsv', 'customImport', 'activation']],
                [['parsing_no', 'driver_name', 'transporter_code', 'wef_date', 'billing_method'], 'required', 'on' => 'importCsv'],
                [['vehicle_type_code', 'fuel_type_code', 'capacity_code'], 'required', 'except' => ['activation']],
//                [['vehicle_type_code', 'capacity_code', 'registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no', 'transporter_code', 'wef_date', 'fuel_type_code', 'parsing_no', 'average', 'rent', 'billing_method'], 'required', 'except' => ['activation']],
//                [['union_code'], 'required', 'except' => ['importCsv', 'activation']],
            [['registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no', 'driving_license_number', 'transporter_code', 'mapped_route', 'rc_book_no', 'average', 'union_code', 'created_by', 'updated_by'], 'string', 'except' => ['activation']],
                [['vehicle_type_code', 'capacity_code', 'pollution_certificate', 'insurance', 'is_active'], 'integer', 'except' => ['activation']],
                [['wef_date', 'expiry_date', 'created_at', 'updated_at', 'vehicle_code', 'licence_expiry_date', 'bmc_code', 'billing_method', 'billing_type_code', 'vehicle_use_type', 'billing_with_capacity', 'flag_wef_date', 'billing_qty_flag', 'no_of_compartment'], 'safe'],
                [['rent', 'average'], 'number', 'min' => 1],
                [['driver_contact_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                [['driver_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                [['driving_license_number', 'parsing_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                [['registration_no', 'rc_book_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['customImport', 'activation']],
                [['parsing_no', 'rc_book_no'], 'unique', 'except' => ['activation']],
                [['transporter_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblTransporter::className(), 'targetAttribute' => ['transporter_code' => 'transporter_code'], 'except' => ['activation']],
                [['vehicle_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleType::className(), 'targetAttribute' => ['vehicle_type_code' => 'vehicle_type_code'], 'except' => ['activation']],
                [['fuel_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblFuelTypeMaster::className(), 'targetAttribute' => ['fuel_type_code' => 'fuel_type_code'], 'except' => ['activation']],
                [['wef_date', 'expiry_date', 'licence_expiry_date', 'flag_wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => ['importCsv', 'customImport']],
                [['is_active'], 'default', 'value' => 1],
                [['rent'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->billing_type_code == '1';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#billing_type_code').val() == '1'; 
          }", 'except' => ['activation']],
                [['average'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->billing_type_code == '3';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#billing_type_code').val() == '3'; 
          }", 'except' => ['activation']],
                [['transporter_code'], 'parsingNoValidate', 'on' => ['importCsv']],
                [['transporter_code'], 'pastDateValidate', 'on' => ['importCsv', 'customImport']],
                [['flag_wef_date', 'billing_qty_flag'], 'required', 'on' => ['vehicleWiseFlag']],
                [['capacity_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblCapacity::className(), 'targetAttribute' => ['capacity_code' => 'capacity_code'], 'on' => ['importCsv']],
                [['wef_date', 'expiry_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['wef_date', 'expiry_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['wef_date', 'expiry_date'], 'convertDate', 'on' => ['importCsv']],
                [['transporter_code'], 'importFieldSet', 'on' => ['importCsv']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblVehicleMaster', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
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
            'rent' => Yii::t('app', 'Fix Freight'),
            'average' => Yii::t('app', 'Average (Km/Ltr)'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'fuel_type_code' => Yii::t('app', 'Fuel Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'billing_type_code' => Yii::t('app', 'Billing Type'),
            'vehicle_use_type' => Yii::t('app', 'Used for'),
            'billing_with_capacity' => Yii::t('app', 'Billing With Capacity ?'),
            'parsing_no' => Yii::t('app', 'Parsing No'),
            'no_of_compartment' => Yii::t('app', 'No Of Compartment'),
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

    public function fieldValidate($attribute, $params) {
        $this->union_code = Yii::$app->general->getforeignkey($this->transporter, 'union_code');
        $this->billing_type_code = Yii::$app->general->getforeignkey($this->transporter, 'billing_type_code');
    }

    public function getBillingType() {
        return $this->hasOne(TblBillingType::className(), ['billing_type_code' => 'billing_type_code']);
    }

    public function pastDateValidate($attribute, $params) {
        $this->licence_expiry_date = empty($this->licence_expiry_date) ? NULL : Yii::$app->formatter->asDate($this->licence_expiry_date, DATE_FORMAT);
        if (!empty($this->licence_expiry_date) && ($this->licence_expiry_date < date('Y-m-d'))) {
            $this->addError('licence_expiry_date', Yii::t('app/validation', $this->getAttributeLabel('licence_expiry_date') . ' Must be Greater than ' . date('d-m-Y')));
        }
    }

    public function parsingNoValidate($attribute, $params) {
        $this->parsing_no = strtoupper($this->parsing_no);
    }

    public function setImportChild(&$model, &$modelSave, &$errors) {
        if (in_array($model->vehicle_use_type, [1, 2])) {
            $model->scenario = 'vehicleWiseFlag';
            $childModel = new TblVehicleWiseQtyFlag();
            $childModel->vehicle_wise_qty_flag_code = Yii::$app->general->getCodeAutoIncrement($childModel);
            $childModel->vehicle_code = $model->vehicle_code;
            $childModel->qty_flag = $model->billing_qty_flag;
            $childModel->wef_date = ($model->flag_wef_date) ? Yii::$app->formatter->asDate($model->flag_wef_date, DATE_FORMAT) : NULL;
            if (!$model->validate()) {
                $errors[] = $model->getErrors();
            }
            array_push($modelSave, $childModel);
        }
    }

    public function setChildTable(&$model, &$modelSave, &$errors) {

        if (in_array($model->vehicle_use_type, [1, 2])) {
            $model->scenario = 'vehicleWiseFlag';
            $childModel = new TblVehicleWiseQtyFlag();
            $childModel->vehicle_code = $model->vehicle_code;
            $childModel->qty_flag = $model->billing_qty_flag;
            $childModel->wef_date = ($model->flag_wef_date) ? Yii::$app->formatter->asDate($model->flag_wef_date, DATE_FORMAT) : NULL;
            $flagData = $childModel->getExistingFlag();
            if (empty($flagData)) {
                $childModel->vehicle_wise_qty_flag_code = Yii::$app->general->getCodeAutoIncrement($childModel);
                array_push($modelSave, $childModel);
            }
            if (!$model->validate()) {
                $errors[] = $model->getErrors();
            }
            $model->scenario = 'customImport';
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code, '', TRUE, 2);
            $sentbox = new TblSentbox();
            $sentbox->source_org_id = $this->union_code;
            if (!($sentbox->setSentboxBatch($this, $flag, $sentboxArray))) {
                throw new UserException("SentBox Entry is not created so transaction is rollback!");
            }
        }
        $tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration($this->union_code, 'tanker_movement_with_trip_sub_status', 'PORTAL') == 1 ? TRUE : FALSE;
        if ($tankerMovementWithTripSubStatus && $flag === 'INSERT' && in_array($this->vehicle_use_type, [1, 2])) {
            $vehicleQaInspection = new TblVehicleQaInspection();
            $vehicleQaInspection->attributes = $this->attributes;
            $vehicleQaInspection->status = 'pending';
            $vehicleQaInspection->trip_code = $vehicleQaInspection->remarks = '';
            $vehicleQaInspection->transaction_datetime = $this->created_at;
            $vehicleQaInspection->save(TRUE, FALSE);
        }
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
            $this->vehicle_use_type = 2;
            $this->flag_wef_date = date('Y-m-d');
            $this->billing_qty_flag = 1;
        }
    }

    public function getVehicleMaster($unionCode, $type, $code, $milkReceipt, $transaction_date) {
        $query = $this->find()
                ->select(['tbl_vehicle_master.vehicle_code', 'tbl_vehicle_master.parsing_no'])
                ->innerJoin('tbl_vehicle_trip', 'tbl_vehicle_master.vehicle_code = tbl_vehicle_trip.vehicle_code')
                ->innerJoin('tbl_vehicle_trip_detail', 'tbl_vehicle_trip_detail.trip_code = tbl_vehicle_trip.trip_code')
                ->where(['tbl_vehicle_master.union_code' => $unionCode, 'LOWER(tbl_vehicle_trip_detail.source_org_type)' => strtolower($type), 'tbl_vehicle_trip_detail.source_org_code' => $code, 'tbl_vehicle_master.vehicle_use_type' => [1, 2]])
                ->andWhere(['<=', 'tbl_vehicle_trip.transaction_date', $transaction_date])
                ->andWhere(['IS NOT', 'arrival_time', null])
                ->andWhere(['IS', 'departure_time', null]);

        if ($milkReceipt) {
            $query->andWhere(['NOT', ['tbl_vehicle_trip.trip_status' => 'closed']]);
        } else {
            $query->andWhere(['tbl_vehicle_trip.trip_status' => ['generated', 'open']]);
        }
        $data = $query->groupBy(['tbl_vehicle_master.vehicle_code', 'tbl_vehicle_master.parsing_no'])->all();
        return ArrayHelper::map($data, 'vehicle_code', function($value) {
                    return $value->parsing_no;
                });
    }
    
    public function getVehicleList() {
        $vehicleUseTypes = ($this->vehicle_use_type == 0) ? [0, 2] : [1, 2];

        $vehicle = $this->find()->select(['vehicle_code', 'parsing_no'])
                ->where(['union_code' => $this->union_code, 'vehicle_use_type' => $vehicleUseTypes, 'is_active' => 1])
                ->all();

        return ArrayHelper::map($vehicle, 'vehicle_code', 'parsing_no');
    }

}
