<?php

namespace app\modules\payment\models;

use Yii;

class TblPartyPaymentHeadDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_party_payment_head_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['head_detail_code', 'party_payment_code', 'payment_head_code', 'payment_head_type', 'amount'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }
}
