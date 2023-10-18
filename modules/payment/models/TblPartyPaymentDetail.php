<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;

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

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_detail_code' => Yii::t('app', 'Payment Detail Code'),
            'party_payment_code' => Yii::t('app', 'Party Payment'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'parsing_no' => Yii::t('app', 'Parsing No'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'receipt_datetime' => Yii::t('app', 'Receipt Datetime'),
            'dispatch_datetime' => Yii::t('app', 'Dispatch Datetime'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'grn_no' => Yii::t('app', 'GRN No'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'disp_qty' => Yii::t('app', 'Disp Qty'),
            'disp_kg_fat' => Yii::t('app', 'Disp Kg FAT'),
            'disp_kg_snf' => Yii::t('app', 'Disp Kg SNF'),
            'rec_qty' => Yii::t('app', 'Recx` Qty'),
            'rec_kg_fat' => Yii::t('app', 'Rec Kg FAT'),
            'rec_kg_snf' => Yii::t('app', 'Rec Kg SNF'),
            'rd_qty_diff' => Yii::t('app', 'RD Qty Diff'),
            'rd_kg_fat_diff' => Yii::t('app', 'RD Kg FAT Diff'),
            'rd_kg_snf_diff' => Yii::t('app', 'RD Kg SNF Diff'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'qty' => Yii::t('app', 'Qty'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'XCol1'),
            'x_col2' => Yii::t('app', 'XCol2'),
            'x_col3' => Yii::t('app', 'XCol3'),
            'x_col4' => Yii::t('app', 'XCol4'),
            'x_col5' => Yii::t('app', 'XCol5'),
        ];
    }

    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'to_dest']);
    }

    public function getPartyPaymentCode() {
        return $this->hasOne(TblPartyPayment::className(), ['party_payment_code' => 'party_payment_code']);
    }
}
