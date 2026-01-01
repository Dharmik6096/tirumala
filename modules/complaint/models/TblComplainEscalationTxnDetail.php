<?php

namespace app\modules\complaint\models;

use Yii;
use yii\db\Expression;
use app\modules\general\models\TblDepartment;

/**
 * This is the model class for table "tbl_complain_escalation_txn_detail".
 *
 * @property integer $complain_escalation_txn_detail_code
 * @property integer $complain_escalation_txn_code
 * @property integer $complain_code
 * @property integer $task_activity_code
 * @property string $union_code
 * @property string $user_type
 * @property integer $escalation_time
 * @property integer $level
 * @property string $user_code
 * @property string $status
 * @property string $device_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblComplainEscalationTxnDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_escalation_txn_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'user_type', 'status', 'complain_escalation_txn_code', 'device_id', 'complain_code', 'task_activity_code', 'escalation_time', 'level', 'originating_type', 'created_at', 'updated_at', 'user_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'process_type', 'cron_status', 'assign_date', 'department'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complain_escalation_txn_detail_code' => Yii::t('app', 'Complain Escalation Txn Detail Code'),
            'complain_escalation_txn_code' => Yii::t('app', 'Complain Escalation Txn Code'),
            'complain_code' => Yii::t('app', 'Complain Code'),
            'task_activity_code' => Yii::t('app', 'Task Activity Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'user_type' => Yii::t('app', 'User Type'),
            'escalation_time' => Yii::t('app', 'Escalation Time'),
            'level' => Yii::t('app', 'Level'),
            'user_code' => Yii::t('app', 'User Code'),
            'status' => Yii::t('app', 'Status'),
            'device_id' => Yii::t('app', 'Device ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getPickRecords() {
        $subquery = $this->find()->select(['tbl_complain_escalation_txn_detail.complain_code'])->distinct();
        $query = $this->find()
                ->innerJoin('tbl_complain', 'tbl_complain.complain_code = tbl_complain_escalation_txn_detail.complain_code')
                ->where(['or', ['<>', 'tbl_complain.resolved_status', 'RESOLVED'], ['is', 'tbl_complain.resolved_status', NULL]])
                ->andWhere(['status' => 'Allocated', 'cron_status' => '0'])
                ->andWhere(['>=', 'GETDATE()', new \yii\db\Expression("DATEADD(MINUTE, escalation_time, assign_date)")])
                ->andWhere(['in', 'tbl_complain_escalation_txn_detail.complain_code', $subquery])
                ->orderBy(['level' => SORT_ASC])
                ->all();

        return $query;
    }

    public function updatePickStatus($ids) {
        return $this->updateAll(['cron_status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['and', ['<>', 'user_code', null], ['complain_escalation_txn_detail_code' => $ids]]);
    }

    public function updateStatusDiscard() {
        return $this->updateAll(['cron_status' => 2, 'status' => 'Discard'], ['complain_code' => $this->complain_code, 'user_code' => null]);
    }

    public function updateStatus() {
        return $this->updateAll(['cron_status' => $this->cron_status, 'status' => new Expression("CASE WHEN user_code IS NULL THEN 'Discard' ELSE 'Escalated' END"), 'response_datetime' => date('Y-m-d H:i:s')], ['complain_escalation_txn_detail_code' => $this->complain_escalation_txn_detail_code]);
    }

    public function updateRecords() {
        return $this->updateAll(['cron_status' => $this->cron_status, 'status' => 'Allocated', 'assign_date' => date('Y-m-d H:i:s')], ['complain_escalation_txn_detail_code' => $this->complain_escalation_txn_detail_code]);
    }

    public function updateProcessStatus() {
        return $this->updateAll(['cron_status' => $this->cron_status, 'response_datetime' => date('Y-m-d H:i:s')], ['complain_escalation_txn_detail_code' => $this->complain_escalation_txn_detail_code]);
    }

    public function getDepartmentId() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

}
