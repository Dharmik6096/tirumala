<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblAnimalInspector;
use app\modules\dcsoperation\models\TblMember;

/**
 * This is the model class for table "tbl_animal_inspector_request".
 *
 * @property string $animal_inspector_request_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $user_type
 * @property string $member_code
 * @property string $member_name
 * @property string $mobile_no
 * @property string $address
 * @property string $ai_request_for
 * @property integer $animal_inspector_code
 * @property string $expected_visit_date
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblAnimalInspectorRequest extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_animal_inspector_request';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['originating_org_code', 'originating_org_type', 'address', 'member_name', 'mobile_no', 'remarks', 'user_type', 'member_code', 'ai_request_for', 'created_by', 'updated_by', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'union_code', 'animal_inspector_request_code', 'expected_visit_date', 'created_at', 'updated_at', 'animal_inspector_code', 'originating_type', 'animal_inspector_request_code', 'request_date', 'status', 'close_remarks'], 'safe'],
                [['close_remarks'], 'required'],
                [['status'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'animal_inspector_request_code' => Yii::t('app', 'Animal Inspector Request Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'user_type' => Yii::t('app', 'User Type'),
            'member_code' => Yii::t('app', 'Member Code'),
            'member_name' => Yii::t('app', 'Member Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'address' => Yii::t('app', 'Address'),
            'ai_request_for' => Yii::t('app', 'Ai Request For'),
            'animal_inspector_code' => Yii::t('app', 'Animal Inspector Name'),
            'expected_visit_date' => Yii::t('app', 'Expected Visit Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'status' => yii::t('app', 'Status'),
            'close_remarks' => yii::t('app', 'Close Remarks')
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getAnimalInspectorCode() {
        return $this->hasOne(TblAnimalInspector::className(), ['animal_inspector_code' => 'animal_inspector_code']);
    }

}
