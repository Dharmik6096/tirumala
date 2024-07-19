<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblRouteMapping;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_VCG_meeting_master".
 *
 * @property integer $VCG_M_Id
 * @property string $VCG_M_code
 * @property string $VCG_date
 * @property string $from_time
 * @property string $to_time
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property integer $attandance_count
 * @property integer $attachment_code
 * @property string $route_supervisor_code
 * @property string $pib_office_code
 * @property string $status
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblVCGMeetingMaster extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_Id', 'VCG_M_code', 'VCG_date', 'from_time', 'to_time', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'attandance_count', 'attachment_code', 'route_supervisor_code', 'pib_office_code', 'status', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VCG_M_Id' => Yii::t('app', 'Vcg M ID'),
            'VCG_M_code' => Yii::t('app', 'Vcg M Code'),
            'VCG_date' => Yii::t('app', 'Vcg Date'),
            'from_time' => Yii::t('app', 'From Time'),
            'to_time' => Yii::t('app', 'To Time'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'route_code' => Yii::t('app', 'Route'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'attandance_count' => Yii::t('app', 'Attandance Count'),
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'route_supervisor_code' => Yii::t('app', 'Route Supervisor'),
            'pib_office_code' => Yii::t('app', 'Pib Office'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
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

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getRouteSupervisorCode() {
        return $this->hasOne(User::className(), ['id' => 'route_supervisor_code']);
    }

    public function getPibOfficeName() {
        return $this->hasOne(User::className(), ['id' => 'pib_office_code']);
    }

}
