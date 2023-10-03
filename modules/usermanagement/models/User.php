<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;
use webvimark\helpers\LittleBigHelper;
use app\modules\usermanagement\components\AuthHelper;
use app\modules\usermanagement\models\rbacDB\Role;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblFederations;
use app\models\TblUserOrganizationMapping;
use app\models\TblUserTypes;
use yii\base\UserException;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\general\models\TblDepartment;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $user_code
 * @property string $user_type_id
 * @property string $portal_type
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
 */
class User extends \webvimark\modules\UserManagement\models\User
{

    public $organizations;
    public $federation;
    public $union;
    public $dcs;
    public $role;

    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'name'], 'required'],
            [['role'], 'required', 'on' => ['newUser']],
            [['username'], 'validateUniqueUsername', 'on' => ['newUser']],
            ['user_code', 'unique'],
            ['username', 'trim'],
            [['status', 'email_confirmed', 'is_active'], 'integer'],
            ['email', 'email'],
            ['email', 'validateEmailConfirmedUnique'],
            ['bind_to_ip', 'validateBindToIp'],
            [['federation', 'mobile_no', 'alert_recipient_group_id', 'designation_code','user_identity', 'union', 'dcs', 'organizations', 'user_type_id', 'role', 'created_by', 'deleted_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'portal_type', 'device_id', 'allow_app_login', 'department', 'login_type'], 'safe'],
            ['bind_to_ip', 'trim'],
            [['bind_to_ip', 'user_code'], 'string', 'max' => 255],
            [['mobile_no'], function ($attribute, $params) {
                Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
            }, 'skipOnEmpty' => false],
            ['password', 'required', 'on' => ['newUser', 'changePassword']],
            ['password', 'string', 'max' => 255, 'on' => ['newUser', 'changePassword']],
            ['password', 'match', 'pattern' => '/^\S*$/', 'message' => Yii::t('app', 'Space not allowed in Password.')],
            ['repeat_password', 'required', 'on' => ['newUser', 'changePassword']],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
            [['allow_app_login'], 'default', 'value' => 0],
            [['department', 'mobile_no'], 'required', 'when' => function ($model) {
                return $model->allow_app_login == 1;
            }, 'whenClient' => "function (attribute, value) {  if($('#user-allow_app_login').is(':checked')){return true;} }"],
            [['mobile_no'], 'unique'],
            [['name'], function ($attribute, $params) {
                Yii::$app->general->validateName($this, $attribute, $params);
            }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_code' => Yii::t('app', 'User Code'),
            'role' => Yii::t('app', 'Role'),
            'username' => Yii::t('app', 'User Name'),
            'user_type_id' => Yii::t('app', 'User Type'),
            'organizations' => Yii::t('app', 'Organizations'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'alert_recipient_group_id' => Yii::t('app', 'alert_recipient_group_id'),
            'superadmin' => Yii::t('app', 'Superadmin'),
            'confirmation_token' => Yii::t('app', 'Confirmation Token'),
            'registration_ip' => Yii::t('app', 'Registration IP'),
            'bind_to_ip' => Yii::t('app', 'Bind to IP'),
            'status' => Yii::t('app', 'Status'),
            'gridRoleSearch' => Yii::t('app', 'Roles'),
            'created_at' => Yii::t('app', 'Created'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated'),
            'password' => Yii::t('app', 'Password'),
            'repeat_password' => Yii::t('app', 'Repeat password'),
            'email_confirmed' => Yii::t('app', 'E-mail confirmed'),
            'email' => Yii::t('app', 'E-mail'),
            'portal_type' => Yii::t('app', 'Portal Type'),
            'designation_code' => yii::t('app', 'Designation'),
        ];
    }
    
    public function getDesignationCode() {
        return $this->hasOne(\app\modules\globalmaster\models\TblDesignation::className(), ['designation_code' => 'designation_code']);
    }
    
    public function getPrimaryParent() {
        return $this->hasOne(User::className(), ['id' => 'primary_parent']);
    }
    
    public function getSecondaryParent() {
        return $this->hasOne(User::className(), ['id' => 'secondary_parent']);
    }

    public function validateUniqueUsername()
    {
        if (!empty($this->username)) {
            $check = $this->find()->where(['username' => $this->username])->count();
            if ($check != 0) {
                $this->addError('username', Yii::t('app', 'This Username already taken'));
            }
        }
    }

    public function getUserList()
    {
        $data = $this->find()->where(['portal_type' => 'portal', 'is_active' => 1])->all();
        $list = ArrayHelper::map($data, 'user_code', function ($array, $key) {
            return $array['name'] . '-' . $array['department'];
        });
        return $list;
    }

    public static function assignRole($userId, $roleName)
    {
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

    public static function revokeRole($userId, $roleName)
    {
        $result = Yii::$app->db->createCommand()
            ->delete(Yii::$app->getModule('user-management')->auth_assignment_table, ['user_id' => $userId, 'item_name' => (string) $roleName])
            ->execute() > 0;
        if ($result) {
            AuthHelper::invalidatePermissions();
        }
        return $result;
    }

    /**
     * getStatusList
     * @return array
     */
    public static function getUserTypeList()
    {
        $userType = TblUserTypes::find()->where('id>="' . Yii::$app->session->get('UserType') . '"')->all();
        $userType = ArrayHelper::map($userType, function ($array, $key) {
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
    public static function getAllRoles()
    {
        $roles = Role::getAvailableRoles(true, true);
        $roles = ArrayHelper::map($roles, 'name', 'description');
        return $roles;
    }

    public static function getAvailableRoles()
    {
        $roles = Role::getAvailableRoles(true, true);
        $out = [];
        //             print_r($roles);
        //             exit;
        foreach ($roles as $key => $row) {
            $out[$key] = str_replace('_', ' ', $row);
        }
        return $out;
    }

    public static function findByRole($role)
    {
        return static::find()
            ->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = id')
            ->where(['auth_assignment.item_name' => $role])
            ->all();
    }

    public function beforeSave($insert)
    {
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
                    if (Yii::$app->user->isSuperadmin and $this->superadmin != 1) {
                        $this->superadmin = 1;
                    }
                }
                // Don't let non-superadmin edit superadmin
                if (isset($this->oldAttributes['superadmin']) && !Yii::$app->user->isSuperadmin && $this->oldAttributes['superadmin'] == 1) {
                    return false;
                }
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
    public function getUserType()
    {
        return $this->hasOne(TblUserTypes::className(), ['id' => 'user_type_id']);
    }

    public function getCode()
    {

        $identity = \app\models\IdentityMaster::find()->one();
        $federation = '00';
        $union = '000';
        $other = '000000';
        if ($identity) {
            switch ($identity->organization_type) {
                case 'Federations':
                    $federation = $identity->organization_code;
                    break;
                case 'Unions':
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


            $value = $code . str_pad($newcode, 3, '0', STR_PAD_LEFT);
            return $value;
        }
    }

    public function getInstallationCode($organization_type, $organization_code, $parent_code)
    {

        $federation = '00';
        $union = '000';
        $other = '000000';
        switch ($organization_type) {
            case 'Federations':
                $federation = $organization_code;
                break;
            case 'Unions':
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
    public static function getUserOrganizations($userID)
    {
        $org = \app\models\TblUserOrganizationMapping::find()->where(['user_id' => $userID])->all();
        $values = '';
        foreach ($org as $val) {
            switch ($val->organization_type) {
                case 'UNION':
                    $union = TblUnions::find()->where(['union_code' => $val->organization_code])->select('union_name')->one();
                    $values[$val->organization_code] = $union->union_name;
                    break;
                case 'DCS':
                    $dcs = TblDcs::find()->where(['dcs_code' => $val->organization_code])->select('dcs_name')->one();
                    $values[$val->organization_code] = $dcs->dcs_name;
                    break;
                case 'FEDERATION':
                    $fed = TblFederations::find()->where(['federation_code' => $val->organization_code])->select('federation_name')->one();
                    $values[$val->organization_code] = $fed->federation_name;
                    break;
                default:
                    $values[$val->organization_code] = 'PCDF';
                    break;
            }
        }
        return $values;
    }

    public static function getSelectedOrganization($value)
    {

        $data = [];
        $field = [];
        $session_fed = Yii::$app->session->get('Federations');

        switch ($value) {
            case '7':
                $model = new TblDcs();
                $data = $model->getBMCDCS();
                $field[0] = 'dcs_code';
                $field[1] = 'dcs_name';
                break;
            case '6':
                $model = new TblDcsBmc();
                $data = $model->getBMC();
                $field[0] = 'bmc_code';
                $field[1] = 'bmc_name';
                break;
            case '5':
                $model = new TblMccPlant();
                $data = $model->getMCC();
                $field[0] = 'mcc_plant_code';
                $field[1] = 'name';
                break;
            case '4':
                $model = new TblPlant();
                $data = $model->getPlant();
                $field[0] = 'plant_code';
                $field[1] = 'name';
                break;
            case '3':
                $model = new TblUnions();
                $data = $model->getUnion($session_fed);
                $field[0] = 'union_code';
                $field[1] = 'union_name';
                break;
            case '2':
                $model = new TblFederations();
                $data = $model->getFederation();
                $field[0] = 'federation_code';
                $field[1] = 'federation_name';
                break;
            case '1':
                $data = Yii::$app->general->getOrganizationName();
                $field[0] = 'national_code';
                $field[1] = 'national_name';
                break;
        }

        return ['data' => $data, 'field' => $field];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        return true;
    }

    public function save($master = true, $validation = TRUE)
    {

        $flag = parent::save($validation);
        if ($master === FALSE && $flag === FALSE) {
            throw new UserException("Child table is not saved so transaction is rollback!");
        }
        return $flag;
    }

    public function getOrganizations()
    {
        return $this->hasMany(TblUserOrganizationMapping::className(), ['user_id' => 'user_code']);
    }

    public function getUserData()
    {
        return $this->find()
            ->where(['mobile_no' => $this->mobile_no, 'is_active' => 1])
            ->one();
    }

    public function getDepartmentCode()
    {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

    public function checkNotSelf()
    {
        return $this->id != Yii::$app->session->get('UserCode');
    }

    public function getLoginDetails()
    {
        return $this->find()
            ->where(['is_active' => 1, 'mobile_no' => $this->mobile_no])
            ->one();
    }

    public function getAssignList($location_type, $code)
    {
        $userData = $this->find()->alias('u')
            ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
            ->where(['tbl_user_organization_mapping.organization_code' => $code, 'tbl_user_organization_mapping.organization_type' => $location_type])->all();
        $user = ArrayHelper::map($userData, 'id', 'name');
        return $user;
    }
}
