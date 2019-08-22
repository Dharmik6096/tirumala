<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;

/**
 * This is the model class for table "tbl_mcc_plant_history".
 *
 * @property integer $id
 * @property string $contact_person
 * @property string $created_at
 * @property string $created_by
 * @property string $description
 * @property string $email
 * @property string $history_created_at
 * @property boolean $is_active
 * @property string $mcc_plant_code
 * @property string $mobile_no
 * @property string $name
 * @property string $local_name
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $village_code
 * @property string $capacity
 *
 * @property TblDistricts $districtCode
 * @property TblHamlets $hamletCode
 * @property TblStates $stateCode
 * @property TblSubDistricts $subDistrictCode
 * @property TblUnions $unionCode
 * @property TblVillages $villageCode
 */
class TblMccPlantHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_plant_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at', 'is_active'], 'safe'],
            [['contact_person', 'description', 'email', 'mcc_plant_code', 'mobile_no', 'name', 'state_code', 'district_code', 'sub_district_code', 'hamlet_code', 'village_code', 'union_code', 'plant_code'], 'safe'],
            [['created_at', 'history_created_at', 'updated_at', 'local_name', 'capacity', 'valid_from', 'is_plant'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'contact_person' => Yii::t('app', 'Contact Person'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'description' => Yii::t('app', 'Description'),
                  'email' => Yii::t('app', 'Email'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'is_active' => Yii::t('app', 'Is Active'),
                  'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
                  'mobile_no' => Yii::t('app', 'Mobileno'),
                  'name' => Yii::t('app', 'Name'),
                  'operation_type' => Yii::t('app', 'Operation Type'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'updated_by' => Yii::t('app', 'Updated By'),
                  'district_code' => Yii::t('app', 'District Code'),
                  'hamlet_code' => Yii::t('app', 'Hamlet Code'),
                  'state_code' => Yii::t('app', 'State Code'),
                  'sub_district_code' => Yii::t('app', 'Sub District Code'),
                  'union_code' => Yii::t('app', 'Union Code'),
                  'village_code' => Yii::t('app', 'Village Code'), */
        ];
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
     * @inheritdoc
     * @return TblMccPlantHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMccPlantHistoryQuery(get_called_class());
    }

}
