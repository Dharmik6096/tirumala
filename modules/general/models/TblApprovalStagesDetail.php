<?php

namespace app\modules\general\models;

use Yii;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_approval_stages_detail".
 *
 * @property integer $approval_stages_detail_code
 * @property integer $approval_stages_code
 * @property integer $level
 * @property string $level_priority
 * @property string $approval_mode
 * @property string $approval_type
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
class TblApprovalStagesDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_approval_stages_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['approval_stages_code'], 'integer'],
                [['approval_type', 'level', 'approval_mode'], 'required'],
                [['approval_type', 'login_type', 'user_code', 'level', 'level_priority', 'approval_mode', 'department'], 'safe'],
                [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['user_code'], 'required', 'when' => function ($model) {
                    return $model->approval_type == 1;
                },
                'whenClient' => "function (attribute, value) { return $('#tblapprovalstagesdetail-approval_type').val() == '1' }"
            ],
                [['login_type', 'department'], 'required', 'when' => function ($model) {
                    return $model->approval_type == 2;
                },
                'whenClient' => "function (attribute, value) { return $('#tblapprovalstagesdetail-approval_type').val() == '2' }"
            ],
                [['approval_mode'], 'validatePaymentMode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'approval_stages_detail_code' => Yii::t('app', 'Approval Stages Detail Code'),
            'approval_stages_code' => Yii::t('app', 'Approval Stages Code'),
            'level' => Yii::t('app', 'Level'),
            'level_priority' => Yii::t('app', 'Level Priority'),
            'approval_mode' => Yii::t('app', 'Approval Mode'),
            'approval_type' => Yii::t('app', 'Approval Type'),
            'login_type' => Yii::t('app', 'Login Type'),
            'user_code' => Yii::t('app', 'User'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'department' => Yii::t('app', 'Department'),
        ];
    }

    public function validatePaymentMode($attribute, $params) {
        $existData = $this->existingLevel();
        if ((!empty($existData)) && (!empty($existData->approval_mode) && ($existData->approval_mode != $this->approval_mode))) {
            $this->addError('approval_mode', Yii::t('app/validation', $this->getAttributeLabel('approval_mode') . ' Must Same in level'));
        }
    }

    public function existingLevel() {
        return $this->find()->where(['level' => $this->level, 'approval_stages_code' => $this->approval_stages_code])
                        ->orderBy('created_at desc')
                        ->one();
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'user_code']);
    }

    public function getApprovalStagesCode() {
        return $this->hasOne(TblApprovalStages::className(), ['approval_stages_code' => 'approval_stages_code']);
    }

    public function approvalStages($union, $process) {
        return $this->find()
                        ->select(['tbl_approval_stages_detail.*', 'tbl_approval_stages.approval_mode as master_approval_mode'])
                        ->joinWith(['approvalStagesCode'])
                        ->where(['tbl_approval_stages.process_name' => $process, 'tbl_approval_stages.union_code' => $union])
                        ->orderBy('tbl_approval_stages_detail.level asc')
                        ->asArray()
                        ->all();
    }

    public function setApprovalData($union_code, $process, $processCode, &$modelSave, &$approval_stages, $create_level = FALSE) {
        $approval_stages = $this->approvalStages($union_code, $process);
        $processCode = (string) $processCode;
        $existDataApproval = TblProcessApproval::find()->where(['process_code' => $processCode, 'process_name' => $process])->count();
        if ($existDataApproval == 0 || $create_level) {
            $i = 1;
            foreach ($approval_stages as $stage) {
                $stage_model = new TblProcessApproval();
                $stage_model->setAttributes($stage);
                //  $stage_model->process_approval_code = Yii::$app->general->getCodeAutoIncrement($stage_model, $i);
                $stage_model->process_code = $processCode;
                $stage_model->process_name = $process;
                $stage_model->status = 0;
                unset($stage_model->created_at);
                unset($stage_model->created_by);
                $modelSave[] = $stage_model;
                $i++;
            }
        }
    }

    public function getLevelList($union, $process) {
        $maxdata = $this->find()->select(['level' => "ISNULL(MAX(CAST(tbl_approval_stages_detail.level AS INT)),0)+1"])
                ->join('INNER JOIN', 'tbl_approval_stages', 'tbl_approval_stages.approval_stages_code = tbl_approval_stages_detail.approval_stages_code')
                ->where(['tbl_approval_stages.process_name' => $process, 'tbl_approval_stages.union_code' => $union])
                ->asArray()
                ->one();

        $levels = [];
        for ($i = 1; $i <= $maxdata['level']; $i++) {
            $levels[$i] = $i;
        }
        return $levels;
    }

    public function setProcessWiseApprovalData($approvalModel, $unionCode, $processName, &$saveModel, &$auto_key_config, &$i, $processFlag = FALSE, $parent_key = '', $created_by = '', $ManualCollectionComplain = false) {
        $approvalStage = $this->approvalStages($unionCode, $processName);
        $errorMessage = '';
        $approvalModel->approval_status = 'Pending';
        $saveModel[] = $approvalModel;
        if ($ManualCollectionComplain) {
            $i++;
            $auto_key_config[$i] = ['self_key' => 'complain_code', 'parent_key' => 'complain_code', 'parent_index' => 0];
        }
        $parent_index = $i;
        if (!empty($approvalStage)) {
            foreach ($approvalStage as $key => $stage) {
                $i++;
                $stage_model = new TblProcessApproval();
                $stage_model->setAttributes($stage);
                $stage_model->process_name = $processName;
                $stage_model->status = 0;
                unset($stage_model->created_at);
                unset($stage_model->created_by);
                if (isset($created_by) && !empty($created_by)) {
                    $stage_model->originating_org_type = 'MOBILE';
                    $stage_model->created_by = $created_by;
                }
                $saveModel[] = $stage_model;
                if ($processFlag) {
                    $auto_key_config[$i] = ['self_key' => 'process_code', 'parent_key' => $parent_key, 'parent_index' => $parent_index];
                }
            }
        } else {
            $errorMessage = "No approval stages found.";
        }
    }

    public function getDepartmentId() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

}
