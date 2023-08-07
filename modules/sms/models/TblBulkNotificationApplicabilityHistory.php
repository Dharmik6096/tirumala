<?php

namespace app\modules\sms\models;

use Yii;

/**
 * This is the model class for table "tbl_bulk_notification_applicability_history".
 *
 * @property integer $id
 * @property integer $bulk_notification_app_code
 * @property integer $bulk_notification_id
 * @property string $wef_date
 * @property string $applicable_for
 * @property string $applicable_code
 * @property string $union_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblBulkNotificationApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bulk_notification_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bulk_notification_app_code', 'bulk_notification_id', 'is_active', 'originating_type'], 'safe'],
                [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['applicable_for', 'applicable_code'], 'safe'],
                [['union_code'], 'safe'],
                [['created_by', 'updated_by', 'history_created_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['operation_type'], 'safe'],
                [['status', 'entry_datetime', 'pickup_datetime', 'response_datetime', 'resp_desc', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bulk_notification_app_code' => Yii::t('app', 'Bulk Notification App Code'),
            'bulk_notification_id' => Yii::t('app', 'Bulk Notification ID'),
            'wef_date' => Yii::t('app', 'Schedual Date'),
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
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
