<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblShift;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;

/**
 * This is the model class for table "tbl_allow_manual_collection_range".
 *
 * @property integer $allow_manual_collection_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $from_shift
 * @property string $to_date
 * @property string $to_shift
 * @property integer $is_weight_manual
 * @property integer $is_quality_manual
 * @property string $remark
 * @property integer $is_approved
 * @property string $entry_type
 * @property string $table_name
 * @property string $application_type
 * @property string $approved_at
 * @property string $approved_by
 * @property string $approval_status
 * @property string $complain_type
 * @property integer $complain_status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAllowManualCollectionRange extends \app\models\ChildModel {

    public $from_date_real, $to_date_real, $operation, $process_approval_code, $from_date_back, $to_date_back, $approve_remarks;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_allow_manual_collection_range';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'entry_type', 'approval_status', 'from_shift', 'to_shift', 'table_name', 'application_type', 'complain_type', 'is_weight_manual', 'is_quality_manual', 'is_approved', 'complain_status', 'originating_type', 'approved_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'from_date', 'to_date', 'approved_at', 'created_at', 'remark', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'updated_at', 'from_date_real', 'to_date_real', ' process_approval_code', 'operation', 'from_date_back', 'to_date_back', 'approve_remarks', 'action_perform'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'entry_type', 'from_shift', 'to_shift', 'from_date', 'to_date', 'table_name'], 'required', 'on' => ['create', 'hosync']],
                [['from_date'], 'checkUnique', 'skipOnError' => true, 'on' => ['create', 'hosync']],
                [['dcs_code'], 'required', 'when' => function ($model) {
                    return $model->table_name == 'tbl_milk_collection';
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblallowmanualcollectionrange-table_name').val() == 'tbl_milk_collection'; 
                    }", 'on' => ['create', 'hosync']
            ],
                [['application_type'], 'default', 'value' => 'MOBILE'],
                [['action_perform'], 'default', 'value' => 'CREATE'],
                [['to_date'], 'checkUniqueDate', 'skipOnError' => true, 'on' => ['create', 'hosync']],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        if ($this->table_name == 'tbl_milk_collection') {
                            $flag = ['data_lock_member', 'billing_lock_member', 'sync_lock_member'];
                            $type = 'DCS';
                        }
                        if ($this->table_name == 'tbl_bmc_collection') {
                            $flag = ['data_lock_bmc', 'billing_lock_bmc', 'sync_lock_bmc'];
                            $type = 'DCS';
                        }
                        Yii::$app->general->paymentCycleLock($this, 'from_date', 'bmc_code', 'BMC', $type, $flag);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create', 'hosync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'allow_manual_collection_code' => Yii::t('app', 'Allow Manual Collection Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_date_real' => Yii::t('app', 'From Date'),
            'from_date_back' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_date_real' => Yii::t('app', 'To Date'),
            'to_date_back' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'remark' => Yii::t('app', 'Remark'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'table_name' => Yii::t('app', 'Process Name'),
            'application_type' => Yii::t('app', 'Application Type'),
            'approved_at' => Yii::t('app', 'Approved At'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approval_status' => Yii::t('app', 'Approval Status'),
            'complain_type' => Yii::t('app', 'Complain Type'),
            'complain_status' => Yii::t('app', 'Complain Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
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

    public function getFromShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

    public function checkUnique($attribute, $params) {
        if ($this->from_date > $this->to_date) {
            $this->addError($attribute, Yii::t('app/validation', 'Invalid Date Range'));
        }
        if (empty($this->is_weight_manual) && empty($this->is_quality_manual)) {
            $this->addError('is_quality_manual', Yii::t('app/validation', 'Please Check Any One Check Box - Weight Manual OR Quality Manual'));
        }
    }

    public function setChildTable(&$model, &$modelSave, &$childModel, &$auto_key_config) {
        $login_data = Yii::$app->eiplapp->identity;
        $created_by = !empty($login_data['module_code']) ? $login_data['module_code'] : '';

        $model->scenario = 'hosync';
        $model->is_approved = 0;
        $model->from_date = empty($model->from_date) ? NULL : Yii::$app->controls->view_date($model->from_date, 'php:Y-m-d') . ' ' . Yii::$app->general->getshift($model->from_shift);
        $model->to_date = empty($model->to_date) ? NULL : Yii::$app->controls->view_date($model->to_date, 'php:Y-m-d') . ' ' . Yii::$app->general->getshift($model->to_shift);

        $config_key = 'manual_collection_request_approval_' . $model->entry_type;
        $manualCollectionConfig = Yii::$app->general->getUnionConfiguration($model->union_code, $config_key, 'PORTAL');
        if (in_array($manualCollectionConfig, [1, 2])) {
            if ($manualCollectionConfig == 2) {
                $i = 0;
                $modelStages = new TblApprovalStagesDetail();
                $modelStages->setProcessWiseApprovalData($model, $model->union_code, 'tbl_allow_manual_collection_range', $childModel, $auto_key_config, $i, true, 'allow_manual_collection_code', $created_by);
            } else {
                $model->approval_status = 'Pending';
                $childModel[] = $model;
            }
        } else {
            $model->is_approved = 1;
            $model->approval_status = 'Approve';
            $model->approved_at = date('Y-m-d H:i:s');
            $childModel[] = $model;
            //$this->model->approved_at = date('Y-m-d H:i:s');
            //$this->model->approved_by = Yii::$app->session['UserCode'];
        }
    }

    public function setChildTableOther(&$model, $transaction_data, &$childModel) {
        $request_model = $model;
        $login_data = Yii::$app->eiplapp->identity;
        $model->allow_manual_collection_code = $transaction_data['content']['allow_manual_collection_code'];
        $manualCollectionData = $this->find()->where(['allow_manual_collection_code' => $model->allow_manual_collection_code])->one();
        $config_key = 'manual_collection_request_approval_' . $manualCollectionData->entry_type;
        $manualCollectionConfig = Yii::$app->general->getUnionConfiguration($manualCollectionData->union_code, $config_key, 'PORTAL');
        $approval_code = !empty($transaction_data['content']['process_approval_code']) ? $transaction_data['content']['process_approval_code'] : '';
        $status_by = !empty($login_data['module_code']) ? $login_data['module_code'] : '';

        $historyModel = new TblAllowManualCollectionRangeHistory();
        Yii::$app->operation->history($manualCollectionData, $historyModel, 'UPDATE');
        $childModel[] = $historyModel;
        $manualCollectionData->attributes = $request_model->toArray();
        $status = '';
        if (strtolower($model->approval_status) == 'approve') {
            if ($manualCollectionConfig == 2 && !empty($approval_code)) {
                $status = 1;
                $this->updateApprovalHistory($approval_code, $childModel, $status, $model->remark, $status_by);
            }
            if ($manualCollectionConfig == 2) {
                $manualCollectionData->approval_status = $status;
                $manualCollectionData->approved_at = date('Y-m-d H:i:s');
                $manualCollectionData->approved_by = $status_by;
                $manualCollectionData->remark = $model->remark;
            }
            if (strtolower($model->approval_status) == 'approve' && empty($approval_code)) {
                $manualCollectionData->approval_status = 'Approve';
                $manualCollectionData->approved_at = date('Y-m-d H:i:s');
                $manualCollectionData->approved_by = $status_by;
                $manualCollectionData->remark = $model->remark;
            }
            if (strtolower($model->approval_status) == 'approve' || strtolower($manualCollectionData->approval_status) == 'approve') {
                $manualCollectionData->is_approved = 1;
            }
            $manualCollectionData->originating_org_type = 'MOBILE';
            $manualCollectionData->originating_org_code = $status_by;
            $manualCollectionData->updated_by = $status_by;
        } else if (strtolower($model->approval_status) == 'reject') {
            if ($manualCollectionConfig == 2) {
                $status = 2;
                $this->updateApprovalHistory($approval_code, $childModel, $status, $manualCollectionData->remark, $status_by);
            }
            if (strtolower($status) == 'reject' || empty($approval_code)) {
                $manualCollectionData->approval_status = 'Reject';
                $manualCollectionData->approved_at = date('Y-m-d H:i:s');
                $manualCollectionData->approved_by = $status_by;
            }
            $manualCollectionData->remark = $model->remark;
        }
        $model = $manualCollectionData;
    }

    public function updateApprovalHistory($approval_code, &$childModel, &$status, $remarks = '', $status_by = '') {
        $approvalModel = TblProcessApproval::findOne($approval_code);
        if ($approvalModel) {
            $historyApproval = new TblProcessApprovalHistory();
            Yii::$app->operation->history($approvalModel, $historyApproval, 'UPDATE');
            $childModel[] = $historyApproval;
            $approvalModel->status = $status;
            $approvalModel->remarks = $remarks;
            $approvalModel->originating_org_type = 'MOBILE';
            $childModel[] = $approvalModel;
            $approvalModel->ApprovalList($approvalModel, $childModel, $status, $status_by);
        }
    }

    public function getcollectionApproval() {
        $this->allow_manual_collection_code = (string) $this->allow_manual_collection_code;
        return $this->hasMany(TblProcessApproval::className(), ['process_code' => 'allow_manual_collection_code'])->orderBy('level ASC');
    }

    public function checkUniqueDate($attribute, $params) {
        if ($this->from_date > $this->to_date) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
            return false;
        }

        $approvalStatus = ($this->from_date == $this->to_date) ? ['Pending', 'Inprogress', 'Approve'] : ['Pending', 'Inprogress'];
        $dataCheck = $this->find()
                ->where(['in', 'approval_status', $approvalStatus])
                ->andWhere('((\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))');

        if ($this->table_name == 'tbl_milk_collection') {
            $dataCheck->andWhere(['dcs_code' => $this->dcs_code]);
        } else {
            $dataCheck->andWhere(['bmc_code' => $this->bmc_code]);
            $dataCheck->andWhere(['or', ['dcs_code' => NULL], ['dcs_code' => '']]);
        }
        if ($this->action_perform == 'CREATE' || $this->action_perform == 'MODIFY') {
            $dataCheck->andWhere(['action_perform' => $this->action_perform]);
            $dataCount = $dataCheck->count();
            if ($dataCount >= 1) {
                $this->addError($attribute, Yii::t('app/validation', 'This date range data already in pending request'));
                return false;
            }
        }
        return true;
    }

}
