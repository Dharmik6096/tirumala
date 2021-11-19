<?php

namespace app\modules\product\models;

use Yii;
use app\modules\details\models\TblContactDetails;
use app\modules\organisation\models\TblUnions;
use app\modules\product\models\TblGrn;

/**
 * This is the model class for table "tbl_vendor_master".
 *
 * @property string $vendor_master_code
 * @property string $vendor_code
 * @property string $vendor_name
 * @property string $pan_no
 * @property string $aadhaar_no
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVendorMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $department, $middle_name, $surname, $local_middlename, $local_surname, $contact_person, $local_contact_person, $mobile_no, $email;

    public static function tableName() {
        return 'tbl_vendor_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vendor_code', 'vendor_name'], 'required'],
                [['created_at', 'updated_at', 'vendor_name', 'pan_no', 'aadhaar_no', 'department', 'surname', 'local_surname', 'contact_person', 'local_contact_person', 'local_middlename', 'middle_name', 'mobile_no', 'email', 'union_code'], 'safe'],
                [['contact_person', 'mobile_no'], 'required', 'on' => 'importCsv'],
                [['vendor_code'], 'integer'],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code'], 'on' => 'importCsv'],
                [['aadhaar_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAadharcard($this, $attribute, $params);
                }],
                [['pan_no'], 'setPanNumber', 'on' => ['importCsv']],
                [['pan_no', 'aadhaar_no', 'vendor_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vendor_master_code' => Yii::t('app', 'Vendor Master Code'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'vendor_name' => Yii::t('app', 'Vendor Name'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function setChildTable(&$model, &$saveModel, &$errors) {
        if (!empty($model->mobile_no)) {
            $contact_model = new TblContactDetails();
            $contact_model->setModel('vendor', (string) $model->vendor_master_code);
            $contact_model->mobile_no = $model->mobile_no;
            $contact_model->email = $model->email;
            $contact_model->department = $model->department;
            $contact_model->contact_person = $model->contact_person;
            $contact_model->firstname = $model->contact_person;
            $contact_model->local_contact_person = $model->local_contact_person;
            $contact_model->lastname = $model->middle_name;
            $contact_model->surname = $model->surname;
            $contact_model->local_lastname = $model->local_middlename;
            $contact_model->local_surname = $model->local_surname;
            $contact_model->mobile_no = $model->mobile_no;
            if (!$contact_model->validate()) {
                $errors[] = $contact_model->getErrors();
            }
            array_push($saveModel, $contact_model);
        }
    }

    public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function checkDelete() {
        $grnModel = new TblGrn();
        $data = $grnModel->find()->where(['vendor_master_code' => $this->vendor_master_code])->one();
        if (count($data) > 0) {
            return false;
        } else {
            return true;
        }
    }

}
