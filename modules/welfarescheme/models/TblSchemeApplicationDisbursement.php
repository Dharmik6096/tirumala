<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\welfarescheme\models\TblSchemeMaster;
use \app\modules\organisation\models\TblUnions;
use app\modules\welfarescheme\models\TblSchemeApplication;

/**
 * This is the model class for table "tbl_scheme_application_disbursement".
 *
 * @property integer $disburse_id
 * @property integer $application_id
 * @property string $disburse_date
 * @property string $disburse_value
 * @property string $disburse_by
 * @property string $payment_mode
 * @property string $bank_name
 * @property string $branch_name
 * @property string $party_name
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
                [['union_code', 'scheme_id', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'payment_detail', 'remarks', 'payment_ref_id', 'branch_name', 'bank_name', 'party_name', 'payment_mode', 'disburse_by', 'party_relation', 'application_id', 'originating_type', 'disburse_value', 'disburse_date', 'created_at', 'updated_at'], 'safe'],
                [['disburse_value'], 'number'],
                [['union_code', 'scheme_id', 'application_id', 'disburse_value', 'party_relation', 'payment_ref_id', 'branch_name', 'bank_name', 'party_name', 'payment_mode', 'disburse_date'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'disburse_id' => 'Disburse ID',
            'scheme_id' => 'Scheme ID',
            'union_code' => 'Union Code',
            'application_id' => 'Application ID',
            'disburse_date' => 'Disburse Date',
            'disburse_value' => 'Disburse Value',
            'disburse_by' => 'Disburse By',
            'payment_mode' => 'Payment Mode',
            'bank_name' => 'Bank Name',
            'branch_name' => 'Branch Name',
            'party_name' => 'Party Name',
            'party_relation' => 'Party Relation',
            'payment_ref_id' => 'Payment Ref ID',
            'payment_detail' => 'Payment Detail',
            'remarks' => 'Remarks',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
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

}
