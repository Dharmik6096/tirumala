<?php

namespace app\modules\organisation\models;

use app\models\TblUsers;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\models\ChildModel;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "tbl_branch".
 *
 * @property string $branch_code
 * @property string $address
 * @property string $branch_name
 * @property string $local_name
 * @property string $created_at
 * @property string $ifsc
 * @property integer $is_active
 * @property string $pincode
 * @property string $updated_at
 * @property string $bank_code
 * @property string $created_by
 * @property string $sub_district_code
 * @property string $updated_by
 * @property string $village_code
 * @property string $state_code
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $contact_person
 * @property string $contact_number
 * @property string $union_code
 * @property string $local_address
 *  *
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblBanks $bankCode
 * @property TblSubDistricts $subDistrictCode
 * @property TblUsers $createdBy
 * @property TblVillages $villageCode
 * @property TblDcs[] $tblDcs
 * @property TblDcsHistory[] $tblDcsHistories
 * @property TblFederations[] $tblFederations
 * @property TblFederationsHistory[] $tblFederationsHistories
 * @property TblSubCenter[] $tblSubCenters
 * @property TblSubCenterHistory[] $tblSubCenterHistories
 * @property TblUnions[] $tblUnions
 * @property TblUnionsHistory[] $tblUnionsHistories
 */
class TblBranch extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_branch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['bank_code', 'branch_name', 'ifsc', 'address'], 'required'],
                [['created_at', 'updated_at', 'district_code', 'state_code', 'union_code', 'local_address', 'valid_from', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['is_active'], 'safe'],
                [['branch_code', 'state_code', 'valid_from'], 'required', 'except' => 'importCsv'],
//            [['ifsc'], 'unique', 'message' => Yii::t('app/validation', 'This {attribute} has already been taken')],
            [['branch_code'], 'unique'],
//            [['ifsc'],'trim'],
//            ['branch_name', 'unique', 'when' => function($model) {
//                    $data = $this->find()->where(['bank_code' => $model->bank_code, 'branch_name' => $model->branch_name])->andWhere(['<>', 'branch_code', $model->branch_code])->one();
//                    return ($data) ? true : false;
//                }],
            [['local_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
//            ['ifsc', 'unique', 'when' => function($model) {
//                    $data = $this->find()->where(['bank_code'=>$model->bank_code,'ifsc'=>$model->ifsc])->andWhere(['<>','branch_code',$model->branch_code])->one();
//                    return ($data)?true:false;
//            }],
//            ['branch_name', 'unique','message'=>'{attribute} has been already taken.', 'targetAttribute' => ['bank_code']],
//            ['branch_name', 'unique', 'targetAttribute' => 'bank_code'],
            [['branch_name', 'contact_person'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['contact_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['address'], 'string', 'max' => 250],
                [['branch_name'], 'string', 'max' => 100],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
            //[['ifsc'], 'string', 'max' =>10,'min'=>10,'message'=>Yii::t('app/validation','Please enter a valid IFSC Length')],
            //[['ifsc'], 'integer', 'max' => 11, 'min' => 11, 'tooBig' => 'Please enter a valid IFSC Length', 'tooSmall' => 'Please enter a valid IFSC Length'],
            [['bank_code'], 'string', 'max' => 4],
                [['sub_district_code'], 'string', 'max' => 5],
            /* [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']], */
                [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
                [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
            //  [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblBranch', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    public function validateIfsc($attribute) {
        if (!preg_match('/[A-Z|a-z]{4}[0][A-Z|a-z]{6}$/', $this->$attribute)) {
            $this->addError($attribute, Yii::t('app/validation', ' Invalid Ifsc Code.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'branch_code' => Yii::t('app', 'Branch Code'),
            'address' => Yii::t('app', 'Address'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'district_code' => Yii::t('app', 'District'),
            'state_code' => Yii::t('app', 'State'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'is_active' => Yii::t('app', 'Is Active'),
            'pincode' => Yii::t('app', 'Pincode'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'created_by' => Yii::t('app', 'Created By'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'local_address' => Yii::t('app', 'Local Address'),
            'valid_from' => Yii::t('app', 'Valid From'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy() {
        return $this->hasOne(TblUsers::className());
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
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
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
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(TblUsers::className(), ['user_code' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcs() {
        return $this->hasMany(TblDcs::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsHistories() {
        return $this->hasMany(TblDcsHistory::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations() {
        return $this->hasMany(TblFederations::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederationsHistories() {
        return $this->hasMany(TblFederationsHistory::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubCenters() {
        return $this->hasMany(TblSubCenter::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubCenterHistories() {
        return $this->hasMany(TblSubCenterHistory::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions() {
        return $this->hasMany(TblUnions::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsHistories() {
        return $this->hasMany(TblUnionsHistory::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @inheritdoc
     * @return TblBranchQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblBranchQuery(get_called_class());
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(CONVERT(bigint,branch_code)) as branch_code"])->one();
        return str_pad(((int) $data['branch_code'] + 1), 6, '0', STR_PAD_LEFT);
    }

    public function getIfcs($code) {

        $record = $this->find()->where(['branch_code' => $code])->one();
        return !empty($record) ? $record->ifsc : '';
    }

    public function getBranchIfcs($ifsc) {

        $record = $this->find()->select('branch_code,bank_code')->where(['ifsc' => $ifsc, 'is_active' => 1])->one();
        return $record;
    }

    public function getExistingIfsc() {
        $record = $this->find()->where(['ifsc' => ucwords($this->ifsc), 'is_active' => 1]);
        if (!empty($this->branch_code)) {
            $record = $record->andWhere(['<>', 'branch_code', $this->branch_code]);
        }
        $record = $record->one();
        return $record;
    }

}
