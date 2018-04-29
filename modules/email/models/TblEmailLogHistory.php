<?php

namespace app\modules\email\models;

use Yii;

/**
 * This is the model class for table "tbl_email_log_history".
 *
 * @property integer $id
 * @property integer $email_log_id
 * @property integer $email_rule_master_id
 * @property string $email
 * @property string $mobile
 * @property string $date
 * @property integer $sent_count
 * @property integer $total_count
 * @property string $next_datetime
 * @property string $current_datetime
 * @property integer $frequency
 * @property integer $is_processed
 * @property string $union_code
 * @property string $email_subject
 * @property string $email_body
 * @property string $message
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblEmailLogHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_email_log_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_log_id'], 'required'],
            [['email_log_id', 'email_rule_master_id', 'sent_count', 'total_count', 'frequency', 'is_processed'], 'integer'],
            [['email', 'mobile', 'union_code', 'email_subject', 'email_body', 'message', 'created_by', 'updated_by', 'operation_type'], 'string'],
            [['to_date', 'from_date', 'next_datetime', 'current_datetime', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email_log_id' => Yii::t('app', 'Email Log ID'),
            'email_rule_master_id' => Yii::t('app', 'Email Rule Master ID'),
            'email' => Yii::t('app', 'Email'),
            'mobile' => Yii::t('app', 'Mobile'),
            'to_date' => Yii::t('app', 'To Date'),
            'from_date' => Yii::t('app', 'From Date'),
            'sent_count' => Yii::t('app', 'Sent Count'),
            'total_count' => Yii::t('app', 'Total Count'),
            'next_datetime' => Yii::t('app', 'Next Datetime'),
            'current_datetime' => Yii::t('app', 'Current Datetime'),
            'frequency' => Yii::t('app', 'Frequency'),
            'is_processed' => Yii::t('app', 'Is Processed'),
            'union_code' => Yii::t('app', 'Union Code'),
            'email_subject' => Yii::t('app', 'Email Subject'),
            'email_body' => Yii::t('app', 'Email Body'),
            'message' => Yii::t('app', 'Message'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
