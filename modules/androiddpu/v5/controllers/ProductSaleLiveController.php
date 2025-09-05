<?php

namespace app\modules\androiddpu\v5\controllers;

use app\modules\payment\models\TblProductSale;
use app\modules\payment\models\TblProductSaleAlias;
use Yii;

class ProductSaleLiveController extends \app\modules\androiddpu\v4\controllers\ProductSaleLiveController {

    public function actionDeleteProductSale() {
        $data = $this->post_data;
        $content = $data['content'];
        $res_data = [];
        $msg = 'Error while save data.';

        if (!empty($data['organization_code']) && !empty($data['organization_type']) && in_array($data['organization_type'], ['VLC', 'BMC']) && !empty($content['customer_type']) && !empty($content['customer_code']) && !empty($content['invoice_date']) && !empty($content['product_code']) && !empty($content['quantity'])) {
            $query = TblProductSale::find()
                    ->joinWith('tblProductSaleDetails')
                    ->select(['tbl_product_sale.*'])
                    ->where([
                'tbl_product_sale.customer_type' => $content['customer_type'],
                'tbl_product_sale.customer_code' => $content['customer_code'],
                'tbl_product_sale.invoice_date' => date('Y-m-d', strtotime($content['invoice_date'])),
                'tbl_product_sale_transaction.product_code' => $content['product_code'],
                'tbl_product_sale_transaction.quantity' => $content['quantity'],
            ]);
            if (!empty($content['sap_batch_no'])) {
                $query->andWhere(['sap_batch_no' => $content['sap_batch_no']]);
            }
            $productSaleData = $query->orderBy('created_at', SORT_DESC)->one();
            if (!empty($productSaleData)) {
                $productSaleAliasData = TblProductSaleAlias::findOne(['product_sale_code' => $productSaleData->product_sale_code]);
                if (!empty($productSaleAliasData)) {
                    $res_data['data'] = '';
                    $msg = 'Delete Request Already Available';
                } else {
                    $productSaleAliasModel = new TblProductSaleAlias();
                    $productSaleAliasModel->action_perform = 'DELETE';
                    $productSaleAliasModel->product_sale_code = $productSaleData->product_sale_code;
                    $productSaleAliasModel->ref_product_sale_code = $content['ref_product_sale_code'];
                    $productSaleAliasModel->customer_type = $productSaleData->customer_type;
                    $productSaleAliasModel->customer_code = $productSaleData->customer_code;
                    $productSaleAliasModel->approval_status = 0;
                    $productSaleAliasModel->originating_type = $data['organization_type'];
                    $productSaleAliasModel->originating_org_code = $data['organization_code'];
                    $productSaleAliasModel->originating_org_type = $data['organization_type'];
                    $productSaleAliasModel->x_col1 = Yii::$app->general->getUuid();
                    $productSaleAliasModel->save();
                    $msg = 'Delete Request saved successfully.';
                    $res_data['data'] = $productSaleAliasModel;
                }
            }
        } else {
            $msg = 'Empty request received.';
        }
        $res_data['msg'] = $msg;
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
