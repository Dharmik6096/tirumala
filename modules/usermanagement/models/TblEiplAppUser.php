<?php

namespace app\modules\usermanagement\models;

use Yii;
use app\modules\general\models\TblDepartment;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblFederations;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_eipl_app_user".
 *
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
 */
class TblEiplAppUser extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['eipl_app_user_code', 'name', 'mobile_no', 'department'], 'required', 'except' => ['deactivate']],
                [['registration_ip', 'device_id', 'password_hash', 'department', 'bind_to_ip', 'confirmation_token', 'mobile_no', 'name', 'user_identity', 'username', 'email', 'eipl_app_user_code', 'user_code', 'created_by', 'updated_by', 'auth_key', 'portal_type', 'created_at', 'updated_at', 'email_confirmed', 'is_active', 'status', 'superadmin', 'alert_recipient_group_id', 'user_type_id', 'allow_app_login'], 'safe'],
                [['email'], 'email', 'except' => ['deactivate']],
                [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                    return $model->is_active;
                }, 'except' => ['deactivate']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
        ];
    }

    public function getCode() {

        $identity = \app\models\IdentityMaster::find()->one();
        $federation = '00';
        $union = '000';
        $other = '000000';
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
                    ->select(["MAX(convert(int,eipl_app_user_code)) as eipl_app_user_code"])
                    ->from('tbl_eipl_app_user')
                    ->one();
            $newcode = (int) $val['eipl_app_user_code'] + 1;


            $value = $code . str_pad($newcode, 3, '0', STR_PAD_LEFT);
            return $value;
        }
    }

    public function getDepartmentCode() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
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

    public static function findByRole($role) {
        return static::find()
                        ->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = eipl_app_user_code')
                        ->where(['auth_assignment.item_name' => $role])
                        ->all();
    }

    public function getUserData() {
        return $this->find()
                        ->where(['mobile_no' => $this->mobile_no, 'is_active' => 1])
                        ->one();
    }

}
