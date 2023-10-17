<?php

namespace app\modules\payment\models;

use Yii;

class TblPartyPaymentDetail extends \app\models\ChildModel {
    public $from_date, $to_date;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_party_payment_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_detail_code', 'party_payment_code', 'vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest'], 'safe'],
            [['receipt_datetime', 'dispatch_datetime', 'challan_no', 'grn_no', 'trip_code', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf'], 'safe'],
            [['rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'milk_type_code', 'milk_quality_type_code'], 'safe'],
            [['qty', 'fat', 'snf', 'rtpl', 'amount'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }
}
