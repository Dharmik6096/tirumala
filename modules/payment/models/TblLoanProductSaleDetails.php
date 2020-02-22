<?php

namespace app\modules\payment\models;

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
class TblLoanProductSaleDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_loan_product_sale_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'union_code', 'member_code', 'created_by', 'updated_by', 'resp_desc'], 'string'],
            [['product_code', 'entry_type', 'send_status'], 'integer'],
            [['sale_date_time', 'created_at', 'updated_at', 'response_datetime', 'picked_datetime'], 'safe'],
            [['amount'], 'number'],
            [['send_status'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
        ];
    }
}
