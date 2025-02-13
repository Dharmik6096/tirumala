<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_bank_verification_history".
 *
 * @property integer $id
 * @property integer $bank_verification_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_code
 * @property string $customer_type
 * @property string $beneficiary_id
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $res_beneficiary_status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblBankVerificationHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bank_verification_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'customer_code', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'bank_account_no', 'res_beneficiary_status', 'ifsc', 'beneficiary_id', 'customer_type', 'operation_type', 'bank_verification_code', 'originating_type', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bank_verification_code' => Yii::t('app', 'Bank Verification Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'beneficiary_id' => Yii::t('app', 'Beneficiary ID'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'res_beneficiary_status' => Yii::t('app', 'Res Beneficiary Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
