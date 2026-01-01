<?php

namespace webvimark\modules\UserManagement\models;

use webvimark\helpers\LittleBigHelper;
use webvimark\helpers\Singleton;
use webvimark\modules\UserManagement\components\AuthHelper;
use webvimark\modules\UserManagement\components\UserIdentity;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use webvimark\modules\UserManagement\UserManagementModule;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblFederations;
use app\models\TblUserOrganizationMapping;
use app\models\TblUserTypes;
//use app\models\IdentityMaster;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\base\UserException;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\general\models\TblDepartment;
use app\modules\globalmaster\models\TblDesignation;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $user_code
 * @property string $user_type_id
 * @property string $portal_type
 * @property string $organizations
 * @property string $username
 * @property string $name
 * @property string $email
 * @property integer $email_confirmed
 * @property string $auth_key
 * @property integer $alert_recipient_group_id
 * @property string $password_hash
 * @property string $mobile_no
 * @property string $confirmation_token
 * @property string $bind_to_ip
 * @property string $registration_ip
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_active
 * @property integer $created_by
 * @property integer $deleted_by
 * @property integer $updated_by
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $user_identity
 * @property string $federation
 * @property string $union
 * @property string $dcs
 */
class User extends UserIdentity {

//        public $user_type;
    public $organizations;
    public $federation;
    public $union;
    public $dcs;
    public $role;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;

    /**
     * @var string
     */
    public $gridRoleSearch;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $repeat_password;

    /**
     * Store result in singleton to prevent multiple db requests with multiple calls
     *
     * @param bool $fromSingleton
     *
     * @return static
     */
    public static function getCurrentUser($fromSingleton = true) {
        if (!$fromSingleton) {
            return static::findOne(Yii::$app->user->id);
        }

        $user = Singleton::getData('__currentUser');

        if (!$user) {
            $user = static::findOne(Yii::$app->user->id);

            Singleton::setData('__currentUser', $user);
        }

        return $user;
    }

    /**
     * Assign role to user
     *
     * @param int  $userId
     * @param string $roleName
     *
     * @return bool
     */
    public static function assignRole($userId, $roleName) {
        try {
            Yii::$app->db->createCommand()
                    ->insert(Yii::$app->getModule('user-management')->auth_assignment_table, [
                        'user_id' => $userId,
                        'item_name' => (string) $roleName,
                        'created_at' => time(),
                    ])->execute();

            AuthHelper::invalidatePermissions();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Revoke role from user
     *
     * @param int    $userId
     * @param string $roleName
     *
     * @return bool
     */
    public static function revokeRole($userId, $roleName) {
        $result = Yii::$app->db->createCommand()
                        ->delete(Yii::$app->getModule('user-management')->auth_assignment_table, ['user_id' => $userId, 'item_name' => (string) $roleName])
                        ->execute() > 0;

        if ($result) {
            AuthHelper::invalidatePermissions();
        }

        return $result;
    }

    /**
     * @param string|array $roles
     * @param bool         $superAdminAllowed
     *
     * @return bool
     */
    public static function hasRole($roles, $superAdminAllowed = true) {
        if ($superAdminAllowed AND Yii::$app->user->isSuperadmin) {
            return true;
        }
        $roles = (array) $roles;

        AuthHelper::ensurePermissionsUpToDate();

        return array_intersect($roles, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_ROLES, [])) !== [];
    }

    /**
     * @param string $permission
     * @param bool   $superAdminAllowed
     *
     * @return bool
     */
    public static function hasPermission($permission, $superAdminAllowed = true) {
        if ($superAdminAllowed AND Yii::$app->user->isSuperadmin) {
            return true;
        }

        AuthHelper::ensurePermissionsUpToDate();

        return in_array($permission, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_PERMISSIONS, []));
    }

    /**
     * Useful for Menu widget
     *
     * <example>
     * 	...
     * 		[ 'label'=>'Some label', 'url'=>['/site/index'], 'visible'=>User::canRoute(['/site/index']) ]
     * 	...
     * </example>
     *
     * @param string|array $route
     * @param bool         $superAdminAllowed
     *
     * @return bool
     */
    public static function canRoute($route, $superAdminAllowed = true) {
        if ($superAdminAllowed AND Yii::$app->user->isSuperadmin) {
            return true;
        }

        $baseRoute = AuthHelper::unifyRoute($route);

        if (Route::isFreeAccess($baseRoute)) {
            return true;
        }

        AuthHelper::ensurePermissionsUpToDate();

        return Route::isRouteAllowed($baseRoute, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_ROUTES, []));
    }

    /**
     * getStatusList
     * @return array
     */
    public static function getStatusList() {
        return array(
            self::STATUS_ACTIVE => UserManagementModule::t('back', 'Active'),
            self::STATUS_INACTIVE => UserManagementModule::t('back', 'Inactive'),
            self::STATUS_BANNED => UserManagementModule::t('back', 'Banned'),
        );
    }

    /**
     * getStatusList
     * @return array
     */
    public static function getUserTypeList() {
        $userType = \app\models\TblUserTypes::find()->where('id>="' . Yii::$app->session->get('UserType') . '"')->all();

        $userType = \yii\helpers\ArrayHelper::map($userType, function($array, $key) {
                    return $array['id'] . '-' . $array['user_type'];
                }, 'user_type');

        return $userType;
    }

    /**
     * getRoles
     *
     * @param string $val
     *
     * @return string
     */
    public static function getAllRoles() {
        $roles = \webvimark\modules\UserManagement\models\rbacDB\Role::getAvailableRoles(true, true);
        $roles = \yii\helpers\ArrayHelper::map($roles, 'name', 'description');

        return $roles;
    }

    public static function getAvailableRoles() {

        $roles = \webvimark\modules\UserManagement\models\rbacDB\Role::getAvailableRoles(true, true);
        $out = [];
//             print_r($roles);
//             exit;
        foreach ($roles as $key => $row) {
            $out[$key] = str_replace('_', ' ', $row);
        }
        return $out;
    }

    /**
     * getStatusValue
     *
     * @param string $val
     *
     * @return string
     */
    public static function getStatusValue($val) {
        $ar = self::getStatusList();

        return isset($ar[$val]) ? $ar[$val] : $val;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return Yii::$app->getModule('user-management')->user_table;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
                //TimestampBehavior::className(),
        ];
    }

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
                [['federation', 'mobile_no', 'alert_recipient_group_id', 'user_identity', 'union', 'dcs', 'organizations', 'user_type_id', 'role', 'created_by', 'deleted_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'portal_type', 'device_id', 'allow_app_login', 'department', 'login_type', 'wef_date', 'designation_code', 'primary_parent', 'secondary_parent'], 'safe'],
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
        ];
    }

    /**
     * Check that there is no such confirmed E-mail in the system
     */
    public function validateEmailConfirmedUnique() {
        if ($this->email) {
            $exists = User::findOne([
                        'email' => $this->email,
                        'email_confirmed' => 1,
            ]);

            if ($exists AND $exists->id != $this->id) {
                $this->addError('email', UserManagementModule::t('front', 'This E-mail already exists'));
            }
        }
    }

    public function validateUniqueUsername() {

        if (!empty($this->username)) {

            $check = $this->find()->where(['username' => $this->username])->count();
            if ($check != 0) {
                $this->addError('username', UserManagementModule::t('front', 'This Username already taken'));
            }
        }
    }

    /**
     * Validate bind_to_ip attr to be in correct format
     */
    public function validateBindToIp() {
        if ($this->bind_to_ip) {
            $ips = explode(',', $this->bind_to_ip);

            foreach ($ips as $ip) {
                if (!filter_var(trim($ip), FILTER_VALIDATE_IP)) {
                    $this->addError('bind_to_ip', UserManagementModule::t('back', "Wrong format. Enter valid IPs separated by comma"));
                }
            }
        }
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles() {
        return $this->hasMany(Role::className(), ['name' => 'item_name'])
                        ->viaTable(Yii::$app->getModule('user-management')->auth_assignment_table, ['user_id' => 'id']);
    }

    public static function findByRole($role) {
        return static::find()
                        ->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = id')
                        ->where(['auth_assignment.item_name' => $role])
                        ->all();
    }

    /**
     * Make sure user will not deactivate himself and superadmin could not demote himself
     * Also don't let non-superadmin edit superadmin
     *
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if ($insert) {
            if (php_sapi_name() != 'cli') {
                $this->registration_ip = LittleBigHelper::getRealIp();
            }
            $this->generateAuthKey();
        } else {
            // Console doesn't have Yii::$app->user, so we skip it for console
            if (php_sapi_name() != 'cli') {
                if (Yii::$app->user->id == $this->id) {
                    // Make sure user will not deactivate himself
                    $this->status = static::STATUS_ACTIVE;

                    // Superadmin could not demote himself
                    if (Yii::$app->user->isSuperadmin AND $this->superadmin != 1) {
                        $this->superadmin = 1;
                    }
                }

                // Don't let non-superadmin edit superadmin
                // Comments Regarding Password Reset Functionality
                // if (isset($this->oldAttributes['superadmin']) && !Yii::$app->user->isSuperadmin && $this->oldAttributes['superadmin'] == 1) {
                //     return false;
                // }
            }
        }

        // If password has been set, than create password hash
        if ($this->password) {
            $this->setPassword($this->password);
        }

        if (parent::beforeSave($insert)) {
            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;

            if ($insert) {
                $this->created_by = $user;
                $this->created_at = date('Y-m-d H:i:s');
            } else {
                $this->updated_by = $user;
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserType() {
        return $this->hasOne(TblUserTypes::className(), ['id' => 'user_type_id']);
    }

    /*     * $user = isset(\Yii::$app->user->identity->user_code)?\Yii::$app->user->identity->user_code:null;
     * Don't let delete yourself and don't let non-superadmin delete superadmin
     *
     * @inheritdoc
     */

    public function beforeDelete() {
        // Console doesn't have Yii::$app->user, so we skip it for console
        if (php_sapi_name() != 'cli') {
            // Don't let delete yourself
            if (Yii::$app->user->id == $this->id) {
                return false;
            }

            // Don't let non-superadmin delete superadmin
            if (!Yii::$app->user->isSuperadmin AND $this->superadmin == 1) {
                return false;
            }
        }

        return parent::beforeDelete();
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

    public function getInstallationCode($organization_type, $organization_code, $parent_code) {

        $federation = '00';
        $union = '000';
        $other = '000000';
        switch ($organization_type) {
            case 'Federations' :
                $federation = $organization_code;
                break;
            case 'Unions' :
                $federation = $parent_code;
                $union = $organization_code;
                break;
        }

        $code = $federation . $union . $other;

        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`id`, length(`id`) -3,length(`id`) +4)) AS UNSIGNED)) as id")
                ->from('user')
                ->where('(CAST(trim(SUBSTRING(id, 1,11)) AS UNSIGNED))="' . trim($code) . '"')
                ->one();
        $newcode = (int) $val['id'] + 1;


        $value = $code . str_pad($newcode, 3, '0', STR_PAD_LEFT);
        return $value;
    }

    /**
     * getRoles
     *
     * @param string $val
     *
     * @return string
     */
    public static function getUserOrganizations($userID) {
        $org = \app\models\TblUserOrganizationMapping::find()->where(['user_id' => $userID])->all();
        $values = '';
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

    public static function getSelectedOrganization($value) {

        $data = [];
        $field = [];
        $session_fed = Yii::$app->session->get('Federations');

        switch ($value) {
            case '7' :
                $model = new TblDcs();
                $data = $model->getBMCDCS();
                $field[0] = 'dcs_code';
                $field[1] = 'dcs_name';
                break;
            case '6' :
                $model = new TblDcsBmc();
                $data = $model->getBMC();
                $field[0] = 'bmc_code';
                $field[1] = 'bmc_name';
                break;
            case '5' :
                $model = new TblMccPlant();
                $data = $model->getMCC();
                $field[0] = 'mcc_plant_code';
                $field[1] = 'name';
                break;
            case '4' :
                $model = new TblPlant();
                $data = $model->getPlant();
                $field[0] = 'plant_code';
                $field[1] = 'name';
                break;
            case '3' : $model = new TblUnions();
                $data = $model->getUnion($session_fed);
                $field[0] = 'union_code';
                $field[1] = 'union_name';
                break;
            case '2' : $model = new TblFederations();
                $data = $model->getFederation();
                $field[0] = 'federation_code';
                $field[1] = 'federation_name';
                break;
            case '1' :
                $data = Yii::$app->general->getOrganizationName();
                $field[0] = 'national_code';
                $field[1] = 'national_name';
                break;
        }

        return ['data' => $data, 'field' => $field];
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        return true;
    }

    public function save($master = true, $validation = TRUE) {

        $flag = parent::save($validation);
        if ($master === FALSE && $flag === FALSE) {
            throw new UserException("Child table is not saved so transaction is rollback!");
        }
        return $flag;
    }

    public function getOrganizations() {
        return $this->hasMany(TblUserOrganizationMapping::className(), ['user_id' => 'user_code']);
    }

    public function getUserData() {
        return $this->find()
                        ->where(['mobile_no' => $this->mobile_no, 'is_active' => 1])
                        ->one();
    }

    public function getDepartmentCode() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

    public function getPickRecords($limit = 10) {
        $date = date('Y-m-d');
        return $query = $this->find()
                ->where(['is_active' => 1])
                ->andWhere(['<=', 'wef_date', $date])
                ->limit($limit)
                ->all();
    }

    public function getAppUserList($login_type) {
        $data = $this->find()
                ->where(['allow_app_login' => 1])
                ->andFilterWhere(['login_type' => $login_type])
                ->all();
        $array = ArrayHelper::map($data, 'user_code', 'name');
        return $array;
    }

    public function validateUniqueParent() {
        if (!empty($this->primary_parent) && !empty($this->secondary_parent) && ($this->primary_parent == $this->secondary_parent)) {
            $this->addError('secondary_parent', UserManagementModule::t('front', 'Parent Must Not Same.'));
        }
    }

    public function getDesignationCode() {
        return $this->hasOne(TblDesignation::className(), ['designation_code' => 'designation_code']);
    }

    public function getPrimaryParent() {
        return $this->hasOne(User::className(), ['id' => 'primary_parent']);
    }

    public function getSecondaryParent() {
        return $this->hasOne(User::className(), ['id' => 'secondary_parent']);
    }

    public function getAppUserLists($login_type, $mcc_code = null, $department = null) {
        $query = $this->find()
                ->alias('U')
                ->select(['U.name', 'U.user_code', 'tuom.organization_type', 'tuom.organization_code'])
                ->innerJoin('tbl_user_organization_mapping tuom', 'tuom.user_id = U.id')
                ->andWhere(['U.allow_app_login' => 1])
                ->andWhere(['U.login_type' => $login_type])
                ->andWhere(['tuom.organization_type' => ['UNION', 'PLANT', 'MCC', 'BMC', 'DCS']]);
        if (!empty($department)) {
            $query->andWhere(['U.department' => $department]);
        }
        $model = new TblDcs();
        $value = $model->getOrgDCS($mcc_code, TRUE);
        $unionCodes = ArrayHelper::getColumn($value, 'union_code');
        $plantCodes = ArrayHelper::getColumn($value, 'plant_code');
        $mccCodes = ArrayHelper::getColumn($value, 'mcc_plant_code');
        $bmcCodes = ArrayHelper::getColumn($value, 'bmc_code');
        $dcsCodes = ArrayHelper::getColumn($value, 'dcs_code');
        $orgCodes = array_merge($unionCodes, $plantCodes, $mccCodes, $bmcCodes, $dcsCodes);

        if (!empty($orgCodes)) {
            $org_string = "'" . implode(',', $orgCodes) . "'";
            $command = Yii::$app->db->createCommand("SELECT distinct code from [SplitToTable](" . $org_string . ",',')");
            $org_codes = $command->sql;
        }

        $query->andWhere('tuom.organization_code in (' . $org_codes . ')');
        $data = $query->all();
        return ArrayHelper::map($data, 'user_code', 'name');
    }

    public function getUserCode() {
        return $this->hasOne(self::className(), ['id' => 'created_by']);
    }

}
