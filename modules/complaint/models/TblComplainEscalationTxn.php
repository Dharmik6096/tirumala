<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\complaint\models\TblComplainType;
use app\modules\usermanagement\models\User;
use app\modules\complaint\models\TblComplain;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\complaint\models\TblComplainEscalation;
use app\modules\complaint\models\TblComplainEscalationTxnDetail;

/**
 * This is the model class for table "tbl_complain_escalation_txn".
 *
 * @property integer $complain_escalation_txn_code
 * @property integer $complain_escalation_code
 * @property string $user_type
 * @property integer $escalation_time
 * @property integer $level
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblComplainEscalationTxn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_escalation_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_type', 'complain_escalation_code', 'escalation_time', 'level', 'originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['user_type', 'escalation_time', 'level'], 'required'],
                [['user_type', 'level'], 'checkUnique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complain_escalation_txn_code' => Yii::t('app', 'Complain Escalation Txn Code'),
            'complain_escalation_code' => Yii::t('app', 'Complain Escalation Code'),
            'user_type' => Yii::t('app', 'User Type'),
            'escalation_time' => Yii::t('app', 'Escalation Time (In Minute)'),
            'level' => Yii::t('app', 'Level'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function checkUnique($attribute, $params) {
        $where = '';
        if ($attribute == 'user_type') {
            $where = ['complain_escalation_code' => $this->complain_escalation_code, 'user_type' => $this->user_type];
        } else {
            $where = ['complain_escalation_code' => $this->complain_escalation_code, 'level' => $this->level];
        }
        $result = $this->find()->where($where)->one();
        if (!empty($result)) {
            $this->addError($attribute, Yii::t('app/validation', 'Already exist'));
        }
    }

    public function setApprovalData($complain_type_code, $unionCode, $codes, &$modelSave, &$user_code, $location_type) {
        $complainType = new TblComplainType();
        $approval_stages = $complainType->levelStages($complain_type_code);
        $i = 1;
        foreach ($approval_stages as $key => $stage) {
            $stage_model = new TblComplainEscalationTxnDetail();
            $stage_model->setAttributes($stage);
            $user = new User();
            $userMapp = $user->setUserCode($stage_model->user_type, $location_type, $codes);

            if (!empty($userMapp['user_code'])) {
                $stage_model->user_code = $userMapp['user_code'];
            }
            $stage_model->union_code = $unionCode;
            $stage_model->status = 'pending';
            $stage_model->cron_status = '-1';

            if ($key == 0) {
                if (!empty($userMapp['user_code'])) {
                    $user_code = $userMapp['user_code'];
                }
                $stage_model->status = 'Allocated';
                $stage_model->cron_status = '0';
                $stage_model->assign_date = date('Y-m-d H:i:s');
            }
            $appLoginModel = new TblEiplAppLogin();
            $appLoginModel->module_code = $stage_model->user_code;
            $appLoginModel->module_type = 'TblContactDetails';
            $appLoginModel->app_type = 1;
            if (!empty($userMapp['user_code'])) {
                $contactDetail = $user->getContactCode($userMapp['user_code']);
            }
            $appLoginModel->mobile_no = !empty($contactDetail) && $contactDetail['mobile_no'] != 'N/A' ? $contactDetail['mobile_no'] : '';
            $appLoginModelData = $appLoginModel->getLoginDetails();
            if (!empty($appLoginModelData->device_id)) {
                $stage_model->device_id = $appLoginModelData->device_id;
            }
            unset($stage_model->created_at);
            unset($stage_model->created_by);

            $modelSave[] = $stage_model;
            $i++;
        }
    }

}
