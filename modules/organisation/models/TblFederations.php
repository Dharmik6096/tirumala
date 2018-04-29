<?php

namespace app\modules\organisation\models;

use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use Yii;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_federations".
 *
 * @property string $federation_code
 * @property string $address
 * @property string $local_address
 * @property string $bank_account_no
 * @property string $city
 * @property string $contact_person
 * @property string $local_contact_person
 * @property string $contact_person_email
 * @property string $contact_person_mobile_no
 * @property string $contact_person_pan_no
 * @property string $contact_person_phone_no
 * @property string $created_at
 * @property string $federation_code_ex
 * @property string $federation_name
 * @property string $local_name
 * @property string $federation_short_name
 * @property string $ifsc
 * @property integer $is_active
 * @property string $phone_no
 * @property string $fax_no
 * @property string $pincode
 * @property string $registration_date
 * @property string $registration_no
 * @property string $updated_at
 * @property string $bank_code
 * @property string $branch_code
 * @property string $created_by
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $updated_by
 * @property string $village_code
 * @property string $logo_path
 * @property string $punch_line
 *
 * @property TblDcs[] $tblDcs
 * @property TblDcsHistory[] $tblDcsHistories
 * @property TblUsers $createdBy
 * @property TblBanks $bankCode
 * @property TblDistricts $districtCode
 * @property TblVillages $villageCode
 * @property TblSubDistricts $subDistrictCode
 * @property TblUsers $deletedBy
 * @property TblHamlets $hamletCode
 * @property TblUsers $updatedBy
 * @property TblStates $stateCode
 * @property TblBranch $branchCode
 * @property TblUnions[] $tblUnions
 * @property TblUnionsHistory[] $tblUnionsHistories
 */
class TblFederations extends ChildModel {

    public $states;
    public $name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_federations';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['federation_code','federation_code_ex', 'federation_name', 'registration_no', 'registration_date', 'district_code','city','sub_district_code','village_code','hamlet_code','state_code','address','pincode'], 'required'],
            [['federation_code', 'federation_code_ex', 'federation_name'], 'unique'],
            ['contact_person_email', 'email', 'message' => Yii::t('app/validation', 'You have entered invalid email address.e.g. "abc@xyz.com"')],
            [['federation_name','contact_person'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['local_name','local_address'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],

            [['is_active','fax_no','created_at', 'registration_date', 'updated_at', 'states','name','logo_path','punch_line','federation_short_name'], 'safe'],
            //[['phone_no','contact_person_phone_no','contact_person_mobile_no'], 'integer','message'=>Yii::t('app/validation','{attribute} must be a numeric.')],
//            [['ifsc'], 'string', 'max' => 11, 'min' => 11, 'message' => Yii::t('app/validation', 'Please enter a valid IFSC Length')],
//            [['contact_person_mobile_no'], function ($attribute, $params) {
//                    Yii::$app->general->vaildateMobileNumbers($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
//            [['contact_person_phone_no', 'phone_no','fax_no'], function ($attribute, $params) {
//                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
//            [['ifsc'], function ($attribute, $params) {
//                    Yii::$app->general->validateIfsc($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
            [['contact_person_pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['phone_no'],function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [[ 'bank_account_no'], 'integer'],
            [['pincode'], 'integer','message'=> Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
            [['pincode'], 'string', 'max' => 6,'min'=>6,'tooLong' =>  Yii::t('app/validation', '{attribute} must contain 6 digit '),
            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')],
            [['federation_code', 'state_code'], 'string', 'max' => 2],
            [['address'], 'string', 'max' => 500],
            [['ifsc','contact_person_pan_no'],'trim'],
//            ['bank_account_no', 'unique', 'targetAttribute' => 'ifsc'],
//            ['bank_account_no', 'unique', 'when' => function($model) {
//                    $data = $this->find()->where(['branch_code'=>$model->branch_code,'ifsc'=>$model->ifsc])->andWhere(['<>','federation_code',$model->federation_code])->one();
//                    return ($data)?true:false;
//            }/*, 'targetAttribute' => 'bank_code'*/],
            [['registration_no'], 'string', 'max' => 20],
            [['city'], 'string', 'max' => 30],
            [['federation_code_ex'], 'string', 'max' => 10],
            [['contact_person', 'contact_person_email', 'federation_name'], 'string', 'max' => 100],
            [['created_by','updated_by'], 'string', 'max' => 14],
//            [['bank_code'], 'string', 'max' => 4],
            [['district_code'], 'string', 'max' => 3],
            [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }],
//            [['bank_code','branch_code','bank_account_no','ifsc'], function ($attribute, $params) {
//                    Yii::$app->general->validateBankDetail($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
            [['hamlet_code'], 'string', 'max' => 8],
            [['sub_district_code'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'federation_code' => Yii::t('app', 'Federation Code'),
            'address' => Yii::t('app', 'Address'),
            'local_address' => Yii::t('app', 'Hindi Address'),
            'states' => Yii::t('app', 'States'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'city' => Yii::t('app', 'City'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'local_contact_person' => Yii::t('app', 'Contact Person Hindi Name'),
            'contact_person_email' => Yii::t('app', 'Email'),
            'contact_person_mobile_no' => Yii::t('app', 'Mobile No'),
            'contact_person_pan_no' => Yii::t('app', 'PAN No'),
            'contact_person_phone_no' => Yii::t('app', 'Phone No'),
            'created_at' => Yii::t('app', 'Created At'),
            'federation_code_ex' => Yii::t('app', 'Existing Code'),
            'federation_name' => Yii::t('app', 'Federation Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'federation_short_name' => Yii::t('app', 'Federation Short Name'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'is_active' => Yii::t('app', 'Is Active'),
            'fax_no' => Yii::t('app', 'Fax No'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'registration_no' => Yii::t('app', 'Registration No'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'created_by' => Yii::t('app', 'Created By'),
            'district_code' => Yii::t('app', 'District'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'state_code' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village'),
            'logo_path' => Yii::t('app', 'Logo Path'),
            'punch_line' => Yii::t('app', 'Punch Line'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcs() {
        return $this->hasMany(TblDcs::className(), ['federation_code' => 'federation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsHistories() {
        return $this->hasMany(TblDcsHistory::className(), ['federation_code' => 'federation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*  public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
      } */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
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
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*  public function getDeletedBy()
      {
      return $this->hasOne(TblUsers::className());
      } */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*  public function getUpdatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
      } */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
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
    public function getTblUnions() {
        return $this->hasMany(TblUnions::className(), ['federation_code' => 'federation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsHistories() {
        return $this->hasMany(TblUnionsHistory::className(), ['federation_code' => 'federation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederationsStateMapping() {
        return $this->hasMany(TblFederationsStateMapping::className(), ['federation_code' => 'federation_code'])->andwhere(['is_active' => 1]);
    }

    /**
     * @inheritdoc
     * @return TblFederationsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFederationsQuery(get_called_class());
    }

    public function getStates() {

        $values = TblStates::find()->where(['is_active' => 1])->all();
        $return_array = [];
        foreach ($values as $key => $row) {
            $return_array[$row->state_code] = $row->state_name;
        }
        return $return_array;
    }

    public function getSelectedStates() {

        $values = TblFederationsStateMapping::find()->where('federation_code = "' . $this->federation_code . '"')->select('state_code')->all();
        $return_array = \yii\helpers\ArrayHelper::map($values, 'state_code', 'state_code');
        $return_array = [];
        foreach ($values as $row) {
            array_push($return_array, $row->state_code);
        }
        return $return_array;
    }

    public function getCode() {

        $data=  $this->find()->select(["MAX(convert(int,federation_code)) as federation_code"])->one();
        $number = (int) $data['federation_code'] + 1;
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }

    public function getActiveFederation($federationCode='') {

        $federation = $this->getFederation($federationCode);
        $federation = ArrayHelper::map($federation, 'federation_code', function($array, $key) {
                    if (!empty($array['local_name']))
                        return $array['federation_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['federation_name'];
                });
        asort($federation,SORT_NATURAL | SORT_FLAG_CASE);
        return $federation;
    }
    public function getFederation($federationCode=''){
        if(!empty($federationCode)){
            $unionQuery=$this->find()
                        ->select(['federation_code', 'federation_name','local_name'])
                        ->where(['federation_code' =>$federationCode]);

            $query=$this->find()->select(['federation_code', 'federation_name','local_name'])->where(['is_active' => 1])->union($unionQuery);
            if(Yii::$app->session->get('Federations')!=='')
                $query->andWhere(['federation_code'=> explode(',',Yii::$app->session->get('Federations'))]);
        }else{
            $query=$this->find()->select(['federation_code', 'federation_name','state_code','local_name'])->where(['is_active' => 1]);
            if(Yii::$app->session->get('Federations')!='')
                $query->andWhere(['federation_code'=> explode(',',Yii::$app->session->get('Federations'))]);
        }
        return $query->all();
    }
    public function getStateList() {
        $out = '';
        foreach ($this->tblFederationsStateMapping as $row) {
            $out .= $row->stateCode->state_name . '<br>';
        }
        return $out;
    }

    public function getFederationStates($code){

        $data = TblFederations::findOne($code);
        $federationStates = [];
        if($data){
            $states = $data->tblFederationsStateMapping;
            $federationStates = ArrayHelper::map($states, 'state_code',  function ($element){
                    return $element->stateCode->state_name;
            });
        }
        return $federationStates;
    }

    public function getCheckUnionExist(){

        $model = new TblUnions();
        $model->federation_code = $this->federation_code;

//        echo '<pre>';
//        echo $this->state_code.' | '.$model->federation_code;
//        print_r($model->getCheckUnionExist());
//        echo '<br>';
//        exit;
        $return = (in_array($this->state_code, $model->getCheckUnionExist()));
        return $return;
        //return TblUnions::find()->where(['federation_code'=>$this->federation_code,'state_code'=>$this->state_code])->count();
    }
}
