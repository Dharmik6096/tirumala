<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use yii\helpers\ArrayHelper;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblMccMilkType;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_mcc_plant".
 *
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $contact_person
 * @property string $created_at
 * @property string $created_by
 * @property string $description
 * @property string $email
 * @property boolean $is_active
 * @property string $mobile_no
 * @property string $name
 * @property string $local_name
 * @property string $updated_at
 * @property string $updated_by
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $village_code
 * @property integer $capacity
 * @property integer $is_plant
 * @property TblDistricts $districtCode
 * @property TblHamlets $hamletCode
 * @property TblStates $stateCode
 * @property TblSubDistricts $subDistrictCode
 * @property TblUnions $unionCode
 * @property TblVillages $villageCode
 */
class TblMccPlant extends \app\models\ChildModel {

    public $milk_type_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_plant';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['is_weight_manual', 'is_quality_manual'], 'default', 'value' => FALSE],
                [['plant_code', 'name', 'union_code', 'has_min_qty_limit'], 'required'],
                [['state_code', 'valid_from', 'milk_type_code'], 'required', 'except' => 'importCsv'],
                [['created_at', 'updated_at', 'is_active', 'capacity', 'valid_from', 'is_plant', 'milk_type_code', 'gst_no', 'min_qty_limit', 'has_min_qty_limit', 'sap_vendor_code', 'recovery_validate'], 'safe'],
                [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['capacity'], 'integer'],
                [['min_qty_limit'], 'integer', 'min' => 1],
                [['min_qty_limit'], 'required', 'when' => function ($model) {
                    return $model->has_min_qty_limit == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblmccplant-has_min_qty_limit').val() == '1'; 
          }"],
                [['has_min_qty_limit'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'skipOnEmpty' => false, 'on' => ['importCsv']],
                [['village_code'], 'string', 'max' => 6],
                [['contact_person'], 'string', 'max' => 100],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['description'], 'string', 'max' => 250],
                [['email'], 'string', 'max' => 50],
                [['email'], 'email'],
                [['name'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => false],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['mobile_no'], 'string', 'max' => 10],
                [['name',], 'string', 'max' => 255],
                [['local_name', 'local_contact_person_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
//            [['mcc_plant_code'], 'integer', 'min' => 1],
//            [['mcc_plant_code'], 'string', 'max' => 6],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_weight_manual', 'is_quality_manual', 'mcc_plant_code_ex', 'ref_code', 'vendor_code'], 'safe'],
                [['is_weight_manual', 'is_quality_manual'], 'boolean'],
                ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'emilk_sync_status', 'emilk_sync_timestamp'], 'safe'],
                [['gst_no'], 'string', 'min' => 15, 'max' => 15],
                [['gst_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['recovery_validate'], 'default', 'value' => 0],
                [['sap_vendor_code'], 'setVendorCode'],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMccPlant', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    public function validateAttribute($attribute, $params) {
        if (!in_array($this->$attribute, ['1', '2'])) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be either "1" or "2".'));
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mcc_plant_code' => Yii::t('app', 'MCC Code'),
            'plant_code' => Yii::t('app', 'Plant'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'description' => Yii::t('app', 'Description'),
            'local_contact_person_name' => Yii::t('app', 'Contact Person Hindi Name'),
            'email' => Yii::t('app', 'Email'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_plant' => Yii::t('app', 'Is Plant'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'name' => Yii::t('app', 'MCC Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'district_code' => Yii::t('app', 'District'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'state_code' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'union_code' => Yii::t('app', 'Union'),
            'village_code' => Yii::t('app', 'Village'),
            'capacity' => Yii::t('app', 'Capacity (LPD)'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'mcc_plant_code_ex' => Yii::t('app', 'MCC Code Ex'),
            'ref_code' => Yii::t('app', 'Code'),
            'gst_no' => Yii::t('app', 'GSTIN No.'),
        ];
    }

    public function addMcc($jsonData) {

        $this->union_code = $jsonData['union_code'];
        $this->mcc_plant_code = $this->getCode();
        $this->name = $jsonData['name'];
        $this->local_name = $jsonData['local_name'];
        $this->capacity = $jsonData['capacity'];
        $this->valid_from = $jsonData['valid_from'];
        $this->description = $jsonData['description'];
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
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
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
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
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
    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    /**
     * @inheritdoc
     * @return TblMccPlantQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMccPlantQuery(get_called_class());
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_mcc_plant', 'mcc_plant_code_ex');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapacity0() {
        return $this->hasOne(TblCapacity::className(), ['capacity_code' => 'capacity']);
    }

    public function getchillingcenter($type, $union) {
        $value = $this->find()->select(['mcc_plant_code', 'name'])->where(['union_code' => $union])->all();
        //$value = ArrayHelper::map($value, 'id', 'name');
        $a = ArrayHelper::map($value, 'mcc_plant_code', function($value) {
                    $type = 'MCC';
                    return $value['name'] . '-' . $type;
                });
        return $a;
    }

    public function getChillingCenterValue($value) {
        $value = $this->find()->select(['mcc_plant_code', 'name'])->where(['mcc_plant_code' => $value])->one();

        return $value;
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['mcc_plant_code' => 'mcc_plant_code'])->where(['is_mcc' => 1]);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getMCCList($plantCode, $RLS = 'TRUE') {
        $value = $this->getMCC($plantCode, $RLS);
        $value = ArrayHelper::map($value, 'mcc_plant_code', function($value) {
                    return $value->name . ' - ' . $value->ref_code;
                });
        return $value;
    }

    public function getMCC($plantCode = [], $RLS = 'TRUE') {
        $query = $this->find()->select(['mcc_plant_code', 'name', 'ref_code'])->where(['is_active' => 1]);
        if (!empty($plantCode))
            $query->andWhere(['plant_code' => $plantCode]);
        if (Yii::$app->session->get('MCC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        }
        if (Yii::$app->session->get('Unions') !== '') {
            $query->andWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        return $query->all();
    }

    public function getMccs($unionCode, $notIn = [], $concatCode = false, $in = []) {
        $query = $this->find()->where(['union_code' => $unionCode, 'is_active' => 1]);
        if (Yii::$app->session->get('MCC') !== '') {
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        }
        if (Yii::$app->session->get('Plant') !== '') {
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }
        if (!empty($notIn)) {
            $query->andWhere(['not in', 'mcc_plant_code', $notIn]);
        }
        if (!empty($in)) {
            $query->andWhere(['mcc_plant_code' => explode(',', $in)]);
        }
        $mcc = $query->all();
        if ($concatCode) {
            $mcc = ArrayHelper::map($mcc, 'mcc_plant_code', function($mcc) {
                        return $mcc->ref_code . '-' . $mcc->name;
                    });
        } else {
            $mcc = ArrayHelper::map($mcc, 'mcc_plant_code', 'name');
        }
        asort($mcc, SORT_NATURAL | SORT_FLAG_CASE);
        return $mcc;
    }

    public function getData($ref_code_check = FALSE) {
        if ($ref_code_check) {
            $data = $this->find()
                    ->where(['or', ['mcc_plant_code' => $this->mcc_plant_code], ['ref_code' => $this->mcc_plant_code]])
                    ->andWhere(['is_active' => 1])
                    ->all();
            $data = (count($data) == 1) ? $data : [];
        } else {
            $data = $this->find()
                    ->where(['mcc_plant_code' => $this->mcc_plant_code])
                    ->one();
        }
        return $data;
    }

    public function getBmcCodes() {
        return $this->hasMany(TblDcsBmc::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->mcc_plant_code, '', '', '', TRUE, 2);
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
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->mcc_plant_code, '');
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function getMccRecords($plant = []) {
        $data = $this->find()
                ->where(['plant_code' => $plant])
                ->all();
        return ArrayHelper::map($data, 'mcc_plant_code', 'name');
    }

    public function getTblDcs() {
        return $this->hasMany(TblDcs::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getTblMccMilkType() {
        return $this->hasMany(TblMccMilkType::className(), ['mcc_plant_code' => 'mcc_plant_code'])->andwhere(['is_active' => 1]);
    }

    public function getMilkTypes() {
        $milkType = new TblAnimalType();
        $data = $milkType->getAnimalMilkTypeArray();

        $values = TblMccMilkType::find()->where(['mcc_plant_code' => $this->mcc_plant_code, 'is_active' => 1])->asArray()->all();
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
        foreach ($this->tblMccMilkType as $row) {
            $out .= $row->milkTypeCode->animal_type_name . '<br>';
        }
        return $out;
    }

    public function getTblMccPlantGroup() {
        return $this->hasMany(TblMccPlantGroupMapping::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getTblMccPlantMain() {
        return $this->hasMany(TblMccPlantGroupMapping::className(), ['p_mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getUnionMCCList($unionCode, $RLS = 'TRUE') {
        $value = $this->getUnionMCC($unionCode, $RLS);
        $value = ArrayHelper::map($value, 'mcc_plant_code', function ($value) {
                    return $value->name . ' - ' . $value->ref_code;
                });
        return $value;
    }

    public function getUnionMCC($unionCode = [], $RLS = 'TRUE') {
        $query = $this->find()->select(['mcc_plant_code', 'name', 'ref_code'])->where(['is_active' => 1]);
        if (!empty($unionCode))
            $query->andWhere(['union_code' => $unionCode]);
        if (Yii::$app->session->get('MCC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        }
        if (Yii::$app->session->get('Plant') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }
        return $query->all();
    }

    public function getTblMccPlant() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getValidMcc($mcc) {
        $data = $this->find()->select('mcc_plant_code')->where(['mcc_plant_code' => $mcc])->andWhere(['is_active' => 1])->all();
        if (empty($data)) {
            $data = $this->find()->select('mcc_plant_code')->where(['or', ['mcc_plant_code' => $mcc], ['mcc_plant_code_ex' => $mcc], ['ref_code' => $mcc]])->andWhere(['is_active' => 1])->all();
        }
        return !empty($data) && count($data) == 1 ? $data[0]->mcc_plant_code : '';
    }

    public function setVendorCode($attribute, $params) {
        if (Yii::$app->session->get('eiplCode') == 'MMD') {
            $this->vendor_code = $this->sap_vendor_code;
        }
    }

}
