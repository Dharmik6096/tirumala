<?php

namespace app\modules\sms\models;

use Yii;
use app\modules\organisation\models\Mastervillage;
use app\modules\organisation\models\MasterBmc;
use app\modules\organisation\models\Mastermcc;
use app\modules\sms\models\TblBulkNotification;
use webvimark\modules\UserManagement\models\User;

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
                [['applicable_code', 'wef_date', 'applicable_for', 'bulk_notification_id'], 'required'],
                [['bulk_notification_id', 'is_active', 'originating_type'], 'safe'],
                [['wef_date', 'created_at', 'updated_at'], 'safe'],
                [['applicable_for', 'applicable_code'], 'safe'],
                [['company_code'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['is_active'], 'default', 'value' => 1],
                [['status'], 'default', 'value' => 0],
                [['entry_datetime'], 'default', 'value' => date('Y-m-d H:i:s')],
                [['status', 'entry_datetime', 'pickup_datetime', 'response_datetime', 'resp_desc', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code'], 'safe'],
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
            'company_code' => Yii::t('app', 'Union Code'),
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
        return $this->hasOne(Mastervillage::className(), ['villageid' => 'applicable_code', 'mccid' => 'mcc_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(MasterBmc::className(), ['BMCID' => 'applicable_code', 'mccid' => 'mcc_code']);
    }

    public function getMccCode() {
        return $this->hasOne(Mastermcc::className(), ['mccid' => 'applicable_code', 'plantid' => 'plant_code']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'applicable_code']);
    }

    public function getBulkNotification() {
        return $this->hasOne(TblBulkNotification::className(), ['bulk_notification_id' => 'bulk_notification_id']);
    }

    public function getPickRecords($limit = 5) {
        return $query = $this->find()->select(['bulk_notification_id', 'applicable_for', 'wef_date'])->distinct()
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

    public function setOrgDetail() {
        $applicable_code = explode(':', $this->applicable_code);
        $this->applicable_code = $applicable_code[0];
        if ($this->applicable_for == 'DCS') {
            $this->dcs_code = $this->applicable_code;
            $hasBmc = !empty(Yii::$app->session->get('has_bmc')) ? true : false;
            if ($hasBmc) {
                $this->bmc_code = $applicable_code[1];
                $this->mcc_code = $applicable_code[2];
            } else {
                $this->mcc_code = $applicable_code[1];
            }
        } elseif ($this->applicable_for == 'MCC') {
            $this->mcc_code = $this->applicable_code;
            $this->plant_code = $applicable_code[1];
        }
    }

}
