<?php

namespace app\modules\sms\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\sms\models\TblBulkNotification;
use webvimark\modules\UserManagement\models\User;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_bulk_notification_applicability".
 *
 * @property integer $bulk_notification_app_code
 * @property integer $bulk_notification_id
 * @property string $wef_date
 * @property string $applicable_for
 * @property string $applicable_code
 * @property string $company_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblBulkNotificationApplicability extends \app\models\ChildModel {
    //public $union_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bulk_notification_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bulk_notification_id'], 'required', 'except' => ['milkBillApplicability']],
                [['applicable_code', 'applicable_for'], 'required'],
                [['bulk_notification_id', 'is_active', 'originating_type'], 'safe'],
                [['wef_date', 'created_at', 'updated_at'], 'safe'],
                [['applicable_for', 'applicable_code'], 'safe'],
                [['union_code'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['is_active'], 'default', 'value' => 1],
                [['status'], 'default', 'value' => 0],
                [['entry_datetime'], 'default', 'value' => date('Y-m-d H:i:s')],
                [['status', 'entry_datetime', 'pickup_datetime', 'response_datetime', 'resp_desc', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'safe'],
                [['wef_date'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->bulkNotification, 'notification_type') != '3';
                },],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bulk_notification_app_code' => Yii::t('app', 'Bulk Notification App Code'),
            'bulk_notification_id' => Yii::t('app', 'Bulk Notification ID'),
            'wef_date' => Yii::t('app', 'Schedual On'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code', 'mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code', 'mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code', 'plant_code' => 'plant_code']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'applicable_code']);
    }

    public function getBulkNotification() {
        return $this->hasOne(TblBulkNotification::className(), ['bulk_notification_id' => 'bulk_notification_id']);
    }

    public function getPickRecords($limit = 100) {
        return $query = $this->find()->select(['bulk_notification_id', 'applicable_for', 'wef_date', 'dcs_code', 'union_code'])->distinct()
                        ->where(['status' => $this->status, 'CAST(wef_date as date)' => date('Y-m-d')])
                        ->limit($limit)
                        ->orderBy([
                            'wef_date' => SORT_ASC,
                        ])->all();
    }

    public function updatePickStatus() {
        return $this->updateAll(['status' => 1, 'pickup_datetime' => date('Y-m-d H:i:s')], ['bulk_notification_id' => $this->bulk_notification_id, 'wef_date' => $this->wef_date, 'applicable_for' => $this->applicable_for]);
    }

    public function updateProcessStatus() {
        return $this->updateAll(['status' => $this->status, 'resp_desc' => $this->resp_desc, 'response_datetime' => date('Y-m-d H:i:s')], ['bulk_notification_id' => $this->bulk_notification_id, 'wef_date' => $this->wef_date, 'applicable_for' => $this->applicable_for, 'status' => 1]);
    }

    public function getDcsCodes() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getMccCodes() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getBmcCodes() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function setOrgDetail() {
        if ($this->applicable_for == 'DCS') {
            $this->dcs_code = $this->applicable_code;
            $this->plant_code = Yii::$app->general->getforeignkey($this->dcsCodes, 'plant_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->dcsCodes, 'mcc_plant_code');
            $this->bmc_code = Yii::$app->general->getforeignkey($this->dcsCodes, 'bmc_code');
        } elseif ($this->applicable_for == 'MCC') {
            $this->mcc_plant_code = $this->applicable_code;
            $this->plant_code = Yii::$app->general->getforeignkey($this->mccCodes, 'plant_code');
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $bulkNotificationType = Yii::$app->general->getforeignkey($this->bulkNotification, 'notification_type');
        if ($bulkNotificationType == 3) {
            $sentboxArray = [];
            $applicableFor = $this->applicable_for;
            if ($applicableFor == 'DCS') {
                $applicableFor = 'VLC';
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->applicable_code);
            } else if ($applicableFor == 'BMC') {
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->applicable_code);
            }

            foreach ($sentboxArray as $sent) {
                if ($applicableFor == $sent['type']) {
                    $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
                    $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                    $bulk_notification = TblBulkNotification::findOne($this->bulk_notification_id);
                    if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                        if (!($sentbox->setSentbox($bulk_notification, $flag))) {
                            throw new UserException("SentBox Entry is not created so transaction is rollback!");
                        }
                    }
                }
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
        $bulkNotificationType = Yii::$app->general->getforeignkey($this->bulkNotification, 'notification_type');
        if ($bulkNotificationType == 3) {
            $sentboxArray = [];
            $applicableFor = $this->applicable_for;
            if ($applicableFor == 'DCS') {
                $applicableFor = 'VLC';
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->applicable_code);
            } else if ($applicableFor == 'BMC') {
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->applicable_code);
            }
            foreach ($sentboxArray as $sent) {
                if ($applicableFor == $sent['type']) {
                    $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                    $bulk_notification = TblBulkNotification::findOne($this->bulk_notification_id);
                    if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                        if (!($sentbox->setSentbox($bulk_notification, 'DELETE'))) {
                            throw new UserException("SentBox Entry is not created so transaction is rollback!");
                        }
                    }
                }
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
