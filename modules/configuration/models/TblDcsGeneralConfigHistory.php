<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_general_config_history".
 *
 * @property integer $id
 * @property integer $code
 * @property integer $allow_multiple_voters
 * @property string $backup_path
 * @property integer $backup_per_shift
 * @property string $created_at
 * @property string $created_by
 * @property integer $election_alert_day
 * @property integer $election_term
 * @property integer $is_backup_user_choice
 * @property integer $is_backup_on_closing
 * @property integer $is_backup_disbursement
 * @property integer $max_share_buy
 * @property integer $min_share_req
 * @property integer $nos_of_reminders
 * @property integer $purchase_rate_with_tax
 * @property integer $sale_rate_with_tax
 * @property integer $share_issued
 * @property string $share_unit_cost
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property integer $milk_dispatch_in
 * @property integer $headload_km
 * @property integer $milk_dispatch_quantity_mode
 * @property integer $milk_receipt_quantity_mode
 * @property string $product_sale_in_cash
 * @property string $share_amount_editable
 * @property string $product_billing
 * @property string $billing_zero_amount_auto
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblDcsGeneralConfigHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_general_config_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'allow_multiple_voters', 'backup_per_shift', 'election_alert_day', 'election_term', 'is_backup_user_choice', 'is_backup_on_closing', 'is_backup_disbursement', 'max_share_buy', 'min_share_req', 'nos_of_reminders', 'purchase_rate_with_tax', 'sale_rate_with_tax', 'share_issued', 'milk_dispatch_in', 'headload_km', 'milk_dispatch_quantity_mode', 'milk_receipt_quantity_mode'], 'integer'],
            [['backup_path', 'created_by', 'updated_by', 'union_code', 'product_sale_in_cash', 'share_amount_editable', 'product_billing', 'billing_zero_amount_auto', 'operation_type', 'history_created_by'], 'string'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['share_unit_cost'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'allow_multiple_voters' => 'Allow Multiple Voters',
            'backup_path' => 'Backup Path',
            'backup_per_shift' => 'Backup Per Shift',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'election_alert_day' => 'Election Alert Day',
            'election_term' => 'Election Term',
            'is_backup_user_choice' => 'Is Backup User Choice',
            'is_backup_on_closing' => 'Is Backup On Closing',
            'is_backup_disbursement' => 'Is Backup Disbursement',
            'max_share_buy' => 'Max Share Buy',
            'min_share_req' => 'Min Share Req',
            'nos_of_reminders' => 'Nos Of Reminders',
            'purchase_rate_with_tax' => 'Purchase Rate With Tax',
            'sale_rate_with_tax' => 'Sale Rate With Tax',
            'share_issued' => 'Share Issued',
            'share_unit_cost' => 'Share Unit Cost',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'union_code' => 'Union Code',
            'milk_dispatch_in' => 'Milk Dispatch In',
            'headload_km' => 'Headload Km',
            'milk_dispatch_quantity_mode' => 'Milk Dispatch Quantity Mode',
            'milk_receipt_quantity_mode' => 'Milk Receipt Quantity Mode',
            'product_sale_in_cash' => 'Product Sale In Cash',
            'share_amount_editable' => 'Share Amount Editable',
            'product_billing' => 'Product Billing',
            'billing_zero_amount_auto' => 'Billing Zero Amount Auto',
            'history_created_at' => 'History Created At',
            'operation_type' => 'Operation Type',
            'history_created_by' => 'History Created By',
        ];
    }
}
