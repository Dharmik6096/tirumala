<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblBranch;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblCasteCategory;
use app\modules\general\models\TblBloodgroup;
use app\modules\general\models\TblGender;
use app\modules\general\models\TblReligion;
use app\modules\dcsoperation\models\TblMemberDownload;
use app\modules\general\models\TblRelationship;
use app\modules\verification\models\TblKycRecord;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_member".
 *
 * @property string $member_code
 * @property string $dcs_code
 * @property string $ex_member_code
 * @property string $member_name
 * @property string $father_name
 * @property string $surname
 * @property string $nominee_name
 * @property string $nominee_relation
 * @property string $dob
 * @property integer $bloodgroup_code
 * @property integer $gender_code
 * @property integer $qualification_code
 * @property integer $caste_category_code
 * @property integer $religion_code
 * @property string $land_class
 * @property string $total_land
 * @property integer $no_of_buffalo
 * @property integer $no_of_cow_cross
 * @property integer $no_of_cow_ind
 * @property integer $total_animals
 * @property integer $member_type_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $mobile_no
 * @property string $email
 * @property string $address
 * @property string $pincode
 * @property string $pan_no
 * @property string $adhar_no
 * @property string $voter_id
 * @property integer $annual_income
 * @property string $village_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property integer $payment_mode
 * @property integer $animal_type_code
 * @property string $hamlet_code
 * @property string $sub_district_code
 * @property string $district_code
 * @property string $state_code
 * @property string $union_code
 * @property string $local_name
 * @property string $local_father_name
 * @property string $local_surname
 * @property string $local_nominee_name
 * @property string $local_address
 * @property string $federation_code
 * @property string $bank_name
 * @property string $branch_name
 * @property string $upload
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblMember extends ChildModel {

    public $cnt, $reference_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
            [['is_download'], 'default', 'value' => '0'],
            [['is_active'], 'default', 'value' => '1'],
            [['member_type_code'], 'default', 'value' => '1'],
            [['dcs_code', 'gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection']],
            [['member_code', 'state_code', 'union_code'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
            [['dcs_code', 'ex_member_code', 'member_name'], 'required', 'on' => ['ApprovalMember']],
            [['member_name'], 'required', 'except' => ['customImport', 'saveCreamyData', 'post_sap_data', 'androidsync']],
            [['branch_code', 'bank_account_no', 'ifsc'], 'required', 'on' => 'bank_selected'],
            /* [['member_name'],'unique', 'when' => function($model) {
              return ($model->isNewRecord)?true:false;
              },'skipOnEmpty'=> true], */
            [['gender_code'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalData($this, $attribute, 'gender', false);
        }, 'on' => 'saveCreamyData'],
            [['email'], 'email', 'except' => ['androidsync']],
            [['member_code', 'dcs_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'land_class', 'total_land', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'address', 'pan_no', 'adhar_no', 'village_code', 'created_by', 'updated_by', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'payment_mode', 'voter_id'], 'string', 'except' => ['androidsync']],
            [['qualification_code', 'caste_category_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'annual_income', 'is_active', 'animal_type_code', 'bloodgroup_code', 'gender_code', 'nominee_relation'], 'integer', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."10"'), 'except' => ['androidsync']],
            [['created_at', 'updated_at', 'federation_code', 'bank_name', 'branch_name', 'upload', 'religion_code', 'is_download', 'download_date_time', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'ex_member_code', 'ref_code', 'beneficiary_name'], 'safe'],
            [['ifsc', 'pan_no'], 'trim', 'except' => ['androidsync']],
            [['member_name', 'father_name', 'surname', 'nominee_name'], function ($attribute, $params) {
            Yii::$app->general->validateDiscriptiveField($this, $attribute, $params);
        }, 'skipOnEmpty' => false, 'except' => ['androidsync']],
            [['mobile_no'], function ($attribute, $params) {
            Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
        }, 'skipOnEmpty' => true, 'except' => ['androidsync']],
            [['mobile_no', 'religion_code'], 'integer', 'except' => ['androidsync']],
            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
            [['pan_no'], 'unique', 'targetAttribute' => ['pan_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
            return $this->is_active;
        }, 'except' => ['androidsync']],
            [['email'], 'unique', 'targetAttribute' => ['email', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
            return $this->is_active;
        }, 'except' => ['androidsync']],
            /*    [['adhar_no'], 'unique', 'targetAttribute' => ['adhar_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
              return $this->is_active;
              }],
              [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
              return $this->is_active;
              }], */
            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
            return $this->is_active;
        }, 'except' => ['saveCreamyData', 'androidsync']],
            [['bank_account_no'], function ($attribute, $params) {
            $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
            if ($error !== TRUE)
                $this->addError($attribute, $error);
        }, 'except' => ['saveCreamyData', 'androidsync']],
            [['pan_no'], function ($attribute, $params) {
            Yii::$app->general->validatePancard($this, $attribute, $params);
        }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'androidsync']],
            [['adhar_no'], function ($attribute, $params) {
            Yii::$app->general->validateAadharcard($this, $attribute, $params);
        }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync']],
            [['ifsc'], function ($attribute, $params) {
            Yii::$app->general->validateIfsc($this, $attribute, $params);
        }, 'skipOnEmpty' => false, 'when' => function() {
            return (!empty($this->branch_code) || (in_array($this->scenario, ['importLimitedCsv', 'importCsv']) && !empty($this->ifsc)));
        }, 'except' => ['saveCreamyData', 'androidsync']],
            [['local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'androidsync']],
            [['voter_id'], 'string', 'max' => 15, 'skipOnEmpty' => true, 'except' => ['androidsync']],
            [['payment_mode'], 'string', 'max' => 10, 'skipOnEmpty' => true, 'except' => ['androidsync']],
            [['dob'], function ($attribute, $params) {
            Yii::$app->general->validateAge($this, $attribute, $params);
        }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync']],
            //   [['ex_member_code'], 'integer', 'min' => 1, 'max' => 1500, 'except' => ['androidsync']],
            //   [['ex_member_code'], 'string', 'min' => 1, 'max' => 4, 'except' => ['androidsync']],
            // [['member_code'], 'unique', 'message' => Yii::t('app', 'Ex Member Code has already been taken.'), 'except' => ['androidsync']],
            [['member_code'], 'validateCreamyData', 'on' => ['saveCreamyData', 'androidsync']],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['member_code', 'federation_code', 'dcs_code', 'ex_member_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'bloodgroup_code', 'gender_code', 'qualification_code', 'caste_category_code', 'land_class', 'total_land', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'mobile_no', 'email', 'address', 'pincode', 'pan_no', 'adhar_no', 'annual_income', 'village_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'payment_mode', 'animal_type_code', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'bank_name', 'branch_name', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'nominee_relation', 'voter_id', 'religion_code', 'upload', 'download_date_time', 'is_download', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'mobile_no'], 'safe'],
            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
            return $this->is_active;
        }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'bank_selected']],
            //  [['member_code'], 'refCodeGenerate', 'except' => ['importLimitedCsv', 'deactivate', 'saveCreamyData']],
            //  [['ex_member_code'], 'setExMember'],
            ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['beneficiary_name'], function ($attribute, $params) {
            Yii::$app->general->validateBeneficiary($this, $attribute, $params);
        }, 'skipOnEmpty' => false, 'except' => ['androidsync']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMember', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Society'),
            'ex_member_code' => Yii::t('app', 'Member Code Ex'),
            'member_name' => Yii::t('app', 'Member Name'),
            'father_name' => Yii::t('app', 'Father’s Name/Husband’s Name'),
            'surname' => Yii::t('app', 'Surname'),
            'nominee_name' => Yii::t('app', 'Nominee Name'),
            'nominee_relation' => Yii::t('app', 'Relation With Nominee'),
            'dob' => Yii::t('app', 'Date of Birth'),
            'bloodgroup_code' => Yii::t('app', 'Bloodgroup'),
            'religion' => Yii::t('app', 'Religion'),
            'religion_code' => Yii::t('app', 'Religion'),
            'gender_code' => Yii::t('app', 'Gender'),
            'qualification_code' => Yii::t('app', 'Qualification'),
            'caste_category_code' => Yii::t('app', 'Caste Category'),
            'land_class' => Yii::t('app', 'Land Class'),
            'total_land' => Yii::t('app', 'Total Land(In Hectares)'),
            'no_of_buffalo' => Yii::t('app', 'No Of Buffalo'),
            'no_of_cow_cross' => Yii::t('app', 'No Of Cow Cross'),
            'no_of_cow_ind' => Yii::t('app', 'No Of Cow Indigenious'),
            'total_animals' => Yii::t('app', 'Total Animals'),
            'member_type_code' => Yii::t('app', 'Member Type'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'account_no' => Yii::t('app', 'Account No'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email Id'),
            'address' => Yii::t('app', 'Address'),
            'pincode' => Yii::t('app', 'Pincode'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'adhar_no' => Yii::t('app', 'Aadhar No'),
            'voter_id' => Yii::t('app', 'Voter ID'),
            'annual_income' => Yii::t('app', 'Annual Income'),
            'village_code' => Yii::t('app', 'Village'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'animal_type_code' => Yii::t('app', 'Milk Type'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'district_code' => Yii::t('app', 'District'),
            'state_code' => Yii::t('app', 'State'),
            'union_code' => Yii::t('app', 'Union'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'local_father_name' => Yii::t('app', 'Hindi Father Name'),
            'local_surname' => Yii::t('app', 'Hindi Surname'),
            'local_nominee_name' => Yii::t('app', 'Hindi Nominee Name'),
            'local_address' => Yii::t('app', 'Hindi Address'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'upload' => Yii::t('app', 'Imported'),
            'is_download' => Yii::t('app', 'Download Status'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'member_class' => Yii::t('app', 'Member Class'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'ref_code' => Yii::t('app', 'Code'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
        ];
    }

    public static function primaryKey() {
        return array('member_code');
    }

    /**
     * @inheritdoc
     * @return TblMemberQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMemberQuery(get_called_class());
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
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
    public function getFederationCode() {
        return $this->hasOne(TblFederations::className(), ['federation_code' => 'federation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberTypeCode() {
        return $this->hasOne(TblMemberTypes::className(), ['member_type_code' => 'member_type_code']);
    }

    public function getCode() {
        Yii::$app->general->setKeyPattern($this, 'tbl_member', 'ex_member_code');
        return $this->dcs_code . $this->ex_member_code;
    }

    public function getAnimalTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'animal_type_code']);
    }

    public function getCasteCategoryCode() {
        return $this->hasOne(TblCasteCategory::className(), ['caste_category_code' => 'caste_category_code']);
    }

    public function getReligionCode() {
        return $this->hasOne(TblReligion::className(), ['religion_code' => 'religion_code']);
    }

    public function getQualificationCode() {
        return $this->hasOne(TblQualification::className(), ['qualification_code' => 'qualification_code']);
    }

    public function getBloodGroupCode() {
        return $this->hasOne(TblBloodgroup::className(), ['blood_group_code' => 'bloodgroup_code']);
    }

    public function getGenderCode() {
        return $this->hasOne(TblGender::className(), ['gender_code' => 'gender_code']);
    }

    public function getRelationship() {
        return $this->hasOne(TblRelationship::className(), ['relationship_code' => 'nominee_relation']);
    }

    public function getMembers($dcs_code, $as_array = false) {
        if (!empty($dcs_code)) {
            $query = $this->find()->where(['dcs_code' => $dcs_code, 'is_active' => 1]);
            if ($as_array)
                $query->asArray();
            $members = $query->all();
            return $members;
        }
        return false;
    }

    public function generateBiplMemberFiles() {
        $cp_code = !empty($this->dcsCode->societyCodes) ? $this->dcsCode->societyCodes->bipl_code : '';
        $path = Yii::$app->params['biplDirPath'] . 'EKOMILK/' . $cp_code . '/' . 'MASFILES';
        $csvPath = Yii::$app->params['rateFilesPath'] . '/members/' . $cp_code;
        if (!empty($cp_code) && Yii::$app->general->checkDirectory($csvPath)) {
            $file = \Yii::getAlias('@webroot') . '/' . $csvPath . '/' . 'members.csv';
            $flag = $this->generateCsvFile($file, $this->dcsCode);
            if ($flag && Yii::$app->general->checkDirectory($path)) {
                $this->generateEncFile($file, $path);
            }
        }
        return;
    }

    private function generateEncFile($file, $path) {
        chdir(Yii::$app->params['biplMemberUtilityPath']);
        //$file=\Yii::getAlias('@webroot').'/'.Yii::$app->params['biplMemberUtilityPath'].'/VFSD_EXAMPLE.csv';
        $path = $path . '/myvendor.ven';
        //echo 'milkvendor_cmd_i386-win32_B.exe -i '.$file.' -o '.$path;
        exec('milkvendor_cmd_i386-win32_B.exe -i ' . $file . ' -o ' . $path);
        //exit;
        return;
    }

    public function generateCsvFile($fileName, $dcs) {

        $result = \Yii::$app->db->createCommand("{CALL sp_Benny_Vendor_Header(:stationid)}")
                ->bindValue(':stationid', $dcs->dcs_code);
        $header = $result->queryAll();
        $header = array_column($header, 'VMin');
        $mresult = \Yii::$app->db->createCommand("{CALL sp_Benny_Vendor_Member(:stationid)}")
                ->bindValue(':stationid', $dcs->dcs_code);
        $members = $mresult->queryAll();
        $members = array_column($members, 'MemberLine');
        $tresult = \Yii::$app->db->createCommand("{CALL sp_Benny_Vendor_Tpt()}");
        $trans = $tresult->queryAll();
        $trans = array_column($trans, 'MemberLine');

        $text = '';
        $flag = false;
        foreach ($header as $h) {
            $text .= $h . PHP_EOL;
        }

        foreach ($members as $m) {
            $text .= $m . PHP_EOL;
        }

        foreach ($trans as $t) {
            $text .= $t . PHP_EOL;
        }
        $vfile = fopen($fileName, "w") or die("Unable to open file!");
        if (fwrite($vfile, $text)) {
            $flag = true;
        }
        fclose($vfile);
        return $flag;
    }

    public function afterSave($insert, $changedAttributes) {
        $model = new TblMemberDownload();
        $model->dcs_code = $this->dcs_code;
        $data = $model->getRecord();
        if (!empty($data)) {
            $model = $data;
        }
        $model->is_download = 1;
        $model->upload_datetime = date('Y-m-d H:i:s');
        $model->save();
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function getKycInfo() {
        $data = $this->find()->where(['member_code' => $this->member_code])->one();
        $address = $data->address . ',' . Yii::$app->general->getforeignkey($data->hamletCode, 'hamlet_name');
        $address .= '<br/>' . Yii::$app->general->getforeignkey($data->villageCode, 'village_name') . ',' . Yii::$app->general->getforeignkey($data->subDistrictCode, 'sub_district_name');
        $address .= '<br/>' . Yii::$app->general->getforeignkey($data->districtCode, 'district_name') . '-' . $data->pincode;

        $bankdata = 'A/C No. - ' . $data->bank_account_no;
        $bankdata .= '<br/>Bank - ' . Yii::$app->general->getforeignkey($data->bankCode, 'bank_name');
        $bankdata .= '<br/>Branch - ' . Yii::$app->general->getforeignkey($data->branchCode, 'branch_name');
        $bankdata .= '<br/>IFSC - ' . $data->ifsc;

        return ['name' => $data->member_name, 'address' => $address, 'bankdetail' => $bankdata, 'panno' => $data->pan_no, 'aadharno' => $data->adhar_no];
    }

    public function getKycCode() {
        return $this->hasOne(TblKycRecord::className(), ['module_id' => 'member_code'])
                        ->where(['module_name' => 'TblMember']);
    }

    public function memberInfo($encryptedmobile) {
        if (strlen($this->member_code) == 4) {
            return $this->find()->where(['mobile_no' => $encryptedmobile, "RIGHT(member_code,4)" => $this->member_code])->andWhere(['is_active' => 1])->all();
        } else {
            return $this->find()->where(['mobile_no' => $encryptedmobile, 'member_code' => $this->member_code])->andWhere(['is_active' => 1])->all();
        }
    }

    public function getmember() {
        return $this->find()->where(['member_code' => $this->member_code])->andWhere(['is_active' => 1])->one();
    }

    public function validateCreamyData($attribute, $params) {
        $this->state_code = Yii::$app->general->getforeignkey($this->dcsCode, 'state_code');
        $this->district_code = Yii::$app->general->getforeignkey($this->dcsCode, 'district_code');
        $this->sub_district_code = Yii::$app->general->getforeignkey($this->dcsCode, 'sub_district_code');
        $this->hamlet_code = Yii::$app->general->getforeignkey($this->dcsCode, 'hamlet_code');
        $this->village_code = Yii::$app->general->getforeignkey($this->dcsCode, 'village_code');
        $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
        $this->federation_code = Yii::$app->general->getforeignkey($this->unionCode, 'federation_code');
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function refCodeGenerate($attribute, $params) {
        if ($this->isNewRecord) {
            $bmc = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
            $bmc_code = !empty($bmc) ? $bmc : '';
            $this->ref_code = $bmc_code . '3' . substr($this->dcs_code, -4) . substr($this->member_code, -3);
        }
    }

    public function setExMember($attribute, $params) {
        $this->ex_member_code = str_pad($this->ex_member_code, 4, '0', STR_PAD_LEFT);
    }

    public function validMember($member) {
        return $this->find()->where(['member_code' => $member, 'is_active' => 1])->one();
    }

    public function validateMember($dcs_code, $memberCode) {
        return $this->find()->where(['dcs_code' => $dcs_code, 'is_active' => 1])
                        ->andWhere(['or', ['member_code' => $memberCode], ['ex_member_code' => $memberCode]])->one();
    }

}
