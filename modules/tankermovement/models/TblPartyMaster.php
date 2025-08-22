<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblBanks;
use app\modules\geo\models\TblStates;
use app\modules\organisation\models\TblBranch;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use yii\helpers\ArrayHelper;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_party_master".
 *
 * @property string $party_master_code
 * @property string $union_code
 * @property string $party_name
 * @property string $party_contact_no
 * @property string $party_address
 * @property string $owner_name
 * @property string $owner_contact_no
 * @property string $owner_email
 * @property string $owner_address
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $beneficiary_name
 * @property string $pan_no
 * @property string $adhar_no
 * @property integer $is_active
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
class TblPartyMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_party_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['union_code', 'party_name', 'party_contact_no', 'party_type'], 'required'],
                [['party_master_code', 'union_code', 'party_name', 'party_contact_no', 'party_address', 'owner_name', 'owner_contact_no', 'owner_email', 'owner_address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'beneficiary_name', 'pan_no', 'adhar_no', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'sap_vendor_code', 'is_sales_office', 'party_type'], 'safe'],
                [['owner_email'], 'email'],
                [['is_active'], 'default', 'value' => 1],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['party_contact_no', 'owner_contact_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['beneficiary_name'], function ($attribute, $params) {
                    Yii::$app->general->validateBeneficiary($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                },],
                [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblPartyMaster', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'party_master_code' => Yii::t('app', 'Party Master Code'),
            'union_code' => Yii::t('app', 'Union'),
            'party_name' => Yii::t('app', 'Party Name'),
            'party_contact_no' => Yii::t('app', 'Party Contact No'),
            'party_address' => Yii::t('app', 'Party Address'),
            'owner_name' => Yii::t('app', 'Owner Name'),
            'owner_contact_no' => Yii::t('app', 'Owner Contact No'),
            'owner_email' => Yii::t('app', 'Owner Email'),
            'owner_address' => Yii::t('app', 'Owner Address'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'adhar_no' => Yii::t('app', 'Adhar No'),
            'is_active' => Yii::t('app', 'Is Active'),
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
            'sap_vendor_code' => Yii::t('app', 'Sap Vendor Code'),
            'is_sales_office' => Yii::t('app', 'Is Sales Office'),
            'party_type' => Yii::t('app', 'Party Type'),
        ];
    }

    public function getPartyList($unionCode, $RLS = 'TRUE', $notIn = [], $concatCode = false, $partyType = '') {
        $query = $this->find()->select(['party_master_code', 'party_name', 'sap_vendor_code'])
                ->where(['union_code' => $unionCode, 'is_active' => 1]);
        if (!empty($partyType)) {
            $partyTypeIn = ($partyType === 'bmcMilkDispatch' || $partyType === 'milkReceiptSource') ? ['conversion_vendor', 'sales_party'] : ($partyType === 'milkReceiptDest' ? ['sales_party'] : []);
            !empty($partyTypeIn) && $query->andWhere(['party_type' => $partyTypeIn]);
        }
        $value = $query->orderBy('party_name asc')->all();
        $value = ArrayHelper::map($value, 'party_master_code', function ($value) use ($concatCode) {
                    return $value->party_name . ($concatCode && !empty($value->sap_vendor_code) ? ' - ' . $value->sap_vendor_code : '');
                });
        return $value;
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    public function getUnionPartyList($unionCode) {
        $partyList = $this->find()->select(["CONCAT(party_master_code, '#party') AS party_master_code, CONCAT(party_name, ' - ', party_type, ' - party') AS party_name"])
                        ->where(['is_active' => 1, 'party_type' => 'sales_party', 'union_code' => $unionCode])->asArray()->all();
        return ArrayHelper::map($partyList, 'party_master_code', 'party_name');
    }

    public function getMappedPartyList($unionCode) {
        $partyList = $this->find()
            ->alias('p')
            ->select(["CONCAT(p.party_master_code, '#party#', p.party_type) AS party_master_code","CONCAT(party_name, ' - ', REPLACE(p.party_type, '_', ' '), ' - party') AS party_name"])
            ->innerJoin('tbl_plant_conversion_vendor_mapping m','m.party_master_code = p.party_master_code')
            ->where(['p.is_active' => 1,'p.party_type' => 'conversion_vendor','p.union_code' => $unionCode])
            ->asArray()->all();
        return ArrayHelper::map($partyList, 'party_master_code', 'party_name');
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code, '', false, 1);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
