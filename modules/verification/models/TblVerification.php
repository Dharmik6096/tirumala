<?php

namespace app\modules\verification\models;

use Yii;

/**
 * This is the model class for table "tbl_verification".
 *
 * @property integer $verification_code
 * @property string $module_name
 * @property string $module_id
 * @property integer $is_verified
 * @property string $verified_by
 * @property string $verified_on
 * @property string $module_field
 * @property integer $status
 */
class TblVerification extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_verification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['verification_code', 'status'], 'safe'],
            [['verification_code', 'is_verified'], 'integer'],
            [['module_name', 'module_id', 'verified_by', 'module_field'], 'string'],
            [['verified_on'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'verification_code' => Yii::t('app', 'Verification Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_id' => Yii::t('app', 'Module ID'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'verified_by' => Yii::t('app', 'Verified By'),
            'verified_on' => Yii::t('app', 'Verified On'),
            'module_field' => Yii::t('app', 'Module Field'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVerificationQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblVerificationQuery(get_called_class());
    }

    public function getBankDetails($flag) {
        $detail = [
            'tbl-member' => ['model' => 'TblMember', 'field' => 'member_code', 'verify_field' => 'bank_account_no'],
            'tbl-dcs' => ['model' => 'TblBankDetails', 'field' => 'detail_code', 'verify_field' => 'bank_account_no'],
            'tbl-transporter' => ['model' => 'TblTransporter', 'field' => 'detail_code', 'verify_field' => 'bank_account_no'],
        ];
        return isset($detail[$flag]) ? $detail[$flag] : FALSE;
    }

    public function getContactDetails($flag) {
        $detail = [
            'tbl-member' => ['model' => 'TblMember', 'field' => 'member_code', 'verify_field' => 'mobile_no'],
            'tbl-dcs' => ['model' => 'TblContactDetails', 'field' => 'detail_code', 'verify_field' => 'mobile_no'],
        ];
        return isset($detail[$flag]) ? $detail[$flag] : FALSE;
    }

    public function getVerifiedBank() {
        return $this->find()->where(['module_name' => $this->module_name, 'module_field' => $this->module_field, 'module_id' => $this->module_id, 'status' => 1, 'is_verified' => 1])->one();
    }

}
