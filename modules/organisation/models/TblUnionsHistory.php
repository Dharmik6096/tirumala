<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;

/**
 * This is the model class for table "tbl_unions_history".
 *
 * @property integer $id
 * @property string $address
 * @property string $bank_account_no
 * @property string $city
 * @property string $contact_person
 * @property string $contact_person_email
 * @property string $contact_person_mobile_no
 * @property string $contact_person_pan_no
 * @property string $contact_person_phone_no
 * @property string $created_at
 * @property string $history_created_at
 * @property string $ifsc
 * @property integer $is_active
 * @property string $operation_type
 * @property string $phone_no
 * @property string $fax_no
 * @property string $pincode
 * @property string $registration_date
 * @property string $registration_no
 * @property string $union_code
 * @property string $union_code_ex
 * @property string $union_name
 * @property string $local_name
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
 * @property string $gst_no
 *
 * @property TblSubDistricts $subDistrictCode
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblStates $stateCode
 * @property TblUsers $deletedBy
 * @property TblDistricts $districtCode
 * @property TblHamlets $hamletCode
 * @property TblBranch $branchCode
 * @property TblFederations $federationCode
 * @property TblVillages $villageCode
 * @property TblBanks $bankCode
 */
class TblUnionsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_unions_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at', 'is_active'], 'safe'],
                [['address', 'city', 'bank_code', 'federation_code', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'contact_person', 'contact_person_email', 'contact_person_phone_no', 'dcs_short_name', 'dcs_code', 'dcs_code_ex', 'contact_person_mobile_no', 'union_code', 'union_code_ex', 'union_name', 'bank_account_no', 'ifsc', 'contact_person_pan_no', 'village_code', 'upi_no', 'branch_code', 'valid_from'], 'safe'],
                [['created_at', 'history_created_at', 'registration_date', 'updated_at', 'fax_no', 'upi_no', 'ifsc'], 'safe'],
                [['is_active', 'local_name', 'local_address', 'gst_no', 'has_bmc', 'eipl_token', 'eipl_code'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'allow_member_create'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'address' => Yii::t('app', 'Address'),
                  'bank_account_no' => Yii::t('app', 'Bank Account No'),
                  'city' => Yii::t('app', 'City'),
                  'contact_person' => Yii::t('app', 'Contact Person'),
                  'contact_person_email' => Yii::t('app', 'Contact Person Email'),
                  'contact_person_mobile_no' => Yii::t('app', 'Contact Person Mobile No'),
                  'contact_person_pan_no' => Yii::t('app', 'Contact Person Pan No'),
                  'contact_person_phone_no' => Yii::t('app', 'Contact Person Phone No'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'ifsc' => Yii::t('app', 'Ifsc'),
                  'is_active' => Yii::t('app', 'Is Active'),
                  'operation_type' => Yii::t('app', 'Operation Type'),
                  'phone_no' => Yii::t('app', 'Phone No'),
                  'pincode' => Yii::t('app', 'Pincode'),
                  'registration_date' => Yii::t('app', 'Registration Date'),
                  'registration_no' => Yii::t('app', 'Registration No'),
                  'union_code' => Yii::t('app', 'Union Code'),
                  'union_code_ex' => Yii::t('app', 'Union Code Ex'),
                  'union_name' => Yii::t('app', 'Union Name'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'bank_code' => Yii::t('app', 'Bank Code'),
                  'branch_code' => Yii::t('app', 'Branch Code'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'district_code' => Yii::t('app', 'District Code'),
                  'federation_code' => Yii::t('app', 'Federation Code'),
                  'hamlet_code' => Yii::t('app', 'Hamlet Code'),
                  'state_code' => Yii::t('app', 'State Code'),
                  'sub_district_code' => Yii::t('app', 'Sub District Code'),
                  'updated_by' => Yii::t('app', 'Updated By'),
                  'village_code' => Yii::t('app', 'Village Code'),
                  'upi_no' => Yii::t('app', 'UPI No.'), */
        ];
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
    /*  public function getUpdatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
      }
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    /*  public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
      }*./

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
      return $this->hasOne(TblUsers::className());
      } */

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
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @inheritdoc
     * @return TblUnionsHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblUnionsHistoryQuery(get_called_class());
    }

}
