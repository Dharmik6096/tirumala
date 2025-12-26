<?php

namespace app\modules\vendorapi\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblDcs;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;

/**
 * This is the model class for table "tbl_vendor_api_data".
 *
 * @property integer $id
 * @property string $parent_code_other
 * @property string $parent_code
 * @property string $master_code
 * @property string $master_name
 * @property string $master_type
 * @property string $date_1
 * @property string $date_2
 * @property string $time_1
 * @property string $time_2
 * @property string $time_3
 * @property string $time_4
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $contact_first_name
 * @property string $contact_middle_name
 * @property string $contact_last_name
 * @property string $capacity
 * @property string $address
 * @property string $address_2
 * @property string $email
 * @property string $mobile_no
 * @property integer $is_active
 * @property string $type_2
 * @property string $bank_name
 * @property string $branch_name
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $route_length
 * @property string $type_of_data
 * @property string $union_code
 * @property string $service_type
 * @property string $username
 * @property string $password
 */
class TblVendorApiData extends \yii\db\ActiveRecord {

    public $date_validate;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vendor_api_data';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['parent_code', 'master_code', 'master_name', 'master_type', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'contact_first_name', 'contact_middle_name', 'contact_last_name', 'address', 'address_2', 'email', 'bank_name', 'branch_name', 'bank_account_no', 'ifsc', 'type_of_data', 'union_code', 'service_type', 'username', 'password'], 'string'],
                [['parent_code_other', 'date_1', 'date_2', 'time_1', 'time_2', 'time_3', 'time_4', 'type_2', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_fat', 'to_fat', 'fat_price', 'from_snf', 'to_snf', 'snf_price'], 'safe'],
                [['capacity', 'route_length'], 'number'],
                [['is_active'], 'integer'],
                [['master_type'], 'default', 'value' => 'Can', 'on' => 'route_master'],
                [['is_active'], 'default', 'value' => 1],
                [['master_code', 'master_name', 'date_1', 'time_1', 'time_2', 'time_3', 'time_4'], 'required', 'on' => 'route_master'],
//            [['date_validate'], 'convertDateDot'],
//            [['date_validate'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 01.12.2018')],
            [['time_1', 'time_2', 'time_3', 'time_4'], 'date', 'format' => 'php:H:i:s', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 12:30'), 'except' => ['route_create']],
                [['type_2'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'vehicle_type_code');
                }, 'on' => 'route_master'],
                [['master_code'], 'string', 'max' => 8, 'on' => 'route_master'],
                [['parent_code'], 'string', 'max' => 4, 'on' => 'route_master'],
            //[['mobile_no'], 'number', 'on' => 'route_master'],
            [['mobile_no'], 'string', 'max' => 50],
                [['master_name', 'master_type', 'contact_first_name', 'contact_middle_name', 'contact_last_name', 'email'], 'string', 'max' => 255, 'on' => 'route_master'],
                [['parent_code', 'master_code', 'master_name', 'date_1'], 'required', 'on' => 'mcc_master'],
                [['parent_code', 'parent_code_other', 'master_code', 'master_name', 'bank_account_no', 'ifsc'], 'required', 'on' => 'vlcc_master'],
                [['parent_code', 'master_code', 'date_1', 'date_2'], 'required', 'on' => 'route_vlcc'],
            //    [['parent_code'], 'validateRouteCode', 'on' => 'route_vlcc'],
            //  [['master_code'], 'validateVlccCode', 'on' => 'route_vlcc'],
            // [['parent_code'], 'validateMccCode', 'on' => 'route_master'],
            // [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
//            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
//            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
//            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
//            [['hamlet_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHamlets::className(), 'targetAttribute' => ['hamlet_code' => 'hamlet_code']],
//            [['parent_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['parent_code' => 'mcc_plant_code'], 'on' => 'route_master'],
            [['parent_code', 'master_code', 'date_1', 'date_2'], 'required', 'on' => 'rate_applicability'],
                [['from_fat', 'to_fat', 'from_snf', 'to_snf', 'fat_price', 'snf_price'], 'trimInputData', 'on' => 'rate_master'],
                [['master_code', 'date_1', 'date_2'], 'required', 'on' => 'rate_master'],
                [['date_1', 'date_2'], 'dateRangeValidate', 'on' => 'rate_master'],
                [['date_1', 'date_2'], 'dateRangeValidate', 'on' => 'rate_applicability'],
                [['from_fat', 'to_fat'], 'fatRangeValidate', 'on' => 'rate_master'],
                [['from_snf', 'to_snf'], 'snfRangeValidate', 'on' => 'rate_master'],
                [['from_fat', 'to_fat', 'from_snf', 'to_snf', 'fat_price', 'snf_price'], 'number', 'min' => 0, 'on' => 'rate_master'],
            //[['hamlet_code'], 'validateHamlet', 'on' => 'vlcc_master']
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['parent_code', 'master_code', 'master_type', 'master_name', 'parent_code_other', 'date_1', 'bank_account_no', 'ifsc', 'is_active'], 'required', 'on' => ['dcs_create']],
                [['parent_code', 'parent_code_other', 'master_code', 'master_name', 'master_type', 'type_2', 'capacity', 'x_col1', 'route_length', 'time_1', 'time_2', 'time_3', 'time_4', 'contact_first_name', 'is_active'], 'required', 'on' => ['route_create']],
//            [['time_1', 'time_2', 'time_3', 'time_4'], 'date', 'format' => 'php:H:i', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 12:30'), 'on' => ['route_create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_code_other' => Yii::t('app', 'Param 1'),
            'parent_code' => Yii::t('app', 'Param 2'),
            'master_code' => Yii::t('app', 'Param 3'),
            'master_name' => Yii::t('app', 'Param 4'),
            'master_type' => Yii::t('app', 'Param 5'),
            'date_1' => Yii::t('app', 'Param 6'),
            'date_2' => Yii::t('app', 'Param 7'),
            'time_1' => Yii::t('app', 'Param 8'),
            'time_2' => Yii::t('app', 'Param 9'),
            'time_3' => Yii::t('app', 'Param 10'),
            'time_4' => Yii::t('app', 'Param 11'),
            'state_code' => Yii::t('app', 'Param 12'),
            'district_code' => Yii::t('app', 'Param 13'),
            'sub_district_code' => Yii::t('app', 'Param 14'),
            'village_code' => Yii::t('app', 'Param 15'),
            'hamlet_code' => Yii::t('app', 'Param 16'),
            'contact_first_name' => Yii::t('app', 'Param 17'),
            'contact_middle_name' => Yii::t('app', 'Param 18'),
            'contact_last_name' => Yii::t('app', 'Param 19'),
            'capacity' => Yii::t('app', 'Param 20'),
            'address' => Yii::t('app', 'Param 21'),
            'address_2' => Yii::t('app', 'Param 22'),
            'email' => Yii::t('app', 'Param 23'),
            'mobile_no' => Yii::t('app', 'Param 24'),
            'is_active' => Yii::t('app', 'Param 25'),
            'type_2' => Yii::t('app', 'Param 26'),
            'bank_name' => Yii::t('app', 'Param 27'),
            'branch_name' => Yii::t('app', 'Param 28'),
            'bank_account_no' => Yii::t('app', 'Param 29'),
            'ifsc' => Yii::t('app', 'Param 30'),
            'route_length' => Yii::t('app', 'Param 31'),
            'type_of_data' => Yii::t('app', 'Param 32'),
            'union_code' => Yii::t('app', 'Param 33'),
            'service_type' => Yii::t('app', 'Param 34'),
            'username' => Yii::t('app', 'Param 35'),
            'password' => Yii::t('app', 'Param 36'),
        ];
    }

    public function validateRouteCode($attribute, $params) {
        $model = new TblRouteMapping();
        $model->route_code = $this->union_code . $this->parent_code;
        $modelData = $model->getData();
        if (empty($modelData)) {
            $this->addError($attribute, Yii::t('app', 'Please enter valid Parent Code.'));
        }
    }

    public function validateVlccCode($attribute, $params) {
        $model = new TblDcs();
        $model->dcs_code = $this->union_code . $this->master_code;
        $modelData = $model->getData();
        if (empty($modelData)) {
            $this->addError($attribute, Yii::t('app', 'Please enter valid Master Code.'));
        }
    }

    public function validateMccCode($attribute, $params) {
        $model = new TblMccPlant();
        $model->mcc_plant_code = $this->union_code . $this->parent_code;
        $modelData = $model->getData();
        if (empty($modelData)) {
//            $this->addError($attribute, Yii::t('app', 'Please enter valid Parent Code.'));
        }
    }

    public function dateRangeValidate($attribute, $params) {
        if (empty($this->getErrors())) {
            $start_date = date('Y-m-d', strtotime($this->date_1));
            $end_date = date('Y-m-d', strtotime($this->date_2));
            if ($start_date > $end_date) {
                $this->addError($attribute, Yii::t('app', 'End date can not be less than Start Date.'));
            }
        }
    }

    public function fatRangeValidate($attribute, $params) {
        if (empty($this->getErrors())) {
            if ((float) $this->from_fat > (float) $this->to_fat) {
                $this->addError($attribute, Yii::t('app', 'End range can not be less than Start range.'));
            }
        }
    }

    public function snfRangeValidate($attribute, $params) {
        if (empty($this->getErrors())) {

            if ((float) $this->from_snf > (float) $this->to_snf) {
                $this->addError($attribute, Yii::t('app', 'End range can not be less than Start range.'));
            }
        }
    }

    public function validateHamlet($attribute, $params) {
        if (!empty($this->hamlet_code) && !preg_match('/^[0-9]*$/', $this->hamlet_code)) {
            $mccCode = $this->union_code . $this->parent_code;
            $mccModel = new TblMccPlant();
            $mccModel->mcc_plant_code = $mccCode;
            $mccData = $mccModel->getData();
            $this->hamlet_code = !empty($mccData->hamlet_code) ? $mccData->hamlet_code : '';
        }
    }

    public function trimInputData($attribute, $params) {
        $this->$attribute = !empty($this->$attribute) ? trim($this->$attribute) : $this->$attribute;
    }

//    public function convertDateDot() {
//        try {
//            $this->date_validate = Yii::$app->controls->view_date($this->date_validate, 'php:d.m.Y');
//        } catch (\Exception $e) {
//            $this->date_validate = '-';
//        }
//    }
}
