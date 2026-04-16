<?php

namespace app\modules\general\models;

use Yii;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\organisation\models\TblDcsProvisional;
use app\modules\usermanagement\models\User;
use yii\db\Expression;
use app\modules\configuration\models\TblShiftTimeExceed;
use app\modules\organisation\models\TblCustomerMasterProvisional;
use app\modules\details\models\TblContactDetails;
use app\models\GeneralModel;

/**
 * This is the model class for table "tbl_process_approval".
 *
 * @property integer $process_approval_code
 * @property string $process_code
 * @property string $process_name
 * @property string $approval_mode
 * @property integer $level
 * @property string $level_priority
 * @property integer $status
 * @property string $login_type
 * @property string $user_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblProcessApproval extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_process_approval';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//                [['process_approval_code'], 'required'],
                [['level', 'status', 'process_code', 'process_name', 'approval_mode', 'level_priority', 'login_type', 'user_code', 'master_approval_mode', 'remarks', 'department'], 'safe'],
                [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type', 'status_date', 'status_by'], 'safe'],
                [['status'], 'required', 'on' => 'approve'],
                [['status', 'remarks'], 'required', 'on' => 'approvalTabWise'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'process_approval_code' => Yii::t('app', 'Process Approval Code'),
            'process_code' => Yii::t('app', 'Process Code'),
            'process_name' => Yii::t('app', 'Process Name'),
            'approval_mode' => Yii::t('app', 'Approval Mode'),
            'level' => Yii::t('app', 'Level'),
            'level_priority' => Yii::t('app', 'Level Priority'),
            'status' => Yii::t('app', 'Status'),
            'login_type' => Yii::t('app', 'Login Type'),
            'user_code' => Yii::t('app', 'User Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'department' => Yii::t('app', 'Department'),
        ];
    }

    public function getData() {
        return $this->find()
                        ->where(['process_code' => $this->process_code, 'process_name' => $this->process_name])
                        ->all();
    }

    public function getDcsProvisional() {
        return $this->hasOne(TblDcsProvisional::className(), ['dcs_provisional_code' => 'process_code']);
    }

    public function getMemberProvisional() {
        return $this->hasOne(TblMemberProvisional::className(), ['provisional_member_code' => 'process_code']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'user_code']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['user_code' => 'updated_by']);
    }

    public function getApproveLavel($process_name, $status = 0) {
        $login_type = '0';
        if (isset(\Yii::$app->user->identity->login_type) && \Yii::$app->user->identity->login_type != '') {
            $login_type = \Yii::$app->user->identity->login_type;
        }
        $department = '';
        if (isset(\Yii::$app->user->identity->department) && \Yii::$app->user->identity->department != '') {
            $department = \Yii::$app->user->identity->department;
        }
        $subquery = $this::find()
                ->select([
            'process_code',
            'process_name',
            new Expression('MIN(level_priority) AS level_priority'),
            new Expression('MIN(level) AS level')
        ]);
        if ($status == 2) {
            $subquery->andWhere(['process_name' => $process_name, 'level' => 1]);
        } else {
            $subquery->where(['status' => $status, 'process_name' => $process_name]);
        }
        $subquery->groupBy(['process_code', 'process_name']);

        $query = $this::find()
                ->alias('app')
                ->innerJoin(
                        ['pnd' => $subquery], [
                    'pnd.process_code' => new Expression('app.process_code'),
                    'pnd.process_name' => new Expression('app.process_name'),
                    'pnd.level' => new Expression('app.level')
                        ]
                )
                ->where([
            'or',
                ['and', ['app.login_type' => $login_type], ['app.department' => $department]],
                ['app.user_code' => \Yii::$app->user->identity->user_code]
        ]);
        if ($status == 2) {
            $query->andWhere([
                'app.level_priority' => new Expression("CASE WHEN app.approval_mode = 'strict' THEN pnd.level_priority ELSE app.level_priority END"),
            ]);
        } else {
            $query->andWhere([
                'app.level_priority' => new Expression("CASE WHEN app.approval_mode = 'strict' THEN pnd.level_priority ELSE app.level_priority END"),
                'app.status' => $status
            ]);
        }
        return $query;
    }

    public function getShiftTimeExceedProvision() {
        return $this->hasOne(TblShiftTimeExceed::className(), ['shift_time_exceed_code' => 'process_code']);
    }

    public function approvalList($model, &$model_save, &$status, $status_by = '') {
        if ($model->status != '2') {
            $next_count = TblProcessApproval::find()
                    ->where(['process_code' => $model->process_code, 'process_name' => $model->process_name, 'status' => 0])
                    ->andWhere(['<>', 'process_approval_code', $model->process_approval_code]);
            if ($model->approval_mode == 'flexi') {
                $next_count = $next_count->andWhere(['<>', 'level', $model->level]);
                $all_level = TblProcessApproval::find()
                                ->where(['process_code' => $model->process_code, 'status' => 0])
                                ->andWhere(['level' => $model->level])->all();

                foreach ($all_level as $level) {
                    $this->updateApprovalHistory($level, $model_save, $model, $status_by);
                }
            } else {
                $model->status_date = date('Y-m-d H:i:s');
                $model->status_by = !empty($status_by) ? $status_by : \Yii::$app->user->identity->user_code;
                $model_save[] = $model;
            }
            $next_count = $next_count->count();
        } else {
            $all_level = TblProcessApproval::find()
                            ->where(['process_code' => $model->process_code, 'process_name' => $model->process_name, 'status' => 0])->all();
            foreach ($all_level as $level) {
                $this->updateApprovalHistory($level, $model_save, $model, $status_by);
            }
        }
        if ($model->status == '2') {
            $status = 'Reject';
        } else if ($model->status == '1' && $next_count > 0) {
            $status = 'Inprogress';
        } else {
            $status = 'Approve';
        }
    }

    private function updateApprovalHistory($level, &$model_save, $model, $status_by = '') {
        if ($model->process_approval_code != $level->process_approval_code) {
            $approvalHistoryModel = new TblProcessApprovalHistory();
            Yii::$app->operation->history($level, $approvalHistoryModel, 'UPDATE');
            $model_save[] = $approvalHistoryModel;
        }
        $level->status_date = date('Y-m-d H:i:s');
        $level->status_by = (isset($status_by) && !empty($status_by)) ? $status_by : \Yii::$app->user->identity->user_code;
        $level->status = $model->status;
        $level->remarks = $model->remarks;
        $model_save[] = $level;
    }

    public function getCustomerProvisional() {
        return $this->hasOne(TblCustomerMasterProvisional::className(), ['customer_provisional_code' => 'process_code']);
    }

    public function RejectList($model, &$model_save, &$status, $autoCode) {
        $all_level = TblProcessApproval::find()->where(['process_code' => $model->process_code, 'process_name' => $model->process_name])->all();
        foreach ($all_level as $level) {
            if ($model->process_approval_code != $level->process_approval_code) {
                $approvalHistoryModel = new TblProcessApprovalHistory();
                Yii::$app->operation->history($level, $approvalHistoryModel, UPDATE);
                $model_save[] = $approvalHistoryModel;
            }
            $level->process_code = $autoCode;
            $level->status_date = date('Y-m-d H:i:s');
            $level->status_by = \Yii::$app->user->identity->user_code;
            $level->status = ($level->status == 0) ? $model->status : $level->status;
            $level->remarks = $model->remarks;
            $model_save[] = $level;
        }
        $status = 'Reject';
    }

    public function getManualCollectionUserCode() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'user_code']);
    }

    public function getManualCollectionUpdatedBy() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'status_by']);
    }

    public function getDepartmentId() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

    public function handleReroute($model, $process_name, $remarks) {
        $saveModel = [];
        $historyClass = get_class($model) . 'History';
        $historyModel = new $historyClass();
        Yii::$app->operation->history($model, $historyModel, UPDATE);
        $saveModel[] = $historyModel;

        $model->status = 'Reroute';
        $model->remarks = $remarks;
        $model->scenario = 'reroute';
        $saveModel[] = $model;
        if (!$model->validate()) {
            $errors = [];
            foreach ($model->getErrors() as $attrErrors) {
                foreach ($attrErrors as $error) {
                    $errors[] = $error;
                }
            }
            Yii::$app->getSession()->setFlash('error', ['type' => 'error', 'message' => implode('<br/>', $errors)]);
            return false;
        }
        $workflowRequired = Yii::$app->general->getUnionConfiguration($model->union_code, 'workflow_require', 'PORTAL');
        if ($workflowRequired == 1) {
            $primaryKey = (string)$model->getPrimaryKey();
            $approvals = TblProcessApproval::find()->where(['process_code' => $primaryKey, 'process_name' => $process_name])->all();
            if(!empty($approvals)){
                foreach ($approvals as $approval) {
                    $approvalHistory = new TblProcessApprovalHistory();
                    Yii::$app->operation->history($approval, $approvalHistory, UPDATE);
                    $saveModel[] = $approvalHistory;
                    $approval->status = 0;
                    $approval->remarks = NULL;
                    $saveModel[] = $approval;
                }
            }
        }
        $removedTbl = str_replace('tbl_', '', $process_name);
        $msg_string = str_replace('_', ' ', $removedTbl);
        $generalModel = new GeneralModel();
        return $generalModel->saveTransaction($saveModel, [$msg_string.' Reroute', 'edit']);
    }

}
