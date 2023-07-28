<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\welfarescheme\models\TblSchemeMaster;
use app\modules\usermanagement\models\User;

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
class TblSchemeApprovalStages extends \app\models\ChildModel {

    public $old_approval_mode;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_approval_stages';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_id', 'level', 'approval_mode', 'user_code'], 'required'],
                [['scheme_id', 'level', 'originating_type'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
                [['approval_mode'], 'string', 'max' => 10],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['user_code'], 'unique', 'targetAttribute' => ['scheme_id', 'level', 'user_code']],
                [['approval_mode'], 'unique', 'targetAttribute' => ['scheme_id', 'level', 'old_approval_mode' => 'approval_mode']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'stage_id' => 'Stage ID',
            'scheme_id' => 'Scheme ID',
            'level' => 'Level',
            'user_code' => 'User',
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

    public function getSchemeId() {
        return $this->hasOne(TblSchemeMaster::className(), ['scheme_id' => 'scheme_id']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'user_code']);
    }

}
