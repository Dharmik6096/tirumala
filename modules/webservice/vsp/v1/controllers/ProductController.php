<?php

namespace app\modules\webservice\vsp\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\vsp\v1\models\Product;
use Yii;
use app\modules\payment\models\TblMemberCreditLimit;
use app\modules\payment\models\TblMemberCreditLimitHistory;
use app\modules\payment\models\TblMemberCreditLimitTransaction;
use app\modules\payment\models\TblProductSale;
use app\modules\payment\models\TblProductSaleDetails;
use app\modules\payment\models\TblSaleInstallments;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;

class ProductController extends ChildController {

    public function actionProductList() {
        $model = new Product();
        $model->setAttributes($this->post_data);
        $data = $model->productList();
        $this->response['data'] = $data;
    }

    public function actionProductSale() {
        $model = new Product();
        $model->setAttributes($this->post_data);

        $product_info = $this->post_data['content'];

        $product_list = $product_info['product_list'];



        $products = [];

        $product_sale = new TblProductSale();
        $product_sale_code = (string) Yii::$app->general->getCodeAutoIncrement($product_sale);
        $product_sale->product_sale_code = $product_sale_code;
        $product_sale->dcs_code = $model->dcs_code;
        $product_sale->member_code = $product_info['member_code'];
        $product_sale->union_code = $product_sale->memberCode->union_code;
        $product_sale->amount = $product_info['payable_amount'];
        $product_sale->sale_date_time = date('Y-m-d H:i:s');
        $product_sale->other_amount = 0;
        $product_sale->discount = 0;
        $product_sale->paid_amount = $product_sale->amount;
        $product_sale->amount_due = 0;
        $product_sale->is_installment = 0;
        $product_sale->no_of_installment = 0;

        $i = 0;
        foreach ($product_list as $product) {
            $product_sale_detail = new TblProductSaleDetails();
            $product_sale_detail_code = (int) Yii::$app->general->getCodeAutoIncrement($product_sale_detail) + $i;
            $product_sale_detail_code = (string) $product_sale_detail_code;
            $i++;
            $product_sale_detail->sale_detail_code = $product_sale_detail_code;
            $product_sale_detail->product_sale_code = $product_sale_code;
            $product_sale_detail->product_code = round($product['product_code']);
            $product_sale_detail->rate = round($product['rate']);
            $product_sale_detail->qty = round($product['qty']);
            $product_sale_detail->amount = round($product['amount']);
            array_push($products, $product_sale_detail);
        }
        $credit_limit = [];
        $payment_mode = 'Cash';
        if ($product_info['payment_type'] == 1) {
            $appCycleModel = new TblDcsPaymentCycleApplicability();
            $data = $appCycleModel->apiCurrentDcsPaymentCycle($product_sale->dcs_code, date('Y-m-d'));
            if (!empty($data)) {              
                $product_sale->paid_amount = 0;
                $product_sale->amount_due = $product_sale->amount;
                $product_sale->is_installment = 1;
                $product_sale->no_of_installment = 1;
                
                $installmentModel = new TblSaleInstallments();
                $installmentModel->installment_code = Yii::$app->general->getCodeAutoIncrement($installmentModel);
                $installmentModel->sale_type = 'product';
                $installmentModel->sale_code = $product_sale_code;
                $installmentModel->member_code = $product_sale->member_code;
                $installmentModel->dcs_code = $product_sale->dcs_code;
                $installmentModel->union_code = $product_sale->union_code;
                $installmentModel->main_amount = $product_sale->amount_due;
                $installmentModel->installment_amount = $product_sale->amount_due;
                $installmentModel->installment_status = 0;
                $installmentModel->is_active = 1;
                $installmentModel->dcs_payment_cycle_code = $data->payment_cycle_code;
                $installmentModel->payment_cycle_applicabilty_code = $appCycleModel->dcsPaymentCycleAppCode($product_sale->dcs_code);
                array_push($products, $installmentModel);
                
                $payment_mode = 'Credit';
                $credit_limit_model = new TblMemberCreditLimit();
                $credit_limit_data = $credit_limit_model->find()->where(['member_code' => $product_info['member_code']])->one();
                if (!empty($credit_limit_data)) {
                    $credit_limit_history_model = new TblMemberCreditLimitHistory();
                    Yii::$app->operation->history($credit_limit_data, $credit_limit_history_model, 'UPDATE');

                    $old_balance = $credit_limit_data->balance;
                    $due = $product_info['payable_amount'];
                    $new_balance = $old_balance - $due;
                    $credit_limit_data->balance = $new_balance;
                    array_push($credit_limit, $credit_limit_data);
                    array_push($credit_limit, $credit_limit_history_model);

                    $credit_limit_transaction_model = new TblMemberCreditLimitTransaction();
                    $credit_limit_transaction_model->member_credit_limit_code = $credit_limit_data->member_credit_limit_code;
                    $credit_limit_transaction_model->old_value = $old_balance;
                    $credit_limit_transaction_model->transaction_type = 2;
                    $credit_limit_transaction_model->new_value = $due;
                    $credit_limit_transaction_model->balance = $new_balance;
                    array_push($credit_limit, $credit_limit_transaction_model);
                } else {
                    $data = ['message' => 'Credit Not Available'];
                    $this->response['data'] = $data;
                }
            } else {
                $data = ['message' => 'Payment Cycle is not available.'];
                $this->response['data'] = $data;
            }
        }
        array_push($products, $product_sale);

        $transaction = $this->generalModel->saveTransaction($products, $credit_limit, ['Product Sale', 'edit']);
        if ($transaction == 'customRedirect') {
            $data = ['message' => 'Product sale done in ' . $payment_mode . ' mode successfully'];
            $this->response['data'] = $data;
        } else {
            $data = ['message' => 'Product Sale not done'];
            $this->response['data'] = $data;
        }
    }

    public function actionProductSaleDetail() {
        $model = new Product();
        $model->setAttributes($this->post_data);
        $product_info = $this->post_data['content'];
        $sale_detail = $model->productSaleDetail($product_info);
        $this->response['data'] = $sale_detail;
    }

    public function actionMemberPurchaseDetail() {
        $model = new Product();
        $model->setAttributes($this->post_data);
        $product_info = $this->post_data['content'];
        $sale_detail = $model->memberPurchaseDetail($product_info);
        $this->response['data'] = $sale_detail;
    }

}
