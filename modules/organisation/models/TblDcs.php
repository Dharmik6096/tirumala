<?php

namespace app\modules\organisation\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\geo\models\TblStates;
use app\modules\organisation\models\TblRoutes;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblUnions;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\globalmaster\models\TblDcsTypes;
use app\modules\general\models\TblOrganisationType;
use app\modules\general\models\TblSchemeType;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcsMilkType;
use app\modules\geo\models\TblBlocks;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\general\models\TblSocietyVendor;
use app\models\ChildModel;
use yii\db\Query;
use app\modules\organisation\models\TblDpuInstallation;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\organisation\models\TblSocietyCollection;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblRouteMapping;

/**
 * This is the model class for table "tbl_dcs".
 *
 * @property string $dcs_code
 * @property string $address
 * @property string $local_address
 * @property integer $allow_multi_family_member
 * @property string $bank_account_no
 * @property string $contact_person
 * @property string $local_contact_person
 * @property string $created_at
 * @property string $dcs_code_ex
 * @property string $dcs_name
 * @property string $local_name
 * @property string $dcs_short_name
 * @property string $local_short_name
 * @property string $destination_code
 * @property integer $destination_type
 * @property string $effective_date
 * @property string $email
 * @property string $ifsc
 * @property integer $is_active
 * @property integer $is_bmc
 * @property string $mobile_no
 * @property string $pan_no
 * @property string $phone_no
 * @property string $pincode
 * @property string $registration_code
 * @property string $registration_date
 * @property string $service_tax
 * @property string $tin_no
 * @property string $updated_at
 * @property string $bank_code
 * @property string $branch_code
 * @property string $created_by
 * @property integer $dcs_type_code
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $route_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $updated_by
 * @property string $village_code
 * @property string $upi_no
 * @property string $secretory_info
 * @property string $gst_no
 * @property string $fssi
 */
class TblDcs extends ChildModel {

    public $villages;
    public $federation_code;
    public $milk_type_code;
    public $street1;
    public $street2;
    public $bipl_code;
    public $vendor;
    public $society_status;
    public $download_status;
    public $tmcc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'dcs_short_name', 'dcs_type_code', 'hamlet_code', 'dcs_name', 'dcs_code_ex', 'pincode', 'bmc_code'], 'required', 'except' => ['deactivate']],
            [['dcs_code', 'milk_type_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'is_bmc', 'destination_type', 'valid_from', 'vendor', 'tmcc_code'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping']],
            [['union_code'], 'required', 'message' => Yii::t('app/validation', 'Union cannot be blank')],
            [['state_code'], 'required', 'message' => Yii::t('app/validation', 'State cannot be blank'), 'except' => 'importCsv'],
            [['district_code'], 'required', 'message' => Yii::t('app/validation', 'District cannot be blank'), 'except' => 'importCsv'],
            [['sub_district_code'], 'required', 'message' => Yii::t('app/validation', 'Sub District cannot be blank'), 'except' => 'importCsv'],
            [['village_code'], 'required', 'message' => Yii::t('app/validation', 'Village cannot be blank'), 'except' => 'importCsv'],
            //[['hamlet_code'], 'required', 'message' => Yii::t('app/validation', 'Hamlet cannot be blank')],
            [['dcs_code', 'dcs_short_name', 'gst_no'], 'unique'],
            [['allow_multi_family_member', /* 'destination_type', */ 'dcs_type_code'], 'integer'],
            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
            //  [['tin_no'], 'string', 'max' => 11, 'min' => 11],
            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')],
            [['is_active', 'created_at', 'milk_type_code', 'destination_code', 'destination_type', 'effective_date', 'registration_date', 'updated_at', 'villages', 'branch_code', 'route_code', 'federation_code', 'upi_no', 'hamlet_code', 'secretory_info', 'gst_no', 'fssi', 'organisation_type_code', 'scheme_type_code', 'is_registered', 'street1', 'street2', 'valid_from', 'bipl_code', 'vendor', 'data_post_status', 'bmc_code', 'mcc_plant_code', 'plant_code'], 'safe'],
            //[['destination_code'],'bmcValidate','skipOnEmpty'=> false],
//            [['effective_date', 'valid_from'],'validateDate'],
            [['address', 'dcs_name'], 'string', 'max' => 500],
            [['registration_code'], 'string', 'max' => 20],
            [['contact_person', 'dcs_short_name'], 'string', 'max' => 100],
            [['dcs_code_ex'], 'string', 'max' => 3, 'min' => '3'],
            [['gst_no'], 'string', 'max' => 15],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['ifsc', 'pan_no'], 'trim'],
            //[['ifsc'], 'string', 'max' => 11, 'min' => 11, 'message' => Yii::t('app/validation', 'Please enter a valid IFSC Length')],
            [['mobile_no'], function ($attribute, $params) {
            Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['gst_no'], function ($attribute, $params) {
            $this->validateGstNo($attribute, $params);
        }, 'skipOnEmpty' => false],
            [['phone_no'], function ($attribute, $params) {
            Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
//            [['service_tax'], function ($attribute, $params) {
//                    Yii::$app->general->vaildateServiceTax($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
//            ['bank_account_no', 'unique', 'targetAttribute' => 'ifsc'],
            ['bank_account_no', 'unique', 'when' => function($model) {
                    $data = $this->find()->where(['ifsc' => $model->ifsc])->andWhere(['<>', 'dcs_code', $model->dcs_code])->one();
                    return ($data) ? true : false;
                }/* , 'targetAttribute' => 'bank_code' */],
                    [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                    [['dcs_name', 'contact_person'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                    [['local_name', 'local_short_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                    [['registration_code'], function ($attribute, $params) {
                    Yii::$app->general->vaildateNumericField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                    [['registration_code', 'registration_date'], 'required', 'when' => function ($model) {
                    return $model->is_registered == 1;
                },
                        'whenClient' => "function (attribute, value) { return $('#tbldcs-is_registered').is(':checked') }"],
                    [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code']],
                    [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code']],
                    [['plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['plant_code' => 'plant_code']],
//            [['branch_code','bank_account_no','ifsc'], function ($attribute, $params) {
//                    Yii::$app->general->validateBankDetail($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
                    [['tmcc_code'], 'string', 'max' => 10],
                    [['tmcc_code'], 'number', 'min' => 1],
                    ['dcs_code_ex', 'unique', 'targetAttribute' => ['dcs_code_ex', 'bmc_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')]
                ];
            }

            public function validateGstNo($attribute, $params) {

                if (!empty($this->gst_no))
                    if (strlen($this->gst_no) != 15) {
                        $this->addError($attribute, Yii::t('app/validation', 'Gst no must contain 15 characters'));
                    }
                return false;
            }

            public function bmcValidate($attribute, $params) {
                if ($this->is_bmc == 0 || $this->is_bmc == 1 || $this->is_bmc == 2) {
                    if (empty($this->destination_code)) {
                        $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' can not be blank.'));
                        return false;
                    }
                }
            }

            public function validateDate($attribute, $params) {

                if (!empty($this->effective_date) && !empty($this->valid_from)) {

                    if ($this->valid_from < $this->effective_date) {
                        $this->addError($attribute, Yii::t('app/validation', 'Valid From Must be Greater Than Start Date.'));
                        return false;
                    }
                }
            }

            /**
             * @inheritdoc
             */
            public function attributeLabels() {
                return [
                    'dcs_code' => Yii::t('app', 'Society Code'),
                    'villages' => Yii::t('app', 'Applicable Villages'),
//            'address' => Yii::t('app', 'Address'),
                    'street1' => Yii::t('app', 'Address Street1'),
                    'street2' => Yii::t('app', 'Address Street2'),
                    'local_address' => Yii::t('app', 'Hindi Address'),
                    'allow_multi_family_member' => Yii::t('app', 'Allow Multi Family Member'),
                    'bank_account_no' => Yii::t('app', 'Bank Account No'),
                    'contact_person' => Yii::t('app', 'Contact Person'),
                    'local_contact_person' => Yii::t('app', 'Contact Person Hindi Name'),
                    'created_at' => Yii::t('app', 'Created At'),
                    'dcs_code_ex' => Yii::t('app', 'Society Code Ex'),
                    'dcs_name' => Yii::t('app', 'Society Name'),
                    'local_name' => Yii::t('app', 'Hindi Name'),
                    'dcs_short_name' => Yii::t('app', 'Society Short Name'),
                    'local_short_name' => Yii::t('app', 'Hindi Short Name'),
                    'destination_code' => Yii::t('app', 'Destination'),
                    'destination_type' => Yii::t('app', 'Destination Type'),
                    'effective_date' => Yii::t('app', 'Start Date'),
                    'email' => Yii::t('app', 'Email'),
                    'ifsc' => Yii::t('app', 'IFSC'),
                    'is_active' => Yii::t('app', 'Is Active'),
                    'is_bmc' => Yii::t('app', 'Society Pouring Type'),
                    'mobile_no' => Yii::t('app', 'Mobile No'),
                    'pan_no' => Yii::t('app', 'PAN No'),
                    'phone_no' => Yii::t('app', 'Phone No'),
                    'pincode' => Yii::t('app', 'Pincode'),
                    'registration_code' => Yii::t('app', 'Registration Code'),
                    'registration_date' => Yii::t('app', 'Registration Date'),
                    'service_tax' => Yii::t('app', 'Service Tax'),
                    'tin_no' => Yii::t('app', 'Tin No'),
                    'updated_at' => Yii::t('app', 'Updated At'),
                    'bank_code' => Yii::t('app', 'Bank'),
                    'branch_code' => Yii::t('app', 'Branch'),
                    'created_by' => Yii::t('app', 'Created By'),
                    'dcs_type_code' => Yii::t('app', 'Society Type'),
                    'district_code' => Yii::t('app', 'District'),
                    'hamlet_code' => Yii::t('app', 'Hamlet'),
                    'milk_type_code' => Yii::t('app', 'Milk Type'),
                    'route_code' => Yii::t('app', 'Route'),
                    'state_code' => Yii::t('app', 'State'),
                    'sub_district_code' => Yii::t('app', 'Sub District'),
                    'union_code' => Yii::t('app', 'Union'),
                    'updated_by' => Yii::t('app', 'Updated By'),
                    'village_code' => Yii::t('app', 'Village'),
                    'block_code' => Yii::t('app', 'Block'),
                    'upi_no' => Yii::t('app', 'UPI No'),
                    'secretory_info' => Yii::t('app', 'Secretary Info'),
                    'gst_no' => Yii::t('app', 'GST No'),
                    'fssi' => Yii::t('app', 'FSSAI'),
                    'valid_from' => Yii::t('app', 'Valid From'),
                    'bipl_code' => Yii::t('app', 'BIPL Code'),
                    'society_status' => Yii::t('app', 'Collection Status'),
                    'bmc_code' => Yii::t('app', 'BMC'),
                    'tmcc_code' => Yii::t('app', 'DCS Code'),
                    'download_status' => Yii::t('app', 'Member Download Status'),
                ];
            }

            /**
             * @inheritdoc
             * @return TblDcsQuery the active query used by this AR class.
             */
            public static function find() {
                return new TblDcsQuery(get_called_class());
            }

            public function getCode() {
                return '00' . $this->tmcc_code;
//                return $this->district_code . $this->village_code . $this->dcs_code_ex;
//                $data = $this->find()->select(["max(convert(int,substring(dcs_code,12,1))) as dcs_code"])->where(['state_code' => $this->state_code, 'district_code' => $this->district_code, 'village_code' => $this->village_code])->one();
//                if ((int) $data['dcs_code'] < 9) {
//                    return $this->state_code . $this->district_code . $this->village_code . ((int) $data['dcs_code'] + 1);
//                } else {
//                    return $this->state_code . $this->district_code . $this->village_code . ((int) $data['dcs_code']);
//                }
            }

            public function getActiveDcs() {
//echo $fedrCode;
                $value = $this->find()->where(['is_active' => 1])->all();
                return ArrayHelper::map($value, 'dcs_code', 'dcs_name');
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getStateCode() {
                return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
            }

            public function getBankDetails() {
                return $this->hasMany(TblBankDetails::className(), ['module_code' => 'dcs_code'])->where(['tbl_bank_details.module_name' => 'society']);
            }

            public function getDefaultBankDetail() {
                return $this->hasOne(TblBankDetails::className(), ['module_code' => 'dcs_code'])->where(['tbl_bank_details.module_name' => 'society', 'tbl_bank_details.is_default' => 1]);
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getBankCode() {
                return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code'])->via('defaultBankDetail');
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getBranchCode() {
                return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code'])->via('defaultBankDetail');
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getDistrictCode() {
                return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getHamletCode() {
                return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getVillageCode() {
                return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
            }

            public function getBlockCode() {
                return $this->hasOne(TblBlocks::className(), ['block_code' => 'block_code']);
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
            public function getRouteCode() {
                return $this->hasOne(TblRoutes::className(), ['route_code' => 'route_code']);
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getSubDistrictCode() {
                return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getMilkQualityTypeCode() {
                return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
            }

            /**
             * @return \yii\db\ActiveQuery
             */
            public function getDcsTypeCode() {
                return $this->hasOne(TblDcsTypes::className(), ['dcs_type_code' => 'dcs_type_code']);
            }

            public function getOrganisationTypeCode() {
                return $this->hasOne(TblOrganisationType::className(), ['organisation_type_code' => 'organisation_type_code']);
            }

            public function getSchemeTypeCode() {
                return $this->hasOne(TblSchemeType::className(), ['scheme_type_code' => 'scheme_type_code']);
            }

            public function getTblDcsVillageMapping() {
                return $this->hasMany(TblDcsVillageMapping::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 1]);
            }

            public function getTblDcsMilkType() {
                return $this->hasMany(TblDcsMilkType::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 1]);
            }

            public function getTblDcsBmc() {
                return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'destination_code'])->andwhere(['is_active' => 1]);
            }

            public function getSocietyCodes() {
                return $this->hasOne(TblSocietyCodes::className(), ['dcs_code' => 'dcs_code']);
            }

            public function getSocietyVendors() {
                return $this->hasOne(TblSocietyVendor::className(), ['dcs_code' => 'dcs_code']);
            }

            public function getDcsForBmc($union) {
                $value = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_bmc' => '2', 'union_code' => $union])->all();
                $value = ArrayHelper::map($value, 'dcs_code', 'dcs_name');
                return $value;
            }

            public function getVillage() {
                $village = new TblVillages();
                $village_list = $village->getDcsVillageList($this->union_code);
                $values = TblDcsVillageMapping::find()->select('village_code')->where(['dcs_code' => $this->dcs_code])->asArray()->all();
                $selected = [];
                foreach ($village_list as $key => $row) {
                    if (array_search($key, array_column($values, 'village_code')) !== FALSE) {
//$selected[$key] = ['selected' => 'selected'];
                        $selected[$key] = $row;
                    }
                }
                return ['value' => $selected, 'selected' => ''];
//return ['value' => $village_list, 'selected' => $selected];
            }

            public function getVillageList() {
                $out = '';
                foreach ($this->tblDcsVillageMapping as $row) {
                    $out .= $row->villageCode->village_name . '<br>';
                }
                return $out;
            }

            public function isBmcValue() {

                switch ($this->is_bmc) {
                    case 0;
                        return 'No BMC';
                        break;
                    case 1;
                        return 'Non Cluster BMC';
                        break;
                    case 2;
                        return 'Cluster BMC';
                        break;
                    case 3;
                        return 'Pours to BMC';
                        break;
                }
            }

            public function destinationTypeValue() {

                if ($this->is_bmc == 3) {
                    $dcs = $this->getDcsName($this->destination_code);
                    $value = isset($dcs) ? $dcs->dcs_name : '';
                } else {
                    $chilling = new TblMccPlant();
                    $value = $chilling->getChillingCenterValue($this->destination_code);
                    $value = isset($value) ? $value->name : '';
                }

                return $value;
            }

            public function getDcsName($value) {
                return $this->find()->select(['dcs_name', 'union_code'])->where(['dcs_code' => $value])->one();
            }

            public function getDcsList($unionCode, $dcsCode = '') {
                if ($unionCode === '')
                    $unionCode = 0;
                $value = $this->getDcs($unionCode, $dcsCode);
                $value = ArrayHelper::map($value, 'dcs_code', 'dcs_name');
                return $value;
            }

            public function getDcs($unionCode, $dcsCode = '') {
                if (!empty($dcsCode)) {
                    $unionQuery = $this->find()->select(['dcs_code', 'dcs_name'])->where(['dcs_code' => $dcsCode]);

                    $query = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1])->union($unionQuery);
                } else {
                    $query = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1]);
                }

                if ($unionCode !== '')
                    $query->andWhere(['union_code' => explode(',', $unionCode)]);
                if (Yii::$app->session->get('Dcs') !== '') {
                    $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
                }
                return $query->all();
            }

            public function getRouteDcs($route) {

                return $routeSocieties = TblSocietyCodes::find()->select(['tbl_dcs.dcs_code', 'tbl_dcs.dcs_name'])->joinWith('dcsCode')->where(['tbl_society_codes.route_code' => $route, 'tbl_dcs.is_active' => 1])->asArray()->all();


//return $routeSocieties = TblSocietyCodes::find()->select(['dcs_code','dcs_name'])->joinWith('dcsCode')->where(['tbl_society_codes.route_code' => $route,'tbl_dcs.is_active'=>1])->indexBy('code')->all();
///$routeSocieties = TblSocietyCodes::find()->select('dcs_code,dcs_name')->where(['route_code'=>$route,'is_active'=>1])->all();
            }

            public function getMilkTypes() {

                $milkType = new TblAnimalType();
                $data = $milkType->getAnimalMilkTypeArray();

                $values = TblDcsMilkType::find()->where(['dcs_code' => $this->dcs_code, 'is_active' => 1])->asArray()->all();
                $selected = [];

                foreach ($data as $key => $row) {
                    if (array_search($key, array_column($values, 'milk_type_code')) !== FALSE) {
                        $selected[$key] = ['selected' => 'selected'];
                    }
                }
                return ['value' => $data, 'selected' => $selected];
            }

            public function milkType() {
                $out = '';
                foreach ($this->tblDcsMilkType as $row) {
                    $out .= $row->milkTypeCode->animal_type_name . '<br>';
                }
                return $out;
            }

            public function getCheckDcsExist() {

                $unions = $this->find()->select('d.district_code')->where(['tbl_dcs.union_code' => $this->union_code, 'tbl_dcs.is_active' => 1])
                                ->join('INNER JOIN', 'tbl_dcs_village dv', 'dv.dcs_code=tbl_dcs.dcs_code')
                                ->join('INNER JOIN', 'tbl_villages dd', 'dd.village_code=dv.village_code')
                                ->join('INNER JOIN', 'tbl_sub_districts sd', 'dd.sub_district_code=sd.sub_district_code')
                                ->join('INNER JOIN', 'tbl_districts d', 'd.district_code=sd.district_code')
                                ->groupBy('d.district_code')->asArray()->all();

                $states = array_column($unions, 'district_code');
                return $states;
            }

            public function loadDcs($union_code = '', $q = '') {
                $records = (new Query())
                                ->select('dcs_code as id, dcs_name as text')
                                ->from('tbl_dcs')
                                ->where(['union_code' => $union_code])
                                ->andWhere(['like', 'dcs_name', $q])->createCommand()->rawSql;
                echo $records;
                exit;
                return $records;
            }

            public function fullAddress() {
                return $this->street1 . ', ' . $this->street2;
            }

            public function getInstallationId() {
                $query = TblDpuInstallation::find()->select(['inst_code'])->where(['dcs_code' => $this->dcs_code])->one();
                if (!empty($query)) {
                    return $query->inst_code;
                }
                return false;
            }

            public function getRouteDcsList($routeCode, $dcsCode = '') {


                if ($routeCode === '')
                    $routeCode = 0;
                $value = $this->getRouteDcs($routeCode);
                $value = ArrayHelper::map($value, 'dcs_code', 'dcs_name');
                asort($value, SORT_NATURAL | SORT_FLAG_CASE);
                return $value;
            }

            public function getDefaultContactDetail() {
                return $this->hasOne(TblContactDetails::className(), ['module_code' => 'dcs_code'])->where(['tbl_contact_details.module_name' => 'society', 'tbl_contact_details.is_default' => 1]);
            }

            public function getSocietyStatus() {
                return $this->hasOne(TblSocietyCollection::className(), ['dcs_code' => 'dcs_code'])->orderBy('collection_id desc');
            }

            public function validDcs($dcs) {
                return $this->find()->where(['dcs_code' => $dcs, 'is_active' => 1])->one();
            }

            public function getNewDcs() {
                return $this->find()
                                ->joinWith(['societyVendors'])
                                ->where(['or', ['data_post_status' => [0, 3]], ['data_post_status' => NULL]])
                                ->andWhere(['tbl_society_vendor.vendor_code' => 'STELLAPPS'])
                                ->limit(200)
                                ->orderby('created_at ASC')
                                ->all();
            }

            public function updateDcs($value) {
                return $this->updateAll(['data_post_status' => 1], ['dcs_code' => $value]);
            }

            public function rlsBmcDcs($parents = '') {
                $rows = $this->find()->where(['bmc_code' => $parents])->all();
                $bmc = [];
                foreach ($rows as $value) {
                    $bmc[] = array('id' => $value->dcs_code, 'name' => $value->dcs_name);
                }
                return $bmc;
            }

            public function getBmcCode() {
                return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
            }

            public function getMccPlantCode() {
                return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
            }

            public function getPlantCode() {
                return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
            }

            public function getBMCDCSList($plantCode, $RLS = 'TRUE') {
                $value = $this->getBMCDCS($plantCode, $RLS);
                $value = ArrayHelper::map($value, 'dcs_code', 'dcs_name');
                return $value;
            }

            public function getBMCDCS($plantCode = [], $RLS = 'TRUE') {
                $query = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1]);
                if (!empty($plantCode))
                    $query->andWhere(['bmc_code' => $plantCode]);
                if (Yii::$app->session->get('Dcs') !== '' && $RLS == 'TRUE') {
                    $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
                }
                return $query->all();
            }

            public function getTblPurchaseRateApplicabilityUnblock() {
                return $this->hasMany(TblPurchaseRateApplicability::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 1]);
            }

            public function getTblPurchaseRateApplicabilityBlock() {
                return $this->hasMany(TblPurchaseRateApplicability::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 0]);
            }

            public function getBmcDcsData() {
                $rows = $this->find()->where(['bmc_code' => $this->bmc_code])->all();
                $dcs = [];
                foreach ($rows as $key => $value) {
                    $dcs[] = $value->dcs_code;
                }
                return $dcs;
            }

            public function getDownloadStatus() {
                $member_model = new TblMember();
                $data = $member_model->find()
                        ->where(['dcs_code' => $this->dcs_code, 'is_download' => '1'])
                        ->all();
                if (count($data) == 0) {
                    return Yii::t('app', 'Downloaded');
                } else {
                    return Yii::t('app', 'Pending');
                }
            }

            public function getSocietyData() {
                return $this->find()
                                ->select(['tbl_dcs.dcs_code', 'tbl_dcs.dcs_name', 'tbl_contact_details.mobile_no'])
                                ->joinWith(['defaultContactDetail'])
                                ->where(['bmc_code' => $this->bmc_code, 'tbl_dcs.is_active' => 1])
                                ->all();
            }

            public function getRouteMapping() {
                return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
            }

            public function getMccDCS($mccCode = [], $RLS = 'TRUE', $values = []) {
                $query = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1]);
                if (!empty($mccCode))
                    $query->andWhere(['mcc_plant_code' => $mccCode]);
                if (Yii::$app->session->get('Dcs') !== '' && $RLS == 'TRUE') {
                    $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
                }
                if (!empty($values)) {
                    $query->andWhere(['not in', 'dcs_code', $values]);
                }
                return $query->all();
            }

        }
        