<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_VCG_meeting_master_history".
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
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblVCGMeetingMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_Id', 'VCG_M_code', 'VCG_date', 'from_time', 'to_time', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'attandance_count', 'attachment_code', 'route_supervisor_code', 'pib_office_code', 'status', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type', 'history_created_at', 'history_created_by'], 'safe'],
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
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'attandance_count' => Yii::t('app', 'Attandance Count'),
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'route_supervisor_code' => Yii::t('app', 'Route Supervisor Code'),
            'pib_office_code' => Yii::t('app', 'Pib Office Code'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
