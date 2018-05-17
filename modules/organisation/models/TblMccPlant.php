<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use yii\helpers\ArrayHelper;

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
        return [
            [['plant_code', 'name', 'hamlet_code', 'union_code'], 'required'],
            [['mcc_plant_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'valid_from'], 'required', 'except' => 'importCsv'],
            [['created_at', 'updated_at', 'is_active', 'capacity', 'valid_from', 'is_plant'], 'safe'],
            [['capacity'], 'integer'],
//            [['is_active'], 'boolean'],
            [['mcc_plant_code'], 'unique'],
            [['mcc_plant_code', 'village_code'], 'string', 'max' => 6],
            [['contact_person'], 'string', 'max' => 100],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['description'], 'string', 'max' => 250],
            [['email'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['name'], function ($attribute, $params) {
            Yii::$app->general->validateName($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['mobile_no'], function ($attribute, $params) {
            Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['mobile_no'], 'string', 'max' => 10],
            [['name',], 'string', 'max' => 255],
            [['local_name', 'local_contact_person_name'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
        ];
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

        $data = $this->find()->select(["MAX(CONVERT(bigint,mcc_plant_code)) as mcc_plant_code"])->one();
        return str_pad(((int) $data['mcc_plant_code'] + 1), 6, '0', STR_PAD_LEFT);
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
        return $this->hasOne(TblDcsBmc::className(), ['mcc_code' => 'mcc_plant_code'])->where(['is_mcc' => 1]);
    }

    public function rlsMcc($parents = '') {
        $rows = $this->find()
                ->where(['plant_code' => $parents])
                ->all();
        $mcc = [];
        foreach ($rows as $value) {
            $mcc[] = array('id' => $value->mcc_plant_code, 'name' => $value->name);
        }
        return $mcc;
    }

}
