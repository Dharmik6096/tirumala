<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_app_user_history".
 *
 * @property integer $id
 * @property string $eipl_app_user_code
 * @property string $auth_key
 * @property string $bind_to_ip
 * @property string $confirmation_token
 * @property string $created_at
 * @property string $email
 * @property integer $email_confirmed
 * @property integer $is_active
 * @property string $mobile_no
 * @property string $name
 * @property string $password_hash
 * @property string $portal_type
 * @property string $registration_ip
 * @property integer $status
 * @property integer $superadmin
 * @property string $updated_at
 * @property string $user_code
 * @property string $user_identity
 * @property string $username
 * @property integer $alert_recipient_group_id
 * @property string $created_by
 * @property string $updated_by
 * @property integer $user_type_id
 * @property string $device_id
 * @property integer $allow_app_login
 * @property string $department
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblEiplAppUserHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_user_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['eipl_app_user_code', 'user_code', 'created_by', 'updated_by', 'auth_key', 'portal_type', 'bind_to_ip', 'confirmation_token', 'mobile_no', 'name', 'user_identity', 'username', 'email', 'password_hash', 'department', 'registration_ip', 'history_created_by', 'operation_type', 'device_id', 'created_at', 'updated_at', 'history_created_at', 'email_confirmed', 'is_active', 'status', 'superadmin', 'alert_recipient_group_id', 'user_type_id', 'allow_app_login'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'eipl_app_user_code' => Yii::t('app', 'Eipl App User Code'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'bind_to_ip' => Yii::t('app', 'Bind To Ip'),
            'confirmation_token' => Yii::t('app', 'Confirmation Token'),
            'created_at' => Yii::t('app', 'Created At'),
            'email' => Yii::t('app', 'Email'),
            'email_confirmed' => Yii::t('app', 'Email Confirmed'),
            'is_active' => Yii::t('app', 'Is Active'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'name' => Yii::t('app', 'Name'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'portal_type' => Yii::t('app', 'Portal Type'),
            'registration_ip' => Yii::t('app', 'Registration Ip'),
            'status' => Yii::t('app', 'Status'),
            'superadmin' => Yii::t('app', 'Superadmin'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_code' => Yii::t('app', 'User Code'),
            'user_identity' => Yii::t('app', 'User Identity'),
            'username' => Yii::t('app', 'Username'),
            'alert_recipient_group_id' => Yii::t('app', 'Alert Recipient Group ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'user_type_id' => Yii::t('app', 'User Type ID'),
            'device_id' => Yii::t('app', 'Device ID'),
            'allow_app_login' => Yii::t('app', 'Allow App Login'),
            'department' => Yii::t('app', 'Department'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
