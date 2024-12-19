<?php

namespace app\modules\organisation\models;

use app\models\ChildModel;
// use app\models\User;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblOrganizationLatLongApplicability;
use Yii;

/**
 * This is the model class for table "tbl_organization_latlong".
 *
 * @property integer $organization_latlong_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $lat_long
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblOrganizationLatlong extends ChildModel {

    public $plant_code, $mcc_plant_code, $bmc_code, $dcs_code, $user_code, $name, $customer_code_other;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_organization_latlong';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'user_code', 'name'], 'safe'],
            [['union_code', 'customer_type', 'customer_code', 'address', 'lat_long', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code', 'originating_type', 'customer_code_other'], 'safe'],
            [['is_active'], 'default', 'value' => 1],
            [['union_code'], 'required', 'on' => 'create'],
            [['customer_type', 'lat_long', 'customer_code'], 'required'],
            [['customer_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'organization_latlong_type');
                }],
            [['plant_code'], 'required', 'on' => ['create'], 'when' => function($model) {
                    return $model->customer_type == 'PLANT';
                }, 'whenClient' => "function (attribute, value) { 
                return $('#tblorganizationlatlong-customer_type').val() == 'PLANT'; 
            }"],
            [['plant_code', 'mcc_plant_code'], 'required', 'on' => ['create'], 'when' => function($model) {
                    return $model->customer_type == 'MCC';
                }, 'whenClient' => "function (attribute, value) { 
                return $('#tblorganizationlatlong-customer_type').val() == 'MCC'; 
            }"],
            [['plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'on' => ['create'], 'when' => function($model) {
                    return $model->customer_type == 'BMC';
                }, 'whenClient' => "function (attribute, value) { 
                return $('#tblorganizationlatlong-customer_type').val() == 'BMC'; 
            }"],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'required', 'on' => ['create'], 'when' => function($model) {
                    return $model->customer_type == 'DCS';
                }, 'whenClient' => "function (attribute, value) { 
                return $('#tblorganizationlatlong-customer_type').val() == 'DCS'; 
            }"],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code_other'], 'required', 'on' => ['create'], 'when' => function($model) {
                    return $model->customer_type == 'BULKVEN';
                }, 'whenClient' => "function (attribute, value) { 
                return $('#tblorganizationlatlong-customer_type').val() == 'BULKVEN'; 
            }"],
            [['user_code'], 'required', 'on' => ['create'], 'when' => function($model) {
                    return in_array($model->customer_type, ['HOME', 'OFFICE', 'OTHER']);
                }, 'whenClient' => "function (attribute, value) { 
                const customerName = $('#tblorganizationlatlong-customer_type').val();
                return customerName === 'HOME' || customerName === 'OFFICE' || customerName === 'OTHER';
            }"],
            [['bmc_code'], function ($attribute, $params) {
                    if ($this->customer_type == 'BULKVEN') {
                        return Yii::$app->general->validateBMC($this, $attribute);
                    }
                    return true;
                }, 'on' => ['importCsv']],
            [['customer_code'], 'setImport', 'on' => ['importCsv']],
            ['customer_code', 'unique', 'targetAttribute' => ['customer_type', 'customer_code'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Combination of Customer Type and Code has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'organization_latlong_code' => Yii::t('app', 'Organization Latlong Code'),
            'union_code' => Yii::t('app', 'Union'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'customer_code_other' => Yii::t('app', 'Customer'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
        ];
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $customerName = strtolower($this->customer_type);
            if (in_array($customerName, ['other', 'home', 'office'])) {
                $customerName = 'user';
            }
            $new_code = Yii::$app->general->fetchData($this, $customerName, $this->customer_code);
            if (empty($new_code)) {
                $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'customer') . ' code is invalid'));
                return false;
            }
            $this->customer_code = $new_code;
            return true;
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'customer_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'customer_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'customer_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'customer_code']);
    }

    public function getUserOrgLatLong() {
        $userData = $this->find()->alias('A')
                ->innerJoin('tbl_user_organization_mapping B', 'A.customer_type = B.organization_type AND A.customer_code = B.organization_code')
                ->where(['B.user_id' => $this->user_code])
                ->where(['A.is_active' => 1])
                ->all();

        $othersData = $this->find()
                ->where(['in', 'customer_type', ['HOME', 'OFFICE', 'OTHER']])
                ->where(['customer_code' => $this->user_code, 'is_active' => 1])
                ->all();
        $userData = array_merge($userData, $othersData);
        $values = TblOrganizationLatLongApplicability::find()->select(['applicable_code', 'applicable_for'])->where(['user_code' => $this->user_code])->asArray()->all();
        $selected = [];

        //var_dump($results);exit;
        if (!empty($userData)) {
            foreach ($userData as $key => $row) {
                if (in_array($row->customer_code, array_column($values, 'applicable_code'), true) !== FALSE && in_array($row->customer_type, array_column($values, 'applicable_for'), true) !== FALSE) {
                    // $selected[] = $row->customer_code . '-'. $row->organization_latlong_code .'-' . $row->customer_type;
                    unset($userData[$key]);
                }
            }
        }
        $userData = array_values($userData);

        return ['userDataOrg' => $userData, 'selected' => $selected];
    }

}
