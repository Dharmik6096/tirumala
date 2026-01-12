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
use yii\base\UserException;
use app\modules\tankermovement\models\TblPartyMaster;

/**
 * This is the model class for table "tbl_plant".
 *
 * @property string $plant_code
 * @property string $contact_person
 * @property string $name
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $local_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property integer $is_active
 * @property string $mobile_no
 * @property string $local_contact_person_name
 * @property string $email
 * @property string $description
 * @property integer $capacity
 */
class TblPlant extends \app\models\ChildModel {

    public $is_sentbox;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['name', 'union_code'], 'required'],
                [['plant_code', 'state_code', 'valid_from'], 'required', 'except' => 'importCsv'],
                [['plant_code', 'contact_person', 'name', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'village_code', 'local_name', 'created_by', 'updated_by', 'union_code', 'mobile_no', 'local_contact_person_name', 'email', 'description'], 'string'],
                [['email'], 'email'],
            //[['plant_code'], 'unique'],
            [['name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['local_name', 'local_contact_person_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['mobile_no'], 'string', 'max' => 10],
            [['created_at', 'updated_at', 'capacity', 'valid_from', 'is_active', 'sap_vendor_code', 'is_virtual_plant', 'is_not_actual_plant'], 'safe'],
            [['capacity'], 'integer'],
            [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
//            [['plant_code'], 'integer', 'min' => 1],
//            [['plant_code'], 'string', 'max' => 6],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'plant_code_ex', 'vendor_code', 'ref_code'], 'safe'],
                ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'emilk_sync_status', 'emilk_sync_timestamp'], 'safe'],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblPlant', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'plant_code' => Yii::t('app', 'Plant Code'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'name' => Yii::t('app', 'Plant Name'),
            'district_code' => Yii::t('app', 'District'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'state_code' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
            'is_active' => Yii::t('app', 'Is Active'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'local_contact_person_name' => Yii::t('app', 'Contact Person Hindi Name'),
            'email' => Yii::t('app', 'Email'),
            'description' => Yii::t('app', 'Description'),
            'capacity' => Yii::t('app', 'Capacity (LPD)'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'plant_code_ex' => Yii::t('app', 'Plant Code Ex'),
            'ref_code' => Yii::t('app', 'Code'),
            'is_virtual_plant' => Yii::t('app', 'is Virtual Plant?'),
            'is_not_actual_plant' => Yii::t('app', 'Is Not Actual Plant'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblPlantQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblPlantQuery(get_called_class());
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
    public function getCapacity0() {
        return $this->hasOne(TblCapacity::className(), ['capacity_code' => 'capacity']);
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_plant', 'plant_code_ex');
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['plant_code' => 'plant_code'])->where(['is_plant' => 1]);
    }

    public function getPlantList($unionCode, $RLS = 'TRUE', $notIn = [], $concatCode = false, $type = '') {
        $value = $this->getPlant($unionCode, $RLS, $notIn, $type);
        $value = ArrayHelper::map($value, 'plant_code', function($value) use ($concatCode) {
                    return $value->name . ($concatCode ? ' - ' . $value->plant_code : ' - ' . $value->ref_code);
                });
        return $value;
    }

    public function getPlant($unionCode = [], $RLS = 'TRUE', $notIn = [], $type = '') {
        $query = $this->find()->select(['plant_code', 'name', 'ref_code'])
                ->where(['is_active' => 1]);
        if (!empty($unionCode))
            $query->andWhere(['union_code' => $unionCode]);
        if (Yii::$app->session->get('Plant') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }
        if (!empty($notIn)) {
            $query->andWhere(['not in', 'plant_code', $notIn]);
        }
        if (!empty($type) && $type == 'tankerMilkDispatch') {
            $query->andWhere(['in', 'is_virtual_plant', [0, NULL]]);
        }

        return $query->orderBy('name asc')->all();
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes($this->plant_code, '', '', '', '', TRUE, 2);
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
        $sentbox->dest_org_type = $type;
        $sentbox->source_org_id = $this->union_code;
        return $sentbox;
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes($this->plant_code, '', '');
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function getPlantRecords() {
        $data = $this->find()
                ->where(['union_code' => $this->union_code])
                ->all();
        return ArrayHelper::map($data, function($data) {
                    return (string) $data->plant_code;
                }, 'name');
    }

    public function getData($ref_code_check = FALSE) {
        if ($ref_code_check) {
            $data = $this->find()
                    ->where(['or', ['plant_code' => $this->plant_code], ['ref_code' => $this->plant_code]])
                    ->andWhere(['is_active' => 1])
                    ->all();
            $data = (count($data) == 1) ? $data : [];
        } else {
            $data = $this->find()
                    ->where(['plant_code' => $this->plant_code])
                    ->one();
        }
        return $data;
    }

    public function getPlantData($plant_code) {
        $partyList = $this->find()->select(["CONCAT(plant_code, '#plant') AS plant_code, CONCAT(name, ' - ', ref_code, ' - PLANT') AS name"])
                        ->where(['ISNULL(is_not_actual_plant, 0)' => 0])
                        ->andWhere(['or', ['plant_code' => $plant_code], ['ref_code' => $plant_code]])->asArray()->all();
        return ArrayHelper::map($partyList, 'plant_code', 'name');
    }

    public function getParty() {
        return TblPartyMaster::find()->alias('p')->select(['p.party_master_code', 'p.party_name', 'p.sap_vendor_code'])
                        ->leftJoin('tbl_plant_conversion_vendor_mapping m', 'p.party_master_code = m.party_master_code')
                        ->where(['p.party_type' => 'conversion_vendor'])
                        ->andWhere(['m.party_master_code' => null])
                        ->orderBy(['p.party_name' => SORT_ASC])
                        ->asArray()
                        ->all();
    }

    public function getNotActualPlant() {
        $plantList = $this->find()->where(['ISNULL(is_not_actual_plant, 0)' => 1])->all();
        return !empty($plantList) ? array_column($plantList, 'plant_code') : [];
    }
}
