<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblBankVerificationHistory;

/**
 * This is the model class for table "tbl_bank_verification".
 *
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
 */
class TblBankVerification extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bank_verification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['beneficiary_id', 'bank_account_no', 'res_beneficiary_status', 'ifsc', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'customer_code', 'customer_type', 'created_at', 'updated_at', 'originating_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
        ];
    }

    public function setBankregistrationData($postData, $model, &$saveModel, $verifymodelData, &$hisModel, $code, $type) {
        if (!empty($postData)) {
            $beneficiaryStatus = !empty($postData->beneficiary_status) ? $postData->beneficiary_status : null;
            $bankAccountNo = !empty($model['bank_account_no']) ? $model['bank_account_no'] : null;
            $ifsc = !empty($model['ifsc']) ? $model['ifsc'] : null;
            $beneficiaryId = $model['beneficiary_id'];
            if (!empty($verifymodelData) && $model['bank_account_no'] == $verifymodelData->bank_account_no) {
                $historyModel = new TblBankVerificationHistory();
                Yii::$app->operation->history($verifymodelData, $historyModel, 'DELETE');
                $hisModel[] = $historyModel;
                $this->setAttributes($model->attributes);
                $this->setModelData($verifymodelData, $beneficiaryId, $code, $type, $bankAccountNo, $ifsc, $beneficiaryStatus);
                $saveModel[] = $verifymodelData;
            } else {
                $this->setAttributes($model->attributes);
                $this->setModelData($this, $beneficiaryId, $code, $type, $bankAccountNo, $ifsc, $beneficiaryStatus);
                $saveModel[] = $this;
            }
        }
    }

    public function setModelData($model, $beneficiaryId, $code, $type, $bankAccountNo, $ifsc, $beneficiaryStatus) {
        $model->beneficiary_id = $beneficiaryId;
        $model->customer_code = $code;
        $model->customer_type = $type;
        $model->bank_account_no = $bankAccountNo;
        $model->ifsc = $ifsc;
        $model->res_beneficiary_status = $beneficiaryStatus;
    }

}
