<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii;
use yii\base\Component;
use yii\helpers\StringHelper;
use app\modules\geo\models\TblStates;
use app\models\TblNational;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblBranch;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblBanksDistrictsMapping;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblFederations;
use yii\helpers\Html;
use webvimark\modules\UserManagement\models\User;
use app\models\TblUserOrganizationMapping;
use app\modules\organisation\models\TblDcs;
use app\modules\general\models\TblSocietyVendor;
use app\modules\dcsoperation\models\TblMember;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblBankDetails;
use app\modules\notification\models\TblNotifications;
use DateTime;
use stdClass;
use SoapClient;
use app\components\SearchFilter;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\sms\models\TblAlertNotification;
use app\modules\syncutility\models\TblSecurity;
use yii\helpers\ArrayHelper;
use app\modules\configuration\models\TblUnionConfigResult;
use app\modules\syncutility\models\TblGenerateSentbox;
use app\models\TblKeyPattern;
use app\models\TblKeyPatternChild;
use app\modules\organisation\models\TblMasterHierarchy;
use app\modules\bkgprocess\models\TblFtpDetail;
use app\modules\dcsoperation\models\TblMemberDeactive;
use app\modules\organisation\models\TblDcsDeactive;
use app\modules\configuration\models\TblConfigMapping;
use app\modules\configuration\models\TblConfig;
use app\modules\general\models\TblAttachment;
use app\modules\payment\models\TblPaymentCycleApplicability;
use yii\db\Query;
use app\modules\organisation\models\TblCustomerDeactive;
use yii\imagine\Image;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\general\models\TblProcessApproval;
use app\modules\product\models\TblDispatchCenter;
use app\modules\product\models\TblDispatchCenterApplicability;
use app\modules\product\models\TblProduct;
use app\modules\product\models\TblGeneralPartyMaster;
use yii\db\Expression;
use app\modules\tankermovement\models\TblBmcDispatchStock;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripTracking;
use Exception;
use webvimark\modules\UserManagement\components\GhostHtml;
use PHPExcel;
use yii\helpers\Url;
use yii\web\View;

class GeneralFunctions extends Component {

    public static function getUserOrganization($user) {
        $org = \app\models\TblUserOrganizationMapping::find()->where(['user_id' => $user])->all();

        $values = '';
        foreach ($org as $val) {
            switch ($val->organizationType) {
                case '2' :
                    $union = \app\models\TblUnions::find()->where(['union_code' => $val->organizationCode])->one();
                    if ($union)
                        $values[$val->organizationCode . '_' . $union->stateCode->default_language_code . '_' . $union->stateCode->defaultLanguageCode->font_name . '_' . $union->union_name] = $union->union_name;
                    break;
                case '3' :
                    $dcs = \app\models\TblDcs::find()->where(['dcs_code' => $val->organizationCode])->one();
//                            print_r($dcs->stateCode);exit;
                    if ($dcs)
                        $values[$val->organizationCode . '_' . $dcs->stateCode->default_language_code . '_' . $dcs->stateCode->defaultLanguageCode->font_name . '_' . $dcs->dcs_name] = $dcs->dcs_name;
                    break;
                case '1' :
                    $fed = \app\models\TblFederations::find()->where(['federation_code' => $val->organizationCode])->select('federation_name')->one();
                    if ($fed)
                        $values[$val->organizationCode . '_' . $fed->stateCode->default_language_code . '_' . $fed->stateCode->defaultLanguageCode->font_name . '_' . $fed->federation_name] = $fed->federation_name;
                    break;
                default:
                    $national = \app\models\TblNational::find()->where(['national_code' => $val->organizationCode])->select('national_name')->one();
                    if ($national)
                        $values[$val->organizationCode . '_' . $national->national_name] = $national->national_name;
                    break;
            }
        }
//         exit;
        return (!empty($values)) ? $values : [];
    }

    public static function getRange($flag, $union = null) {

        $query = \app\models\SystemConfiguration::find()->where(['module_name' => $flag]);

        if (Yii::$app->session->get('organizations_type') == 'UNION') {
            $query->andWhere(['organization_id' => explode(',', Yii::$app->session->get('organizations_code'))]);
        }
        $systemConfig = $query->one();
        $from = 0;
        $to = 100000;
        if ($systemConfig) {
            $from = $systemConfig->from_value;
            $to = $systemConfig->to_value;
        }

        return ['from' => $from, 'to' => $to];
    }

    /**
     * Description: get local name in gridview
     * @param type $model
     * @param type $field
     * @param type $field_value
     * @param type $languageId
     * @return type
     */
    public static function getLocalName($model, $field, $field_value, $languageId = NULL) {

        $languageId = Yii::$app->session->get('LanguageId');
        $model_name = Yii::$app->path->getModel($model);

        $record = $model_name::find()->where(['language_code' => $languageId, $field => $field_value])->select('local_name')->one();

        return isset($record) ? $record->local_name : '';
    }

    public static function getLocalAddress($model, $field, $field_value, $languageId = NULL) {

        $languageId = Yii::$app->session->get('LanguageId');
        $model_name = Yii::$app->path->getModel($model);
        $record = $model_name::find()->where(['language_code' => $languageId, $field => $field_value])->select('local_address')->one();
        return isset($record) ? $record->local_address : '';
    }

    public static function getLocalShortName($model, $field, $field_value, $languageId = NULL) {

        $languageId = Yii::$app->session->get('LanguageId');
        $model_name = Yii::$app->path->getModel($model);
        $record = $model_name::find()->where(['language_code' => $languageId, $field => $field_value])->select('local_name_short')->one();
        return isset($record) ? $record->local_name_short : '';
    }

    public static function getLocalDescription($model, $field, $field_value, $languageId) {

        $model_name = Yii::$app->path->getModel($model);

        $record = $model_name::find()->where(['language_code' => $languageId, $field => $field_value])->select('local_description')->one();

        return isset($record) ? $record->local_description : '';
    }

    public static function getClassFromTable($table_name) {
        $modelName = str_replace('_', ' ', $table_name);
        $modelName = ucwords($modelName);
        return '\\app\models\\' . str_replace(' ', '', $modelName);
    }

    public static function getStates($value) {

//$model = IdentityMaster::find()->where(['organization_type'=>$value])->one();

        $return_array = [];
        switch ($value) {
            case 'Federations':
                $record = TblFederations::find()->where(['federation_code' => Yii::$app->session->get('organizations_code')])->one();
                break;
            case 'Unions' :
                $record = TblUnions::find()->where(['union_code' => explode(',', Yii::$app->session->get('organizations_code'))])->one();
                break;
            default:
                $record = TblNational::find()->where(['national_code' => Yii::$app->session->get('organizations_code')])->one();
                break;
        }
        if ($record) {
            $states = explode(',', $record->state_code);
            foreach ($states as $row) {
                $statename = TblStates::find()->where(['state_code' => $row])->select('state_name')->one();
                $return_array[$row] = [$statename->state_name];
            }
        }

        return $return_array;
    }

    public static function organizationSessionCheck() {
        if (Yii::$app->session->get('organizations_type') == 'NATIONAL' || Yii::$app->session->get('LanguageId') == 0)
            return FALSE;
        else
            return true;
    }

    public static function getRecordStatus($status) {
        return $status == 1 ? 'Active' : 'In Active';
    }

    /**
     * validate bank detail if bank code is not empty
     * @param type $bank
     * @param type $attribute
     * @param type $params
     * @return boolean
     */
    public function validateBankDetail($bank, $attribute, $params) {
        if ((!empty($bank->bank_code))) {
            if (empty($bank->branch_code) || empty($bank->bank_account_no) || empty($bank->ifsc)) {
                $bank->addError($attribute, Yii::t('app/validation', $bank->getAttributeLabel($attribute) . ' cannot be blank.'));
                return false;
            }
        }
    }

    public function validateName($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[a-zA-Z ]+$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' should contain Alphabetic Character Only'));
                return false;
            }
    }

    public function validateAlphaNumber($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[a-zA-Z0-9 ]+$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' should not contain the special characters'));
                return false;
            }
    }

    public function validateDiscriptiveField($model, $attribute, $has_strict_address_validation = TRUE) {
        if (!empty($model->$attribute)) {
            $validationFailed = FALSE;
            if ($has_strict_address_validation) {
                if (!preg_match('/^[a-z0-9 .,\-]+$/i', $model->$attribute)) {
                    $validationFailed = TRUE;
                }
            } else {
                if (preg_match('/[<>&"\']/', $model->$attribute)) {
                    $validationFailed = TRUE;
                }
            }
            if ($validationFailed) {
                $model->addError($attribute, Yii::t('app/validation', 'Please enter valid ' . $model->getAttributeLabel($attribute) . '.'));
                return false;
            }
        }
    }

    public function validateIfsc($model, $attribute, $params) {
        if (!empty($model->$attribute)) {
            if (!preg_match('/[a-zA-Z]{4}[0][a-zA-Z0-9]{6}$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', ' You have entered invalid ifsc code. e.g. "SBIN0005748"(length=11).'));
                return false;
            }
        }
    }

    public function validatePancard($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', ' You have entered invalid pancard number. e.g. "AAAPL1234C".'));
            }
    }

    public function validateAadharcard($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[0-9]{12}$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', $attribute . ' card number can only contain exactly 12 digits.'));
            }
    }

    public function vaildateMobileNumbers($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[0-9]{10}$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' must contain exactly 10 digits.'));
            }
    }

    public function vaildatePhoneNumbers($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[0-9]{10,16}$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' must contain minimum 10 and maximum 16 digits.'));
            }
    }

    public function vaildateServiceTax($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^\d+(?:\.\d{2})?$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', 'Please enter valid ' . $model->getAttributeLabel($attribute)));
            }
    }

    public function vaildateNumericField($model, $attribute, $params) {
        if (!empty($model->$attribute)) {
            if (!preg_match('/^[1-9][0-9]*$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', 'Please enter valid ' . $model->getAttributeLabel($attribute) . '. e.g "25"'));
            }
        }
    }

    public function vaildateLocalField($model, $attribute, $params) {
        if (!empty($model->$attribute)) {
            if (strlen($model->$attribute) == mb_strlen($model->$attribute, 'UTF-8')) {
                $model->addError($attribute, Yii::t('app/validation', 'Data Should be in UTF-8 Format'));
            }
        }
    }

    public function validateTime($model, $attribute) {
        if (!empty($model->$attribute)) {
            $value = explode(':', $model->$attribute);
            if ((!preg_match('/^[0-9]{2}[:][0-9]{2}$/', $model->$attribute)) || $value[0] > 24 || $value[1] > 60) {
                $model->addError($attribute, Yii::t('app/validation', 'Please enter valid ' . $model->getAttributeLabel($attribute) . ' Formate. e.g "01:00"'));
            }
        }
    }

//    public function validateBranch($model,$attribute,$params) {
//        if(!empty($model->$attribute)){
//            $branch = new TblBranch();
//            $data = $branch->getBranchIfcs($model->ifsc);
//           
//            if(!$data){
//                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) ." '".$model->branch_code."'". ' is invalid.'));
//               return false;
//            }else{
//                if($data->branch_code != $model->branch_code){
//                    $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) ." '".$model->branch_code."'". ' is invalid.'));
//                    return false;
//                }else{
//                    $hamlet = new TblHamlets();
//                    $hamlet= $hamlet->getRecord($model->hamlet_code);
//                    if($hamlet){
//                        $district = $hamlet->villageCode->subDistrictCode->district_code;
//                        $mapping = new TblBanksDistrictsMapping();
//                        $mapping = $mapping->getRecord($data->bank_code,$district);
//                        if(!$mapping){
//                            $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) ." '".$model->branch_code."'". ' is invalid.'));
//                            return false;
//                        }else{
//                            $model->bank_code = $data->bank_code;
//                        }
//                    }
//                }
//            }
//        }
//    }

    public function validateBranch($model, $attribute, $params) {
        if (!empty($model->$attribute)) {
            $data = Yii::$app->general->validateActiveRelation($model, 'TblBranch', 'branch_code', 'branch_code', 'Branch Code', $params, 'ifsc,bank_code');
            if ($data['msg'] != '') {
                $model->addError($attribute, Yii::t('app/validation', $data['msg']));
                return false;
            } else {
                $model->bank_code = $data['model']->bank_code;
                $bank = Yii::$app->general->validateActiveRelation($model, 'TblBanks', 'bank_code', 'bank_code', 'Bank', $params);
                if ($bank != '') {
                    $model->addError($attribute, Yii::t('app/validation', $bank));
                    return false;
                } else {
//                    if($data['model']->branch_code != $model->branch_code){
//                        $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) ." '".$model->branch_code."'". ' is invalid.'));
//                        return false;
//                    }else{
                    $model->ifsc = $data['model']->ifsc;
                    $district = $model->district_code;
                    $mapping = new TblBanksDistrictsMapping();
                    $mapping = $mapping->getRecord($data['model']->bank_code, $district);
                    if (!$mapping && $data['model']->bankCode->nationalized_bank == 0) {
                        $model->addError($attribute, Yii::t('app/validation', "District is not mapped in relevant bank for branch '" . $model->branch_code . "'."));
                        return false;
                    } else {
                        $model->bank_code = $data['model']->bank_code;
                    }
//                    }
                }
            }
        } else {
            $model->ifsc = '';
            $model->bank_code = '';
        }
    }

    public function validateIsBmc($model, $attribute, $params) {
        if (!empty($model->is_bmc)) {
            if (!in_array($model->$attribute, ['0', '1', '2', '3'])) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' must be from "0" to "4".'));
                return false;
            }
        }
    }

    public function validateMilkType($model, $attribute, $params) {
        if (!empty($model->milk_type_code)) {
            $milkType = new TblAnimalType();
            $data = $milkType->getRecords();
            if (!in_array(strval($model->milk_type_code), array_map('strval', array_column($data, 'animal_type_code')), true)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . " '" . $model->milk_type_code . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function getUserName($username) {
        $name = explode('#', $username);
        $username = isset($name[1]) ? $name[1] : $name[0];

        return $username;
    }

    public function getUnionName($model) {
        if (isset($model->dcsCode)) {
            return Yii::$app->general->getforeignkey($model->dcsCode->unionCode, 'union_name');
        } else {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'union_code');
        }
    }

    public function array_flatten($array, $isKey = '') {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!empty($isKey))
                    $result = $result + $this->array_flatten($value);
                else
                    $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function getLocalCode($tableName) {

//        $identity = new IdentityMaster();
//        $idenRecord = $identity->getIdentity();

        $orgCode = Yii::$app->session->get('organizations_code');
        $len = strlen($orgCode);

        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`local_code` FROM " . $len . " +2)) AS UNSIGNED)) as local_code")
                ->from($tableName)
                ->where('(CAST(trim(SUBSTRING(local_code, 1,' . $len . ')) AS UNSIGNED))="' . trim($orgCode) . '"')
                ->one();

        $code1 = (int) $val['local_code'] + 1;

        $value = $orgCode . '-' . $code1;
        return $value;
    }

    public function getCodeAutoIncrement($model, $autoIncrement = 1) {
        $primaryKey = $model->tableSchema->primaryKey[0];
        $tableName = $model->tableName();
        $maxValue = (new \yii\db\Query())
                ->select(["MAX(CAST(LTRIM(RTRIM([{$primaryKey}])) AS INT)) AS max_value"])
                ->from($tableName)
                ->scalar();
        return (int) $maxValue + $autoIncrement;
    }

    public function getOrganizationName() {
        return [0 => ['national_code' => 91, 'national_name' => 'PCDF']];
    }

    public function uploadFile($file, $model, $name = "logo_path", $folder = 'attachments') {
        $info = $this->getPath($folder, $file->baseName, '.' . $file->extension);
        if ($file->saveAs($info['path'])) {
            $model->$name = $info['name'];
            $model->updateAttributes([$name]);
        }
    }

    private function getPath($folder, $name, $ext) {
        $path = Yii::getAlias('@webroot') . '/web/uploads/' . $folder . '/' . $name . $ext;
        $info = [];
        if (!file_exists($path)) {
            $info['path'] = $path;
            $info['name'] = $name . $ext;
            return $info;
        } else {
            $name = $name . '1';
            return $this->getPath($folder, $name, $ext);
        }
    }

    public function getAttachmentLink($ext, $src) {
        ?><li><span><?php
                switch (1) {
                    case (in_array($ext, array('.gif', '.jpg', '.jpeg', '.png', '.bmp'))):
                        return Html::a(Html::img($src, ['class' => 'img-responsive']), $src, ['data-toggle' => 'modal', 'data-target' => '#attachedImg']);
                        break;
                    case $ext == '.pdf':
                        return Html::a('<i class="fa fa-file-pdf-o"></i>', $src);
                    case (in_array($ext, array('.doc', '.docx'))):
                        return Html::a('<i class="fa fa-file-o"></i>', $src);
                        break;
                    case $ext == '.xls':
                        return Html::a('<i class="fa fa-file-excel-o"></i>', $src);
                        break;
                    default:
                        return Html::a('<i class="fa fa-file-text-o"></i>', $src);
                        break;
                }
                ?></span></li><?php
    }

    public function checkAccess($route, $superadmin = 'true') {
        return User::canRoute($route, $superAdminAllowed = $superadmin);
    }

    public function getforeignkey($value, $field) {
        return !empty($value) ? $value->$field : '';
    }

    public function getforeignkeyWithArray($value, $field) {
        $returnValue = '';
        if (!empty($value[0])) {
            foreach ($value as $val) {
                $returnValue = !empty($returnValue) ? $returnValue . ', ' . $val->$field : $val->$field;
            }
        } else {
            $returnValue = !empty($value) ? $value->$field : '';
        }
        return $returnValue;
    }

    public function valiadteUnique($model, $field, $value, $msg = '') {

        $primaryKey = $model->tableSchema->primaryKey[0];
        $values = $model->find()->where([$field => ucwords($value), 'is_active' => 1])->andWhere(['<>', $primaryKey, $model->$primaryKey])->count();
        if ($values != 0) {

            $modleName = StringHelper::basename(get_class($model));
            $field = strtolower($modleName) . '-' . $field;
            $value = !empty($msg) ? $msg : $value;
            return 0;
        }
        return 1;
    }

    public function validateActiveRelation($model, $modelName, $parentField, $childField, $parentLabel, $childLabel, $returnParams = '') {

        $className = Yii::$app->path->getModel($modelName);
        $returnParams = ($returnParams) ? ',' . $returnParams : '';
        if (!is_array($parentField)) {
            $check = $className::find()->select('is_active' . $returnParams)->where([$parentField => $model->{$childField}])->one();
        } else {
            $check = $className::find()->select('is_active' . $returnParams);
            foreach ($parentField as $key => $pf) {
                $check->andwhere([$pf => $model->{$childField[$key]}]);
            }
            $check = $check->one();
        }

        $msg = '';
        if (!$check)
            $msg = $parentLabel . ' is invalid.';
        else if ($check->is_active != '1')
            $msg = 'You can not add ' . ucfirst($childLabel) . ' for deleted/inactive ' . ucfirst($parentLabel) . '.';

        if ($returnParams == '')
            return $msg;
        else
            return ['msg' => $msg, 'model' => $check];
    }

    public function getChildOrgs() {
        switch (Yii::$app->session->get('UserType')) {
            case 2:
                $orgs = ['UNION', 'DCS', 'PLANT', 'MCC', 'BMC'];
                $i = 2;
                break;
            case 3:
                $orgs = ['UNION', 'DCS', 'PLANT', 'MCC', 'BMC'];
                $i = 3;
                break;
            case 4:
                $orgs = ['DCS', 'MCC', 'BMC'];
                $i = 4;
                break;
            case 5:
                $orgs = ['MCC', 'BMC', 'DCS'];
                $i = 5;
                break;
            case 6:
                $orgs = ['BMC', 'DCS'];
                $i = 6;
                break;
            case 7:
                $orgs = ['none'];
                $i = 7;
                break;
            default :
                $orgs = ['none'];
                $i = 0;
        }
        return [$orgs, $i];
    }

    public function getMappedData($filter = [], $model_val, $select_param, $filter_param) {
        $data = [];
        if (!empty($filter)) {
            $model_name = Yii::$app->path->define($model_val);
            $model = new $model_name();
            $mapping = $model->find()->select($select_param)->where([$filter_param => $filter])->asArray()->all();
            if (!empty($mapping)) {
                $map = array_values(yii\helpers\ArrayHelper::getColumn($mapping, $select_param));
                if (!empty($data))
                    array_merge($data, $map);
                else
                    $data = $map;
            }
        }
        return $data;
    }

    public function checkDirectory($path, $permission = '0755') {

        if (file_exists($path)) {
            if (!is_dir($path)) { //if file is already present, but it's not a dir
                if (mkdir($path, 0777, true) == false) {
                    die('Failed to create folders...' . $path);
                    return false;
                }
                if (strstr($path, 'EKOMILK') || strstr($path, 'LOCALBIPL')) {
                    $command = 'chmod 777 -R ' . $path;
                    exec($command);
                }
            }
        } else { //no file exists with this name
            if (!is_dir($path)) {
                if (mkdir($path, 0777, true) == false) {
                    die('Failed to create folders...' . $path);
                    return false;
                }
                if (strstr($path, 'EKOMILK') || strstr($path, 'LOCALBIPL')) {
                    $command = 'chmod 777 -R ' . $path;
                    exec($command);
                }
            }
        }



        return true;
    }

    public function createLogFile($path, $text, $file_name = '', $append = '') {
        if (!empty($append)) {
            $path = $path . '\\' . $append;
        }
        $path = str_replace('\\', '/', realpath(\Yii::$app->basePath . '/../')) . $path;
        $dir = $this->checkDirectory($path);
        if ($dir) {
            if (empty($file_name)) {
                $timestamp = date('d-m-Y-H-i-s');
                $fileName = $path . "/" . $timestamp . '.txt';
            } else {
                $fileName = $path . "/" . $file_name . '.txt';
            }
            $logfile = fopen($fileName, "w") or die("Unable to open file!");
            fwrite($logfile, $text);
            fclose($logfile);
        }
        return;
    }

    public function findCensus($model, $attribute) {
        if (!empty($model->$attribute)) {
            $code = TblSocietyVendor::find()->where(['dcs_code' => $model->$attribute, 'vendor_code' => 'BIPL'])->one();
            if (empty($code)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . " '" . $model->$attribute . "'" . ' not found.'));
                return false;
            }
        }
    }

    public function isVendor($dcs_code, $vendor) {
        if (!empty($dcs_code) && !empty($vendor)) {
            $code = TblSocietyVendor::find()->where(['dcs_code' => $dcs_code, 'vendor_code' => $vendor])->one();
            return empty($code) ? false : true;
        }
        return false;
    }

    public function validateBiplMilkType($model, $attribute) {
        if (!empty($model->$attribute)) {
            if (!in_array(strtolower($model->$attribute), ['cow', 'buffalo', 'mix', 'mixed'])) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' can only have values from: Cow, Buffalo, Mix, Mixed'));
                return false;
            }
        }
    }

    public function filterByOrg($query, $model, $union_table = '', $plant_table = 'tbl_dcs', $bmc_table = 'tbl_dcs', $dcs_table = '') {
        $model_class = (new \ReflectionClass($model))->getShortName();
        $filter_model = new SearchFilter();
        $filter_data = $filter_model->getRecord($model_class);
        if (!empty($filter_data)) {
            $filters = $filter_data['filter'];
            $q_param = Yii::$app->request->queryParams;
            if (isset($q_param[$model_class])) {
                $data = $q_param[$model_class];
                isset($data['f_union_code']) ? $model->f_union_code = $data['f_union_code'] : NULL;
                isset($data['f_plant_code']) ? $model->f_plant_code = $data['f_plant_code'] : NULL;
                isset($data['f_mcc_code']) ? $model->f_mcc_code = $data['f_mcc_code'] : NULL;
                isset($data['f_bmc_code']) ? $model->f_bmc_code = $data['f_bmc_code'] : NULL;
                isset($data['f_dcs_code']) ? $model->f_dcs_code = $data['f_dcs_code'] : NULL;
            }
            $tablename = $model->tableSchema->fullName;

            if (in_array('f_dcs_code', $filters)) {
                $dcsTable = !empty($dcs_table) ? $dcs_table : $tablename;
                if (Yii::$app->session->get('Dcs') !== '')
                    $query->andFilterWhere([$dcsTable . '.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
                if (!empty($model->f_dcs_code))
                    $query->andFilterWhere([$dcsTable . '.dcs_code' => $model->f_dcs_code]);
            }

            if (in_array('f_bmc_code', $filters)) {
                if (Yii::$app->session->get('BMC') !== '')
                    $query->andFilterWhere([$bmc_table . '.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
                if (!empty($model->f_bmc_code))
                    $query->andFilterWhere([$bmc_table . '.bmc_code' => $model->f_bmc_code]);
            }

            if (in_array('f_mcc_code', $filters)) {
                if (Yii::$app->session->get('MCC') !== '')
                    $query->andFilterWhere([$plant_table . '.mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
                if (!empty($model->f_mcc_code))
                    $query->andFilterWhere([$plant_table . '.mcc_plant_code' => $model->f_mcc_code]);
            }

            if (in_array('f_plant_code', $filters)) {
                if (Yii::$app->session->get('Plant') !== '')
                    $query->andFilterWhere([$plant_table . '.plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
                if (!empty($model->f_plant_code))
                    $query->andFilterWhere([$plant_table . '.plant_code' => $model->f_plant_code]);
            }

            if (in_array('f_union_code', $filters)) {
                $union_table = !empty($union_table) ? $union_table : $tablename;
                if (Yii::$app->session->get('Unions') !== '')
                    $query->andFilterWhere([$union_table . '.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
                if (!empty($model->f_union_code))
                    $query->andFilterWhere([$union_table . '.union_code' => $model->f_union_code]);
            }
        }
    }

    public function validateLocalCode($model, $attribute) {
        if (!empty($model->$attribute)) {
            $mcode = str_pad($model->$attribute, 4, '0', STR_PAD_LEFT);
            $code = TblMember::find()->where(['dcs_code' => $model->census_code, 'member_code' => $model->census_code . $mcode])->one();
            if (empty($code)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . " '" . $model->$attribute . "'" . ' not found.'));
                return false;
            }
        }
    }

    public function getEntryType() {

        switch (Yii::$app->session->get('organizations_type')) {
            case 'NATIONAL':
                return 0;
                break;
            case 'FEDERATION':
                return 1;
                break;
            case 'UNION':
                return 2;
                break;
        }
    }

    public function getEntryValue($entry_type) {

        switch ($entry_type) {
            case '0':
                return 'NATIONAL';
                break;
            case 1:
                return 'FEDERATION';
                break;
            case 2:
                return 'UNION';
                break;
            case 3:
                return 'DCS';
                break;
            case NULL:
                return '';
                break;
        }
    }

    public function getDefaultContactDetail($code, $module) {
        $detail = TblContactDetails::find()->where(['is_active' => 1, 'is_default' => 1, 'module_code' => (string) $code, 'module_name' => $module])->one();
        return $detail;
    }

    public function getDefaultBankDetail($code, $module) {
        $detail = TblBankDetails::find()->where(['is_active' => 1, 'is_default' => 1, 'module_code' => $code, 'module_name' => $module])->one();
        return $detail;
    }

    public function getNotifications() {
        $notifications = TblNotifications::find()->where(['is_active' => 1])->orderBy(['id' => SORT_DESC])->all();
        return $notifications;
    }

    public function getshift($shift) {
        $shift_time = '00:00:00';
        if ($shift == 1) {
            $shift_time = '06:00:00';
        } else if ($shift == 2) {
            $shift_time = '18:00:00';
        }
        return $shift_time;
    }

    public function encryptData($string) {
        return !empty($string) ? \Yii::$app->encrypter->encrypt($string) : $string;
    }

    public function decryptData($string) {
        if ($string == null)
            return FALSE;
        $decryptedData = !empty($string) ? \Yii::$app->encrypter->decrypt($string) : $string;
        if ($decryptedData) {
            return $decryptedData;
        }
        return FALSE;
    }

    public function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data) {
        if (in_array(explode('/', $data)[0], ['restservices', 'webservice', 'androiddpu', 'embededdpu', 'bkgprocess', 'dataexchange', 'clienterp', 'privacy-policy'])) {
            return $data;
        }
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function filterByNumber($query, $model, $fields) {
        $tablename = $model->tableSchema->fullName;
        foreach ($fields as $fd) {
            if (!empty($model->{$fd}) || $model->{$fd} == 0) {
                if (preg_match('/^[0-9][0-9]*$/', $model->{$fd})) {
                    $query->andwhere([$fd => $model->{$fd}]);
                } else {
                    $query->andFilterWhere(['like', $fd, $model->{$fd}]);
                }
            }
        }
    }

    public function dropdownRange($model, $field, $range) {
        $model_name = Yii::$app->path->define($model);
        $model = new $model_name();
        $min = $model::find()->select('min(' . $field . ') as ' . $field)->one();
        $max = $model::find()->select('max(' . $field . ') as ' . $field)->one();
        $min_data = (int) $min[$field];
        $max_data = (int) $max[$field];
        $array = [];
        $i = $min_data;
        $j = $min_data;
        for ($min_data; $min_data <= $max_data; $min_data += $range) {
            $array[$i . ',' . $i += $range] = $j . ' <= ' . $j += $range;
        }
        return $array;
    }

    public function filterByDropdownRange($query, $model, $fields) {
        if (is_array($fields)) {
            foreach ($fields as $fd) {
                if (!empty($model->{$fd})) {
                    $range = explode(',', $model->{$fd});
                    $query->andFilterWhere(['between', $fd, $range[0], $range[1]]);
                }
            }
        } else {
            $range = explode(',', $model->{$fields});
            $query->andFilterWhere(['between', $fields, $range[0], $range[1]]);
        }
    }

    public function validateAge($model, $attribute, $params) {
        if (!empty($model->$attribute)) {
            $from = new DateTime($model->$attribute);
            $to = new DateTime('today');
            $age = $from->diff($to)->y;
            if ($age < 18) {
                $model->addError($attribute, Yii::t('app/validation', 'Member\'s age should be greater than 18 years.'));
            }
        }
    }

    public function CurrencyFormat() {
        return ['decimal', 2];
    }

    public function ColoumnAlign() {
        return 'right';
    }

    public function validateVehiclePayment($model) {
        $wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);
        $payment_model = new \app\modules\payment\models\TblVehiclePayment();
        $data = $payment_model->find()
                ->where(['=', 'vehicle_code', $model->vehicle_code])
                ->andWhere(['<=', 'from_date', $wef_date])
                ->andWhere(['>=', 'to_date', $wef_date])
                ->andWhere(['!=', 'status', 'processed'])
                ->one();
        if (!empty($data)) {
            $model->addError('wef_date', "Payment for that vehicle has been sent or disbursed");
            return false;
        } else {
            return true;
        }
    }

    public function sendEmail($subject, $body, $to_mail) {
        try {
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            $headers[] = 'From: PCDF <no-reply@portal.pcdf-eipl.com>';
            mail($to_mail, $subject, $body, implode("\r\n", $headers));
            return true;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            return true;
        }
    }

    public function getmultiforeignkey($value, $relations = [], $field) {
        $data = '';
        if (isset($value)) {
            foreach ($relations as $key => $rel) {
                $value = $value->$rel;
                if (!isset($value)) {
                    $data = 'error';
                    break;
                }
            }
        } else {
            $data = 'error';
        }
        return $data == '' ? (!empty($value->$field) ? $value->$field : '') : '';
    }

    public function getSpData($sp, $param, $execute = false, $db = 'db', $dbtype = 'sql') {
        $str = '';
        $count = count($param);
        for ($i = 1; $i <= $count; $i++) {
            $str .= ':paramName' . $i . ',';
        }
        $str = substr($str, 0, -1);
        if ($dbtype == 'mysql') {
            $command = \Yii::$app->{$db}->createCommand("CALL {$sp}({$str})");
        } else {
            $command = \Yii::$app->{$db}->createCommand("{CALL {$sp}({$str})}");
        }
        $i = 1;
        foreach ($param as $key => $value) {
            $command->bindValue(':paramName' . $i, $value);
            $i++;
        }
        if ($execute) {
            return $command->execute();
        } else {
            return $command->queryAll();
        }
    }

    public function validateGlobalData($model, $attribute, $flag, $limit = FALSE, $show_error = true, $where = [], $numericVal = false) {
        $dropDown = new DropDown();
        $labelData = $dropDown->getLabels($flag);
        $fields = explode(',', $labelData['fields']);
        $model_name = Yii::$app->path->define($labelData['model']);
        $datamodel = new $model_name();

        if ($datamodel->hasAttribute('is_active')) {
            $where['is_active'] = 1;
        }
        $len = strlen($model->$attribute);
        if (!preg_match('/^[0-9]*$/', $model->$attribute)) {
            $query = $datamodel->find()
                    ->select([$fields[0]])
                    ->where($where)
                    ->andWhere(['UPPER(SUBSTRING(' . $fields[1] . ', 1, ' . $len . '))' => strtoupper($model->$attribute)]);
            if ($limit) {
                $query->limit(1);
            }
            $records = $query->all();
        } else {
            if ($numericVal) {
                $records = $datamodel->find()
                                ->select([$fields[0]])
                                ->where([$fields[1] => strval($model->$attribute)])
                                ->andWhere($where)->all();
            } else {
                $records = $datamodel->find()
                                ->select([$fields[0]])
                                ->where([$fields[0] => $model->$attribute])
                                ->andWhere($where)->all();
            }
        }
        if (!empty($records)) {
            if (count($records) == 1) {
                $model->$attribute = $records[0]->{$fields[0]};
            } else {
                if ($show_error) {
                    $model->addError($attribute, Yii::t('app/validation', 'Please Check ' . ucfirst(ucwords(str_replace('_', ' ', $attribute))) . ' Value.'));
                    return false;
                } else {
                    $model->$attribute = NULL;
                }
            }
        }
    }

    public function PrepareDsn($model) {
        $dsn = '';
        switch ($model->db_type) {
            case 'mysql':
                $dsn = 'mysql:host=' . $model->db_host;
                $dsn .= (!empty($model->db_port) && $model->db_port != '3306' ) ? ':' . $model->db_port : '';
                $dsn .= ';dbname=' . $model->db_name;
                break;
            case 'sql' :
                $dsn = 'sqlsrv:server=' . $model->db_host;
                $dsn .= !empty($model->db_port) ? ',' . $model->db_port : '';
                $dsn .= ';Database=' . $model->db_name . ';ConnectionPooling=0';
                break;
        }
        return $dsn;
    }

    public function SetDBConnection($db, $connection) {
        \Yii::$app->{$db}->close();
        Yii::$app->{$db}->dsn = \Yii::$app->general->PrepareDsn($connection);
        Yii::$app->{$db}->username = $connection->db_username;
        Yii::$app->{$db}->password = $connection->db_password;
    }

    public function getSentBoxCodes($plant_code = '', $mcc_code = '', $bmc_code = '', $union_code = '', $vlc_code = '', $appendVlc = true, $plantFilter = 0) {
//$plantFilter (0-noimpact/1-onlyforplant/2-includeplant)
        $sentboxArray = [];
        $mcc = [];
        $bmc = [];
        $plants = [];
        $plant = [];
        $vlc = [];

        if (!empty($union_code)) {
            $model = new TblPlant();
            $model->union_code = $union_code;
            $modelData = $model->getPlantRecords();
            $plants = array_keys($modelData);
        }
        if (!empty($plant_code)) {
            $plants[] = $plant_code;
        }
        if (!empty($plants)) {
            foreach ($plants as $pl) {
                $plant[] = (string) $pl;
            }
        }
        if ($plantFilter == 1) {
            $plant = array_unique($plant);
            foreach ($plant as $key => $plantCode) {
                $array = [];
                $array['code'] = $plantCode;
                $array['type'] = 'PLANT';
                $sentboxArray[] = $array;
            }
            return $sentboxArray;
        }
        if (!empty($plant)) {
            $model = new TblMccPlant();
            $modelData = $model->getMccRecords($plant);
            $mcc = array_keys($modelData);
        }

        if (!empty($mcc_code)) {
            $mcc[] = $mcc_code;
        }
        if (!empty($mcc)) {
            $model = new TblDcsBmc();
            $modelData = $model->getBmcRecords($mcc);
            $bmc = array_keys($modelData);
        }
        if (!empty($bmc_code)) {
            $bmc[] = $bmc_code;
            $model = new TblDcsBmc();
            $model->bmc_code = $bmc_code;
            $modelData = $model->singleBmcData();
            if (!empty($modelData)) {
                $mcc[] = $modelData->mcc_plant_code;
            }
        }

        if (!empty($bmc) && $appendVlc) {
            $model = new TblDcs();
            $model->bmc_code = $bmc;
            $modelData = $model->getBmcDcsData();
            $vlc = array_values($modelData);
        }
        if (!empty($vlc_code)) {
            if ($appendVlc) {
                $vlc[] = $vlc_code;
            }
            $model = new TblDcs();
            $model->dcs_code = $vlc_code;
            $modelData = $model->getData();
            if (!empty($modelData)) {
                $bmc[] = $modelData->bmc_code;
                $mcc[] = Yii::$app->general->getforeignkey($modelData->bmcCode, 'mcc_plant_code');
            }
        }

        $mcc = array_unique($mcc);

        foreach ($mcc as $key => $mccCode) {
            $model = new TblMccPlant();
            $model->mcc_plant_code = $mccCode;
            $mcc_plant_array = ArrayHelper::getColumn($model->tblMccPlantMain, 'mcc_plant_code');
            $mcc = array_merge($mcc, $mcc_plant_array);
        }
        $mcc = array_unique($mcc);

        $bmc = array_unique($bmc);

        foreach ($bmc as $key => $bmcCode) {
            $model = new TblDcsBmc();
            $model->bmc_code = $bmcCode;
            $bmc_array = ArrayHelper::getColumn($model->tblBmcMain, 'bmc_code');
            $bmc = array_merge($bmc, $bmc_array);
        }



        foreach ($mcc as $key => $mccCode) {
            $array = [];
            $array['code'] = $mccCode;
            $array['type'] = 'MCC';
            $sentboxArray[] = $array;
        }
        $bmc = array_unique($bmc);
        foreach ($bmc as $key => $bmcCode) {
            $array = [];
            $array['code'] = $bmcCode;
            $array['type'] = 'BMC';
            $sentboxArray[] = $array;
        }
        $vlc = array_unique($vlc);
        foreach ($vlc as $key => $vlcCode) {
            $array = [];
            $array['code'] = $vlcCode;
            $array['type'] = 'VLC';
            $sentboxArray[] = $array;
        }

        /* include plant for sentbox */
        if ($plantFilter == 2) {
            if (empty($union_code)) {
                foreach ($bmc as $key => $bmcCode) {
                    $model = new TblDcsBmc();
                    $model->bmc_code = $bmcCode;
                    $modelData = $model->getDcsBmcData('bmc_code');
                    $union_code = !empty($modelData) ? $modelData->union_code : '';
                    break;
                }
                $model = new TblPlant();
                $model->union_code = $union_code;
                $modelData = $model->getPlantRecords();
                $plant = array_keys($modelData);
            }
            $plant = array_unique($plant);
            foreach ($plant as $key => $plantCode) {
                $array = [];
                $array['code'] = $plantCode;
                $array['type'] = 'PLANT';
                $sentboxArray[] = $array;
            }
        }
        /* include plant for sentbox */

        return $sentboxArray;
    }

    public function &camelCaseToUnderscore(&$post_data) {
        if (is_array($post_data)) {
            $post_data = array_combine(array_map(function ($str) {
                        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                    }, array_keys($post_data)), array_values($post_data));
            foreach ($post_data as $key => $val) {
                if (is_array($post_data[$key])) {
                    $arr1 = array_combine(array_map(function ($str) {
                                return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                            }, array_keys($post_data[$key])), array_values($post_data[$key]));
                    $post_data[$key] = $arr1;
                    $this->camelCaseToUnderscore($post_data[$key]);
                }
            }
            return $post_data;
        }
        return $post_data;
    }

    public function dateRangeValidate($model, $attribute, $params, $fromDateField, $toDateField, $count = 366, $operator = '>', $message = '') {
        if (!empty($model->$fromDateField) && !empty($model->$toDateField)) {
            $fDate = date('Y-m-d', strtotime($model->$fromDateField));
            $tDate = date('Y-m-d', strtotime($model->$toDateField));
            if ($tDate < $fDate) {
                $model->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
                return false;
            } else {
                $fDate = date_create($fDate);
                $tDate = date_create($tDate);
                $diff = date_diff($fDate, $tDate);
                $DayCount = $diff->format("%a");
                $DayCount = $DayCount + 1;
                if (($operator == '>' && $DayCount > $count) || ($operator == '!=' && $DayCount != $count)) {
                    if (empty($message)) {
                        $model->addError($attribute, Yii::t('app/validation', 'Day diff can not be greater than 1 year.'));
                    } else {
                        $model->addError($attribute, Yii::t('app/validation', $message));
                    }
                    return false;
                }
            }
        }
    }

    public function getUuid() {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('SELECT NEWID() as id')->queryOne();
        return strtolower($command['id']);
    }

    public function saveAlertNotification($mobile, $message = '', $sms_data = [], $save_data = false, $templateid = '', $content_id = '1') {
//        $content_id = '1';
        $result = Yii::$app->alertnotification->sendSms($content_id, $mobile, $message, $templateid);
        if ($save_data) {
            $model = new TblAlertNotification();
            $model->setAttributes($sms_data);
            $datetime = date('Y-m-d H:i:s');
            $model->content_id = $content_id;
            $model->receiver_detail = $mobile;
            $model->receiver_type = 'SMS';
            $model->message = $message;
            $model->send_status = '2';
            $model->entry_datetime = $datetime;
            $model->pick_datetime = $datetime;
            $model->response_datetime = $datetime;
            $model->response_status = $result;
            $model->template_id = $templateid;
            $model->save();
        }
    }

    public function getDestRelation($type = '') {
        $type = !empty($type) ? strtolower($type) : '';
        switch ($type) {
            case '2' :
                $rel = 'plantCode';
                break;
            case 'plant':
                $rel = 'plantCode';
                break;
            case '1' :
                $rel = 'mccPlantCode';
                break;
            case 'mcc' :
                $rel = 'mccPlantCode';
                break;
            case '0' :
                $rel = 'bmcCode';
                break;
            case 'bmc':
                $rel = 'bmcCode';
                break;
            case 'vendor':
                $rel = 'customerCode';
                break;
            case 'dcs':
                $rel = 'dcsCode';
                break;
            case 'party':
                $rel = 'partyMasterCode';
                break;
            default :
                $rel = '';
        }
        return $rel;
    }

    public function vaildateCheckBoxValue($model, $attribute, $params) {
        if (!empty($model->$attribute)) {
            if (!in_array($model->$attribute, ['0', '1'])) {
                $model->addError($attribute, Yii::t('app/validation', 'Please Check ' . ucfirst(ucwords(str_replace('_', ' ', $attribute))) . ' Value.'));
                return false;
            }
        }
    }

    public function SystemOS() {
        return PHP_OS;
    }

    public function ZipOperation($zipfolder, $operation, $ExtractPath = '', $password = '', $ext = 'csv', $type = '7z', $has_psd = TRUE) {
        $zipexe = Yii::$app->basePath . '/web/utility';
        if (empty($password) && $has_psd) {
            $model = new TblSecurity();
            $data = $model->getRecord();
            if (!empty($data) && !empty($data->col_f)) {
                $password = $data->col_f;
            }
        }
        if (!empty($password) || !$has_psd) {
            $cmd = '';
            if ($this->SystemOS() == 'Linux') {
                if ($operation) {
                    if ($has_psd) {
                        $cmd = "cd  $zipexe && 7za a -p$password $zipfolder.$type  $zipfolder//*.$ext";
                    } else {
                        $cmd = "cd  $zipexe && 7za a -r $zipfolder.$type  $zipfolder//*.$ext";
                    }
                } else {
                    if ($has_psd) {
                        $cmd = "cd  $zipexe && 7za x -p$password $zipfolder -o$ExtractPath";
                    } else {
                        $cmd = "cd  $zipexe && 7za x $zipfolder -o$ExtractPath";
                    }
                }
            } else if ($this->SystemOS() == 'WINNT') {
                if ($operation) {
                    if ($has_psd) {
                        $cmd = "cd  $zipexe && 7za.exe a -p$password $zipfolder.$type  $zipfolder//*.$ext";
                    } else {
                        $cmd = "cd  $zipexe && 7za.exe a -r $zipfolder.$type  $zipfolder//*.$ext";
                    }
                } else {
                    if ($has_psd) {
                        $cmd = "cd  $zipexe && 7za.exe x -p$password $zipfolder -o$ExtractPath";
                    } else {
                        $cmd = "cd  $zipexe && 7za.exe x $zipfolder -o$ExtractPath";
                    }
                }
            }
            exec($cmd);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function SetSecurityEncryptionKey($org_type, $org_code) {
        $key = '';
        switch ($org_type) {
            case 'NATIONAL':
                $key = '59867' . $org_code . '976699686';
                return $key;
                break;
            case 'FEDERATION':
                $key = '8976699' . $org_code . '6598676';
                return $key;
                break;
            case 'UNION':
                $key = '76599' . $org_code . '88121659';
                return $key;
                break;
        }
        return $key;
    }

    public function SetDataType($model) {
        $scema = $model->getTableSchema();
        foreach ($model->attributes as $key => $a) {
            if (!empty($a)) {
                $type = $scema->columns[$key]->type;
                if ($type == 'datetime' || $type == 'date') {
                    $format = ($type == 'date') ? 'php:Y-m-d' : 'php:Y-m-d H:i:s';
                    $a = Yii::$app->controls->save_datetime($a, $format);
                }
                $model->{$key} = $a;
            }
        }
        return $model;
    }

    public function validateGlobalStatic($model, $attribute, $flag) {
        $dropDown = new DropDown();
        $labelData = $dropDown->getRecords($flag);
        $data = $labelData['data'];
        $records = '';
        $errorStr = [];
        foreach ($data as $key => $value) {
            if (!preg_match('/^[0-9]*$/', $model->$attribute)) {
                if (strstr(strtoupper($model->$attribute), strtoupper($value))) {
                    $records = $key;
                } elseif (in_array($key, array($model->$attribute))) {
                    $records = $key;
                }
            } elseif (in_array($key, array($model->$attribute))) {
                $records = $key;
            }
            $errorStr[] = $key . '-' . $value;
        }
        if (array_keys($data) == array_values($data)) {
            $errorMsg = implode('/', array_keys($data));
        } else {
            $errorMsg = implode(', ', $errorStr);
        }
        $model->$attribute = !empty($records) ? $records : $model->$attribute;
        if (empty($records) && $records != '0') {
            $model->addError($attribute, Yii::t('app/validation', 'Please Check ' . ucfirst(ucwords(str_replace('_', ' ', $attribute))) . '. ') . 'Value must be ' . $errorMsg . '.');
            return false;
        }
    }

    public function getCustomer($model, $type, $exCode = false, $bmcCode = false, $refCode = false, $sapCode = false, $consumerCode = false) {
        $type = !empty($type) ? $type : '';
        $name = '';
        if ($exCode) {
            if (strtolower($type) == 'dcs') {
                $name = $this->getforeignkey($model->dcsCode, 'dcs_code_ex');
            } else if (strtolower($type) == 'member') {
                $name = $this->getforeignkey($model->memberCode, 'ex_member_code');
            } else if (strtolower($type) == 'bmc') {
                $name = $this->getforeignkey($model->bmcCode, 'bmc_code_ex');
            } else {
                $name = $this->getforeignkey($model->mainCustomerCode, 'customer_code_ex');
            }
        } else if ($bmcCode) {
            if (strtolower($type) == 'dcs') {
                $name = $this->getforeignkey($model->dcsCode, 'bmc_code');
            } else if (strtolower($type) == 'bmc') {
                $name = $this->getforeignkey($model->bmcCode, 'bmc_code');
            } else {
                $name = $this->getforeignkey($model->mainCustomerCode, 'bmc_code');
            }
        } else if ($refCode) {
            if (strtolower($type) == 'dcs') {
                $name = $this->getforeignkey($model->dcsCode, 'ref_code');
            } else if (strtolower($type) == 'member') {
                $name = $this->getforeignkey($model->memberCode, 'ref_code');
            } else if (strtolower($type) == 'bmc') {
                $name = $this->getforeignkey($model->bmcCode, 'ref_code');
            } else {
                $name = $this->getforeignkey($model->mainCustomerCode, 'ref_code');
            }
        } else if ($sapCode) {
            if (strtolower($type) == 'dcs') {
                $name = $this->getforeignkey($model->dcsCode, 'sap_vendor_code');
            } else {
                $name = $this->getforeignkey($model->mainCustomerCode, 'sap_vendor_code');
            }
        } else if ($consumerCode) {
            if (strtolower($type) == 'dcsmember' || strtolower($type) == 'nonmember') {
                $name = $this->getforeignkey($model->memberCode, 'member_name');
            } else {
                $name = $this->getforeignkey($model->mainCustomerCode, 'customer_name');
            }
        } else {
            if (strtolower($type) == 'dcs') {
                $name = $this->getforeignkey($model->dcsCode, 'dcs_name');
            } else if (strtolower($type) == 'member') {
                $name = $this->getforeignkey($model->memberCode, 'member_name');
            } else {
                $name = $this->getforeignkey($model->mainCustomerCode, 'customer_name');
            }
        }
        return $name;
    }

    public function getUnionConfiguration($union, $field, $for) {
        $model = new TblUnionConfigResult();
        $data = $model->find()->select('config_result_key')->where(['union_code' => $union, 'config_key' => $field, 'config_for' => $for])->one();
        return !empty($data) ? $data->config_result_key : '';
    }

    public function getCheckBmcConfiguration($union, $field, $org_code, $org_type, $process_name) {
        $model = new TblConfig();
        $data = $model->find()->select(['tbl_config_mapping.config_result'])
                        ->join('inner join', 'tbl_config_mapping', 'tbl_config_mapping.config_code = tbl_config.config_code')
                        ->where([
                            'tbl_config.config_for' => $org_type,
                            'tbl_config.process_name' => $process_name,
                            'tbl_config.is_input_config' => 1,
                            'tbl_config.config_key' => $field,
                            'tbl_config_mapping.org_type' => $org_type,
                            'tbl_config_mapping.org_code' => $org_code,
                            'tbl_config_mapping.union_code' => $union,
                        ])->asArray()->one();
        return !empty($data) ? $data['config_result'] : '';
    }

    public function getGroupMappingSetBoxConfig($key = 'bmc_code') {
        return [
                ['table_name' => 'tbl_plant', 'where_clause' => 'plant_code=\'{plant_code}\''],
                ['table_name' => 'tbl_mcc_plant', 'where_clause' => 'mcc_plant_code=\'{mcc_plant_code}\''],
                ['table_name' => 'tbl_bmc', 'where_clause' => $key . '=\'{' . $key . '}\'', 'model_name' => 'TblDcsBmc'],
                ['table_name' => 'tbl_route_mapping', 'where_clause' => '(to_dest=\'{bmc_code}\' and to_type=\'bmc\') or (to_dest=\'{mcc_plant_code}\' and to_type=\'mcc\')'],
                ['table_name' => 'tbl_dcs', 'where_clause' => $key . '=\'{' . $key . '}\''],
                ['table_name' => 'tbl_member', 'where_clause' => 'dcs_code in (SELECT dcs_code from tbl_dcs where ' . $key . '=\'{' . $key . '}\'' . ')'],
                ['table_name' => 'tbl_dpu_incentive_master', 'where_clause' => 'dcs_code in (SELECT dcs_code from tbl_dcs where ' . $key . '=\'{' . $key . '}\'' . ')'],
                ['table_name' => 'tbl_customer_master', 'where_clause' => $key . '=\'{' . $key . '}\'']
        ];
    }

    public function generateGroupMappingSetBox(&$master, $main_org_data, $group_org_data, $key = 'bmc_code', $dest_org_type = 'BMC') {
        $sentbox_config = $this->getGroupMappingSetBoxConfig($key);
        $default_config = ['entry_datetime' => date('Y-m-d H:i:s'), 'status' => 0, 'operation_type' => 'INSERT', 'sentbox_key' => $key, 'dest_org_type' => $dest_org_type];
        foreach ($sentbox_config as $config) {
            $model = new TblGenerateSentbox();
            $model->attributes = $config;
            $model->attributes = $default_config;
            $model->attributes = $group_org_data;
            $model->where_clause = str_replace('{union_code}', $model->union_code, $model->where_clause);
            $model->where_clause = str_replace('{plant_code}', $model->plant_code, $model->where_clause);
            $model->where_clause = str_replace('{mcc_plant_code}', $model->mcc_plant_code, $model->where_clause);
            $model->where_clause = str_replace('{bmc_code}', $model->bmc_code, $model->where_clause);
            $model->where_clause = str_replace('{dcs_code}', $model->dcs_code, $model->where_clause);
            $model->attributes = $main_org_data;
            $master[] = $model;
        }
        return $master;
    }

    public function getAllUnionWiseConfig($union, $for) {
        $model = new TblUnionConfigResult();
        $data = $model->find()->where(['union_code' => $union, 'config_for' => $for])->all();
        return $data;
    }

    public function validateOnUnionConfig($model, $attribute, $flag, $flagVal) {
        $session = isset(Yii::$app->session->get('unionConfig')[$model->union_code][$flag]) ? Yii::$app->session->get('unionConfig')[$model->union_code][$flag] : '';
        if (empty($model->$attribute) && !empty($session) && $session == $flagVal) {
            $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' cannot be blank.'));
            return false;
        }
    }

    public function getSapStatus($label) {
        $data = [
            'X' => Yii::t('app', 'New'),
            'XA' => Yii::t('app', 'Sent'),
            'AZ' => Yii::t('app', 'Success'),
            'XE' => Yii::t('app', 'Error'),
            'Y' => Yii::t('app', 'Edited'),
            'YA' => Yii::t('app', 'Sent'),
            'AZ' => Yii::t('app', 'Success'),
            'YE' => Yii::t('app', 'Error'),
        ];
        return !empty($data[$label]) ? $data[$label] : '';
    }

    public function getStaticDropdownVal($flag, $model, $field) {
        return !empty(Yii::$app->dropdown->getRecords($flag)['data'][$model->{$field}]) ? Yii::$app->dropdown->getRecords($flag)['data'][$model->{$field}] : $model->{$field};
    }

    public function validateCustomer($model) {
        if (empty($model->customer_type) || strtoupper($model->customer_type) == 'DCS') {
            $model->customer_type = 'DCS';
            $dcs = new TblDcs();
            $model->customer_code = $dcs->validDcs($model->customer_code, $model->bmc_code);
        } else if (strtoupper($model->customer_type) == 'PARTY') {
            $model->customer_type = strtoupper($model->customer_type);
            $party = new TblGeneralPartyMaster();
            $model->customer_code = $party->validateGenaralPartyCode($model->customer_code, $model->bmc_code);
        } else {
            $model->customer_type = strtoupper($model->customer_type);
            $model->customer_code = $this->validateCustomerCode($model);
        }
        if (empty($model->customer_code)) {
            $model->addError('customer_code', Yii::t('app/validation', Yii::t('app', 'Code') . ' is invalid'));
        }
    }

    public function validateCustomerCode($model) {
        if (strtolower($model->customer_type) != 'dcs') {
            $prefix = $this->getforeignkey($model->customerType, 'code_prefix');
            $length = $this->getforeignkey($model->customerType, 'code_length');
            $Code = '';
            if (!empty($prefix) && is_numeric($model->customer_code)) {
                $customerModel = new TblCustomerMaster();
                $customerModel->customer_type = $model->customer_type;
                $customerModelData = $customerModel->find()
                        ->where(['customer_type' => $model->customer_type, 'bmc_code' => $model->bmc_code])
                        ->andWhere(['CAST(REPLACE(customer_code_ex,\'' . $prefix . '\', \'\') as int)' => (int) $model->customer_code])
                        ->all();
                if (count($customerModelData) == 1) {
                    $Code = $customerModelData[0]->customer_code;
                    $model->ex_code = $customerModelData[0]->customer_code_ex;
                } else {
                    $Code = $model->customer_code;
                }
            } else {
//$model->ex_code = !empty($length) ? $prefix . str_pad($model->customer_code, $length, '0', STR_PAD_LEFT) : '';
                $model->ex_code = $model->customer_code;
                $Code = $this->getforeignkey($model->customerCode, 'customer_code');
            }
            return $data = empty($Code) ? '' : $Code;
        }
    }

    public function validateGeneratePartyMasterCode($model) {
        if (!empty($model->customer_code) && strtolower($model->customer_type) == 'party') {
            $modelData = TblGeneralPartyMaster::find()
                    ->where(['general_party_master_code' => $model->customer_code])
                    ->one();
            return !empty($modelData) ? $modelData->party_name : '';
        }
    }

    public function validateNameWithDash($model, $attribute) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[a-zA-Z-\\s]+$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', 'Invalid ' . $model->getAttributeLabel($attribute) . '.'));
                return false;
            }
    }

    public function validateDescription($model, $attribute) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[a-zA-Z0-9,\\/@!#$%^&*()_+=\\.\\s-]*$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', 'Invalid ' . $model->getAttributeLabel($attribute) . '.'));
                return false;
            }
    }

    public function validateNameGlobal($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[a-zA-Z0-9-(), ]+$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' should contain Alphabetic Character Only'));
                return false;
            }
    }

    public function decimalformat($value) {
        return sprintf('%0.2f', $value);
    }

    public function getPrimaryCode($model, $autoInc = 1) {
        $primaryKey = $model->tableSchema->primaryKey[0];
        $organizations_code = !empty(Yii::$app->session->get('organizations_code')) ? Yii::$app->session->get('organizations_code') : $model->originating_org_code;
        $orgCode = 'PORTAL-' . $organizations_code . '-';
        $len = strlen($orgCode);
        $val = $model->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,6))) AS " . $primaryKey])
                ->where(['like', $primaryKey, trim($orgCode) . '%', false])
                ->one();
        $code1 = (int) $val[$primaryKey] + $autoInc;
        $value = $orgCode . $code1;

        return $value;
    }

    public function getTransactionCode($model, $primaryCode, $autoInc = 1, $autoLength = 4) {
        $primaryKey = $model->tableSchema->primaryKey[0];
        $orgCode = $primaryCode . 'T';
        $len = strlen($orgCode);
        $val = $model->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1," . $autoLength . "))) AS " . $primaryKey])
                ->where(['like', $primaryKey, trim($orgCode) . '%', false])
                ->one();
        $code1 = (int) $val[$primaryKey] + $autoInc;
        $value = $orgCode . $code1;
        return $value;
    }

    public function allowUpdateDelete($model) {
        return $model->originating_org_type == 'PORTAL';
    }

    public function getStaticValue($value, $flag) {
        return isset(Yii::$app->dropdown->getRecords($flag)['data'][$value]) ? Yii::$app->dropdown->getRecords($flag)['data'][$value] : '';
    }

    public function getDateDifference($fromDate, $toDate) {
        $tenure_from = date_create($fromDate);
        $tenure_to = date_create($toDate);
        $diff = date_diff($tenure_to, $tenure_from);
        return $diff->format("%a");
    }

    public function getUnionKeyPattern($union) {
        $PatternArray = [];
        if (count($union) == 1) {
            $model = new TblKeyPattern();
            $unionKeyPattern = $model->find()->where(['union_code' => $union])->all();
            foreach ($unionKeyPattern as $data) {
                $key_config = [];
                $key_config['ex_code_auto'] = $data->ex_code_auto;
                $key_config['ex_code_length'] = $data->ex_code_length;
                $key_config['ex_code_reset_on'] = $data->ex_code_reset_on;
                $key_config['prefix_field'] = $data->prefix_field;
                $key_config['ref_code_type'] = $data->ref_code_type;
                $key_config['ref_code_length'] = $data->ref_code_length;
                $key_config['ref_code_fix_length'] = $data->ref_code_fix_length;
                $key_config['master_hierarchy_auto_entry'] = $data->master_hierarchy_auto_entry;
                $key_config['key_pattern_code'] = $data->key_pattern_code;
//                $key_config['has_prefix'] = $data->has_prefix;
//$PatternArray[$data->union_code][$data->pattern_for] = $key_config;
                $PatternArray[$data->pattern_for] = $key_config;
            }
            return $PatternArray;
        }
    }

    public function getKeyPattern($table_name, $union_code = null) {

        if (isset(Yii::$app->user) && !Yii::$app->user->isGuest) {
            return !empty(Yii::$app->session->get('unionKeyPattern')[$table_name]) ? Yii::$app->session->get('unionKeyPattern')[$table_name] : NULL;
        }

        static $localCache = [];
        $cacheKey = $table_name . ($union_code ? '_' . $union_code : '');
        if (array_key_exists($cacheKey, $localCache)) {
            return $localCache[$cacheKey];
        }

        $query = TblKeyPattern::find()->where(['pattern_for' => $table_name]);
        if (!empty($union_code)) {
            $query->andWhere(['union_code' => $union_code]);
        }
        $localCache[$cacheKey] = $query->one();
        return $localCache[$cacheKey];
    }

    public function setKeyPattern(&$model, $table_name, $ex_code_key, $auto_code_lenght = 3, $setkeyPattern = '', $conacte = true) {
        $union_code = !empty($model->union_code) ? $model->union_code : NULL;
        $keyPattern = !empty($setkeyPattern) ? $setkeyPattern : $this->getKeyPattern($table_name, $union_code);
        if (!empty($keyPattern)) {
            $ref_code_length = (int) $keyPattern['ref_code_length'];
            $ref_code_fix_length = (int) $keyPattern['ref_code_fix_length'];
            $ex_code_reset_on = $keyPattern['ex_code_reset_on'];
            if ($keyPattern['ex_code_auto'] == 1) {
                $data = $model->find()->select(['ex_code' => 'ISNULL(MAX(CAST(' . $ex_code_key . ' as int)),0)+1'])
                        ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                        ->asArray()
                        ->one();
                $model->{$ex_code_key} = str_pad(($data['ex_code']), $keyPattern['ex_code_length'], '0', STR_PAD_LEFT);
            } else {
                if (!empty($model->{$ex_code_key})) {
                    if ($conacte) {
                        $model->{$ex_code_key} = str_pad(($model->{$ex_code_key}), $keyPattern['ex_code_length'], '0', STR_PAD_LEFT);
                    }
                    $ex_cnt = $model->find()
                            ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                            ->andWhere([$ex_code_key => $model->{$ex_code_key}])
                            ->count();
                    if ($ex_cnt != '0') {
                        $model->addError($ex_code_key, Yii::t('app/validation', $model->getAttributeLabel($ex_code_key) . ' has already been taken.'));
                    }
                    if (strlen($model->{$ex_code_key}) != $keyPattern['ex_code_length']) {
                        $model->addError($ex_code_key, Yii::t('app/validation', $model->getAttributeLabel($ex_code_key) . ' length must be ' . $keyPattern['ex_code_length'] . '.'));
                    }
                } else {
                    $model->addError($ex_code_key, Yii::t('app/validation', $model->getAttributeLabel($ex_code_key) . ' can not be blank.'));
                }
            }
            $data = $model->find()->select(['ref_code' => 'ISNULL(MAX(CAST(RIGHT(ref_code,' . $ref_code_length . ')as bigint)),0)+1', 'auto_code' => 'ISNULL(MAX(auto_code),0)+1'])
                    ->where(['union_code' => $model->union_code])
                    ->asArray()
                    ->one();
            $model->auto_code = $data['auto_code'];
            $pk_code = $model->union_code . str_pad(($data['auto_code']), $auto_code_lenght, '0', STR_PAD_LEFT);
            if ($keyPattern['ref_code_type'] == 0) {
                $model->ref_code = $pk_code;
            } else if ($keyPattern['ref_code_type'] == 1) {
                $prefix_seq = explode(',', $keyPattern['prefix_field']);
                $ref_code = ($keyPattern['ref_code_length'] > 0 ) ? str_pad($data['ref_code'], $keyPattern['ref_code_length'], '0', STR_PAD_LEFT) : '';
                $model->ref_code = '';
                foreach ($prefix_seq as $pre) {
                    $pre_info = explode(':', $pre);
                    if (isset($pre_info[1])) {
                        $t_info = explode('#', $pre_info[0]);
                        $table_name = $t_info[0];
                        $where_key = $t_info[1];
                        $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                        $append_field = $pre_info[1];
                        $query = new Query();
                        $res = $query->select($append_field)
                                        ->from($table_name)
                                        ->where([$where_key => $model->{$where_val}])->one();
                        if (!empty($res)) {
                            $model->ref_code .= $res[$append_field];
                        } else {
                            $message = 'Ref Code : No Data Found for ' . $table_name . '(' . $where_key . '=' . $model->{$where_val} . ')';
                            $model->addError('ref_code', $message);
                            return;
                        }
                    } else {
                        $model->ref_code .= $model->{$pre};
                    }
                }
                $model->ref_code .= $ref_code;
            }
            if (empty($model->ref_code)) {
                $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' can not be blank.'));
            } else if (!preg_match('/^[0-9]*$/', $model->ref_code)) {
                $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' must be numeric.'));
            } else if ($keyPattern['ref_code_type'] == 2) {
                $cnt = $model->find()->where(['union_code' => $model->union_code, 'convert(bigint,ref_code)' => (int) $model->ref_code])
                        ->count();
                if ($cnt > 0) {
                    $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' has already been taken.'));
                }
            }
            $model->ref_code = str_pad(($model->ref_code), $ref_code_fix_length, '0', STR_PAD_LEFT);
            if (strlen($model->ref_code) != $ref_code_fix_length) {
                $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' length must be ' . $ref_code_fix_length . '.'));
            }
            if ($keyPattern['master_hierarchy_auto_entry'] == 1) {
                $this->setKeyPatternChild($keyPattern, $model, $table_name, $pk_code);
            }
            return $pk_code;
        } else {
            $model->addError('auto_code', Yii::t('app/validation', 'Key pattern config missing.'));
            return;
        }
    }

    public function setKeyPatternChild($pattern, &$model, $table_name, $pk_code, $master_key = '', $hierarchyData = [], $is_exist = false) {
        $childPattern = new TblKeyPatternChild();
        $childKeyPatterns = $childPattern->find()->where(['key_pattern_code' => $pattern['key_pattern_code']])->all();
        if (!empty($childKeyPatterns)) {
            if (!empty($hierarchyData)) {
                $masterHierarchy = $hierarchyData;
            } else {
                $masterHierarchy = new TblMasterHierarchy();
                $pk_name = $model::primaryKey()[0];
                $masterHierarchy->attributes = $model->attributes;
                $masterHierarchy->master_key = $pk_name;
                $masterHierarchy->{$pk_name} = $pk_code;
            }
            $masterHierarchy->wef_date = !empty($masterHierarchy->wef_date) ? $masterHierarchy->wef_date : date('Y-m-d');
            $masterHierarchy->is_active = 1;
            foreach ($childKeyPatterns as $key => $keyPattern) {
                $index = $key + 1;
                $key_name = 'ref_code' . $index;
                $masterHierarchy->master_type = $keyPattern->pattern_for;
                $key_length = (int) $keyPattern['key_length'];

                $key_fix_length = (int) $keyPattern['key_fix_length'];
                $key_reset_on = $keyPattern['key_reset_on'];

                if (!$is_exist && $keyPattern['key_code_type'] == 1) {
                    $data = $masterHierarchy->find()->select(['ref_code' => 'ISNULL(MAX(CAST(RIGHT(' . $key_name . ',' . $key_length . ')as bigint)),0)+1'])
                            ->where(['union_code' => $model->union_code])
                            ->asArray()
                            ->one();
                    $ref_code = ($key_length > 0 ) ? str_pad($data['ref_code'], $key_length, '0', STR_PAD_LEFT) : '';
                    if (!empty($keyPattern['prefix_field'])) {
                        $masterHierarchy->{$key_name} = '';
                        $prefix_seq = explode(',', $keyPattern['prefix_field']);
                        foreach ($prefix_seq as $pre) {
                            $pre_info = explode(':', $pre);
                            if (isset($pre_info[1])) {
                                $t_info = explode('#', $pre_info[0]);
                                $table_name = $t_info[0];
                                $where_key = $t_info[1];
                                $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                                $append_field = $pre_info[1];
                                $query = new Query();
                                $res = $query->select($append_field)
                                                ->from($table_name)
                                                ->where([$where_key => $model->{$where_val}])->one();
                                if (!empty($res)) {
                                    $masterHierarchy->{$key_name} .= $res[$append_field];
                                } else {
                                    $message = 'Ref Code : No Data Found for ' . $table_name . '(' . $where_key . '=' . $model->{$where_val} . ')';
                                    $model->addError('ref_code', $message);
                                    return;
                                }
                            } else {
                                $masterHierarchy->{$key_name} .= $model->{$pre};
                            }
                        }
                    }
                    $masterHierarchy->{$key_name} .= $ref_code;
                    if (!empty($keyPattern['suffix_field'])) {
                        $suffix_seq = explode(',', $keyPattern['suffix_field']);
                        foreach ($suffix_seq as $pre) {
                            $pre_info = explode(':', $pre);
                            if (isset($pre_info[1])) {
                                $t_info = explode('#', $pre_info[0]);
                                $table_name = $t_info[0];
                                $where_key = $t_info[1];
                                $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                                $append_field = $pre_info[1];
                                $query = new Query();
                                $res = $query->select($append_field)
                                                ->from($table_name)
                                                ->where([$where_key => $model->{$where_val}])->one();
                                if (!empty($res)) {
                                    $masterHierarchy->{$key_name} .= $res[$append_field];
                                } else {
                                    $message = 'Ref Code : No Data Found for ' . $table_name . '(' . $where_key . '=' . $model->{$where_val} . ')';
                                    $model->addError('ref_code', $message);
                                    return;
                                }
                            } else {
                                $masterHierarchy->{$key_name} .= $model->{$pre};
                            }
                        }
                    }
                } else if ($keyPattern['key_code_type'] == 2) {
                    $masterHierarchy->{$key_name} = !empty($masterHierarchy->{$key_name}) ? $masterHierarchy->{$key_name} : NULL;
                }
                if ($keyPattern['key_code_type'] == 1) {
                    if (empty($masterHierarchy->{$key_name})) {
                        $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' can not be blank.'));
                    } else {
                        $cnt = $masterHierarchy->getActiveCount($key_name, $key_reset_on);
                        if ($cnt > 0) {
                            $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' has already been taken.'));
                        }
                    }
                    if (!empty($masterHierarchy->{$key_name})) {
                        $masterHierarchy->{$key_name} = str_pad(($masterHierarchy->{$key_name}), $key_fix_length, '0', STR_PAD_LEFT);
                        if (strlen($masterHierarchy->{$key_name}) != $key_fix_length) {
                            $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' length must be ' . $key_fix_length . '.'));
                        }
                    }
                }
                if (!empty($hierarchyData)) {
                    $cnt = $masterHierarchy->getActiveCount($key_name, $key_reset_on);
                    if ($cnt > 0) {
                        $model->addError($key_name, Yii::t('app/validation', $model->getAttributeLabel($key_name) . ' has already been taken.'));
                    }
                    if (!empty($masterHierarchy->{$key_name})) {
                        $masterHierarchy->{$key_name} = str_pad(($masterHierarchy->{$key_name}), $key_fix_length, '0', STR_PAD_LEFT);
                        if (strlen($masterHierarchy->{$key_name}) != $key_fix_length) {
                            $model->addError($key_name, Yii::t('app/validation', $model->getAttributeLabel($key_name) . ' length must be ' . $key_fix_length . '.'));
                        }
                    }
                }
            }
            $model->set_master_hierarchy[] = $masterHierarchy;
            return;
        } else {
            $model->addError('ref_code', Yii::t('app/validation', 'Child Key pattern config missing.'));
            return;
        }
    }

    public function getFTPDirStructure($cp_code) {
        $data = [
            '/' . $cp_code . '/DIAG/ARCHIVES/ERRORS/',
            '/' . $cp_code . '/DIAG/ARCHIVES/SUCCESS/',
            '/' . $cp_code . '/MASFILES/ARCHIVES/ERRORS/',
            '/' . $cp_code . '/MASFILES/ARCHIVES/SUCCESS/',
            '/' . $cp_code . '/RIPFILES/ARCHIVES/ERRORS/',
            '/' . $cp_code . '/RIPFILES/ARCHIVES/SUCCESS/',
            '/' . $cp_code . '/DATFILES/ARCHIVES/ERRORS/',
            '/' . $cp_code . '/DATFILES/ARCHIVES/SUCCESS/',
            '/' . $cp_code . '/TDFILES/ARCHIVES/ERRORS/',
            '/' . $cp_code . '/TDFILES/ARCHIVES/SUCCESS/',
        ];
        return $data;
    }

    public function generateFTPDir($model, $attribute, $params, $ftp_conn_code, $cp_code) {
        $ftp_model = new TblFtpDetail();
        $ftp_model->ftp_connection_code = $ftp_conn_code;
        $ftpData = $ftp_model->getData();
        $status = false;
        if (!empty($ftpData)) {
            $ftp = new FTPConnection();
            $ftp->ftp_type = $ftpData->ftp_type;
            $ftp->ftp_host = $ftpData->ftp_host;
            $ftp->ftp_username = $ftpData->ftp_username;
            $ftp->ftp_password = $ftpData->ftp_password;
            $ftp->ftp_port = $ftpData->ftp_port;
            $ftp->isPassiveFtp = !empty($ftpData->ftp_mode) && $ftpData->ftp_mode == 'active' ? false : true;
            $ftpDir = $this->getFTPDirStructure($cp_code);
            foreach ($ftpDir as $dir) {
                $ftp->ftp_path = $ftpData->ftp_path . $dir;
                $localDirPath = \Yii::$app->params['biplDirPath'] . $dir;
                $localDirPath = Yii::$app->basePath . '/' . str_replace(Yii::$app->basePath, '', $localDirPath);
                $localDirPath = str_replace('\\', '/', $localDirPath);
                if ($ftp->CreateDirectory() && $this->checkDirectory($localDirPath)) {
//                if ($ftp->CreateDirectory() && $this->checkDirectory(\Yii::$app->params['biplDirPath'] . $dir)) {
                    $status = true;
                } else {
                    $status = false;
                    break;
                }
            }
            if ($status) {
                $perMissionPath = $ftpData->ftp_path . '/' . $cp_code;
                $perMissionPath = str_replace('//', '/', $perMissionPath);
                $command = 'chmod 777 -R ' . $perMissionPath;
                exec($command);
            }
        }
        if ($status === false) {
            $model->addError($attribute, Yii::t('app/validation', 'FTP Directory not Generated.'));
            return false;
        }
    }

    public function validateDeactivateDcs($model, $date, $dcs = '', $memberCheck = false, $member = '') {
        $dcsCode = !empty($dcs) ? $dcs : $model->dcs_code;
        $checkdate = date('Y-m-d', strtotime($date));
        if ($memberCheck) {
            $memberCode = !empty($member) ? $member : $model->member_code;
            $memberModel = new TblMemberDeactive();
            $records = $memberModel->find()
                    ->where('dcs_code=\'' . $dcsCode . '\' and member_code=\'' . $memberCode . '\'')
                    ->andWhere('((\'' . $checkdate . '\' between cast(from_date as date)  and case when to_date is null then \'9999-12-31\' else cast(to_date as date) end))')
                    ->count();
            if ($records > 0) {
                $model->addError('member_code', Yii::t('app/validation', Yii::t('app', 'Member') . ' Is Deactivated.'));
                return false;
            }
        }

        $memberModel = new TblDcsDeactive();
        $records = $memberModel->find()
                ->where('dcs_code=\'' . $dcsCode . '\'')
                ->andWhere('((\'' . $checkdate . '\' between cast(from_date as date)  and case when to_date is null then \'9999-12-31\' else cast(to_date as date) end))')
                ->count();
        if ($records > 0) {
            $model->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' Is Deactivated.'));
            return false;
        }
    }

    public function vaildateKeyCodes($model, $table_name, $ex_code_key, $pk_key, $conacte = true) {
        if (!$model->isNewRecord) {
            $keyPattern = $this->getKeyPattern($table_name);
            if (!empty($keyPattern)) {
                $ref_code_fix_length = (int) $keyPattern['ref_code_fix_length'];
                $ex_code_reset_on = $keyPattern['ex_code_reset_on'];
                if (empty($model->{$ex_code_key})) {
                    $model->addError($ex_code_key, Yii::t('app/validation', $model->getAttributeLabel($ex_code_key) . ' can not be blank.'));
                } else {
                    $old_ex_code = $model->oldAttributes[$ex_code_key];
                    $allow_update = ($old_ex_code === $model->{$ex_code_key}) ? FALSE : TRUE;
                    if ($allow_update && $conacte) {
                        $model->{$ex_code_key} = str_pad(($model->{$ex_code_key}), $keyPattern['ex_code_length'], '0', STR_PAD_LEFT);
                    }
                    if ($allow_update && strlen($model->{$ex_code_key}) != $keyPattern['ex_code_length']) {
                        $model->addError($ex_code_key, Yii::t('app/validation', $model->getAttributeLabel($ex_code_key) . ' length must be ' . $keyPattern['ex_code_length'] . '.'));
                    } else {
                        $ex_cnt = $model->find()
                                ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                                ->andWhere([$ex_code_key => $model->{$ex_code_key}])
                                ->andWhere(['!=', $pk_key, $model->{$pk_key}])
                                ->count();
                        if ($ex_cnt > 0) {
                            $model->addError($ex_code_key, Yii::t('app/validation', $model->getAttributeLabel($ex_code_key) . ' has already been taken.'));
                        }
                    }
                }
                if (empty($model->ref_code)) {
                    $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' can not be blank.'));
                } else {
                    $model->ref_code = str_pad(($model->ref_code), $ref_code_fix_length, '0', STR_PAD_LEFT);
                    if (strlen($model->ref_code) != $ref_code_fix_length) {
                        $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' length must be ' . $ref_code_fix_length . '.'));
                    } else {
                        $cnt = $model->find()
                                ->where(['union_code' => $model->union_code, 'convert(bigint,ref_code)' => (int) $model->ref_code])
                                ->andWhere(['!=', $pk_key, $model->{$pk_key}])
                                ->count();
                        if ($cnt > 0) {
                            $model->addError('ref_code', Yii::t('app/validation', $model->getAttributeLabel('ref_code') . ' has already been taken.'));
                        }
                    }
                }
            } else {
                $model->addError('ref_code', Yii::t('app/validation', 'Key pattern config missing.'));
                return;
            }
        }
    }

    public function getConfigMapping($config_key, $org_code, $org_type) {
        $model = new TblConfigMapping();
        $data = $model->find()->select(['tbl_config_mapping.config_result'])
                        ->joinWith(['configCode'])
                        ->where(['tbl_config_mapping.org_code' => $org_code,
                            'tbl_config_mapping.org_type' => $org_type,
                            'tbl_config.config_key' => $config_key])->one();
        return !empty($data) ? $data->config_result : '';
    }

    public function validateRateRange($model, $fatAttr = '', $snfAttr = '') {
        $fat = !empty($fatAttr) ? $fatAttr : 'fat';
        $snf = !empty($snfAttr) ? $snfAttr : 'snf';
        $minFat = !empty(Yii::$app->general->getforeignkey($model->rateRange, 'min_fat')) ? Yii::$app->general->getforeignkey($model->rateRange, 'min_fat') : '0.01';
        $maxFat = Yii::$app->general->getforeignkey($model->rateRange, 'max_fat');
        $minSnf = !empty(Yii::$app->general->getforeignkey($model->rateRange, 'min_snf')) ? Yii::$app->general->getforeignkey($model->rateRange, 'min_snf') : '0.01';
        $maxSnf = Yii::$app->general->getforeignkey($model->rateRange, 'max_snf');

        if (($minFat > $model->$fat) || (!empty($maxFat) && $maxFat < $model->$fat)) {
            $model->addError($fat, Yii::t('app/validation', $model->getAttributeLabel('fat') . ' is Invalid'));
        }
        if (($minSnf > $model->$snf) || (!empty($maxSnf) && $maxSnf < $model->$snf)) {
            $model->addError($snf, Yii::t('app/validation', $model->getAttributeLabel('snf') . ' is Invalid'));
        }
    }

    public function paymentCycleLock($model, $dateParam, $codeParam, $for, $type, $flagArray = [], $showError = '') {
        if (!empty($model->$dateParam)) {
            $showError = !empty($showError) ? $showError : $dateParam;
            $date = Yii::$app->formatter->asDate($model->$dateParam, 'php:Y-m-d');

            $payment_model = new TblPaymentCycleApplicability;
            $data = $payment_model->find()
                    ->where(['applicable_code' => $model->$codeParam, 'applicable_for' => $for, 'applicable_type' => $type])
                    ->andWhere(['<=', 'CAST(from_date as date)', $date])
                    ->andWhere(['>=', 'CAST(to_date as date)', $date])
                    ->one();

            if (empty($data)) {
                $model->addError($showError, "Payment Cycle is Not Available");
                return false;
            } else if (!empty($data)) {

                foreach ($flagArray as $flag) {
                    if ($data->$flag == 1) {
                        $model->addError($showError, "Payment Cycle is Locked");
                        return false;
                    }
                }
            }
        }
    }

    public function shiftLock($model, $dateParam, $codeParam, $showError = '', $lock_flag = 'data_lock') {
        if (!empty($model->$dateParam)) {
            $date = Yii::$app->formatter->asDate($model->$dateParam, 'php:Y-m-d');
            $showError = !empty($showError) ? $showError : $dateParam;

            $payment_model = new \app\modules\collection\models\TblMccShiftLock();
            $data = $payment_model->find()
                    ->where([
                        'cast(date_time_of_collection as date)' => $date,
                        'shift_code' => $model->shift_code,
                        $lock_flag => 1,
                        'mcc_plant_code' => $model->$codeParam
                    ])
                    ->one();
            if (!empty($data)) {
                $model->addError($showError, "Shift Is Already Lock");
                return false;
            }
        }
    }

    public function validateBMC($model, $attribute, $hierarchy = FALSE, $check_vendor = FALSE) {
        $bmcModel = new TblDcsBmc();
        $query = $bmcModel->find()->select(['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code']);
        if ($check_vendor) {
            $query->where(['or', ['bmc_code' => $model->$attribute], ['ref_code' => $model->$attribute], ['sap_vendor_code' => $model->$attribute]]);
        } else {
            $query->where(['or', ['bmc_code' => $model->$attribute], ['ref_code' => $model->$attribute]]);
        }
        $records = $query->all();
        if (!empty($records) && count($records) == 1) {
            $model->$attribute = $records[0]->bmc_code;
            if ($hierarchy == 'TRUE') {
                $model->union_code = $records[0]->union_code;
                $model->plant_code = $records[0]->plant_code;
                $model->mcc_plant_code = $records[0]->mcc_plant_code;
            }
        } else {
            $model->addError('bmc_code', Yii::t('app/validation', Yii::t('app', 'BMC') . ' Is Invalid.'));
            return false;
        }
    }

    function validVehicleNumber($model, $attribute, $params) {
//        $pattern = "/^[A-Z]{2}[ -][0-9]{1,2}(?: [A-Z])?(?: [A-Z]*)? [0-9]{4}$/";--MP 09 AB 1234
//        $pattern = "/^[A-Z]{2}-[0-9]{2}-{1}[A-Z]{1,3}-{1}[0-9]{4}$/"; //--MP-09-AB-1234
        $pattern = "/^[A-Z]{2}[0-9]{2}[A-Z]{0,3}[0-9]{4}$/"; //--GJ10AB1111,GJ101111,GJ10ABC1111,GJ10A1111
        if (!preg_match($pattern, $model->$attribute)) {
            $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' is must be like GJ10AB1111'));
            return false;
        }
        return TRUE;
    }

    function validOneDigitDecimal($model, $attribute, $params) {
        $pattern = "/^[0-9]{2}[.][0-9]{1}$/";
        if (!preg_match($pattern, $model->$attribute)) {
            $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' is must be between 0.1 to 99.9'));
            return false;
        }

        return TRUE;
    }

    public function validateBeneficiary($model, $attribute, $params) {
        if (!empty($model->$attribute))
            if (!preg_match('/^[a-zA-Z]+(\s{1}+[a-zA-Z]+)*$/', $model->$attribute)) {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' Is Invalid'));
                return false;
            }
    }

    public function getClientCode($union) {
        $model = new TblUnions();
        $data = $model->find()->where(['union_code' => $union])->one();
        return !empty($data) ? $data->eipl_code : '';
    }

    function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
// Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat)) * sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat)) * cos(deg2rad($point2_lat)) * cos(deg2rad($point1_long - $point2_long)))));

// Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        switch ($unit) {
            case 'km':
                $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
                break;
            case 'mi':
                $distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
                break;
            case 'nmi':
                $distance = $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
        }
        return round($distance, $decimals);
    }

    public function validateDeactivateCustomer($model, $date, $code) {
        $checkdate = date('Y-m-d', strtotime($date));
        $memberModel = new TblCustomerDeactive();
        $records = $memberModel->find()
                ->where('customer_code=\'' . $code . '\' and customer_type=\'' . $model->customer_type . '\'')
                ->andWhere('((\'' . $checkdate . '\' between cast(from_date as date)  and case when to_date is null then \'9999-12-31\' else cast(to_date as date) end))')
                ->count();

        if ($records > 0) {
            $model->addError('customer_code', Yii::t('app/validation', Yii::t('app', 'Customer') . ' Is Deactivated.'));
            return false;
        }
    }

    public function validateExCodes($model, $master_table, $ex_code_key, $cmpare_table, $cmpare_key, $find_model, $unionCode, $update) {
        $flag = isset(Yii::$app->session->get('unionConfig')[$unionCode]['check_ex_code_unique']) ? Yii::$app->session->get('unionConfig')[$unionCode]['check_ex_code_unique'] : '';
        $keyPattern = $this->getKeyPattern($cmpare_table);
        $MasterKeyPattern = $this->getKeyPattern($master_table);
        if (!empty($flag) && !empty($keyPattern)) {
            $ex_code_reset_on = $keyPattern['ex_code_reset_on'];
            $Master_code_reset_on = $MasterKeyPattern['ex_code_reset_on'];

            $model_name = Yii::$app->path->define($find_model);
            $findModel = new $model_name();
            if (!empty($model->{$ex_code_key})) {
                if ($master_table != 'tbl_customer_master') {
                    $model->{$ex_code_key} = str_pad(($model->{$ex_code_key}), $MasterKeyPattern['ex_code_length'], '0', STR_PAD_LEFT);
                }
                $ex_cnt = 0;
                $main_ex_cnt = 0;
                if ($master_table == 'tbl_customer_master') {
                    $prefix = Yii::$app->general->getforeignkey($model->customerTypePre, 'code_prefix');
                    $code = str_replace($prefix, '', $model->{$ex_code_key});
                    $code = intval($code);
                    $q = $model->find()
                            ->where([$Master_code_reset_on => $model->{$Master_code_reset_on}])
                            ->join('LEFT JOIN', 'tbl_customer_type ct', 'tbl_customer_master.union_code = ct.union_code AND tbl_customer_master.customer_type=ct.customer_type AND ct.is_active =1')
                            ->andWhere(['CAST(REPLACE(' . $ex_code_key . ', code_prefix, \'\') as int)' => $code]);
//                            ->count();

                    if (!empty($model->customer_code)) {
                        $q->andWhere(['!=', 'tbl_customer_master.customer_code', $model->customer_code]);
                    }
                    $main_ex_cnt = $q->count();
                    $ex_cnt = $findModel->find()
                            ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                            ->andWhere(['CAST(' . $cmpare_key . ' as int)' => $code])
                            ->count();
                }
                if ($master_table == 'tbl_dcs') {
                    $code = intval($model->{$ex_code_key});
                    $ex_cnt = $findModel->find()
                            ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                            ->join('LEFT JOIN', 'tbl_customer_type ct', 'tbl_customer_master.union_code = ct.union_code AND tbl_customer_master.customer_type=ct.customer_type AND ct.is_active =1')
                            ->andWhere(['CAST(REPLACE(' . $cmpare_key . ', code_prefix, \'\') as int)' => $code])
                            ->count();
//                    var_dump($findModel->createCommand()->getRawSql());
//                    die;
                }
                if ($ex_cnt > 0 || $main_ex_cnt > 0) {
                    if ($MasterKeyPattern['ex_code_auto'] == 0 || $update) {
                        $model->addError($ex_code_key, Yii::t('app/validation', $model->getAttributeLabel($ex_code_key) . ' has already been taken.'));
                    } else {

                        if ($master_table == 'tbl_dcs') {
                            $MasterData = $model->find()
                                    ->select(['ex_code' => 'ISNULL(MAX(CAST(' . $ex_code_key . ' as int)),0)+1'])
                                    ->where([$Master_code_reset_on => $model->{$Master_code_reset_on}])
                                    ->asArray()
                                    ->one();
                        }
                        if ($cmpare_table == 'tbl_dcs') {
                            $findData = $findModel->find()
                                    ->select(['ex_code' => 'ISNULL(MAX(CAST(' . $cmpare_key . ' as int)),0)+1'])
                                    ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                                    ->asArray()
                                    ->one();
                        }
                        if ($master_table == 'tbl_customer_master') {
                            $MasterData = $model->find()
                                    ->select(['ex_code' => 'ISNULL(MAX(CAST(REPLACE(' . $ex_code_key . ', code_prefix, \'\') as int)), 0) + 1'])
                                    ->join('LEFT JOIN', 'tbl_customer_type ct', 'tbl_customer_master.union_code = ct.union_code AND tbl_customer_master.customer_type=ct.customer_type AND ct.is_active =' . 1)
                                    ->where([$Master_code_reset_on => $model->{$Master_code_reset_on}])
                                    ->asArray()
                                    ->one();
                        }
                        if ($cmpare_table == 'tbl_customer_master') {
                            $findData = $findModel->find()
                                    ->select(['ex_code' => 'ISNULL(MAX(CAST(REPLACE(' . $cmpare_key . ', code_prefix, \'\') as int)), 0) + 1'])
                                    ->join('LEFT JOIN', 'tbl_customer_type ct', 'tbl_customer_master.union_code = ct.union_code AND tbl_customer_master.customer_type=ct.customer_type AND ct.is_active =' . 1)
                                    ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                                    ->asArray()
                                    ->one();
                        }
                        $ex_code = 0;
                        $masterMax = !empty($MasterData) ? $MasterData['ex_code'] : 0;
                        $cmprMax = !empty($findData) ? $findData['ex_code'] : 0;
                        $ex_code = ($masterMax >= $cmprMax) ? (int) $masterMax : (int) $cmprMax;
                        $model->{$ex_code_key} = str_pad(($ex_code), $MasterKeyPattern['ex_code_length'], '0', STR_PAD_LEFT);
                    }
                }
            }
        }
    }

    public function getSpDropData($sp, $param, $execute = false) {
        $str = '';
        $count = count($param);
        for ($i = 1; $i <= $count; $i++) {
            $str .= ':paramName' . $i . ',';
        }
        $str = substr($str, 0, -1);
        $command = \Yii::$app->db->createCommand("{CALL {$sp}({$str})}");
        $i = 1;

        if ($execute) {
            return $command->execute();
        } else {
            return $command->queryAll();
        }
    }

    public function generateRandomString() {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                . '0123456789');
//                . '0123456789!@#$%^&*()');
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 8) as $k) {
            $rand .= $seed[$k];
        }

        return $rand;
    }

    public function setAttachment(&$child, $files, $module_code, $module_name) {
        $filesArray = explode(',', $files);
        unset($filesArray[0]);

        $auto_inc = 1;
        foreach ($filesArray as $key => $file) {
            $file_name = $file;
            $path = Yii::$app->basePath . '/web/upload/images//' . $file; //Generate your save file path here;
            $modelAttachment = new TblAttachment();
            $modelAttachment->attachment_code = (string) Yii::$app->general->getCodeAutoIncrement($modelAttachment, $auto_inc);
            $file = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/images/' . $file_name;
            $modelAttachment->attachment = $file;
            $modelAttachment->module_name = $module_name;
            $modelAttachment->module_code = $module_code;
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $modelAttachment->remarks = 'Documents';
            $modelAttachment->attachment_type = $ext;
// save thumbnail
            $imagePath = Yii::getAlias('@webroot') . '/web/upload/images/';
            $thumbnail_path = $imagePath . 'thumbnail';
            $thumbnail_base_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/images/' . 'thumbnail';
            if (Yii::$app->general->checkDirectory($thumbnail_path)) {
                list($width, $height) = getimagesize($file);
                $data = Image::thumbnail($file, 60, 60)->save($thumbnail_path . '/' . $file_name, ['quality' => 100]);
                $modelAttachment->thumbnail = $thumbnail_base_path . '/' . $file_name;
            }
            $child[] = $modelAttachment;
            $auto_inc++;
        }
    }

    public function getAttachment($module_name, $reference_code, $link = true, $OnlyData = false, $remarks = null, $downloadOnly = false, $multiple = false) {
        $model = new TblAttachment();
        $model->module_name = $module_name;
        $model->module_code = $reference_code;
        $model->remarks = $remarks;
        $attachments = $model->getData();
        if ($OnlyData) {
            return $attachments;
        }
        $attachmentsList = [];
        if ($attachments) {
            foreach ($attachments as $attachment) {
                if ($downloadOnly && $attachment->attachment_type == 'pdf') {
                    $attachmentsList[] = Html::a('<i class="fa fa-download"></i> ' . $attachment->file_name, $attachment->attachment, [
                                'title' => 'Download',
                                'download' => $reference_code . $attachment->attachment_type,
                                'class' => 'text-white hover-black',
                    ]);
                } else if ($link) {
                    $attachmentsList[] = Html::a(Html::img($attachment->thumbnail, ['class' => 'thumbnail_image', 'alt' => '']), $attachment->attachment, [
//                            'title' => 'Download',
                                'class' => 'image-popup-no-margins',
                                'download' => $attachment->attachment_type,
                    ]);
                } else {
                    $attachmentsList[] = Html::img($attachment->attachment, ['class' => 'disp_image', 'alt' => '']);
                }
            }
            return $multiple ? $attachmentsList : $attachmentsList[0];
        }
        return "";
    }

    public function getOriginatingType($model, $field) {
        $value = '';
        if (isset($model->{$field})) {
            if ($model->{$field} == 0) {
                $value = 'Create';
            } elseif ($model->{$field} == 1) {
                $value = 'Import';
            } elseif (in_array($model->{$field}, [2, 11, 12, 21, 23, 24])) {
                $value = 'Sync';
            } elseif ($model->{$field} == 3) {
                $value = 'Auto Entry';
            } elseif (in_array($model->{$field}, [13, 22])) {
                $value = 'Pendrive Import';
            }
        }
        return Yii::t('app', $value);
    }

    public function validateEmail($model, $attribute, $params, $check_char = false) {
        if (!empty($model->$attribute)) {
            $model->$attribute = trim($model->$attribute, ",");
            $error = false;
            $err_msg = '';
            $existEmail = [];
            $sameEmail = false;
            $emails = explode(',', $model->$attribute);
            foreach ($emails as $email) {
                if (in_array($email, $existEmail)) {
                    $sameEmail = true;
                }
                $existEmail[] = $email;
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = true;
                    $err_msg = empty($err_msg) ? $err_msg . $email : $err_msg . ',' . $email;
                }
            }
            if ($error) {
                $err_msg = $err_msg . ' ' . Yii::t('app', 'is Invalid.');
            }
            if ($sameEmail) {
                $msg = Yii::t('app', 'Please remove duplicate emails.');
                $err_msg = empty($err_msg) ? $msg : $err_msg . ' ' . $msg;
            }
            if ($error || $sameEmail) {
                $model->addError($attribute, $err_msg);
            }
        }
    }

    public function RemoveDirectory($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->RemoveDirectory($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            //  rmdir($dir);
        }
    }

    public function CreateDirectory($folder_path) {
        if (!is_dir($folder_path)) {
            $oldmask = umask(0);
            mkdir($folder_path, 0777, TRUE);
            umask($oldmask);
        } else {
            $files = glob($folder_path . '*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file))
                    unlink($file); // delete file
            }
        }
    }

    public function DeliveryChallanOrgFilter($query, $main_table, $from_dest = 'Ownmccid', $to_dest = 'ToPlace') {
        $unions = !empty(Yii::$app->session->get('Unions')) ? explode(',', Yii::$app->session->get('Unions')) : NULL;
        $plants = !empty(Yii::$app->session->get('Plant')) ? explode(',', Yii::$app->session->get('Plant')) : NULL;
        $mccs = !empty(Yii::$app->session->get('MCC')) ? explode(',', Yii::$app->session->get('MCC')) : NULL;
        $query->andFilterWhere(['or',
                ['pd.union_code' => $unions],
                ['ms.union_code' => $unions],
                ['md.union_code' => $unions],
                ['cs.union_code' => $unions],
                ['cd.union_code' => $unions]
        ]);
        $form_to = !empty($mccs) ? $mccs : $plants;
        $query->andFilterWhere(['or',
                [$main_table . '.' . $from_dest => $form_to],
                [$main_table . '.' . $to_dest => $form_to],
        ]);
    }

    public function validateMCC($model, $attribute) {
        $mccModel = new TblMccPlant();
        $records = $mccModel->find()->select('mcc_plant_code')->where(['or', ['mcc_plant_code' => $model->$attribute], ['ref_code' => $model->$attribute]])->all();
        if (!empty($records) && count($records) == 1) {
            $model->$attribute = $records[0]->mcc_plant_code;
        } else {
            $model->addError('mcc_plant_code', Yii::t('app/validation', Yii::t('app', 'MCC') . ' Is Invalid.'));
            return false;
        }
    }

    public function dateRangeConflict($model, $attribute, $params, $from_dates, $to_dates) {
        $fromDate = date('Y-m-d', strtotime($model->$from_dates));
        $toDate = date('Y-m-d', strtotime($model->$to_dates));

        if ($fromDate > $toDate) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }

        $dateData = $model->find()
                ->Where('((\'' . $model->from_date . '\' between from_date  and to_date) OR (\'' . $model->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $model->from_date . '\' and  \'' . $model->to_date . '\') OR (to_date between \'' . $model->from_date . '\' and \'' . $model->to_date . '\'))')
                ->one();
        if (!empty($dateData)) {
            $model->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function setDesignTheme(&$layout, $eiplCode = '') {
        if (empty($eiplCode)) {
            $unionData = TblUnions::find()->where(['is_active' => 1])->one();
            $eiplCode = !empty($unionData) && !empty($unionData->eipl_code) ? ($unionData->eipl_code) : '';
        }
        if (!empty($eiplCode)) {
            $eipl_css_file_path = 'themes/pcdf/assets/css/style.css';
            $eipl_js_file_path = 'themes/pcdf/assets/js/style.js';
            $client_css_file_path = 'themes/pcdf/assets/css/style_' . strtolower($eiplCode) . '.css';
            $client_js_file_path = 'themes/pcdf/assets/js/style_' . strtolower($eiplCode) . '.js';
            $check_client_css_file_path = \Yii::$app->basePath . '/' . $client_css_file_path;
            $check_client_js_file_path = \Yii::$app->basePath . '/' . $client_js_file_path;
            if (file_exists($check_client_css_file_path)) {
                if (($key = array_search($eipl_css_file_path, $layout->css)) !== false) {
                    unset($layout->css[$key]);
                    $layout->css[] = $client_css_file_path;
                }
            }
            if (file_exists($check_client_js_file_path)) {
                if (($key = array_search($eipl_js_file_path, $layout->js)) !== false) {
                    unset($layout->js[$key]);
                    $layout->js[] = $client_js_file_path;
                }
            }
        }
    }

    public function getMaxCode($model, $field, $dcs_code, $proModel, $auto_inc = 1) {
        $tableName = $model->tableName();
        $val = (new \yii\db\Query)
                ->select("MAX(convert(int,LTRIM(RTRIM(" . $field . ")))) as " . $field)
                ->from($tableName)
                ->where(['dcs_code' => $dcs_code])
                ->andWhere(['<>', 'ISNULL(is_dcs_member, 0)', 1])
                ->one();
        $maxNumber1 = (int) $val[$field] + $auto_inc;

        $proTableName = $proModel->tableName();
        $proValue = (new \yii\db\Query)
                ->select("MAX(convert(int,LTRIM(RTRIM(" . $field . ")))) as " . $field)
                ->from($proTableName)
                ->where(['dcs_code' => $dcs_code])
                ->one();
        $maxNumber2 = (int) $proValue[$field] + $auto_inc;

        $number = max($maxNumber1, $maxNumber2);

        $existsInModel = (new \yii\db\Query)
                ->select('ex_member_code')
                ->from($tableName)
                ->where(['dcs_code' => $dcs_code, 'ex_member_code' => $number])
                ->exists();

        $existsInProModel = (new \yii\db\Query)
                ->select('ex_member_code')
                ->from($proTableName)
                ->where(['dcs_code' => $dcs_code, 'ex_member_code' => $number])
                ->exists();

        if (!empty($existsInModel) || !empty($existsInProModel)) {
            $number = (int) $number + $auto_inc;
        }
        $number = str_pad($number, 4, '0', STR_PAD_LEFT);
        return $number;
    }

    public function getCurrentDateShift() {
        $currentDateTime = date('Y-m-d H:i:s');
        $hour = date('H', strtotime($currentDateTime));
        $hourMinute = date('H:i', strtotime($currentDateTime));
        $currentDate = date('Y-m-d', strtotime($currentDateTime));
        $date_time_of_collection = ($hour < 3) ? date('Y-m-d', strtotime($currentDate . " -1 days")) : $currentDate;
        $defaultShiftCode = ($hourMinute >= '03:00' && $hourMinute < '15:00') ? 1 : 2;
        return [$defaultShiftCode, $date_time_of_collection];
    }

    public static function getAttachmentUrl($module_name, $module_Code) {
        $attachment = \app\modules\general\models\TblAttachment::find()
                ->where(['module_name' => $module_name, 'module_Code' => $module_Code])
                ->one();
    }

    public function validateExceedTime($model, $attribute, $min, $max) {
        $value = $model->$attribute;
        if ($value < $min || $value > $max) {
            $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' must be between ' . $min . ' and ' . $max));
            return false;
        }
    }

    public function setCode($model) {
        $plants = !empty(Yii::$app->session->get('Plant')) ? count(explode(',', Yii::$app->session->get('Plant'))) : 0;
        $mccs = !empty(Yii::$app->session->get('MCC')) ? count(explode(',', Yii::$app->session->get('MCC'))) : 0;
        $bmcs = !empty(Yii::$app->session->get('BMC')) ? count(explode(',', Yii::$app->session->get('BMC'))) : 0;
        $plants == 1 ? $model->plant_code = explode(',', Yii::$app->session->get('Plant'))[0] : NULL;
        $mccs == 1 ? $model->mcc_plant_code = explode(',', Yii::$app->session->get('MCC'))[0] : NULL;
        if ($bmcs == 1) {
            $model->bmc_code = explode(',', Yii::$app->session->get('BMC'))[0];
        }
    }

    function openImage($attachment) {
        $AttachmentIcon = '';
        if ($attachment) {
            $AttachmentIcon = Html::a(
                            '<span class="glyphicon glyphicon-picture"></span>', $attachment, [
                        'title' => Yii::t('yii', 'Attachment'),
                        'target' => '_blank',
                            ]
            );
        }
        return $AttachmentIcon;
    }

    public function validateCargillAadharcard($model, $attribute, $params) {
        $aadharNumber = $model->$attribute;
        if (!empty($aadharNumber)) {
            if (strlen($aadharNumber) == 10 || strlen($aadharNumber) == 12) {
                if (strlen($aadharNumber) == 10) {
                    if (!preg_match('/^[0-9]{9}[vx]$/i', $aadharNumber)) {
                        $model->addError($attribute, Yii::t('app/validation', $attribute . ' can only contain exactly 9 digits and 1 character.'));
                    }
                } elseif (!preg_match('/^[0-9]{12}$/', $aadharNumber)) {
                    $model->addError($attribute, Yii::t('app/validation', $attribute . ' can only contain exactly 12 digits.'));
                }
            } else {
                $model->addError($attribute, Yii::t('app/validation', $attribute . ' can only contain exactly 10 or 12 digits.'));
            }
        }
    }

    public function asciiToTextConvert($asciiValue) {
        $asciiValueArray = explode(',', $asciiValue);
        $string = '';
        foreach ($asciiValueArray as $char) {
            $string = $string . chr($char);
        }
        return $string;
    }

    public function textToAsciiConvert($text) {
        $string = '';
        $textArray = str_split($text);
        foreach ($textArray as $char) {
            $string = $string . ord($char) . ', ';
        }
        return substr($string, 0, -2);
    }

    function generateActivityStatus($model, $attribute, $dataOriginalTitle = '', $hours = '-24', $colorClass = 'green-text') {
        $dateTime = date("Y-m-d H:i:s", strtotime("$hours hours"));
        if ($model->$attribute >= $dateTime) {
            return '<i class="fa fa-circle ' . $colorClass . '" data-toggle="tooltip" data-placement="top" title="' . $dataOriginalTitle . '"></i>';
        } else {
            return '';
        }
    }

    function getPaymentHeader($model) {
        $data = [];
        if (is_array($model->mcc_plant_code)) {
            $model->plant_code = empty($model->plant_code) ? $model->mccPlantCode->plant_code : $model->plant_code;
            $detail = $model->plantCode;
            if (!empty($detail)) {
                $data['code'] = $detail->ref_code;
                $data['name'] = $detail->name;
            }
        } else {
            if (is_array($model->bmc_code)) {
                $detail = $model->mccPlantCode;
                if (!empty($detail)) {
                    $data['code'] = $detail->ref_code;
                    $data['name'] = $detail->name;
                }
            } else {
                $detail = $model->bmcCode;
                if (!empty($detail)) {
                    $data['code'] = $detail->ref_code;
                    $data['name'] = $detail->bmc_name;
                }
            }
        }
        return $data;
    }

    public function getShiftWithDate($shift, $date) {
        $date = date('Y-m-d', strtotime($date));
        if ($shift == 3) {
            $dateshift['from_date'] = $date . ' ' . Yii::$app->general->getshift(1);
            $dateshift['to_date'] = $date . ' ' . Yii::$app->general->getshift(2);
        } else {
            $setshift = Yii::$app->general->getshift($shift);
            $dateshift['from_date'] = $date . ' ' . $setshift;
            $dateshift['to_date'] = $date . ' ' . $setshift;
        }
        return $dateshift;
    }

    public function getShiftName($shift) {
        $shift_time = 'A';
        if ($shift == 1) {
            $shift_time = 'M';
        } else if ($shift == 2) {
            $shift_time = 'E';
        }
        return $shift_time;
    }

    public function getUnionConfigResult($union, $field, $model = '') {
        if (!empty(Yii::$app->session->get('unionConfig'))) {
            $data = isset(Yii::$app->session->get('unionConfig')[$union][$field]) ? Yii::$app->session->get('unionConfig')[$union][$field] : '';
        } else {
            $data = is_object($model) && property_exists($model, 'import_union_config') ? $model->import_union_config[$field] : '';
        }
        return !empty($data) ? $data : '';
    }

    public function getDisplayDocumentLink($module_code, $module_name, $doc_key, $type = 'link') {
        $attchmentModel = new \app\modules\document\models\TblAttachment();

        $records = $attchmentModel->find()
                        ->select(['attachment'])
                        ->innerJoin('tbl_document_master_info', 'tbl_document_master_info.doc_id = tbl_attachment.doc_id')
                        ->where(['tbl_attachment.module_code' => $module_code, 'tbl_attachment.module_name' => $module_name, 'tbl_document_master_info.doc_key' => $doc_key])
                        ->asArray()->all();
        $links = '';
        $class = '';
        foreach ($records as $record) {
            if ($links != '') {
                $class = 'icon-set-right';
            }
            if ($type == 'image') {
                $links .= ' ' . Html::img($record['attachment'], ['class' => 'img-responsive img-thumbnail image-preview-click', 'style' => 'height: 100px; width: auto; cursor: pointer;', 'data-src' => $record['attachment']]);
            } else {
                $links .= ' ' . Html::a('<i class="fa fa-picture-o"></i>', $record['attachment'], ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'target' => '_blank', 'class' => $class]);
            }
        }
        return $links;
    }

    public function getStaticData() {
        return array(
            'UNION' => 'UNION',
            'PLANT' => 'PLANT',
            'MCC' => 'MCC',
            'DCS' => 'DCS',
        );
    }

    public function createRePushLink($url, $model, $gridId, $pk, $var = 'data_post_status') {
        $class = 're-push ' . ((isset($model->is_stock_posted) && $model->is_stock_posted == 1) ? 'disabled' : ($model->$var == '3' ? '' : 'disabled'));
        $options = [
            'title' => Yii::t('app', 'Repush'),
            'data-toggle' => 'tooltip',
            'data-placement' => 'top',
            'class' => $class,
            'data-val' => $model->$pk
        ];

        $link = GhostHtml::a_alert('<i class="fa fa-share-square-o"></i>', $url, $options);

        $script = "
        $(document).ready(function(){
            $(document).on('click','.re-push',function(e){
                var id= $(this).attr('data-val');
                bootbox.confirm({
                    message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Re-Push Data?</span></div></div>',
                    buttons: {
                        'cancel': {
                            label: 'Cancel',
                            className: 'btn btn-danger'
                        },
                        'confirm': {
                            label: 'Ok',
                            className: 'btn btn-primary'
                        }
                    },
                    callback: function(result) {
                        if (result) {
                            $('#loader').show();
                            $.ajax({
                                type: 'get',
                                url: '" . Url::to(['rfc-re-push']) . "',
                                data: {'id':id},
                                success: function(data) {
                                    var obj1 = $.parseJSON(data);
                                    if (obj1.status == 'success'){
                                         $.pjax.reload({container: '#" . $gridId . "'});
                                        bootbox.alert(\"<div class='row'><div class='col-sm-12'><div class='bg-info'><i class='fa fa-info'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                    } else if (obj1.status == 'error'){
                                        bootbox.alert(\"<div class='row'><div class='col-sm-12'><div class='bg-danger'><i class='fa fa-times'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                    }
                                },
                            });
                        }
                    }
                });
            });
        });
        ";
        Yii::$app->view->registerJs($script, View::POS_END, 're-push');

        return $link;
    }

    public function getField($model, $type, $field = '') {
        $type = strtolower($type);
        $fieldname = 'name';
        if (!empty($field)) {
            if (in_array($type, ['home', 'office', 'other'])) {
                $fieldname = 'user_code';
            } else if ($field === 'code_ex') {
                $fieldname = ($type === 'mcc') ? "{$type}_plant_code_ex" : "{$type}_code_ex";
            } elseif ($field === 'ref_code') {
                $fieldname = 'ref_code';
            }
        } else if (in_array($type, ['mcc', 'plant'])) {
            $fieldname = 'name';
        } else if (in_array($type, ['bmc', 'dcs'], true)) {
            $fieldname = "{$type}_name";
        } else if ($type == 'bulkven') {
            $fieldname = 'customer_name';
        } else if ($type == 'member') {
            $fieldname = 'member_name';
        }

        switch ($type) {
            case 'plant':
                $code = $model->plantCode;
                break;
            case 'mcc':
                $code = $model->mccPlantCode;
                break;
            case 'bmc':
                $code = $model->bmcCode;
                break;
            case 'dcs':
                $code = $model->dcsCode;
                break;
            case 'member':
                $code = $model->memberCode;
                break;
            case 'home':
            case 'office':
            case 'other':
                $code = $model->userCode;
                break;
            default:
                $code = $model->customerCode;
        }

        return $this->getforeignkey($code, $fieldname);
    }

    public function fetchData($mod, $type, $code) {
        $new_code = '';
        $data = '';
        $modelMapping = [
            'plant' => TblPlant::class,
            'mcc' => TblMccPlant::class,
            'bmc' => TblDcsBmc::class,
            'dcs' => TblDcs::class,
            'user' => User::class,
            'customer' => TblCustomerMaster::class,
        ];
        if (!isset($modelMapping[$type])) {
            throw new \Exception("Invalid module: {$type}");
        }
        $model = $modelMapping[$type];
        $query = $model::find();
        $query->andWhere(['is_active' => 1]);

        switch ($type) {
            case 'plant':
                $data = $query->andWhere(['or', ['plant_code' => $code], ['ref_code' => $code], ['plant_code_ex' => $code]])->all();
                $new_code = (count($data) === 1) ? $data[0]->plant_code : [];
                break;

            case 'mcc':
                $data = $query->andWhere(['or', ['mcc_plant_code' => $code], ['ref_code' => $code], ['mcc_plant_code_ex' => $code]])->all();
                $new_code = (count($data) === 1) ? $data[0]->mcc_plant_code : [];
                break;

            case 'bmc':
                $data = $query->andWhere(['or', ['bmc_code' => $code], ['ref_code' => $code], ['bmc_code_ex' => $code]])->all();
                $new_code = (count($data) === 1) ? $data[0]->bmc_code : [];
                break;

            case 'dcs':
                $data = $query
                                ->andWhere(['bmc_code' => $mod->bmc_code])
                                ->andWhere(['or',
                                        ['dcs_code' => $code],
                                        ['dcs_code_ex' => $code],
                                        ['ref_code' => $code]
                                ])->all();
                $new_code = (!empty($data) && count($data) === 1) ? $data[0]->dcs_code : '';
                break;

            case 'user':
                $data = $query->andWhere(['id' => $code])->one();
                $new_code = (!empty($data)) ? $data->id : '';
                break;

            case 'customer':
                $data = $query->andWhere(['bmc_code' => $mod->bmc_code])
                                ->andWhere(['or', ['customer_code' => $code], ['ref_code' => $code], ['customer_code_ex' => $code]])->all();
                $new_code = (count($data) === 1) ? $data[0]->customer_code : [];
                break;

            default:
                break;
        }
        $mod->union_code = (!empty($data) && $type != 'user') ? $data[0]->union_code : '';
        return $new_code;
    }

    public function getMonthStartEndDate($date, $monthType = 'current') {
        $modifier = $monthType === 'previous' ? 'last month' : 'this month';
        $dateTime = new \DateTime($date);
        $fromDate = $dateTime->modify("first day of $modifier")->format('Y-m-d');
        $toDate = (new \DateTime($date))->modify("last day of $modifier")->format('Y-m-d');
        return [$fromDate, $toDate];
    }

    public function getDispCenterProducts($dispatch_center_code = "") {
        $product_array = [];
        $product_array['product_list'] = [];
        $product_array['pass_where_close'] = 'Yes';
        $user_dispatch_center_code = User::find()->select('dispatch_center_code')->where(['id' => Yii::$app->session->get('UserCode')])->one();

        $getDataUsingDisCenter = false;
        $dispCenterCode = '0';
        if (!empty($user_dispatch_center_code) && !empty($user_dispatch_center_code->dispatch_center_code)) {
            if (empty($dispatch_center_code) || $user_dispatch_center_code->dispatch_center_code == $dispatch_center_code) {
                $getDataUsingDisCenter = true;
                $dispCenterCode = $user_dispatch_center_code['dispatch_center_code'];
            }
        } else {
            if (empty($dispatch_center_code)) {
                $product_array['pass_where_close'] = 'No';
            } else {
                $getDataUsingDisCenter = true;
                $dispCenterCode = $dispatch_center_code;
            }
        }
        if ($getDataUsingDisCenter) {
            $dispatch_center_type_code = TblDispatchCenter::find()->select('dispatch_center_type_code')->where(['dispatch_center_code' => $dispCenterCode])->one();
            if (!empty($dispatch_center_type_code) && !empty($dispatch_center_type_code->dispatch_center_type_code)) {
                $groupIds = explode(',', $dispatch_center_type_code['dispatch_center_type_code']);
                $productData = TblProduct::find()->select(['product_code'])->where(['product_group_code' => $groupIds])->all();
                $product_list = \yii\helpers\ArrayHelper::map($productData, 'product_code', 'product_code');
                $product_array['product_list'] = $product_list;
            }
        }
        return $product_array;
    }

    public function maskAadhar($aadhar_no) {
        if (!empty($aadhar_no)) {
            $maskedAadhar = 'xxxx-xxxx-' . substr($aadhar_no, 8);
            return $maskedAadhar;
        } else {
            return '';
        }
    }

    public function getCodeMax($model, $autoInc = 1) {
        $primaryKey = $model->tableSchema->primaryKey[0];
        $organizations_code = !empty(Yii::$app->session->get('organizations_code')) ? Yii::$app->session->get('organizations_code') : $model->originating_org_code;
        $prefix = 'PORTAL-' . $organizations_code . '-';
        $prefixLen = strlen($prefix);

        $result = $model->find()
                ->select(["MAX(CAST(SUBSTRING(" . $primaryKey . ", " . ($prefixLen + 1) . ", LEN(" . $primaryKey . ") - " . $prefixLen . ") AS INT)) AS max_code"])
                ->where(['like', $primaryKey, $prefix])
                ->asArray()
                ->one();

        $highestNumber = isset($result['max_code']) ? (int) $result['max_code'] + $autoInc : $autoInc;
        $value = $prefix . $highestNumber;

        return $value;
    }

    public function getDispatchCenter($applicable_code, $for, $product_code) {
        return TblDispatchCenterApplicability::find()->select(['tbl_dispatch_center_applicability.dispatch_center_code'])
                        ->join('INNER JOIN', 'tbl_dispatch_center', 'tbl_dispatch_center.dispatch_center_code=tbl_dispatch_center_applicability.dispatch_center_code')
                        ->join('INNER JOIN', 'tbl_product', 'tbl_product.product_group_code in (SELECT code from SplitToTable (tbl_dispatch_center.dispatch_center_type_code,\',\'))')
                        ->where(['tbl_dispatch_center_applicability.applicable_code' => $applicable_code, 'tbl_dispatch_center_applicability.applicable_for' => $for, 'tbl_product.product_code' => $product_code])
                        ->asArray()
                        ->one();
    }

    public function setVehicleTripTrackingDetail($trip, $trackingDetail, $remarks = '') {
        if (!empty($trip)) {
            $tripTrackingModel = new TblVehicleTripTracking();
            $tripTrackingModel->attributes = $trip->attributes;
            $tripTrackingModel->trip_date = $trip->transaction_date;
            $tripTrackingModel->remarks = !empty($remarks) ? $remarks : '';
            $tripTrackingModel->created_at = $tripTrackingModel->updated_at = $tripTrackingModel->created_by = $tripTrackingModel->updated_by = $tripTrackingModel->originating_type = $tripTrackingModel->originating_org_code = $tripTrackingModel->originating_org_type = '';
            $tripTrackingModel->visibility_status = $trackingDetail['visibility_status'];
            $tripTrackingModel->module_code = $trackingDetail['module_code'];
            $tripTrackingModel->module_type = $trackingDetail['module_type'];
            $tripTrackingModel->save(TRUE, FALSE);
        }
    }

    public function getColumnName($type) {
        $rel = $this->getDestRelation($type);
        $type = !empty($type) ? strtolower($type) : '';
        $ref_code = 'ref_code';
        $name = 'name';
        if ($type == 'plant') {
            $name = 'name';
            $ref_code = 'ref_code';
        } else if ($type == 'bmc') {
            $name = 'bmc_name';
            $ref_code = 'ref_code';
        } elseif ($type == 'vendor') {
            $name = 'customer_name';
            $ref_code = 'ref_code';
        } elseif ($type == 'party') {
            $name = 'party_name';
            $ref_code = 'sap_vendor_code';
        }
        return ['rel' => $rel, 'ref_code' => $ref_code, 'name' => $name];
    }

    public function calculateData($config, $union = '', $orgCode = '', $fat = '', $snf = '', $clr = '', $orgType = '', $is_clr_input = '') {
        $response = [];

        $lr1 = (float) $this->getCheckBmcConfiguration($union, 'clr_constant1', $orgCode, $orgType, $config);
        $lr2 = (float) $this->getCheckBmcConfiguration($union, 'clr_constant2', $orgCode, $orgType, $config);
        if ($lr1 == '' || $lr2 == '') {
            $lr1 = (float) $this->getUnionConfiguration($union, 'clr_constant1', 'PORTAL');
            $lr2 = (float) $this->getUnionConfiguration($union, 'clr_constant2', 'PORTAL');
        }

        $lr1 = empty($lr1) ? 1 : $lr1;
        $lr2 = empty($lr2) ? 0 : $lr2;

        $response['clr'] = $is_clr_input == 0 ? number_format(($snf - ($fat * $lr1) - $lr2) * 4, 2) : number_format((($clr / 4) + ($fat * $lr1) + $lr2), 2);

        return $response;
    }

    public static function generateDepartmentId($model, $autoIncrement = 1) {
        $primaryKey = $model->tableSchema->primaryKey[0];
        $tableName = $model->tableName();
        $maxValue = (new Query())
                ->select([("ISNULL(MAX({$primaryKey}), 0) AS max_value")])
                ->from($tableName)
                ->where("ISNUMERIC({$primaryKey}) = 1")
                ->scalar();

        $newId = (int) $maxValue + $autoIncrement;
        return (string) $newId;
    }

    public function getDeactivateRecords($code, $modelClass, $columnName, $type = '') {
        $date = date('Y-m-d');

        $query = $modelClass::find()
                ->where(['<=', 'from_date', $date])
                ->andWhere(['or', ['>=', 'to_date', $date], ['is', 'to_date', NULL]])
                ->andWhere([$columnName => $code]);
        if (!empty($type)) {
            $query->andWhere(['customer_type' => $type]);
        }
        return $query->one();
    }

    public function moveAttachments($allDoc, $newDoc, $attachments, $masterDirName, $provisionalDirName) {
        $baseDirPath = Yii::$app->params['document_upload'];
        $baseDir = Yii::getAlias('@webroot') . '/' . $baseDirPath;
        $masterDir = $baseDir . $masterDirName;
        $provisionalDir = $baseDir . $provisionalDirName;
        $this->checkDirectory($masterDir);
        for ($i = 0; $i < count($allDoc); $i++) {
            $docFileName = basename($newDoc[$i]);
            $file = $masterDir . '/' . $docFileName;
            if (!empty($attachments[$i])) {
                try {
                    file_put_contents($file, file_get_contents($attachments[$i]));
                } catch (\Throwable $ex) {
                    \Yii::error("Failed to move attachment for {$masterDirName}: " . $ex->getMessage());
                }
            }
            if (file_exists($provisionalDir . '/' . $allDoc[$i])) {
                unlink($provisionalDir . '/' . $allDoc[$i]);
            }
        }
    }

}
