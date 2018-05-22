<?php

namespace app\modules\webservice\member\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\payment\models\TblProductSale;
use app\modules\payment\models\TblProductSaleDetails;
use app\modules\payment\models\TblSaleInstallments;
use Yii;

class ProductSaleToMemberController extends ChildController {

    public $replace_array = [
        'replace_coloumn' => [
            'product_sale_code' => 'invoice_no',
            'amount' => 'cash',
            'amount_due' => 'credit',
            'sale_date_time' => 'date',
            'no_of_installment' => 'no_of_installments',
            'is_installment' => 'payment_mode',
            'member_code' => 'consumer_code',
            'installment_code' => 'installment_no',
            'sale_code' => 'invoice_no',
            'sale_type' => 'type',
            'sale_detail_code' => 'invoice_transaction_no',
            'qty' => 'quantity',
        ],
        'add_coloumn' => [
            'deduction_start_date' => '',
            'voucher_no' => '',
            'collection_point_code' => '',
            'sub_center_code' => '',
            'consumer_type' => '1',
            'transaction_type' => '',
            'actual_installment' => '',
            'deduction_date' => '',
            'is_billing' => '',
            'pending_principle_amount' => '',
            'previous_pending_amount' => '',
            'discount' => '',
            'is_loose_sale' => '',
            'tax_amount' => '',
            'parent_product_code' => '',
            'product_scheme_code' => '',
            'tax_code' => '',
            'unit_code' => '',
        ],
    ];

    public function actionMemberSaleData() {
        $model = new TblProductSale();
        $instModel = new TblSaleInstallments();
        $tranModel = new TblProductSaleDetails();
        $model->setAttributes($this->post_data);
        $result_master = $model->getData($this->post_data['content']);
        $data = [];
        foreach ($result_master as $value) {
            $inst_data = $trns_data = [];
            $array = $value;
            $inst = $instModel->getInstData($value['product_sale_code']);
            foreach ($inst as $in) {
                $inst_temp = $in->oldAttributes;
                $inst_temp['date'] = $array['sale_date_time'];
                $inst_temp = $this->replaceColoumn($inst_temp);
                $inst_temp = $this->addColoumn($inst_temp);
                $inst_data[] = $inst_temp;
            }
            $trns = $tranModel->getTransData($value['product_sale_code']);
            foreach ($trns as $tr) {
                $trns_temp = $tr->oldAttributes;
                $trns_temp['product_name'] = $tr->productCode->product_name;
                $trns_temp['date'] = $array['sale_date_time'];
                $trns_temp = $this->replaceColoumn($trns_temp);
                $trns_temp = $this->addColoumn($trns_temp);
                $trns_data[] = $trns_temp;
            }
            $array['ProductSaleMemberInstallment'] = $inst_data;
            $array['ProductSaleToMemberTransactions'] = $trns_data;
            $array = $this->replaceColoumn($array);
            $array = $this->addColoumn($array);
            $data[] = $array;
        }
        $this->response['data'] = $data;
        return $this->response;
    }

}
