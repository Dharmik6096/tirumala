<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_approval_stages_process".
 *
 * @property integer $approval_process_code
 * @property string $union_code
 * @property string $process_name
 * @property string $process_desc
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblApprovalStagesProcess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_approval_stages_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['process_name'], 'string', 'max' => 55],
            [['process_desc'], 'string', 'max' => 255],
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
            'approval_process_code' => Yii::t('app', 'Approval Process Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'process_name' => Yii::t('app', 'Process Name'),
            'process_desc' => Yii::t('app', 'Process Desc'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
