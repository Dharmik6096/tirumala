<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblCapacity;
use webvimark\modules\UserManagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblUnions;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\general\models\TblBmcType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_dcs_bmc".
 *
 * @property string $bmc_code
 * @property string $bmc_type_code
 * @property string $mcc_code
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

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_subcenter_bmc_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_name', 'union_code', 'hamlet_code', 'mcc_code'], 'required'],
            [['model', 'capacity', 'manufacturer_code'], 'required', 'except' => 'from_mcc'],
            [['bmc_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'valid_from'], 'required', 'except' => 'importCsv'],
            [['is_active', 'is_mcc', 'created_at', 'updated_at', 'valid_from'], 'safe'],
//            [['bmc_name'], 'unique'],
            [['bmc_name'], function ($attribute, $params) {
            Yii::$app->general->validateName($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['bmc_milk_type', 'capacity', 'manufacturer_code', 'bmc_type_code'], 'integer'],
            //[['bmc_code', 'dcs_code'], 'string', 'max' => 9],
            [['model'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['local_name'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'bmc_name' => Yii::t('app', 'BMC Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'mcc_code' => Yii::t('app', 'MCC'),
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
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_code']);
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
        $data = $this->find()->select(["max(convert(int,bmc_code)) as bmc_code"])->one();
        return str_pad((int) $data['bmc_code'] + 1, 5, '0', STR_PAD_LEFT);
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
            $bmc[] = array('id' => $value->bmc_code, 'name' => $value->bmc_name);
        }
        return $bmc;
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getBMCList($plantCode, $RLS = 'TRUE') {
        $value = $this->getBMC($plantCode, $RLS);
        $value = ArrayHelper::map($value, 'bmc_code', 'bmc_name');
        return $value;
    }

    public function getBMC($plantCode = [], $RLS = 'TRUE') {
        $query = $this->find()->select(['bmc_code', 'bmc_name'])->where(['is_active' => 1]);
        if (!empty($plantCode))
            $query->andWhere(['mcc_code' => $plantCode]);
        if (Yii::$app->session->get('BMC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        return $query->all();
    }

}
