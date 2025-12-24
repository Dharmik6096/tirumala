<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_approval_stages_detail_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblApprovalStagesDetailHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_approval_stages_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['approval_stages_detail_code', 'approval_stages_code', 'level', 'originating_type'], 'integer'],
            [['created_at', 'updated_at', 'history_created_at', 'department'], 'safe'],
            [['level_priority', 'approval_mode', 'operation_type'], 'string', 'max' => 10],
            [['approval_type', 'login_type', 'user_code'], 'string', 'max' => 255],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'approval_stages_detail_code' => Yii::t('app', 'Approval Stages Detail Code'),
            'approval_stages_code' => Yii::t('app', 'Approval Stages Code'),
            'level' => Yii::t('app', 'Level'),
            'level_priority' => Yii::t('app', 'Level Priority'),
            'approval_mode' => Yii::t('app', 'Approval Mode'),
            'approval_type' => Yii::t('app', 'Approval Type'),
            'login_type' => Yii::t('app', 'Login Type'),
            'user_code' => Yii::t('app', 'User Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'department' => Yii::t('app', 'Department'),
        ];
    }
}
