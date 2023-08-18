<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_bill_head_applicability_history".
 *
 * @property integer $id
 * @property integer $mcc_bill_head_applicabilty_code
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $wef_date
 * @property string $from_date
 * @property string $to_date
 * @property string $mcc_bill_head_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $union_code
 * @property string $bill_head_for
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblMccBillHeadApplicabilityHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mcc_bill_head_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mcc_bill_head_applicabilty_code', 'originating_type'], 'integer'],
            [['wef_date', 'from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['applicable_code', 'applicable_for', 'bill_head_for'], 'string', 'max' => 20],
            [['mcc_bill_head_code', 'operation_type'], 'string', 'max' => 10],
            [['dcs_code', 'bmc_code'], 'string', 'max' => 12],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
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
            'mcc_bill_head_applicabilty_code' => 'Mcc Bill Head Applicabilty Code',
            'applicable_code' => 'Applicable Code',
            'applicable_for' => 'Applicable For',
            'wef_date' => 'Wef Date',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'mcc_bill_head_code' => 'Mcc Bill Head Code',
            'dcs_code' => 'Dcs Code',
            'bmc_code' => 'Bmc Code',
            'union_code' => 'Union Code',
            'bill_head_for' => 'Bill Head For',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
            'history_created_at' => 'History Created At',
            'operation_type' => 'Operation Type',
            'history_created_by' => 'History Created By',
        ];
    }
}
