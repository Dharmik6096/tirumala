<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
/**
 * This is the model class for table "tbl_transporter".
 *
 * @property string $transporter_code
 * @property string $transporter_name
 * @property string $local_name
 * @property string $address
 * @property string $phone_no
 * @property string $mobile_no
 * @property string $email
 * @property string $pincode
 * @property string $registration_no
 * @property string $contact_person
 * @property string $local_contact_person
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $gstin
 * @property string $tds_per
 * @property string $pan_no
 * @property string $beneficiary_name
 * @property string $agreement_no
 * @property string $declaration
 * @property string $security_cheque_no
 * @property string $union_code
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property string $security_amount
 */
class TblTransporter extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_transporter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transporter_name','address','union_code','hamlet_code','contact_person','mobile_no'], 'required'],
            [['transporter_name', 'local_name', 'address', 'phone_no', 'mobile_no', 'email', 'contact_person', 'local_contact_person', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'gstin', 'pan_no', 'beneficiary_name', 'agreement_no', 'declaration', 'security_cheque_no', 'union_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'created_by', 'updated_by'], 'string'],
            [['registration_no'], 'unique','skipOnEmpty'=>true],
            [['transporter_code', 'state_code', 'district_code', 'sub_district_code', 'village_code'], 'required','except'=>'importCsv'],
//            [['tds_per'], 'number'],
            [['transporter_code'], 'integer'],
            [['email'], 'email'],
            [['local_contact_person', 'local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['transporter_name', 'contact_person', 'beneficiary_name'], function ($attribute, $params) {
                Yii::$app->general->validateName($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute,$params);
                },'skipOnEmpty'=> true],
            [['phone_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['pincode'], 'integer','message'=> Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
            [['pincode'], 'string', 'max' => 6,'min'=>6,'tooLong' =>  Yii::t('app/validation', '{attribute} must contain 6 digit ')],
            [['registration_no'], 'string', 'max' => 20],
            [['created_at', 'updated_at','security_amount'], 'safe'],
            ['bank_account_no', 'unique', 'when' => function($model) {
                    $data = $this->find()->where(['ifsc'=>$model->ifsc])->one();
                    return ($data)?true:false;
            },'skipOnEmpty'=> true,/* 'targetAttribute' => 'bank_code' */],
            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no','ifsc'],'message'=> Yii::t('app/validation', '{attribute} has already been taken.')],
            [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
            }],
            [['pan_no','email','mobile_no'], 'unique'],
            [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            
            [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute,$params);
                },'skipOnEmpty'=> false,'when'=>function(){
                    return !empty($this->branch_code);
                }],
            [['tds_per'], 'number'],
            [['security_amount', 'tds_per'], 'string', 'max' => 16,'tooLong' =>  Yii::t('app/validation', '{attribute} should contain at most 16 digit '),'skipOnEmpty'=> true],
            [['gstin'], 'string', 'max' => 11,'skipOnEmpty'=> true],
            [['agreement_no', 'declaration', 'security_cheque_no'], 'string', 'max' => 20,'skipOnEmpty'=> true],
            [['is_active'], 'integer'],
            [['agreement_no', 'security_cheque_no','registration_no'], function ($attribute, $params) {
                Yii::$app->general->vaildateNumericField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            ['security_amount','number','min'=>1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'transporter_name' => Yii::t('app', 'Transporter Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'address' => Yii::t('app', 'Address'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_no' => Yii::t('app', 'Registration No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'local_contact_person' => Yii::t('app', 'Contact Person Hindi Name'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'gstin' => Yii::t('app', 'GSTIN'),
            'tds_per' => Yii::t('app', 'TDS %'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'agreement_no' => Yii::t('app', 'Agreement No'),
            'declaration' => Yii::t('app', 'Declaration'),
            'security_cheque_no' => Yii::t('app', 'Security Cheque No'),
            'union_code' => Yii::t('app', 'Union'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'security_amount'=>Yii::t('app', 'Security Amount')
        ];
    }

    /**
     * @inheritdoc
     * @return TblTransporterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblTransporterQuery(get_called_class());
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }
    
    public function getCode(){
        $data=$this->find()->select(["MAX(CONVERT(INT,substring(transporter_code,4,5))) AS transporter_code"])->where(['union_code'=>  $this->union_code])->one();        
        return $this->union_code.str_pad((int)$data['transporter_code']+1,5,'0',STR_PAD_LEFT);
    }
}
