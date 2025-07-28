<?php

namespace app\modules\usermanagement\models\forms;

use Yii;
use app\modules\usermanagement\models\User;
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
use app\modules\dcsaccounting\models\TblFinancialYear;
use app\modules\general\models\TblViewHistoryTableList;
use app\modules\organisation\models;
use app\modules\usermanagement\models\TblFailedPasswordAttempts;
use Exception;
use yii\base\UserException;
use webvimark\modules\UserManagement\UserManagementModule;

class LoginForm extends \webvimark\modules\UserManagement\models\forms\LoginForm {

    public $type;
    public $organization;

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
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember me'),
            'type' => Yii::t('app', 'Organization Type'),
            'organization' => Yii::t('app', 'Organization Name'),
        ];
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
            $this->addError('useranme', Yii::t('app', 'Insufficiant data'));
            return false;
        }
        $list = \app\models\TblUserOrganizationMapping::find()->select(['organization_code', 'organization_type'])->where(['is_active' => 1, 'user_id' => $user->user_code])->asArray()->all();

        if (!empty($list)) {
            $user_organisation = ArrayHelper::getColumn($list, 'organization_code');
//            if (!empty($_POST['LoginForm']['organization']))
//                if (!in_array($_POST['LoginForm']['organization'], $user_organisation, true)) {
//                    $this->addError('useranme', Yii::t('app', 'Select assigned oraganisation.'));
//                    return FALSE;
//                }
        } else {
            $this->addError('useranme', Yii::t('app', 'No oraganisation has assigned'));
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
        $federation = '';
        $union = '';
        $plant = '';
        $mcc = '';
        $bmc = '';
        $dcs = '';
        $states = '';
        $allow_zero_rate = 0;
        $language = '';
        $with_member_rate = 0;
        $unionConfigArray = [];
        $unionKeyPattern = [];
        $hasBMC = 1;
        $finacialYear = '';
        $ViewHistory = [];
        switch ($main_org_type) {

            case 'PCDF':
                $name = models\TblFederations::find()->select('federation_name as name,federation_code')->where(['is_active' => 1])->one();
                $union_names = !(empty($name)) ? $name['name'] : '';
                switch ($user->user_type_id) {
                    case 2 : $federation = $this->getFederation($user_organisation, $name['federation_code'], 0);
                        break;
                    default : $this->addError('useranme', Yii::t('app', 'Selected oraganisation is not assigned to user'));
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
                    default : $this->addError('useranme', Yii::t('app', 'Selected oraganisation is not assigned to user'));
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
                if (count($name) == 1) {
                    $allow_zero_rate = Yii::$app->general->getUnionConfiguration($union, 'bmc_collection_allow_on_zero_rate', 'BMC');
                    $with_member_rate = Yii::$app->general->getUnionConfiguration($union, 'dcs_create_with_member_rate', 'PORTAL');
                }
                $unionCode = (!empty($name)) && !empty($name[0]) ? $name[0]->union_code : '';
                $unionData = !empty($unionCode) ? TblUnions::find()->where(['union_code' => $unionCode])->one() : '';
                $language = !empty($unionData) && !empty($unionData->eipl_code) ? ($unionData->eipl_code) : '';
                $orgType = 'UNION';
                $organization = $union;
                $unionConfigData = Yii::$app->general->getAllUnionWiseConfig(explode(',', $union), 'PORTAL');
                foreach ($unionConfigData as $data) {
                    $unionConfigArray[$data->union_code][$data->config_key] = $data->config_result_key;
                }
                $unions = models\TblUnions::find()->where(['union_code' => explode(',', $union), 'is_active' => 1, 'has_bmc' => 1])->count();
                $hasBMC = !empty($unions) && $unions > 0 ? 1 : 0;
                $unionKeyPattern = Yii::$app->general->getUnionKeyPattern(explode(',', $union));
                $finacialModel = new TblFinancialYear;
                $finacialYear = $finacialModel->getCurrentYear();
                if (count($name) == 1) {
                    $tableName = TblViewHistoryTableList::find()->where(['union_code' => explode(',', $union)])->all();
                    if (count($tableName) > 0) {
                        $ViewHistory = implode(',', array_map(function($tableName) {
                                    return $tableName->table_name;
                                }, $tableName));
                    }
                }
                break;
        }
        try {
            $model = new \app\models\TblClientPayment();
            $overDuePayment = $model->getOverDuePayment($union);
            if (!empty($overDuePayment)) {
                $overDueDate = date('d.m.Y', strtotime($overDuePayment->allow_till_date));
                Yii::$app->getSession()->setFlash('success', ['type' => 'paymentErr',
                    'message' => 'Dear Customer, Your payment for the Solution Services are due, services are terminated on ' . $overDueDate]);
                return false;
            } else {
                $pendingPayment = $model->getPendingPaymentCount($union);
                if (!empty($pendingPayment)) {
                    $pendigAmountDate = date('d.m.Y', strtotime($pendingPayment->allow_till_date));
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Dear Customer, Your payment for the Solution Services are due, non-payment will lead to service termination on ' . $pendigAmountDate]);
                }
            }
        } catch (UserException $e) {
            return false;
        } catch (\yii\db\Exception $e) {
            return false;
        } catch (Exception $ex) {
            return false;
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
        Yii::$app->session->set('eiplCode', $language);
        Yii::$app->session->set('unionConfig', $unionConfigArray);
        Yii::$app->session->set('hasBMC', $hasBMC);
        Yii::$app->session->set('unionKeyPattern', $unionKeyPattern);
        Yii::$app->session->set('financialYear', $finacialYear);
        Yii::$app->session->set('ViewHistory', $ViewHistory);
        Yii::$app->session->set('isEngineer', $user->is_engineer);
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

    public function getStateCode() {
        $model = new models\TblFederations;
        $federation = $model->getFederation();

        return $federation[0]->state_code;
    }

    public function identityCheck() {
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

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword($isExpired = false, &$userCode = '', &$maxLoginAttempts = '') {
        static $callCount = 0;
        $callCount++;
        $currentDateTime = new \DateTime();
        $showError = TRUE;
        if (!$isExpired && !Yii::$app->getModule('user-management')->checkAttempts()) {
            $this->addError('password', UserManagementModule::t('front', 'Too many attempts'));
            return false;
        }

        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $unionCode = TblUnions::find()->select('union_code')->where(['is_active' => 1])->scalar();
            $maxLoginAttemptsConfig = (int) Yii::$app->general->getUnionConfiguration($unionCode, 'portal_max_login_attempts', 'PORTAL');
            $loginSuspensionTimeConfig = (int) Yii::$app->general->getUnionConfiguration($unionCode, 'portal_login_suspension_time', 'PORTAL');
            if (!$user || !$user->validatePassword($this->password)) {
                if ($maxLoginAttemptsConfig > 0 && $isExpired && $callCount < 2 && !empty($user)) {
                    $failedAttempt = new TblFailedPasswordAttempts();
                    $suspensionDatetime = new \DateTime($user->suspension_datetime ?? '');
                    if ($user->max_login_attempts === 0) {
                        if ($currentDateTime > $suspensionDatetime) {
                            $user->max_login_attempts = $maxLoginAttemptsConfig - 1;
                            $user->suspension_datetime = NULL;
                        } else if ($suspensionDatetime > $currentDateTime) {
                            $interval = $currentDateTime->diff($suspensionDatetime);
                            $maxLoginAttempts = min((($interval->days * 1440) + ($interval->h * 60) + $interval->i + 1), $loginSuspensionTimeConfig);
                        }
                    } else if ($user->max_login_attempts > 0) {
                        $user->suspension_datetime = NULL;
                        $user->max_login_attempts--;
                        if ($user->max_login_attempts === 0) {
                            $user->suspension_datetime = date('Y-m-d H:i:s', strtotime('+' . $loginSuspensionTimeConfig . ' minutes'));
                        }
                    } else {
                        $user->max_login_attempts = $maxLoginAttemptsConfig - 1;
                        $user->suspension_datetime = NULL;
                    }
                    $user->save(TRUE, FALSE);
                    $failedAttempt->saveFailedPasswordAttempts($user);
                }
                if ($maxLoginAttemptsConfig > 0 && !empty($user) && $user->max_login_attempts <= 2) {
                    $showError = FALSE;
                    if ($user->max_login_attempts != 0) {
                        $this->addError('password', UserManagementModule::t('front', 'Incorrect username or password. You have ' . $user->max_login_attempts . ' attempts remaining'));
                    }
                }
                if ($showError) {
                    $this->addError('password', UserManagementModule::t('front', 'Incorrect username or password.'));
                }
            } else if ($isExpired) {
                $expirationDays = 0;
                $userCode = $user->id;
                if (!empty($unionCode)) {
                    $configDays = Yii::$app->general->getUnionConfiguration($unionCode, 'portal_password_expiry_days', 'PORTAL');
                    if (is_numeric($configDays)) {
                        $expirationDays = (int) $configDays;
                    }
                }
                if ($expirationDays > 0) {
                    $passwordUpdatedAt = $user->last_password_updated_at;
                    if (empty($passwordUpdatedAt)) {
                        return true;
                    }
                    $updatedDateTime = new \DateTime($passwordUpdatedAt);
                    $expirationDateTime = clone $updatedDateTime;
                    $expirationDateTime->add(new \DateInterval("P{$expirationDays}D"));
                    $currentDateTime = new \DateTime();
                    if ($currentDateTime > $expirationDateTime) {
                        return true;
                    }
                }
            }

            if ($maxLoginAttemptsConfig > 0 && !empty($user) && !$this->hasErrors()) {
                $suspensionDatetime = new \DateTime($user->suspension_datetime ?? '');
                if ($user->max_login_attempts == 0 && !empty($user->suspension_datetime) && $suspensionDatetime > $currentDateTime) {
                    $interval = $currentDateTime->diff($suspensionDatetime);
                    $maxLoginAttempts = min((($interval->days * 1440) + ($interval->h * 60) + $interval->i + 1), $loginSuspensionTimeConfig);
                } else {
                    $user->max_login_attempts = $user->suspension_datetime = NULL;
                    $failedAttempt = new TblFailedPasswordAttempts();
                    $failedAttempt->saveFailedPasswordAttempts($user, 'success');
                    $user->save(TRUE, FALSE);
                }
            }
        }
    }

}
