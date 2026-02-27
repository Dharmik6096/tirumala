<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;

/**
 * This is the model class for table "tbl_branch_history".
 *
 * @property integer $id
 * @property string $address
 * @property string $branch_code
 * @property string $branch_name
 * @property string $local_name
 * @property string $created_at
 * @property string $history_created_at
 * @property string $ifsc
 * @property integer $is_active
 * @property string $operation_type
 * @property string $pincode
 * @property string $updated_at
 * @property string $bank_code
 * @property string $created_by
 * @property string $sub_district_code
 * @property string $district_code
 * @property string $state_code
 * @property string $updated_by
 * @property string $village_code
 *
 * @property TblBanks $bankCode
 * @property TblUsers $createdBy
 * @property TblSubDistricts $subDistrictCode
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblVillages $villageCode
 */
class TblBranchHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_branch_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at', 'is_active'], 'safe'],
                [['branch_code', 'state_code', 'district_code', 'ifsc', 'bank_code', 'sub_district_code', 'union_code', 'address', 'pincode', 'village_code', 'branch_name', 'hamlet_code'], 'safe'],
                [['created_at', 'branch_code', 'state_code', 'district_code', 'ifsc', 'history_created_at', 'updated_at', 'union_code'], 'safe'],
                [['is_active', 'local_name', 'valid_from', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'address' => Yii::t('app', 'Address'),
                  'branch_code' => Yii::t('app', 'Branch Code'),
                  'branch_name' => Yii::t('app', 'Branch Name'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'ifsc' => Yii::t('app', 'Ifsc'),
                  'is_active' => Yii::t('app', 'Is Active'),
                  'operation_type' => Yii::t('app', 'Operation Type'),
                  'pincode' => Yii::t('app', 'Pincode'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'bank_code' => Yii::t('app', 'Bank Code'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'sub_district_code' => Yii::t('app', 'Sub District Code'),
                  'updated_by' => Yii::t('app', 'Updated By'),
                  'village_code' => Yii::t('app', 'Village Code'), */
        ];
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
    public function getCreatedBy() {
        return $this->hasOne(TblUsers::className(), ['user_code' => 'created_by']);
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
    public function getDeletedBy() {
        return $this->hasOne(TblUsers::className());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(TblUsers::className(), ['user_code' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @inheritdoc
     * @return TblBranchHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblBranchHistoryQuery(get_called_class());
    }

}
