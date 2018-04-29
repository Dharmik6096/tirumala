<?php

namespace app\modules\email\models;

use Yii;

/**
 * This is the model class for table "tbl_email_rule_master_history".
 *
 * @property integer $id
 * @property integer $email_rule_master_id
 * @property integer $rule_id
 * @property integer $frequency
 * @property integer $interval
 * @property integer $no_of_email
 * @property string $email
 * @property string $mobile
 * @property string $message
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property string $history_created_at
 */
class TblEmailRuleMasterHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_email_rule_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_rule_master_id', 'rule_id', 'frequency', 'interval', 'no_of_email', 'is_active','email_subject','email_body'], 'safe'],
            [['email', 'mobile', 'message', 'union_code', 'created_by', 'updated_by'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email_rule_master_id' => Yii::t('app', 'Email Rule Master ID'),
            'rule_id' => Yii::t('app', 'Rule ID'),
            'frequency' => Yii::t('app', 'Frequency'),
            'interval' => Yii::t('app', 'Interval'),
            'no_of_email' => Yii::t('app', 'No Of Email'),
            'email' => Yii::t('app', 'Email'),
            'mobile' => Yii::t('app', 'Mobile'),
            'email_subject' => Yii::t('app', 'Email Subject'),
            'email_body' => Yii::t('app', 'Email Body'),
            'message' => Yii::t('app', 'Message'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }
}
