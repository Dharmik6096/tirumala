<?php

namespace app\modules\details\models;

use Yii;

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
        return [
            [['detail_code', 'firstname'], 'required'],
            [['email'], 'email', 'message' => Yii::t('app/validation', 'You have entered invalid email address.e.g. "abc@xyz.com"')],
            [['detail_code', 'mobile_no'], 'integer'],
            [['mobile_no'], 'CheckDuplicate'],
            [['mobile_no'], 'required', 'on' => 'additional'],
            [['department', 'firstname', 'lastname', 'surname'], function ($attribute, $params) {
            Yii::$app->general->validateName($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['mobile_no'], function ($attribute, $params) {
            Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['local_firstname', 'local_lastname', 'local_surname'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['module_name', 'module_code', 'contact_person', 'email', 'local_contact_person', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'department', 'lastname', 'surname', 'is_default', 'is_active'], 'safe'],
        ];
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
        if (!empty($this->mobile_no)) {
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
                ->where(['module_code' => $this->module_code ,'module_name' => $this->module_name, 'is_default' => 1])
                ->one();
    }

}
