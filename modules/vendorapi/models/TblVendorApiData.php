<?php

namespace app\modules\vendorapi\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;

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
            [['parent_code_other', 'parent_code', 'master_code', 'master_name', 'master_type', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'contact_first_name', 'contact_middle_name', 'contact_last_name', 'address', 'address_2', 'email', 'mobile_no', 'bank_name', 'branch_name', 'bank_account_no', 'ifsc', 'type_of_data', 'union_code', 'service_type', 'username', 'password'], 'string'],
            [['date_1', 'date_2', 'time_1', 'time_2', 'time_3', 'time_4', 'type_2'], 'safe'],
            [['capacity', 'route_length'], 'number'],
            [['is_active'], 'integer'],
            [['master_type'], 'default', 'value' => 'Can', 'on' => 'route_master'],
            [['is_active'], 'default', 'value' => 1],
            [['master_code', 'master_name', 'date_1', 'time_1', 'time_2', 'time_3', 'time_4'], 'required', 'on' => 'route_master'],
            [['parent_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['parent_code' => 'mcc_plant_code'], 'on' => 'route_master'],
//            [['date_validate'], 'convertDateDot'],
//            [['date_validate'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 01.12.2018')],
            [['time_1', 'time_2', 'time_3', 'time_4'], 'date', 'format' => 'php:H:i', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 12:30')],
            [['type_2'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalData($this, $attribute, 'vehicle_type_code');
        }, 'on' => 'route_master'],
            [['master_code'], 'string', 'max' => 8, 'on' => 'route_master'],
            [['parent_code'], 'string', 'max' => 4, 'on' => 'route_master'],
            [['mobile_no'], 'number', 'on' => 'route_master'],
            [['mobile_no'], 'string', 'max' => 12, 'on' => 'route_master'],
            [['master_name', 'master_type', 'contact_first_name', 'contact_middle_name', 'contact_last_name', 'email'], 'string', 'max' => 255, 'on' => 'route_master'],
            [['parent_code', 'master_code', 'master_name', 'date_1', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => 'mcc_master'],
            [['parent_code', 'parent_code_other', 'master_code', 'master_name', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'bank_account_no', 'ifsc'], 'required', 'on' => 'dcs_master'],
            [['parent_code', 'master_code', 'date_1', 'date_2'], 'required', 'on' => 'route_dcs'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_code_other' => Yii::t('app', 'Parent Code Other'),
            'parent_code' => Yii::t('app', 'Parent Code'),
            'master_code' => Yii::t('app', 'Master Code'),
            'master_name' => Yii::t('app', 'Master Name'),
            'master_type' => Yii::t('app', 'Master Type'),
            'date_1' => Yii::t('app', 'Date 1'),
            'date_2' => Yii::t('app', 'Date 2'),
            'time_1' => Yii::t('app', 'Time 1'),
            'time_2' => Yii::t('app', 'Time 2'),
            'time_3' => Yii::t('app', 'Time 3'),
            'time_4' => Yii::t('app', 'Time 4'),
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'village_code' => Yii::t('app', 'Village Code'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'contact_first_name' => Yii::t('app', 'Contact First Name'),
            'contact_middle_name' => Yii::t('app', 'Contact Middle Name'),
            'contact_last_name' => Yii::t('app', 'Contact Last Name'),
            'capacity' => Yii::t('app', 'Capacity'),
            'address' => Yii::t('app', 'Address'),
            'address_2' => Yii::t('app', 'Address 2'),
            'email' => Yii::t('app', 'Email'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'is_active' => Yii::t('app', 'Is Active'),
            'type_2' => Yii::t('app', 'Type 2'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'route_length' => Yii::t('app', 'Route Length'),
            'type_of_data' => Yii::t('app', 'Type Of Data'),
            'union_code' => Yii::t('app', 'Union Code'),
            'service_type' => Yii::t('app', 'Service Type'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

//    public function convertDateDot() {
//        try {
//            $this->date_validate = Yii::$app->controls->view_date($this->date_validate, 'php:d.m.Y');
//        } catch (\Exception $e) {
//            $this->date_validate = '-';
//        }
//    }
}
