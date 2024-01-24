<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\UserManagementModule;

class User extends \webvimark\modules\UserManagement\models\User {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'name'], 'required'],
            [['role'], 'required', 'on' => ['newUser']],
            [['username'], 'validateUniqueUsername', 'on' => ['newUser']],
//			['username', 'unique'],
            ['user_code', 'unique'],
            ['username', 'trim'],
            [['status', 'email_confirmed', 'is_active'], 'integer'],
            ['email', 'email', 'except' => ['DeactiveUser']],
            ['email', 'validateEmailConfirmedUnique', 'except' => ['DeactiveUser']],
            ['bind_to_ip', 'validateBindToIp', 'except' => ['DeactiveUser']],
            [['federation', 'mobile_no', 'alert_recipient_group_id', 'user_identity', 'union', 'dcs', 'organizations', 'user_type_id', 'role', 'created_by', 'deleted_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'portal_type', 'device_id', 'allow_app_login', 'department', 'login_type', 'wef_date', 'designation_code', 'primary_parent', 'secondary_parent', 'employee_id'], 'safe'],
            ['bind_to_ip', 'trim'],
            [['bind_to_ip', 'user_code'], 'string', 'max' => 255],
            [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['DeactiveUser']],
            ['password', 'required', 'on' => ['newUser', 'changePassword']],
            ['password', 'string', 'max' => 255, 'on' => ['newUser', 'changePassword']],
//            ['password', 'trim', 'on' => ['newUser', 'changePassword']],
            ['password', 'match', 'pattern' => '/^\S*$/', 'message' => Yii::t('app', 'Space not allowed in Password.')],
            ['repeat_password', 'required', 'on' => ['newUser', 'changePassword']],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
            [['allow_app_login'], 'default', 'value' => 0],
            [['department', 'mobile_no', 'login_type'], 'required', 'when' => function($model) {
                    return $model->allow_app_login == 1;
                }, 'whenClient' => "function (attribute, value) {  if($('#user-allow_app_login').is(':checked')){return true;} }", 'except' => ['DeactiveUser', 'orgMapping']],
            [['mobile_no'], 'unique', 'except' => ['DeactiveUser']],
            [['name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['DeactiveUser']],
            [['wef_date'], 'required', 'on' => ['DeactiveUser']],
            [['username'], 'validateUniqueParent', 'on' => ['newUser', 'userUpdate']],
            [['primary_parent'], 'required', 'when' => function ($model) {
                    return !empty($model->secondary_parent);
                }, 'whenClient' => "function (attribute, value) { 
                            return $('#user-secondary_parent').val() != ''; 
                        }"],
            [['employee_id'], 'string', 'max' => 14],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_code' => UserManagementModule::t('back', 'User Code'),
            'role' => UserManagementModule::t('back', 'Role'),
            'username' => UserManagementModule::t('back', 'User Name'),
            'user_type_id' => UserManagementModule::t('back', 'User Type'),
            'organizations' => UserManagementModule::t('back', 'Organizations'),
            'name' => UserManagementModule::t('back', 'Name'),
            'mobile_no' => UserManagementModule::t('back', 'Mobile No'),
            'alert_recipient_group_id' => UserManagementModule::t('back', 'alert_recipient_group_id'),
            'superadmin' => UserManagementModule::t('back', 'Superadmin'),
            'confirmation_token' => UserManagementModule::t('back', 'Confirmation Token'),
            'registration_ip' => UserManagementModule::t('back', 'Registration IP'),
            'bind_to_ip' => UserManagementModule::t('back', 'Bind to IP'),
            'status' => UserManagementModule::t('back', 'Status'),
            'gridRoleSearch' => UserManagementModule::t('back', 'Roles'),
            'created_at' => UserManagementModule::t('back', 'Created'),
            'is_active' => UserManagementModule::t('back', 'Is Active'),
            'updated_at' => UserManagementModule::t('back', 'Updated'),
            'password' => UserManagementModule::t('back', 'Password'),
            'repeat_password' => UserManagementModule::t('back', 'Repeat password'),
            'email_confirmed' => UserManagementModule::t('back', 'E-mail confirmed'),
            'email' => UserManagementModule::t('back', 'E-mail'),
            'portal_type' => UserManagementModule::t('back', 'Portal Type'),
            'designation_code' => UserManagementModule::t('back', 'Designation'),
            'employee_id' => UserManagementModule::t('back', 'Employee Id'),
        ];
    }

    public function getUserList() {
        $data = $this->find()->where(['portal_type' => 'portal', 'is_active' => 1])->all();
        $list = ArrayHelper::map($data, 'user_code', function($array, $key) {
                    return $array['name'] . '-' . $array['department'];
                });
        return $list;
    }

    public function getLoginDetails() {
        return $this->find()
                        ->where(['is_active' => 1, 'mobile_no' => $this->mobile_no])
                        ->one();
    }

    public function getAssignList($location_type, $code) {
        $userData = $this->find()->alias('u')
                        ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
                        ->where(['tbl_user_organization_mapping.organization_code' => $code, 'tbl_user_organization_mapping.organization_type' => $location_type])
                        ->andWhere(['NOT IN', 'u.login_type', ['vsp', 'farmer', '']])->all();

        $user = ArrayHelper::map($userData, 'id', function($data) {
                    return $data->name . ' (' . $data->mobile_no . '-' . $data->login_type . ')';
                });
        return $user;
    }

    public function getCode() {

        $identity = \app\models\IdentityMaster::find()->one();
        $federation = '00';
        $union = '000';
        $other = '0000';
        if ($identity) {
            switch ($identity->organization_type) {
                case 'Federations' :
                    $federation = $identity->organization_code;
                    break;
                case 'Unions' :
                    $federation = $identity->parent_code;
                    $union = $identity->organization_code;
                    break;
            }

            $code = $federation . $union . $other;

            $val = (new \yii\db\Query)
                    ->select(["MAX(convert(int,id)) as id"])
                    ->from('user')
                    ->one();
            $newcode = (int) $val['id'] + 1;


            $value = $code . str_pad($newcode, 5, '0', STR_PAD_LEFT);
            return $value;
        }
    }

}
