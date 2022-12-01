<?php

namespace app\modules\welfarescheme\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_approval_stages".
 *
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
class TblSchemeApprovalStages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_scheme_approval_stages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scheme_id', 'level', 'originating_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_code'], 'string', 'max' => 20],
            [['approval_mode'], 'string', 'max' => 10],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
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
