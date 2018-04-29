<?php

namespace app\modules\webservice\v1\models;

use Yii;
use app\modules\payment\models\TblProductSale;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\product\models\TblProduct;
use app\modules\product\models\TblProductRate;
use app\modules\payment\models\TblProductSaleDetails;

class Product extends TblProductSale {

    public function productSale() {
        return $this->find()->select('*')->where(['dcs_code' => $this->dcs_code])->all();
    }
        
    public function productList(){
        $product_model = new TblProduct();
        $rate_model = new TblProductRate();
        $rate_data = $rate_model->find()->select(['*'])->where(['<=','wef_date',date('Y-m-d')])
                ->andFilterWhere(['=','is_active','1'])
                ->all();
        $product = [];
        $i = 0;
        foreach($rate_data as $rate_code){
            $product[$i]['product_code'] = $rate_code['product_code'];
            $product[$i]['product_name'] = $rate_code->productCode->product_name;
            $product[$i]['product_rate'] = $rate_code['rate'];
            $i++;
        }
        return $product;
    }
    
    public function productSaleDetail($product_info){
        $sale_detail_model =  new TblProductSaleDetails();
        $sale_detail = $sale_detail_model->find()
                ->joinWith('productSaleCode')
                ->select(['tbl_product_sale_details.*','tbl_product_sale.*'])
                ->where(['tbl_product_sale_details.product_code' => $product_info['product_code']])
                ->andWhere(['tbl_product_sale.dcs_code' => $this->dcs_code])
                ->andFilterWhere(['>=','tbl_product_sale.sale_date_time',$product_info['from_date']])
                ->andFilterWhere(['<=','tbl_product_sale.sale_date_time',$product_info['to_date']])
                ->all();
        $sale_details = [];
        $sale_product = [];
        if(!empty($sale_detail)){
            foreach($sale_detail as $product_detail){
                $member_name = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->memberCode, 'member_name');
                $product_name = Yii::$app->general->getforeignkey($product_detail->productCode, 'product_name');
    //            $product_rate = Yii::$app->general->getforeignkey($product_detail->productCode0, 'product_name');;
                $sale_product['member_name'] = $member_name;
                $sale_product['product_name'] = $product_name;
                $sale_product['rate'] = $product_detail->rate;
                $sale_product['quantity'] = $product_detail->qty;
                $sale_product['amount'] = $product_detail->amount;
                $sale_product['sale_date_time'] = $product_detail['productSaleCode']->sale_date_time;
                $sale_details[] = $sale_product;
            }   
        }
        return $sale_details;
        
    }
    
    
    public function memberPurchaseDetail($product_info){
        $sale_model = new TblProductSale();
        $sale_data = $sale_model->find()
                ->where(['member_code' => $product_info['member_code']])
                ->andWhere(['dcs_code' => $this->dcs_code])
                ->andFilterWhere(['>=','sale_date_time',$product_info['from_date']])
                ->andFilterWhere(['<=','sale_date_time',$product_info['to_date']])
                ->all();
        $member_purchase_detail = [];
        $product_detail = [];
        if(!empty($sale_data)){
            foreach($sale_data as $product_sale){
                $sale_date = date('Y-m-d', strtotime($product_sale['sale_date_time']));
                $products = $product_sale->tblProductSaleDetails;
                if(!empty($products)){
                    foreach ($products as $product){
                        $product_name = Yii::$app->general->getforeignkey($product->productCode, 'product_name');
                        $product_detail['product_name'] = $product_name;
                        $product_detail['purchase_code'] = $product->product_sale_code;
                        $product_detail['quantity'] = $product->qty;
                        $product_detail['rate'] = $product->rate;
                        $product_detail['amount'] = $product->amount;
                        $product_detail['is_installment'] = ($product_sale->is_installment == 0) ? 'No' : 'Yes';
                        $product_detail['no_of_installment'] = $product_sale->no_of_installment;
                        $product_detail['amount_due'] = $product_sale->amount_due;
                        $product_detail['purchase_date'] = $product_sale->sale_date_time;
                        $member_purchase_detail[] = $product_detail;
                    }
                }
            }
        }
        return $member_purchase_detail;        
    }
    
}
