<?php

namespace webvimark\modules\UserManagement\models\forms;

use webvimark\helpers\LittleBigHelper;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
//use app\models\IdentityMaster;
use app\modules\organisation\models;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblUnions;
use app\models\TblUserOrganizationMapping;
use app\modules\setting\models\TblGeneralConfig;

class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = false;
    public $type;
    public $organization;
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'password', 'type'], 'required'],
            [['type'], 'required', 'on' => 'non_national'],
            [['organization'], 'required', 'on' => 'non_national', 'message' => 'Organization cannot be blank.'],
            ['rememberMe', 'boolean'],
            [['organization', 'type', 'state'], 'safe'],
            ['password', 'validatePassword'],
            [['db'], 'safe'],
            ['username', 'validateIP'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => UserManagementModule::t('front', 'Username'),
            'password' => UserManagementModule::t('front', 'Password'),
            'rememberMe' => UserManagementModule::t('front', 'Remember me'),
            'type' => UserManagementModule::t('front', 'Organization Type'),
            'organization' => UserManagementModule::t('front', 'Organization Name'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword() {
        if (!Yii::$app->getModule('user-management')->checkAttempts()) {
            $this->addError('password', UserManagementModule::t('front', 'Too many attempts'));

            return false;
        }

        if (!$this->hasErrors()) {
            $user = $this->getUser();
            //   var_dump($user);exit;
            if (!$user || !$user->validatePassword($this->password)) {

                $this->addError('password', UserManagementModule::t('front', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Check if user is binded to IP and compare it with his actual IP
     */
    public function validateIP() {
        $user = $this->getUser();

        if ($user AND $user->bind_to_ip) {
            $ips = explode(',', $user->bind_to_ip);

            $ips = array_map('trim', $ips);

            if (!in_array(LittleBigHelper::getRealIp(), $ips)) {
                $this->addError('password', UserManagementModule::t('front', "You could not login from this IP"));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate() && $this->setSession()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? Yii::$app->user->cookieLifetime : 0);
        } else {
            return false;
        }
    }

    public function setSession() {
        $user = User::find()->where(['username' => $this->username, 'is_active' => 1])->one();

        if (!isset($user->user_code)) {
            $this->addError('useranme', UserManagementModule::t('front', 'Insufficiant data'));
            return false;
        }
        $list = \app\models\TblUserOrganizationMapping::find()->select(['organization_code'])->where(['is_active' => 1, 'user_id' => $user->user_code])->asArray()->all();

        if (!empty($list)) {
            $user_organisation = ArrayHelper::getColumn($list, 'organization_code');
            if (!empty($_POST['LoginForm']['organization']))
                if (!in_array($_POST['LoginForm']['organization'], $user_organisation, true)) {
                    $this->addError('useranme', UserManagementModule::t('front', 'Select assigned oraganisation.'));
                    return FALSE;
                }
        } else {
            $this->addError('useranme', UserManagementModule::t('front', 'No oraganisation has assigned'));
            return FALSE;
        }

//        $identityModel = new IdentityMaster();
//        $identity_data = $identityModel->getIdentity();
        $identity_data = $_POST['LoginForm']['type'];
        $district = '';
        $name = '';
        $user_type = $user->user_type_id;
        $GCModel = new TblGeneralConfig();
        if ($GCModel->getNoOfData() == 1) {
            $data = $GCModel->getData();
            $maker_checker = $data->is_active;
        } else {
            $maker_checker = 0;
        }
        $organization_logo = '';
        switch ($identity_data) {
            /* case 'NATIONAL' : 
              //$name = \app\models\TblNational::find()->select(['national_name as name'])->where(['national_code' => $identity_data->organization_code])->one();
              $name = ['name'=>'PCDF'];
              switch ($user->user_type_id) {
              case 1 : $federation = '';
              $union = '';
              $dcs = '';
              break;
              case 2 : $federation = $this->getFederation($user_organisation, 0, 0);
              $union = '';
              $dcs = '';

              break;
              case 3 : $union = $this->getUnion($user_organisation, 0, 0, 0);
              $federation = $this->getFederation(0, 0, $union);
              $dcs = '';
              break;
              case 4 :
              $dcs = $this->getDcs($user_organisation, 0);
              $union = $this->getUnion(0, 0, 0, $dcs);
              $federation = $federation = $this->getFederation(0, 0, $union);
              break;
              }
              break; */
            case 'PCDF':
                $name = models\TblFederations::find()->select('federation_name as name,federation_code')->where(['is_active' => 1])->one();
                $union_names = !(empty($name)) ? $name['name'] : '';
                switch ($user->user_type_id) {
                    /* case 1 : $federation = $this->getFederation(0, $name['federation_code'], 0);
                      $union = '';
                      $dcs = '';
                      break; */

                    case 2 : $federation = $this->getFederation($user_organisation, $name['federation_code'], 0);
                        $union = '';
                        $dcs = '';
                        break;
                    /* case 3 : $federation = $this->getFederation(0, $name['federation_code'], 0);
                      $union = $this->getUnion($user_organisation, 0, $federation, 0);
                      $dcs = '';
                      break;
                      case 4 : $federation = $this->getFederation(0, $name['federation_code'], 0);
                      $dcs = $this->getDcs($user_organisation, 0);
                      $union = $this->getUnion(0, 0, $federation, $dcs);

                      break; */
                    default : $this->addError('useranme', UserManagementModule::t('front', 'Selected oraganisation is not assigned to user'));
                        return FALSE;
                        break;
                }
                $orgType = 'FEDERATION';
                $organization = $name['federation_code'];
                break;

            case 'UNION': $name = models\TblUnions::find()->select('union_name as name, union_code, logo')->where(['union_code' => $user_organisation, 'is_active' => 1])->all();
                $union_names = ArrayHelper::getColumn($name, 'name');
                $union_names = implode(',', $union_names);
                if (count($name) == 1) {
                    $organization_logo = !empty($name[0]->logo) ? array_reverse(explode('/', $name[0]->logo))[0] : '';
                }
                switch ($user->user_type_id) {
                    /* case 1 : $union = $this->getUnion(0, 0, 0, 0);
                      $federation = $this->getFederation(0, 0, $union);
                      $dcs = '';
                      break;
                      case 2 : $union = $this->getUnion(0, 0, 0, 0);
                      $federation = $this->getFederation(0, 0, $union);
                      $dcs = '';
                      break; */

                    case 3 :
                        $union = $this->getUnion($user_organisation, 0, 0, 0);
                        $federation = $this->getFederation(0, 0, $union);
                        $dcs = '';
                        break;
                    case 4 :
                        $dcs = $this->getDcs($user_organisation, 0);
                        $union = $this->getUnion(0, 0, 0, $dcs);
                        $federation = $federation = $this->getFederation(0, 0, $union);
                        break;
                    default : $this->addError('useranme', UserManagementModule::t('front', 'Selected oraganisation is not assigned to user'));
                        return FALSE;
                        break;
                }
                $district = $this->getDistrict($_POST['state'], $user_organisation);
                $orgType = 'UNION';
                $organization = implode(',', $user_organisation);
                break;
        }
        $language_code = 'en';
        Yii::$app->session->set('Federations', $federation);
        Yii::$app->session->set('Unions', $union);
        Yii::$app->session->set('Dcs', $dcs);
        Yii::$app->session->set('States', $_POST['state']);
        Yii::$app->session->set('Districts', $district);
        Yii::$app->session->set('organizations_type', $orgType);
        Yii::$app->session->set('organizations_code', $organization);
        Yii::$app->session->set('OrganizationName', $union_names);
        Yii::$app->session->set('LanguageCode', $language_code);
        Yii::$app->session->set('UserCode', $user->user_code);
        Yii::$app->session->set('UserName', $user->username);
        Yii::$app->session->set('UserType', $user_type);
        Yii::$app->session->set('makerChecker', $maker_checker);
        Yii::$app->session->set('Plant', '');
        Yii::$app->session->set('BMC', '');
        Yii::$app->session->set('MCC', '');
        Yii::$app->session->set('organization_logo', $organization_logo);
        return true;
    }

    private function getFederation($code, $identity_code, $union_code) {

        $query = models\TblFederations::find();
        $query->select(['federation_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0) {
            $query->andWhere(['federation_code' => $code]);
        }
        if ($identity_code != 0)
            $query->andWhere(['federation_code' => $identity_code]);
        if ($union_code != 0) {
            $codes = models\TblUnions::find()->select(['federation_code'])->where(['union_code' => explode(',', $union_code)])->asArray()->all();
            $query->andWhere(['federation_code' => $codes]);
        }

        $list = $query->asArray()->all();
        if (count($list) > 0)
            return implode(',', array_map(function($a) {
                        return $a['federation_code'];
                    }, $list));
        else
            return 0;
    }

    private function getUnion($code, $identity_code, $federation, $dcs) {
        $query = models\TblUnions::find();
        $query->select(['union_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0)
            $query->andWhere(['union_code' => $code]);
        if ($identity_code != 0)
            $query->andWhere(['union_code' => $identity_code]);
        if ($federation != 0)
            $query->andWhere(['federation_code' => explode(',', $federation)]);
        if ($dcs != 0) {
            $codes = models\TblDcs::find()->select(['union_code'])->where(['dcs_code' => explode(',', $dcs)])->asArray()->all();
            $query->andWhere(['union_code' => $codes]);
        }
        //echo $query->createCommand()->rawSql; exit;
        $list = $query->asArray()->all();
        if (count($list) > 0)
            return implode(',', array_map(function($a) {
                        return $a['union_code'];
                    }, $list));
        else
            return 0;
    }

    private function getDcs($code, $union) {
        $query = models\TblDcs::find();
        $query->select(['dcs_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0)
            $query->andWhere(['dcs_code' => $code]);
        if ($union != 0)
            $query->andWhere(['union_code' => explode(',', $union)]);
        $list = $query->asArray()->all();
        if (count($list) > 0)
            return implode(',', array_map(function($a) {
                        return $a['dcs_code'];
                    }, $list));
        else
            return 0;
    }

    /**
     * Finds user by [[username]]
     * @return User|null
     */
    public function getUser() {

        if ($this->_user === false) {
            $u = new \Yii::$app->user->identityClass;
            $this->_user = ($u instanceof User ? $u->findByUsername($this->username) : User::findByUsername($this->username));
        }

        return $this->_user;
    }

    public function getStateCode() {
        $model = new models\TblFederations;
        $federation = $model->getFederation();

        return $federation[0]->state_code;
    }

    public function identityCheck() {

//        $identityModel = new IdentityMaster();
//        $identity = $identityModel->getIdentity();
        $identity = Yii::$app->session->get('organizations_code');
        $states = [];
        $organization_type = '';
        $code = '';
        if ($identity) {

            switch ($identity) {
                case 'FEDERATION':
                    $model = new models\TblFederations;
                    $states = $model->getFederationStates($identity->organization_code);
                    break;
                case 'UNION':
                    $model = new TblUnions();
                    $model->federation_code = $identity->parent_code;
                    $model->union_code = $identity->organization_code;
                    $states = $model->getMappedStates();
                    break;
            }
            $organization_type = $identity->organization_type;
        }
        return ['organization_type' => $identity->organization_type, 'states' => $states];
    }

    public function getDistrict($stateCode, $unionCode) {

        $model = new models\TblUnionsDistrictMapping();
        $districts = $model->getUnionDistrict($unionCode, $stateCode);
        return implode(',', array_map(function($a) {
                    return $a['district_code'];
                }, $districts));
    }

}
