<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\dcsoperation\models\TblMember;
use app\modules\globalmaster\models\TblCommitteeType;

/**
 * This is the model class for table "tbl_committee_members".
 *
 * @property integer $committee_member_code
 * @property integer $committee_type_code
 * @property string $union_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $member_name
 * @property string $election_date
 * @property string $tenure_from_date
 * @property string $tenure_to_date
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblCommitteeMembers extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_committee_members';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['committee_type_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'member_name', 'election_date', 'tenure_from_date', 'tenure_to_date', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'local_name'], 'safe'],
            [['committee_type_code' ,'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'member_name', 'election_date', 'tenure_from_date', 'tenure_to_date'], 'required'],
            [['tenure_to_date'], 'validateToDate'],
            [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            ['dcs_code', 'unique', 'targetAttribute' => ['dcs_code', 'committee_type_code'], 'message' => Yii::t('app/validation', 'Combination of DCS and Committee Type has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'committee_member_code' => Yii::t('app', 'Committee Member Code'),
            'committee_type_code' => Yii::t('app', 'Committee Member Type'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_code' => Yii::t('app', 'Member Code'),
            'member_name' => Yii::t('app', 'Member Name'),
            'election_date' => Yii::t('app', 'Election Date'),
            'tenure_from_date' => Yii::t('app', 'Tenure From Date'),
            'tenure_to_date' => Yii::t('app', 'Tenure To Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }
    
    public function getCommitteeType() {
        return $this->hasOne(TblCommitteeType::className(), ['committee_type_code' => 'committee_type_code']);
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->tenure_to_date) && !empty($this->tenure_from_date) && ($this->tenure_from_date > $this->tenure_to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than From Date.'));
            return false;
        }
    }

}
