<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblCapacity;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblUnions;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\general\models\TblBmcType;
use yii\helpers\ArrayHelper;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblBmcMilkType;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\organisation\models\TblChannelMaster;
use yii\base\UserException;
use app\modules\details\models\TblContactDetails;

/**
 * This is the model class for table "tbl_dcs_bmc".
 *
 * @property string $bmc_code
 * @property string $bmc_type_code
 * @property string $mcc_plant_code
 * @property string $bmc_name
 * @property string $created_at
 * @property integer $is_active
 * @property string $model
 * @property string $updated_at
 * @property string $bmc_milk_type
 * @property integer $capacity
 * @property string $created_by
 * @property string $subcenter_code
 * @property string $union_code
 * @property integer $manufacturer_code
 * @property string $updated_by
 * @property integer $is_mcc
 * @property TblAnimalType $bmcMilkType
 * @property TblCapacity $capacity0
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblManufacturer $manufacturerCode
 * @property User $updatedBy
 */
class TblDcsBmc extends \app\models\ChildModel {

    public $is_sentbox, $milk_type_code;
    public $toEncrypt = ['password', 'pan_no'];
    public $channel_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['is_weight_manual', 'is_quality_manual'], 'default', 'value' => FALSE],
                [['bmc_name', 'union_code', 'mcc_plant_code'], 'required'],
                [['model', 'capacity', 'manufacturer_code'], 'required', 'except' => 'from_mcc'],
                [['bmc_code', 'state_code', 'valid_from'], 'required', 'except' => 'importCsv'],
                [['milk_type_code'], 'required', 'except' => ['from_mcc', 'importCsv']],
                [['is_active', 'is_mcc', 'created_at', 'updated_at', 'valid_from', 'milk_type_code', 'sap_vendor_code', 'password', 'antibiotic_check', 'channel_type', 'fssi', 'bmc_short_name', 'fssi_expiry_date'], 'safe'],
                [['fssi_expiry_date'], 'required', 'when' => function ($model) {
                        return !empty($model->fssi);
                    }, 'whenClient' => "function (attribute, value) {return $('#tbldcsbmc-fssi').val() !== '';
                    }"],
                [['fssi_expiry_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['fssi_expiry_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['fssi_expiry_date'], 'convertDate', 'on' => ['importCsv']],
                [['fssi_expiry_date'], 'validateDate', 'when' => function ($model) {
                        return !empty($model->fssi);
                    }, 'on' => ['importCsv']],
                [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['post_sap_data']],
//            [['bmc_name'], 'unique'],
            [['bmc_name'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => false],
                [['bmc_milk_type', 'capacity', 'manufacturer_code', 'bmc_type_code'], 'integer'],
            //[['bmc_code', 'dcs_code'], 'string', 'max' => 9],
            [['model'], 'string', 'max' => 255],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
//            [['bmc_code'], 'integer', 'min' => 1],
//            [['bmc_code'], 'string', 'max' => 5],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'plant_code', 'is_weight_manual', 'is_quality_manual', 'ref_code', 'bmc_code_ex', 'rate_calculate_on_merge', 'billing_type', 'gst_no', 'pan_no', 'is_rented_bmc'], 'safe'],
                [['mcc_plant_code'], 'setField'],
                [['is_weight_manual', 'is_quality_manual'], 'boolean'],
//            [['bmc_code'], 'unique'],
            ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'address', 'aadhaar_no'], 'safe'],
                [['data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'emilk_sync_status', 'emilk_sync_timestamp'], 'safe'],
                [['bmc_code'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
                [['rate_calculate_on_merge'], 'default', 'value' => 0],
                [['is_rented_bmc'], 'default', 'value' => 0],
                [['password'], 'string', 'min' => 8, 'max' => 8],
                [['antibiotic_check'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => ['importCsv']],
                [['pan_no'], 'trim'],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data', 'from_mcc']],
                [['pan_no'], 'setPanNumber', 'on' => ['importCsv']],
                [['gst_no'], 'unique', 'except' => ['post_sap_data', 'from_mcc']],
                [['gst_no'], 'string', 'max' => 15],
                [['gst_no'], function ($attribute, $params) {
                    $this->validateGstNo($attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data', 'from_mcc']],
                [['address'], 'string', 'max' => 500],
                [['aadhaar_no'], 'unique', 'skipOnError' => TRUE, 'on' => ['importCsv']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblDcsBmc', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'bmc_name' => Yii::t('app', 'BMC Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_type_code' => Yii::t('app', 'BMC Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_mcc' => Yii::t('app', 'Is MCC'),
            'model' => Yii::t('app', 'Model No'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bmc_milk_type' => Yii::t('app', 'Milk Type'),
            'capacity' => Yii::t('app', 'Capacity (LPD)'),
            'created_by' => Yii::t('app', 'Created By'),
            'subcenter_code' => Yii::t('app', 'Sub Center'),
            'manufacturer_code' => Yii::t('app', 'Manufacturer Name'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'district_code' => Yii::t('app', 'District'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'state_code' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'union_code' => Yii::t('app', 'Union'),
            'village_code' => Yii::t('app', 'Village'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'plant_code' => Yii::t('app', 'Plant'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'bmc_code_ex' => Yii::t('app', 'BMC Code Ex'),
            'ref_code' => Yii::t('app', 'Code'),
            'x_col1' => Yii::t('app', 'Channel Type'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'gst_no' => Yii::t('app', 'GST No'),
            'pincode' => Yii::t('app', 'Pincode'),
            'address' => Yii::t('app', 'Address'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
            'fssi' => Yii::t('app', 'FSSAI'),
            'fssi_expiry_date' => Yii::t('app', 'FSSAI Expiry Date'),
            'bmc_short_name' => Yii::t('app', 'Bmc Short Name'),
        ];
    }

    public function addBmc($jsonData) {

        $this->union_code = $jsonData['union_code'];
        $this->bmc_code = $this->getCode();
        $this->bmc_name = $jsonData['bmc_name'];
        $this->bmc_type_code = $jsonData['bmc_type_code'];
        $this->model = $jsonData['model'];
        $this->bmc_milk_type = $jsonData['bmc_milk_type'];
        $this->capacity = $jsonData['capacity'];
        $this->manufacturer_code = $jsonData['manufacturer_code'];
        $this->district_code = $jsonData['district_code'];
        $this->hamlet_code = $jsonData['hamlet_code'];
        $this->state_code = $jsonData['state_code'];
        $this->sub_district_code = $jsonData['sub_district_code'];
        $this->village_code = $jsonData['village_code'];
        $this->is_active = 1;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBmcMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'bmc_milk_type']);
    }

    public function getTblMccPlant() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getTblBmcType() {
        return $this->hasOne(TblBmcType::className(), ['bmc_type_code' => 'bmc_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapacity0() {
        return $this->hasOne(TblCapacity::className(), ['capacity_code' => 'capacity']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy() {
        return $this->hasOne(User::className());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerCode() {
        return $this->hasOne(TblManufacturer::className(), ['id' => 'manufacturer_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblDcsBmcQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsBmcQuery(get_called_class());
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_bmc', 'bmc_code_ex');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    public function getDcsBmcData($field) {
        return $this->find()
                        ->where([$field => $this->$field])
                        ->one();
    }

    public function bmcUnion($parents = '') {
        $rows = $this->find()
                ->where(['union_code' => $parents])
                ->all();
        $bmc = [];
        foreach ($rows as $value) {
            $bmc[] = array('id' => $value->bmc_code, 'name' => $value->bmc_name . ' - ' . $value->ref_code);
        }
        return $bmc;
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getBMCList($plantCode, $RLS = 'TRUE', $hasBMC = false, $invert = false, $channelCode = [], $plant_bmc = [], $concatField = '') {
        $value = $this->getBMC($plantCode, $RLS, $hasBMC, $channelCode, $plant_bmc);
        $concatField = !empty($concatField) ? ' - ' . $concatField : '';
        $value = ArrayHelper::map($value, 'bmc_code', function($value) use ($invert, $concatField) {
                    return $invert ? $value->ref_code . ' - ' . $value->bmc_name : $value->bmc_name . ' - ' . $value->ref_code . $concatField;
                });
        return $value;
    }

    public function getBMC($plantCode = [], $RLS = 'TRUE', $hasBMC = 1, $channelCode = [], $plant_bmc = []) {
        $query = $this->find()->select(['bmc_code', 'bmc_name', 'ref_code'])->where(['is_active' => 1]);
        if (!empty($plantCode)) {
            $query->andWhere(['mcc_plant_code' => $plantCode]);
        }
        if (!empty($channelCode)) {
            $query->andWhere(['x_col1' => $channelCode]);
        }
        if (Yii::$app->session->get('BMC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        if (Yii::$app->session->get('hasBMC') == 0) {
            $query->andFilterWhere(['is_mcc' => 1]);
        }
        if (!empty($plant_bmc)) {
            $query->andWhere(['plant_code' => $plant_bmc]);
        }
        return $query->orderby('bmc_name asc')->all();
    }

    public function bmcData($ref_code_check = FALSE) {
        if ($ref_code_check) {
            $data = $this->find()
                    ->where(['or', ['bmc_code' => $this->bmc_code], ['ref_code' => $this->bmc_code]])
                    ->andWhere(['is_active' => 1])
                    ->all();
            $data = (count($data) == 1) ? $data : [];
        } else {
            $data = $this->find()->select(['bmc_code', 'bmc_name'])->where(['bmc_code' => $this->bmc_code])->one();
        }
        return $data;
    }

    public function bmcInfo() {
        return $this->find()->where(['bmc_code' => $this->bmc_code,])->andWhere(['is_active' => 1])->one();
    }

    public function getDcsCodes() {
        return $this->hasMany(TblDcs::className(), ['bmc_code' => 'bmc_code']);
    }

    public function singleBmcData() {
        return $this->find()->where(['bmc_code' => $this->bmc_code])->one();
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code, '', '', TRUE, 2);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
        if (!empty($this->set_master_hierarchy) && $flag == 'INSERT') {
            foreach ($this->set_master_hierarchy as $hierarchy) {
                $hierarchy->save();
            }
        }
        $destCacheKey = 'bmc_dispatch_dest_name_bmc_' . $this->bmc_code;
        Yii::$app->general->removeRedisCache($destCacheKey);
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
        $destCacheKey = 'bmc_dispatch_dest_name_bmc_' . $this->bmc_code;
        Yii::$app->general->removeRedisCache($destCacheKey);
    }

    public function getBmcRecords($mcc_plant_code) {
        $data = $this->find()
                ->where(['mcc_plant_code' => $mcc_plant_code])
                ->all();
        return ArrayHelper::map($data, 'bmc_code', 'bmc_name');
    }

    public function setField($attribute, $params) {
        $this->plant_code = Yii::$app->general->getforeignkey($this->tblMccPlant, 'plant_code');
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getTblBmcMilkType() {
        return $this->hasMany(TblBmcMilkType::className(), ['bmc_code' => 'bmc_code'])->andwhere(['is_active' => 1]);
    }

    public function getMilkTypes() {
        $milkType = new TblAnimalType();
        $data = $milkType->getAnimalMilkTypeArray();

        $values = TblBmcMilkType::find()->where(['bmc_code' => $this->bmc_code, 'is_active' => 1])->asArray()->all();
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
        foreach ($this->tblBmcMilkType as $row) {
            $out .= $row->milkTypeCode->animal_type_name . '<br>';
        }
        return $out;
    }

    public function getTblBmcGroup() {
        return $this->hasMany(TblBmcGroupMapping::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getBmcs($unionCode, $notIn = [], $concatCode = false, $RLS = 'TRUE') {
        $query = $this->find()->where(['union_code' => $unionCode, 'is_active' => 1]);
        if (Yii::$app->session->get('Plant') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }
        if (Yii::$app->session->get('MCC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        }
        if (Yii::$app->session->get('BMC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (!empty($notIn)) {
            $query->andWhere(['not in', 'bmc_code', $notIn]);
        }
        $bmc = $query->all();
        $bmc = ArrayHelper::map($bmc, 'bmc_code', function($bmc) use ($concatCode) {
                    return ($concatCode ? $bmc->ref_code . ' - ' : '') . $bmc->bmc_name;
                });
        asort($bmc, SORT_NATURAL | SORT_FLAG_CASE);
        return $bmc;
    }

    public function getTblBmcMain() {
        return $this->hasMany(TblBmcGroupMapping::className(), ['p_bmc_code' => 'bmc_code']);
    }

    public function getMccBmcList($unionCode, $mccCodes) {
        $query = $this->find()->where(['union_code' => $unionCode, 'is_active' => 1, 'mcc_plant_code' => $mccCodes]);
        if (Yii::$app->session->get('BMC') !== '') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        $bmc = $query->all();
        $bmc = ArrayHelper::map($bmc, 'bmc_code', function($bmc) {
                    return $bmc->ref_code . ' - ' . $bmc->bmc_name;
                });
        asort($bmc, SORT_NATURAL | SORT_FLAG_CASE);
        return $bmc;
    }

    public function getTblDcsBmc() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getAndroidInstallation() {
        return $this->hasOne(TblAndroidInstallation::className(), ['organization_code' => 'ref_code'])->andOnCondition(['organization_type' => 'BMC']);
    }

    public function encryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model));
        foreach ($result as $key => $value) {
            if ($this->hasAttribute($value) && $this->{$value} != '')
                $model[$value] = \Yii::$app->general->encryptData($model[$value]);
        }

        return $model;
    }

    public function decryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model->attributes));
        foreach ($result as $key => $value) {
            $decryptData = \Yii::$app->general->decryptData($model->{$value});
            if ($decryptData) {
                $model->{$value} = $decryptData;
            }
        }
        return $model;
    }

    public function getChannelMaster() {
        return $this->hasOne(TblChannelMaster::className(), ['channel_master_code' => 'x_col1']);
    }

    public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }

    public function validateGstNo($attribute, $params) {

        if (!empty($this->gst_no))
            if (strlen($this->gst_no) != 15) {
                $this->addError($attribute, Yii::t('app/validation', 'Gst no must contain 15 characters'));
            }
        return false;
    }

    public function getUnionBMCList($unionCode, $RLS = 'TRUE') {
        $value = $this->getUnionBMC($unionCode, $RLS);
        $value = ArrayHelper::map($value, 'bmc_code', function ($value) {
                    return $value->bmc_name . ' - ' . $value->ref_code;
                });
        return $value;
    }

    public function getUnionBMC($unionCode = [], $RLS = 'TRUE') {
        $query = $this->find()->select(['bmc_code', 'bmc_name', 'ref_code'])->where(['is_active' => 1, 'union_code' => $unionCode]);
        if (Yii::$app->session->get('BMC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (Yii::$app->session->get('MCC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        }
        if (Yii::$app->session->get('Plant') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }
        if (Yii::$app->session->get('hasBMC') == 0) {
            $query->andFilterWhere(['is_mcc' => 1]);
        }
        return $query->all();
    }

    public function convertDateDot() {
        try {
            $this->fssi_expiry_date = Yii::$app->controls->view_date($this->fssi_expiry_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->fssi_expiry_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->fssi_expiry_date = !empty($this->fssi_expiry_date) ? Yii::$app->controls->view_date($this->fssi_expiry_date, 'php:Y-m-d') : NULL;
        }
    }

    public function validateDate($attribute, $params) {
        if (empty($this->getErrors())) {
            if ($this->$attribute < date('Y-m-d')) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' Must Not Allow Past Date.'));
                return false;
            }
        }
    }

    public function getAllBmcData() {
        return $this->find()->where(['bmc_code' => $this->bmc_code])->all();
    }

    public function getBmcPlantList() {
        return $this->find()
            ->select('plant_code')
            ->distinct()
            ->where(['is_active' => 1])
            ->all();
    }
    
    public function getContactDetails() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'bmc_code'])->andOnCondition(['tbl_contact_details.is_active' => 1, 'tbl_contact_details.is_default' => 1, 'module_name' => 'bmc']);
    }
}
