<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\payment\models\TblSaleInstallments;

/**
 * This is the model class for table "tbl_product_sale_details".
 *
 * @property integer $product_sale_transaction_code
 * @property string $product_sale_code
 * @property integer $product_code
 * @property string $product_sale_rate_applicability_code
 * @property double $rate
 * @property string $quantity
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblProduct $productCode
 * @property TblProduct $productCode0
 * @property TblProductSale $productSaleCode
 */
class TblProductSaleTransaction extends \app\models\ChildModel {

    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
//        return 'tbl_product_sale_details';
        return 'tbl_product_sale_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_sale_transaction_code', 'product_sale_code', 'product_code', 'quantity'], 'required', 'except' => ['saleProduct', 'androidsync']],
                [['product_sale_code', 'product_code', 'quantity', 'rate', 'unit_code', 'tax_code'], 'required', 'on' => ['saleProduct']],
                [['product_code', 'quantity'], 'integer', 'except' => ['androidsync']],
                [['product_sale_code', 'product_sale_rate_applicability_code', 'created_by', 'updated_by'], 'string', 'except' => ['androidsync']],
                [['rate', 'quantity', 'amount'], 'number', 'min' => 0, 'except' => ['androidsync']],
                [['created_at', 'updated_at', 'product_sale_rate_applicability_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'product_sale_transaction_code', 'discount', 'unit_code', 'tax_code', 'tax_amount', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code'], 'except' => ['androidsync']],
//                [['product_sale_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProductSale::className(), 'targetAttribute' => ['product_sale_code' => 'product_sale_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_sale_transaction_code' => Yii::t('app', 'Sale Detail Code'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'product_code' => Yii::t('app', 'Product'),
            'product_sale_rate_applicability_code' => Yii::t('app', 'Rate App Code'),
            'rate' => Yii::t('app', 'Rate'),
            'quantity' => Yii::t('app', 'Quantity'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'amount_due' => Yii::t('app', 'Total Amount'),
            'unit_code' => Yii::t('app', 'Unit'),
            'tax_code' => Yii::t('app', 'Tax'),
            'tax_amount' => Yii::t('app', 'Tax Amount'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCode0() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSaleCode() {
        return $this->hasOne(TblProductSale::className(), ['product_sale_code' => 'product_sale_code']);
    }

    /**
     * @inheritdoc
     * @return TblProductSaleTransactionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProductSaleTransactionQuery(get_called_class());
    }

    public function getTransData($invoice_no) {
        return $this->find()->select(['product_sale_transaction_code', 'amount',
                    'quantity', 'rate', 'product_sale_code', 'product_code',
                ])->where(['product_sale_code' => $invoice_no])->all();
    }

    public function productSaleDetail($product_info, $dcs_code) {
        $sale_detail = $this->find()
                ->joinWith('productSaleCode')
                ->select(['tbl_product_sale_details.*', 'tbl_product_sale.*'])
                ->where(['tbl_product_sale_details.product_code' => $product_info['product_code']])
                ->andWhere(['tbl_product_sale.dcs_code' => $dcs_code])
                ->andFilterWhere(['>=', 'tbl_product_sale.sale_date_time', $product_info['from_date']])
                ->andFilterWhere(['<=', 'tbl_product_sale.sale_date_time', $product_info['to_date']])
                ->all();
        $sale_details = [];
        $sale_product = [];
        if (!empty($sale_detail)) {
            foreach ($sale_detail as $product_detail) {
                $product_name = Yii::$app->general->getforeignkey($product_detail->productCode, 'product_name');
                $sale_product['dcs_code'] = $product_detail['productSaleCode']->dcs_code;
                $type = Yii::$app->general->getforeignkey($product_detail->productSaleCode, 'type');
                $sale_product['dcs_name'] = '';
                $sale_product['member_name'] = '';
                if ($type = 'MEMBER') {
                    $sale_product['dcs_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->dcsCode, 'dcs_name');
                    $sale_product['member_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->memberCode, 'member_name');
                }
                if ($type = 'DCS') {
                    $sale_product['dcs_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->dcsBmcCode, 'bmc_name');
                    $sale_product['member_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->memberDcsCode, 'dcs_name');
                }
                $sale_product['product_name'] = $product_name;
                $sale_product['rate'] = $product_detail->rate;
                $sale_product['quantity'] = $product_detail->quantity;
                $sale_product['amount'] = $product_detail->amount;
                $sale_product['sale_date_time'] = $product_detail['productSaleCode']->sale_date_time;
                $sale_details[] = $sale_product;
            }
        }
        return $sale_details;
    }

    public function getSaleInstallments() {
        return $this->hasMany(TblSaleInstallments::className(), ['product_sale_code' => 'product_sale_code']);
    }

}
