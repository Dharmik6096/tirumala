<?php

namespace app\modules\welfarescheme\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_application_disbursement_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
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
class TblSchemeApplicationDisbursementHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_scheme_application_disbursement_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'disburse_date', 'created_at', 'updated_at'], 'safe'],
            [['disburse_id', 'application_id', 'originating_type'], 'integer'],
            [['disburse_value'], 'number'],
            [['operation_type'], 'string', 'max' => 10],
            [['history_created_by', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['disburse_by', 'party_relation'], 'string', 'max' => 20],
            [['payment_mode'], 'string', 'max' => 15],
            [['bank_name', 'party_name'], 'string', 'max' => 100],
            [['branch_name'], 'string', 'max' => 150],
            [['payment_ref_id'], 'string', 'max' => 50],
            [['payment_detail', 'remarks'], 'string', 'max' => 255],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
            'disburse_id' => 'Disburse ID',
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
}
