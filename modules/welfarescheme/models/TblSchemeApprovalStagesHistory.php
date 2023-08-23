<?php

namespace app\modules\welfarescheme\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_approval_stages_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $stage_id
 * @property integer $scheme_id
 * @property integer $level
 * @property string $user_code
 * @property string $approval_mode
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeApprovalStagesHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_scheme_approval_stages_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['stage_id', 'scheme_id', 'level', 'originating_type'], 'integer'],
            [['operation_type', 'approval_mode'], 'string', 'max' => 10],
            [['history_created_by', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['user_code'], 'string', 'max' => 20],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
            'stage_id' => 'Stage ID',
            'scheme_id' => 'Scheme ID',
            'level' => 'Level',
            'user_code' => 'User Code',
            'approval_mode' => 'Approval Mode',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
        ];
    }
}
