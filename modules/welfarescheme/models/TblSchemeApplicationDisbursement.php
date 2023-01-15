<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\welfarescheme\models\TblSchemeMaster;
use \app\modules\organisation\models\TblUnions;
use app\modules\welfarescheme\models\TblSchemeApplication;
use \app\modules\general\models\TblRelationship;

/**
 * This is the model class for table "tbl_scheme_application_disbursement".
 *
 * @property integer $disburse_id
 * @property integer $application_id
 * @property string $disburse_date
 * @property string $disburse_value
 * @property string $disburse_by
 * @property string $payment_mode
 * @property string $bank_code
 * @property string $branch_code
 * @property string $beneficiary_name
 * @property string $party_relation
 * @property string $payment_ref_id
 * @property string $payment_detail
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeApplicationDisbursement extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_application_disbursement';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['application_id', 'originating_type'], 'integer'],
                [['union_code', 'scheme_id', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'payment_detail', 'remarks', 'payment_ref_id', 'branch_code', 'bank_code', 'beneficiary_name', 'payment_mode', 'disburse_by', 'party_relation', 'application_id', 'originating_type', 'disburse_value', 'disburse_date', 'created_at', 'updated_at'], 'safe'],
                [['disburse_value'], 'number'],
                [['union_code', 'scheme_id', 'application_id', 'disburse_value', 'party_relation', 'payment_ref_id', 'branch_code', 'bank_code', 'beneficiary_name', 'payment_mode', 'disburse_date', 'bank_account_no', 'ifsc'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'disburse_id' => Yii::t('app', 'Disburse ID'),
            'scheme_id' => Yii::t('app', 'Scheme'),
            'union_code' => Yii::t('app', 'Union'),
            'application_id' => Yii::t('app', 'Applicant'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'disburse_value' => Yii::t('app', 'Disburse Value'),
            'disburse_by' => Yii::t('app', 'Disburse By'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'party_relation' => Yii::t('app', 'Party Relation'),
            'payment_ref_id' => Yii::t('app', 'Payment Ref ID'),
            'payment_detail' => Yii::t('app', 'Payment Detail'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'bank_account_no' => Yii::t('app', 'Bank Account No.'),
            'ifsc' => Yii::t('app', 'IFSC'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getSchemeId() {
        return $this->hasOne(TblSchemeMaster::className(), ['scheme_id' => 'scheme_id']);
    }

    public function getApplicationId() {
        return $this->hasOne(TblSchemeApplication::className(), ['application_id' => 'application_id']);
    }

    public function getRelationshipId() {
        return $this->hasOne(TblRelationship::className(), ['relationship_code' => 'party_relation']);
    }

}
