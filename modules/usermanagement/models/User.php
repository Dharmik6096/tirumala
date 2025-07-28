<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\UserManagementModule;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblFederations;

class User extends \webvimark\modules\UserManagement\models\User {

    public $otp_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'name'], 'required'],
            [['role'], 'required', 'on' => ['newUser']],
            [['username'], 'validateUniqueUsername', 'on' => ['newUser']],
//			['username', 'unique'],
            [['user_code', 'employee_id'], 'unique'],
            ['username', 'trim'],
            [['status', 'email_confirmed', 'is_active'], 'integer'],
            ['email', 'email', 'except' => ['DeactiveUser']],
            ['email', 'validateEmailConfirmedUnique', 'except' => ['DeactiveUser']],
            ['bind_to_ip', 'validateBindToIp', 'except' => ['DeactiveUser']],
            [['federation', 'mobile_no', 'alert_recipient_group_id', 'user_identity', 'union', 'dcs', 'organizations', 'user_type_id', 'role', 'created_by', 'deleted_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'portal_type', 'device_id', 'allow_app_login', 'department', 'login_type', 'wef_date', 'designation_code', 'primary_parent', 'secondary_parent', 'employee_id', 'otp_code', 'last_password_updated_at', 'date_of_joining', 'max_login_attempts', 'suspension_datetime'], 'safe'],
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
            ['password', 'validatePasswordStrength', 'on' => ['newUser', 'passwordReset']],
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
            [['username', 'password', 'repeat_password', 'otp_code'], 'required', 'on' => 'verifyOtp'],
            [['username'], 'required', 'on' => 'forgetPsd'],
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
            'date_of_joining' => UserManagementModule::t('back', 'Date Of Joining'),
        ];
    }

    public function getUserList() {
        $data = $this->find()->where(['portal_type' => 'portal', 'is_active' => 1])->all();
        $list = ArrayHelper::map($data, 'user_code', function($array, $key) {
                    return $array['name'] . '-' . $array['department'];
                });
        return $list;
    }

    public function validatePasswordStrength($attribute, $params) {
         if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $this->$attribute)) {
             $this->addError($attribute, 'Password must be at least 8 characters long and include at least one letter, one number, and one special character.');
         } 
        if ($this->scenario === 'passwordReset') {
            if($this->validatePassword($this->password)){
                $this->addError('password', 'New password cannot be the same as the old password.');
                return;
            }
            if($this->password === preg_replace('/^01#/', '', $this->username)){
                $this->addError('password', 'New password cannot be the same as the username.');
                return;
            }
        }
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

    public function getTaskUserSelection($location_type, $mcccode, $bmccode) {
        $model = new TblDcs();
        $value = $model->getBMCDCS($bmccode);
        $values = ArrayHelper::map($value, 'dcs_code', function ($value) {
                    return $value->dcs_code;
                });
        $userData = $this->find()
                ->alias('u')
                ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
                ->select(['u.id', 'u.mobile_no', 'u.name'])
                ->distinct()
                ->orWhere(['AND', ['tbl_user_organization_mapping.organization_code' => $values], ['tbl_user_organization_mapping.organization_type' => 'DCS']])
                ->orWhere(['AND', ['tbl_user_organization_mapping.organization_code' => $bmccode], ['tbl_user_organization_mapping.organization_type' => 'BMC']])
                ->orWhere(['AND', ['tbl_user_organization_mapping.organization_code' => $mcccode], ['tbl_user_organization_mapping.organization_type' => 'MCC']])
                ->all();

        $user = ArrayHelper::map($userData, 'id', function($data) {
                    $mobileNo = !empty($data->mobile_no) ? $data->mobile_no : '';
                    return $data->name . ($mobileNo !== '' ? ' (' . $mobileNo . ')' : '');
                });

        return $user;
    }

    public function getUser() {
        $userData = $this->find()->where(['portal_type' => 'portal', 'is_active' => 1])->all();
        $user = ArrayHelper::map($userData, 'id', function($data) {
                    $mobileNo = !empty($data->mobile_no) ? $data->mobile_no : '';
                    return $data->name . ($mobileNo !== '' ? ' (' . $mobileNo . ')' : '');
                });

        return $user;
    }

    public function getUserId($id, $type) {
        $query = $this->find()
                ->where(['is_active' => 1])
                ->andWhere(['or', ['id' => $id], ['user_code' => $id], ['employee_id' => $id]]);
        if ($type == 'DCS') {
            $query->andWhere(['user_type_id' => 7]);
        }
        $userData = $query->one();
        return $userData ?: null;
    }

    public static function getUserOrganizations($userID) {
        $org = \app\models\TblUserOrganizationMapping::find()->where(['user_id' => $userID])->all();
        $values = [];
        foreach ($org as $val) {
            switch ($val->organization_type) {
                case 'UNION' :
                    $union = TblUnions::find()->where(['union_code' => $val->organization_code])->select('union_name')->one();
                    $values[$val->organization_code] = $union->union_name;
                    break;
                case 'DCS' :
                    $dcs = TblDcs::find()->where(['dcs_code' => $val->organization_code])->select('dcs_name')->one();
                    $values[$val->organization_code] = $dcs->dcs_name;
                    break;
                case 'FEDERATION' :
                    $fed = TblFederations::find()->where(['federation_code' => $val->organization_code])->select('federation_name')->one();
                    $values[$val->organization_code] = $fed->federation_name;
                    break;
                default:
                    //$national = \app\models\TblNational::find()->where(['national_code'=>$val->organization_code])->select('national_name')->one();
                    $values[$val->organization_code] = 'PCDF';
                    break;
            }
        }
        return $values;
    }

}
