<?php

namespace app\modules\payment\models;

use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblMember;
use app\modules\product\models\TblProduct;
use Yii;

/**
 * This is the model class for table "tbl_loan_product_sale_details".
 *
 * @property integer $sale_detail_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $member_code
 * @property integer $product_code
 * @property string $sale_date_time
 * @property string $amount
 * @property integer $entry_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $send_status
 * @property string $response_datetime
 * @property string $picked_datetime
 * @property string $resp_desc
 */
class TblLoanProductSaleDetails extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $dcs_name, $member_name;

    public static function tableName() {
        return 'tbl_loan_product_sale_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_code', 'union_code', 'member_code', 'created_by', 'updated_by', 'resp_desc'], 'safe'],
                [['product_code', 'entry_type', 'send_status', 'reference_code', 'data_lock', 'lock_date'], 'safe'],
                [['sale_date_time', 'created_at', 'updated_at', 'response_datetime', 'picked_datetime', 'txfarmer_id', 'txfarmer_id', 'send_status', 'dcs_name', 'member_name', 'received_timestamp'], 'safe'],
                [['amount'], 'number', 'except' => ['locksale']],
                [['send_status'], 'required', 'except' => ['locksale']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sale_detail_code' => Yii::t('app', 'Sale Detail Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'sale_date_time' => Yii::t('app', 'Sale Date Time'),
            'amount' => Yii::t('app', 'Amount'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'send_status' => Yii::t('app', 'Send Status'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'received_timestamp' => Yii::t('app', 'Received Time'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

}
