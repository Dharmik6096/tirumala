<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\general\models\TblDepartment;
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
                [['user_type', 'complain_escalation_code', 'escalation_time', 'level', 'originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'department'], 'safe'],
                [['user_type', 'escalation_time', 'level', 'department'], 'required'],
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
            $where = ['complain_escalation_code' => $this->complain_escalation_code, 'user_type' => $this->user_type, 'department' => $this->department];
        } else {
            $where = ['complain_escalation_code' => $this->complain_escalation_code, 'level' => $this->level];
        }
        $result = $this->find()->where($where)->one();
        if (!empty($result)) {
            $this->addError($attribute, Yii::t('app/validation', 'Already exist'));
        }
    }

    public function getDepartmentId() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

}
