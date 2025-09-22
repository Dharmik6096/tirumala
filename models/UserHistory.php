<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_history".
 *
 * @property integer $id
 * @property string $user_history_id
 * @property string $auth_key
 * @property string $bind_to_ip
 * @property string $confirmation_token
 * @property string $created_at
 * @property string $deleted_at
 * @property string $email
 * @property integer $email_confirmed
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $name
 * @property string $password_hash
 * @property string $registration_ip
 * @property integer $status
 * @property integer $superadmin
 * @property integer $alert_recipient_group_id
 * @property string $mobile_no
 * @property string $updated_at
 * @property string $user_code
 * @property string $username
 * @property string $created_by
 * @property string $deleted_by
 * @property string $updated_by
 * @property integer $user_type_id
 * @property string $portal_type
 * @property string $user_identity
 * @property string $history_created_at
 * @property string $operation_type
 */
class UserHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'deleted_at', 'updated_at', 'history_created_at', 'id', 'mobile_no', 'alert_recipient_group_id', 'device_id', 'allow_app_login', 'department', 'wef_date', 'employee_id', 'date_of_joining', 'login_type', 'designation_code', 'primary_parent', 'secondary_parent', 'language_code', 'is_engineer', 'last_password_updated_at', 'max_login_attempts', 'suspension_datetime'], 'safe'],
                [['email_confirmed', 'is_active', 'status', 'superadmin', 'user_type_id'], 'integer'],
                [['user_history_id', 'user_code', 'created_by', 'updated_by'], 'string', 'max' => 14],
                [['auth_key'], 'string', 'max' => 50],
                [['bind_to_ip', 'confirmation_token', 'name', 'username', 'user_identity'], 'string', 'max' => 255],
                [['email'], 'string', 'max' => 128],
                [['password_hash'], 'string', 'max' => 100],
                [['registration_ip', 'portal_type'], 'string', 'max' => 15],
                [['operation_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_history_id' => Yii::t('app', 'User ID'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'bind_to_ip' => Yii::t('app', 'Bind To Ip'),
            'confirmation_token' => Yii::t('app', 'Confirmation Token'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'email' => Yii::t('app', 'Email'),
            'email_confirmed' => Yii::t('app', 'Email Confirmed'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'alert_recipient_group_id' => Yii::t('app', 'alert_recipient_group_id'),
            'name' => Yii::t('app', 'Name'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'registration_ip' => Yii::t('app', 'Registration Ip'),
            'status' => Yii::t('app', 'Status'),
            'superadmin' => Yii::t('app', 'Superadmin'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_code' => Yii::t('app', 'User Code'),
            'username' => Yii::t('app', 'Username'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'user_type_id' => Yii::t('app', 'User Type ID'),
            'portal_type' => Yii::t('app', 'Portal Type'),
            'user_identity' => Yii::t('app', 'User Identity'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return UserHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new UserHistoryQuery(get_called_class());
    }

}
