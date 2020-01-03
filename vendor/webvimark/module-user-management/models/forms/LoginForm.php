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
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblFederationsStateMapping;
use app\modules\organisation\models\TblUnionsDistrictMapping;
use app\modules\geo\models\TblDistricts;
use app\modules\configuration\models\TblUnionConfigResult;

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
            [['username', 'password'], 'required'],
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
        $list = \app\models\TblUserOrganizationMapping::find()->select(['organization_code', 'organization_type'])->where(['is_active' => 1, 'user_id' => $user->user_code])->asArray()->all();

        if (!empty($list)) {
            $user_organisation = ArrayHelper::getColumn($list, 'organization_code');
//            if (!empty($_POST['LoginForm']['organization']))
//                if (!in_array($_POST['LoginForm']['organization'], $user_organisation, true)) {
//                    $this->addError('useranme', UserManagementModule::t('front', 'Select assigned oraganisation.'));
//                    return FALSE;
//                }
        } else {
            $this->addError('useranme', UserManagementModule::t('front', 'No oraganisation has assigned'));
            return FALSE;
        }
        $organisation_type = $list[0]['organization_type'];
        $main_org_type = ($organisation_type == 'FEDERATION') ? 'PCDF' : 'UNION';
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
        $union = '';
        $plant = '';
        $mcc = '';
        $bmc = '';
        $dcs = '';
        $states = '';
        $allow_zero_rate = 0;
        switch ($main_org_type) {

            case 'PCDF':
                $name = models\TblFederations::find()->select('federation_name as name,federation_code')->where(['is_active' => 1])->one();
                $union_names = !(empty($name)) ? $name['name'] : '';
                switch ($user->user_type_id) {
                    case 2 : $federation = $this->getFederation($user_organisation, $name['federation_code'], 0);
                        break;
                    default : $this->addError('useranme', UserManagementModule::t('front', 'Selected oraganisation is not assigned to user'));
                        return FALSE;
                        break;
                }
                $orgType = 'FEDERATION';
                $organization = $name['federation_code'];
                $states = $this->getFedStates($organization);
                break;

            case 'UNION':
                switch ($user->user_type_id) {
                    case 3 :
                        $union = $this->getUnion($user_organisation, 0, 0, 0);
                        $federation = $this->getFederation(0, 0, $union);
                        break;
                    case 4 :
                        $plant = $this->getPlant($user_organisation, 0, 0);
                        $union = $this->getUnion(0, 0, 0, $plant);
                        $federation = $this->getFederation(0, 0, $union);
                        break;
                    case 5 :
                        $mcc = $this->getMCC($user_organisation, 0, 0);
                        $plant = $this->getPlant(0, 0, $mcc);
                        $union = $this->getUnion(0, 0, 0, $plant);
                        $federation = $this->getFederation(0, 0, $union);
                        break;
                    case 6 :
                        $bmc = $this->getBMC($user_organisation, 0, 0);
                        $mcc = $this->getMcc(0, 0, $bmc);
                        $plant = $this->getPlant(0, 0, $mcc);
                        $union = $this->getUnion(0, 0, 0, $plant);
                        $federation = $federation = $this->getFederation(0, 0, $union);
                        break;
                    case 7 :
                        $dcs = $this->getDcs($user_organisation, 0);
                        $bmc = $this->getBMC(0, 0, $dcs);
                        $mcc = $this->getMCC(0, 0, $bmc);
                        $plant = $this->getPlant(0, 0, $mcc);
                        $union = $this->getUnion(0, 0, 0, $plant);
                        $federation = $federation = $this->getFederation(0, 0, $union);
                        break;
                    default : $this->addError('useranme', UserManagementModule::t('front', 'Selected oraganisation is not assigned to user'));
                        return FALSE;
                        break;
                }
                $name = models\TblUnions::find()->select('union_name as name, union_code, logo,state_code')->where(['union_code' => explode(',', $union), 'is_active' => 1])->all();
                $union_names = ArrayHelper::getColumn($name, 'name');
                $union_names = implode(',', $union_names);
                if (count($name) == 1) {
                    $organization_logo = !empty($name[0]->logo) ? array_reverse(explode('/', $name[0]->logo))[0] : '';
                }
                $states = $this->getUnionStates(explode(',', $union));
                $district = $this->getDistrict($states, explode(',', $union));
                $code = ArrayHelper::getColumn($name, 'union_code');
                $union_code = implode(',', $code);
                if (count($union_code) == 1) {
                    $allow_zero_rate = Yii::$app->general->getUnionConfiguration($union, 'bmc_collection_allow_on_zero_rate', 'BMC');
                }
                $orgType = 'UNION';
                $organization = $union;
                break;
        }
        $language_code = 'en';
        Yii::$app->session->set('Federations', $federation);
        Yii::$app->session->set('Unions', $union);
        Yii::$app->session->set('Dcs', $dcs);
        Yii::$app->session->set('States', $states);
        Yii::$app->session->set('Districts', $district);
        Yii::$app->session->set('organizations_type', $orgType);
        Yii::$app->session->set('organizations_code', $organization);
        Yii::$app->session->set('OrganizationName', $union_names);
        Yii::$app->session->set('LanguageCode', $language_code);
        Yii::$app->session->set('UserCode', $user->user_code);
        Yii::$app->session->set('UserName', $user->username);
        Yii::$app->session->set('UserType', $user_type);
        Yii::$app->session->set('makerChecker', $maker_checker);
        Yii::$app->session->set('Plant', $plant);
        Yii::$app->session->set('BMC', $bmc);
        Yii::$app->session->set('MCC', $mcc);
        Yii::$app->session->set('organization_logo', $organization_logo);
        Yii::$app->session->set('AllowOnZeroRate', $allow_zero_rate);
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

    private function getUnion($code, $identity_code, $federation, $plant) {
        $query = models\TblUnions::find();
        $query->select(['union_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0)
            $query->andWhere(['union_code' => $code]);
        if ($identity_code != 0)
            $query->andWhere(['union_code' => $identity_code]);
        if ($federation != 0)
            $query->andWhere(['federation_code' => explode(',', $federation)]);
        if ($plant != 0) {
            $codes = TblPlant::find()->select(['union_code'])->where(['plant_code' => explode(',', $plant)])->asArray()->all();
            $query->andWhere(['union_code' => $codes]);
        }
        $list = $query->asArray()->all();
        if (count($list) > 0)
            return implode(',', array_map(function($a) {
                        return $a['union_code'];
                    }, $list));
        else
            return 0;
    }

    private function getPlant($code, $union, $mcc) {
        $query = TblPlant::find();
        $query->select(['plant_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0)
            $query->andWhere(['plant_code' => $code]);
        if ($union != 0)
            $query->andWhere(['union_code' => explode(',', $union)]);
        if ($mcc != 0) {
            $codes = TblMccPlant::find()->select(['plant_code'])->where(['mcc_plant_code' => explode(',', $mcc)])->asArray()->all();
            $query->andWhere(['plant_code' => $codes]);
        }
        $list = $query->asArray()->all();
        if (count($list) > 0)
            return implode(',', array_map(function($a) {
                        return $a['plant_code'];
                    }, $list));
        else
            return 0;
    }

    private function getMCC($code, $plant, $bmc) {
        $query = TblMccPlant::find();
        $query->select(['mcc_plant_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0)
            $query->andWhere(['mcc_plant_code' => $code]);
        if ($plant != 0)
            $query->andWhere(['plant_code' => explode(',', $plant)]);
        if ($bmc != 0) {
            $codes = TblDcsBmc::find()->select(['mcc_plant_code As mcc_plant_code'])->where(['bmc_code' => explode(',', $bmc)])->asArray()->all();
            $query->andWhere(['mcc_plant_code' => $codes]);
        }
        $list = $query->asArray()->all();
        if (count($list) > 0)
            return implode(',', array_map(function($a) {
                        return $a['mcc_plant_code'];
                    }, $list));
        else
            return 0;
    }

    private function getBMC($code, $mcc, $dcs) {
        $query = TblDcsBmc::find();
        $query->select(['bmc_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0)
            $query->andWhere(['bmc_code' => $code]);
        if ($mcc != 0)
            $query->andWhere(['mcc_plant_code' => explode(',', $mcc)]);
        if ($dcs != 0) {
            $codes = TblDcs::find()->select(['bmc_code'])->where(['dcs_code' => explode(',', $dcs)])->asArray()->all();
            $query->andWhere(['bmc_code' => $codes]);
        }
        $list = $query->asArray()->all();
        if (count($list) > 0)
            return implode(',', array_map(function($a) {
                        return $a['bmc_code'];
                    }, $list));
        else
            return 0;
    }

    private function getDcs($code, $bmc) {
        $query = TblDcs::find();
        $query->select(['dcs_code']);
        $query->where(['is_active' => 1]);
        if ($code != 0)
            $query->andWhere(['dcs_code' => $code]);
        if ($bmc != 0)
            $query->andWhere(['bmc_code' => explode(',', $bmc)]);
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
        $districts = $model->getUnionDistrict($unionCode, explode(',', $stateCode));
        return implode(',', array_map(function($a) {
                    return $a['district_code'];
                }, $districts));
    }

    private function getFedStates($fed_code = []) {
        $query = TblFederationsStateMapping::find();
        $query->select(['distinct(state_code)']);
        $query->where(['federation_code' => $fed_code, 'is_active' => 1]);
        $list = $query->asArray()->all();
        if (count($list) > 0) {
            return implode(',', array_map(function($a) {
                        return $a['state_code'];
                    }, $list));
        } else {
            return 0;
        }
    }

    private function getUnionStates($union_code = []) {
        $query = TblUnionsDistrictMapping::find();
        $query->select(['distinct(district_code)']);
        $query->where(['union_code' => $union_code, 'is_active' => 1]);
        $list = $query->asArray()->all();
        if (count($list) > 0) {
            $state_query = TblDistricts::find();
            $state_query->select(['distinct(state_code)']);
            $state_query->where(['district_code' => $list, 'is_active' => 1]);
            $state_list = $state_query->asArray()->all();
            return implode(',', array_map(function($a) {
                        return $a['state_code'];
                    }, $state_list));
        } else {
            return 0;
        }
    }

}
