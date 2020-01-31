<?php

namespace app\modules\sms\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\sms\models\TblApiMaster;
use app\modules\dcsoperation\models\TblMember;

/**
 * This is the model class for table "tbl_bulk_notification".
 *
 * @property integer $bulk_notification_id
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $app_type
 * @property string $login_type
 * @property string $wef_date
 * @property string $title
 * @property string $message
 * @property string $campaign_name
 * @property string $created_at
 * @property string $created_by
 * @property integer $receiver_type
 * @property integer $content_id
 * @property integer $status
 * @property string $entry_datetime
 * @property string $pickup_datetime
 * @property string $response_datetime
 */
class TblBulkNotification extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bulk_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'app_type', 'login_type', 'title', 'message', 'campaign_name', 'created_by'], 'string'],
            [['wef_date', 'created_at', 'entry_datetime', 'pickup_datetime', 'response_datetime', 'receiver_type'], 'safe'],
            [['content_id', 'status'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'app_type', 'login_type', 'title', 'message', 'campaign_name', 'wef_date'], 'required'],
            [['status'], 'default', 'value' => 0],
            [['updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bulk_notification_id' => Yii::t('app', 'Bulk Notification ID'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_code' => Yii::t('app', 'Member Code'),
            'app_type' => Yii::t('app', 'App Type'),
            'login_type' => Yii::t('app', 'Login Type'),
            'wef_date' => Yii::t('app', 'Schedule On'),
            'title' => Yii::t('app', 'Tittle'),
            'message' => Yii::t('app', 'Message'),
            'campaign_name' => Yii::t('app', 'Campaign Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'receiver_type' => Yii::t('app', 'Receiver Type'),
            'content_id' => Yii::t('app', 'Content ID'),
            'status' => Yii::t('app', 'Status'),
            'entry_datetime' => Yii::t('app', 'Entry Datetime'),
            'pickup_datetime' => Yii::t('app', 'Pickup Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
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

    public function getApiMaster() {
        return $this->hasOne(TblApiMaster::className(), ['operator_type' => 'app_type']);
    }

    public function getPickRecords($limit = 30) {
        $query = $this->find()
                ->where(['status' => $this->status])
                ->andWhere(['<=', 'CAST(wef_date as date)', date('Y-m-d')]);
        $query->limit($limit);
        $query->orderBy([
            'wef_date' => SORT_ASC,
        ]);
        return $query->all();
    }

    public function updateFileStatus($value) {
        return $this->updateAll(['status' => 1, 'pickup_datetime' => date('Y-m-d H:i:s')], ['bulk_notification_id' => $value]);
    }

    public function disableDelete() {
        if ($this->status != 0)
            return false;
        else
            return true;
    }

}
