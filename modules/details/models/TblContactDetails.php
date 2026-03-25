<?php

namespace app\modules\details\models;

use app\modules\assetmanagement\models\TblAssetDetail;
use app\modules\assetmanagement\models\TblStoreLocation;
use app\modules\usermanagement\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_contact_details".
 *
 * @property integer $detail_code
 * @property string $module_name
 * @property string $module_code
 * @property string $contact_person
 * @property string $email
 * @property string $mobile_no
 * @property string $local_contact_person
 * @property string $department
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 */
class TblContactDetails extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_contact_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['detail_code'], 'required'],
                [['detail_code'], 'required', 'on' => ['additional']],
                [['detail_code'], 'required', 'except' => ['additional']],
                [['email'], 'email', 'message' => Yii::t('app/validation', 'You have entered invalid email address.e.g. "abc@xyz.com"')],
                [['detail_code', 'mobile_no'], 'integer'],
                [['firstname', 'lastname', 'surname'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => false, 'except' => 'verification'],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => 'verification'],
                [['local_firstname', 'local_lastname', 'local_surname'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => 'verification'],
            // [['module_name', 'module_code', 'contact_person', 'email', 'local_contact_person', 'created_by', 'updated_by'], 'string'],
            [['module_name', 'contact_person', 'email', 'local_contact_person', 'created_by', 'updated_by'], 'string'],
                [['created_at', 'updated_at', 'department', 'lastname', 'surname', 'is_default', 'is_active', 'is_verified', 'is_contact_verified', 'remarks', 'email_to', 'email_cc', 'email_bcc', 'union_code', 'from_date', 'to_date', 'primary_parent', 'secondary_parent'], 'safe'],
                [['email_to', 'email_cc', 'email_bcc'], function ($attribute, $params) {
                    Yii::$app->general->validateEmail($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => 'verification'],
                [['is_verified', 'is_contact_verified'], 'default', 'value' => 0],
                [['to_date'], 'validateToDate'],
                [['secondary_parent'], 'validateUniqueParent'],
                [['to_date'], 'convertDate', 'except' => ['verification']]
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblContactDetails', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'detail_code' => Yii::t('app', 'Detail Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
//            'contact_person' => Yii::t('app', 'Contact Person'),
            'firstname' => Yii::t('app', 'First Name'),
            'lastname' => Yii::t('app', 'Middle Name'),
            'surname' => Yii::t('app', 'Surname'),
            'fullname' => Yii::t('app', 'Contact Person'),
            'localfullname' => Yii::t('app', 'Contact Person Hindi Name'),
            'email' => Yii::t('app', 'Email'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
//            'local_contact_person' => Yii::t('app', 'Contact Person Hindi Name'),
            'local_firstname' => Yii::t('app', 'Hindi First Name'),
            'local_lastname' => Yii::t('app', 'Hindi Middle Name'),
            'local_surname' => Yii::t('app', 'Hindi Surname'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'department' => Yii::t('app', 'Department'),
            'is_active' => Yii::t('app', 'Is Active'),
            'email_to' => Yii::t('app', 'Email To'),
            'email_cc' => Yii::t('app', 'Email CC'),
            'email_bcc' => Yii::t('app', 'Email BCC'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'primary_parent' => Yii::t('app', 'Primary Parent'),
            'secondary_parent' => Yii::t('app', 'Secondary Parent'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblContactDetailsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblContactDetailsQuery(get_called_class());
    }

    public function setModel($module, $module_code, $default = 1) {
        $this->module_name = $module;
        $this->module_code = $module_code;
        $this->detail_code = Yii::$app->general->getCodeAutoIncrement($this);
        $this->is_active = 1;
        $this->is_default = $default;
    }

    public function getFullName() {
        if (isset($this->firstname))
            return $this->firstname . ' ' . $this->lastname . ' ' . $this->surname;
    }

    public function getLocalFullName() {
        if (isset($this->local_firstname))
            return $this->local_firstname . ' ' . $this->local_lastname . ' ' . $this->local_surname;
    }

    public function CheckDuplicate($attribute, $param) {
        if (!empty($this->mobile_no) && $this->is_active == 1) {
            $data = $this->find()->where(['or', ['mobile_no' => $this->mobile_no], ['mobile_no' => \Yii::$app->general->encryptData($this->mobile_no)]])
                            ->andWhere(['<>', 'detail_code', $this->detail_code])
                            ->andWhere(['is_active' => 1])->one();
            if (!empty($data)) {
                $this->addError($attribute, Yii::t('app/validation', 'Mobile No has already been taken.'));
            }
        }
    }

    public function getContactDetails() {
        return $this->find()
                        ->where(['module_code' => $this->module_code, 'module_name' => $this->module_name, 'is_default' => 1])
                        ->one();
    }

    public function getContactDetailsRecord() {
        return $this->find()
                        ->where(['mobile_no' => $this->mobile_no, 'is_active' => 1])
                        ->one();
    }

    public function getContactDetailsOrg() {
        return $this->find()
                        ->select([
                            'organization_code' => 'module_code',
                            'organization_type' => 'case when module_name = \'mccPlant\' then \'MCC\' else UPPER(module_name) end',
                            'organization_type_id' => "CASE WHEN (module_name='union') THEN '3' "
                            . "WHEN  (module_name='plant') THEN '4' "
                            . "WHEN  (module_name='mccPlant') THEN '5' "
                            . "WHEN  (module_name='bmc') THEN '6' "
                            . "ELSE '7' END"
                        ])
                        ->where(['mobile_no' => $this->mobile_no])
                        ->andWhere(['module_name' => ['union', 'plant', 'society', 'mccPlant', 'bmc']])
                        ->asArray()
                        ->all();
    }

    public function getContactDetail() {
        return $this->find()
                        ->where(['module_code' => $this->module_code, 'module_name' => $this->module_name, 'is_default' => 1, 'is_active' => 1])
                        ->one();
    }

    public function getAllContactData($status) {
        return $this->find()
                        ->where(['module_code' => $this->module_code, 'module_name' => $this->module_name, 'is_active' => $status])
                        ->all();
    }

    public function getOrgDetail() {
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        return $OrgContacts = TblContactDetails::find()
                ->where(['or',
                        ['mobile_no' => $encryptedmobile],
                        ['mobile_no' => $this->mobile_no]
                ])->andWhere(['module_name' => $this->module_name, 'is_active' => 1, 'is_default' => 1])
                ->all();
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->union_code = Yii::$app->session->get('Unions');
            return true;
        }
        return false;
    }

    public function getPrimaryParent() {
        return $this->hasOne(User::className(), ['id' => 'primary_parent']);
    }

    public function getSecondaryParent() {
        return $this->hasOne(User::className(), ['id' => 'secondary_parent']);
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->to_date) && !empty($this->from_date) && ($this->from_date > $this->to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than From Date.'));
            return false;
        }
    }

    public function validateUniqueParent($attribute, $params) {
        if (!empty($this->primary_parent) && !empty($this->secondary_parent) && ($this->primary_parent == $this->secondary_parent)) {
            $this->addError($attribute, Yii::t('app/validation', 'Parent Must Not Same.'));
            return false;
        }
    }

    public function contactDetailList($moduleCode) {
        $detailCode = TblAssetDetail::find()->select('detail_code')->joinWith(['storeLocCode'])->where(['reference_code' => $moduleCode])->column();
        $data = $this->find()
                ->where(['module_code' => $moduleCode, 'module_name' => 'society', 'is_active' => 1])
                ->andWhere(['not in', 'detail_code', $detailCode])
                ->all();
        return ArrayHelper::map($data, 'detail_code', 'firstname');
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : NULL;
            $this->to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : NULL;
        }
    }

    public static function updateContactDetails($code, $mobile_no, &$mapList) {
        $activeContacts = self::find()
            ->where(['module_name' => 'society', 'module_code' => $code, 'is_active' => 1])
            ->indexBy('mobile_no')
            ->all();

        $currentDefaultContact = false;
        foreach ($activeContacts as $c) {
            if ($c->is_default == 1) {
                $currentDefaultContact = $c;
                break;
            }
        }

        $contactDetails = null;
        if (isset($activeContacts[$mobile_no])) {
            $contactDetails = $activeContacts[$mobile_no];
            if ($contactDetails->is_default != 1) {
                $historyContactDetails = new TblContactDetailsHistory();
                \Yii::$app->operation->history($contactDetails, $historyContactDetails, UPDATE);
                $mapList[] = $historyContactDetails;
            }
        } else {
            $contactDetails = new self();
        }

        if ($currentDefaultContact && $currentDefaultContact->detail_code != $contactDetails->detail_code) {
            $historyOldDefault = new TblContactDetailsHistory();
            \Yii::$app->operation->history($currentDefaultContact, $historyOldDefault, UPDATE);
            $mapList[] = $historyOldDefault;
            $currentDefaultContact->is_active = 0;
            $currentDefaultContact->is_default = 0;
            $mapList[] = $currentDefaultContact;
        }

        $contactDetails->is_default = 1;
        $contactDetails->is_active = 1;

        return $contactDetails;
    }

}
