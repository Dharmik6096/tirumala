<?php

namespace app\modules\general\models;

use Yii;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\organisation\models\TblDcsProvisional;
use app\modules\usermanagement\models\User;
use yii\db\Expression;
use app\modules\configuration\models\TblShiftTimeExceed;

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
                [['level', 'status', 'process_code', 'process_name', 'approval_mode', 'level_priority', 'login_type', 'user_code', 'master_approval_mode', 'remarks'], 'safe'],
                [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['status'], 'required', 'on' => 'approve'],
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

    public function getApproveLavel($process_name) {
        $login_type = '0';
        if (isset(\Yii::$app->user->identity->login_type) && \Yii::$app->user->identity->login_type != '') {
            $login_type = \Yii::$app->user->identity->login_type;
        }
        $subquery = $this::find()
                ->select([
                    'process_code',
                    'process_name',
                    new Expression('MIN(level_priority) AS level_priority'),
                    new Expression('MIN(level) AS level')
                ])
                ->where(['status' => 0, 'process_name' => $process_name])
                ->groupBy(['process_code', 'process_name']);

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
                        ['app.login_type' => $login_type],
                        ['app.user_code' => \Yii::$app->user->identity->user_code]
                ])
                ->andWhere([
            'app.level_priority' => new Expression("CASE WHEN app.approval_mode = 'strict' THEN pnd.level_priority ELSE app.level_priority END"),
            'app.status' => 0
        ]);
        return $query;
    }

    public function getShiftTimeExceedProvision() {
        return $this->hasOne(TblShiftTimeExceed::className(), ['shift_time_exceed_code' => 'process_code']);
    }

}
