<?php

namespace app\modules\organisation\models;

use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use app\models\SystemConfiguration;
use Yii;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;
use app\models\GeneralModel;
use app\modules\details\models\TblBankDetails;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_unions".
 *
 * @property string $union_code
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
 * @property string $ifsc
 * @property integer $is_active
 * @property string $phone_no
 * @property string $fax_no
 * @property string $pincode
 * @property string $registration_date
 * @property string $registration_no
 * @property string $union_code_ex
 * @property string $union_name
 * @property string $local_name
 * @property string $union_short_name
 * @property string $updated_at
 * @property string $bank_code
 * @property string $branch_code
 * @property string $created_by
 * @property string $district_code
 * @property string $federation_code
 * @property string $hamlet_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $updated_by
 * @property string $village_code
 * @property string $upi_no
 *
 * @property TblDcs[] $tblDcs
 * @property TblDcsHistory[] $tblDcsHistories
 * @property TblMilkQualityGrade[] $tblMilkQualityGrades
 * @property TblMilkQualityGradeHistory[] $tblMilkQualityGradeHistories
 * @property TblRoutes[] $tblRoutes
 * @property TblRoutesHistory[] $tblRoutesHistories
 * @property TblBranch $branchCode
 * @property TblFederations $federationCode
 * @property TblVillages $villageCode
 * @property TblUsers $createdBy
 * @property TblSubDistricts $subDistrictCode
 * @property TblDistricts $districtCode
 * @property TblStates $stateCode
 * @property TblUsers $deletedBy
 * @property TblBanks $bankCode
 * @property TblHamlets $hamletCode
 * @property TblUsers $updatedBy
 * @property string $gst_no
 */
class TblUnions extends ChildModel {

    public $districts;
    public $name;
    public $state_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_unions';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['federation_code', 'union_code_ex', 'union_name', 'address', 'registration_no', 'hamlet_code', 'pincode', 'registration_date', 'city', 'union_short_name'], 'required'],
            [['union_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'valid_from'], 'required', 'except' => ['importCsv']],
            [['created_at', 'updated_at', 'districts', 'union_code_ex', 'is_active', 'name', 'fax_no', 'upi_no', 'union_short_name', 'registration_date', 'valid_from', 'logo', 'has_bmc', 'eipl_token', 'eipl_code'], 'safe'],
            [['union_code', 'union_code_ex', 'registration_no', 'union_short_name', 'contact_person_mobile_no', 'gst_no'], 'unique'],
            [['union_code', 'district_code'], 'string', 'max' => 3],
            [['address'], 'string', 'max' => 500],
//            [['registration_no', 'union_code_ex'], 'string', 'max' => 20],
            [['city'], 'string', 'max' => 50],
            [['union_code_ex'], 'string', 'max' => 10],
            [['union_name', 'contact_person'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['local_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['contact_person_email'], 'email'],
            [['phone_no'], 'integer'],
            //[['phone_no','contact_person_phone_no','contact_person_mobile_no'], 'integer','message'=>Yii::t('app/validation','{attribute} must be a numeric.')],
            //[['ifsc'], 'string', 'max' => 11, 'min' => 11, 'message' => Yii::t('app/validation', 'Please enter a valid IFSC Length')],
            //  [['contact_person_mobile_no'], 'integer', 'max' => 10, 'min' => 10,'message'=>Yii::t('app/validation','{attribute} must be a numeric.'), 'tooBig' => 'Please enter a valid Mobile No Length', 'tooSmall' => 'Please enter a valid Mobile No Length'],
            //  [['contact_person_phone_no','phone_no'], 'integer', 'max' => 16, 'min' => 16, 'tooBig' => 'Please enter a valid Phone No length', 'tooSmall' => 'Please enter a valid Phone No length'],
            [['union_name'], 'string', 'max' => 100],
            [['phone_no', 'fax_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
//            ['bank_account_no', 'unique', 'targetAttribute' => 'ifsc'],
            [['contact_person_pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['union_code_ex'], 'integer'],
            [['pincode', 'registration_no'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')],
            [['ifsc', 'contact_person_pan_no'], 'trim'],
            //[['bank_code'], 'string', 'max' => 4],
            [['federation_code', 'state_code'], 'string', 'max' => 2],
            [['hamlet_code'], 'string', 'max' => 8],
            [['sub_district_code'], 'string', 'max' => 5],
            //[['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code']],
            [['federation_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblFederations::className(), 'targetAttribute' => ['federation_code' => 'federation_code']],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
            //[['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
            [['hamlet_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHamlets::className(), 'targetAttribute' => ['hamlet_code' => 'hamlet_code']],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
            [['gst_no'], 'string', 'min' => 15, 'max' => 15],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'allow_member_create'], 'safe'],
        ];
    }

    public function validateAccountNo($bankCode, $attribute) {
        $bank = TblBanks::find()->where(['bank_code' => $bankCode])->select('ac_no_length')->one();
        if (isset($bank))
            if (strlen($this->$attribute) < $bank->ac_no_length || strlen($this->$attribute) > $bank->ac_no_length) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' length is not in required bank length.'));
            }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'union_code' => Yii::t('app', 'Union Code'),
            'address' => Yii::t('app', 'Address'),
            'local_address' => Yii::t('app', 'Hindi Address'),
            'districts' => Yii::t('app', 'Districts'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'city' => Yii::t('app', 'City'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'local_contact_person' => Yii::t('app', 'Contact Person Local Name'),
            'contact_person_email' => Yii::t('app', 'Email'),
            'contact_person_mobile_no' => Yii::t('app', 'Mobile No'),
            'contact_person_pan_no' => Yii::t('app', 'PAN No'),
            'contact_person_phone_no' => Yii::t('app', 'Phone No'),
            'created_at' => Yii::t('app', 'Created At'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'is_active' => Yii::t('app', 'Is Active'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'registration_no' => Yii::t('app', 'Registration No'),
            'union_code_ex' => Yii::t('app', 'Old Union Code'),
            'union_name' => Yii::t('app', 'Union Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'union_short_name' => Yii::t('app', 'Union Short Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'created_by' => Yii::t('app', 'Created By'),
            'district_code' => Yii::t('app', 'District'),
            'federation_code' => Yii::t('app', 'Federation'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'state_code' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village'),
            'upi_no' => Yii::t('app', 'UPI No'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'gst_no' => Yii::t('app', 'GST No'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcs() {
        return $this->hasMany(TblDcs::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsHistories() {
        return $this->hasMany(TblDcsHistory::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkQualityGrades() {
        return $this->hasMany(TblMilkQualityGrade::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkQualityGradeHistories() {
        return $this->hasMany(TblMilkQualityGradeHistory::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutes() {
        return $this->hasMany(TblRoutes::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutesHistories() {
        return $this->hasMany(TblRoutesHistory::className(), ['union_code' => 'union_code']);
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
    public function getFederationCode() {
        return $this->hasOne(TblFederations::className(), ['federation_code' => 'federation_code']);
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
    /* public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
      } */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
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
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*  public function getDeletedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'deleted_by']);
      }
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    public function getBankDetails() {
        return $this->hasMany(TblBankDetails::className(), ['module_code' => 'union_code'])->where(['tbl_bank_details.module_name' => 'union']);
    }

    public function getDefaultBankDetail() {
        return $this->hasOne(TblBankDetails::className(), ['module_code' => 'union_code'])->where(['tbl_bank_details.module_name' => 'union', 'tbl_bank_details.is_default' => 1]);
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
    /*  public function getUpdatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
      } */

    public function getTblUnionsDistrictMapping() {
        return $this->hasMany(TblUnionsDistrictMapping::className(), ['union_code' => 'union_code'])->andwhere(['is_active' => 1]);
    }

    public function getSystemConfig() {
        return $this->hasOne(SystemConfiguration::className(), ['organization_id' => 'union_code']);
    }

    /**
     * @inheritdoc
     * @return TblUnionsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblUnionsQuery(get_called_class());
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(union_code) as union_code"])->one();
        $number = (int) $data['union_code'] + 1;
        return str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function getActiveUnions($is_array = 0) {

        $unions = $this->find()->where(['is_active' => 1]);
        if (!empty(Yii::$app->session->get('Unions'))) {
            $selected = explode(',', Yii::$app->session->get('Unions'));
            $unions->andWhere(['union_code' => $selected]);
        }
        $unions->andWhere(['<=', 'valid_from', date('Y-m-d')]);
        $unions = $unions->all();
        if ($is_array == 1)
            $unions = ArrayHelper::map($unions, 'union_code', function($array, $key) {
                        if (!empty($array['local_name']))
                            return $array['union_name'] . '(' . $array['local_name'] . ')';
                        else
                            return $array['union_name'];
                    });

        return $unions;
    }

    public function getDistrictList() {
        $out = '';
        foreach ($this->tblUnionsDistrictMapping as $row) {
            $out .= $row->singleDistrict->district_name . '<br>';
        }
        return $out;
    }

    public function getUnions($federation, $unionCode = '') {

        if ($federation === '')
            $federation = 0;

        $records = $this->getUnion($federation, $unionCode);
        $unions = ArrayHelper::map($records, 'union_code', function($array, $key) {
                    if (!empty($array['local_name']))
                        return $array['union_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['union_name'];
                });
        return $unions;
    }

    public function getUnion($federation, $unionCode = '') {

        if (!empty($unionCode)) {
            $unionQuery = $this->find()
                            ->select(['union_code', 'union_name', 'local_name'])->where(['union_code' => $unionCode]);

            $query = $this->find()
                    ->select(['union_code', 'union_name', 'local_name'])->where(['is_active' => 1])
                    ->union($unionQuery);
        } else {
            $query = $this->find();
            $query->select(['union_code', 'union_name', 'local_name'])->where(['is_active' => 1]);
        }

        if ($federation !== '')
            $query->andWhere(['federation_code' => explode(',', $federation)]);
        if (Yii::$app->session->get('Unions') != '')
            $query->andWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        return $query->all();
    }

    public function getCheckChildExist() {
        $model = new TblDistricts();
        $model = $model->findOne($this->district_code);
        $general = new GeneralModel();
        $return = $general->callSp('sp_delete_union_district', ['tbl_districts', $this->district_code, 'district_code', $this->union_code]);
        //var_dump($return); exit;
//        print_r($model->getCheckDcsExist());
        return $return;
//        return TblDcs::find()->where(['union_code'=>$this->union_code,'district_code'=>$this->district_code])->count();
    }

    public function getCheckDcsExist() {
        $model = new TblDcs();
        $model->union_code = $this->union_code;

        $return = (in_array($this->district_code, $model->getCheckDcsExist()));

//        print_r($model->getCheckDcsExist());
        return $return;
//        return TblDcs::find()->where(['union_code'=>$this->union_code,'district_code'=>$this->district_code])->count();
    }

    public function getCheckUnionExist() {

        $unions = $this->find()->select('DISTINCT(us.state_code) as state_code')->where(['tbl_unions.federation_code' => $this->federation_code, 'tbl_unions.is_active' => 1])
                        ->join('INNER JOIN', 'tbl_union_district ud', 'ud.union_code=tbl_unions.union_code')
                        ->join('INNER JOIN', 'tbl_districts us', 'us.district_code=ud.district_code')
                        ->groupBy('us.state_code')->asArray()->all();

        $states = array_column($unions, 'state_code');
        return $states;
    }

    public function getMappedStates() {

        $unions = $this->mappedDistrictStateQuery($this->union_code);

        $states = ArrayHelper::map($unions, 'state_code', 'state_name');
        return $states;
    }

    public function mappedDistrictStateQuery($union) {

        $unions = $this->find()->select('DISTINCT(us.state_code) as state_code,s.state_name as state_name')->where('tbl_unions.union_code="' . $union . '" and tbl_unions.is_active="1"')
                        ->join('INNER JOIN', 'tbl_federations f', 'tbl_unions.federation_code=f.federation_code')
                        ->join('INNER JOIN', 'tbl_union_district ud', 'ud.union_code=tbl_unions.union_code')
                        ->join('INNER JOIN', 'tbl_districts us', 'us.district_code=ud.district_code')
                        ->join('INNER JOIN', 'tbl_states s', 's.state_code=us.state_code and s.is_active="1"')
                        ->join('INNER JOIN', 'tbl_federation_state fs', 'fs.state_code=s.state_code')->asArray()->all();

        return $unions;
    }

    public function getTblPlant() {
        return $this->hasMany(TblPlant::className(), ['union_code' => 'union_code']);
    }

    public function getTblMccPlant() {
        return $this->hasMany(TblMccPlant::className(), ['union_code' => 'union_code']);
    }

    public function getTblBmc() {
        return $this->hasMany(TblDcsBmc::className(), ['union_code' => 'union_code']);
    }

    public function getTblCustomerType() {
        return $this->hasMany(TblCustomerType::className(), ['union_code' => 'union_code'])->where(['is_organisation' => 0]);
    }

    public function emilkProLiteUnionCount() {
        return $this->find()
                        ->where(['eipl_token' => $this->eipl_token, 'eipl_code' => $this->eipl_code])
                        ->count();
    }

    public function getTblUnion() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
