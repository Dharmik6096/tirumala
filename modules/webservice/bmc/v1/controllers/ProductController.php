<?php

namespace app\modules\webservice\bmc\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\payment\models\TblProductSaleDetails;
use app\modules\organisation\models\TblDcs;
use app\modules\product\models\TblProductRate;
use Yii;

class ProductController extends ChildController {

    public function actionProductList() {
        $model = new TblProductRate();
        $model->setAttributes($this->post_data);
        $data = $model->productList();
        $this->response['data'] = $data;
    }

    public function actionProductSaleDetail() {
        $model = new TblProductSaleDetails();
        $model->setAttributes($this->post_data);
        $product_info = $this->post_data['content'];
        $dcs_model = new TblDcs();
        $dcs_model->bmc_code = $this->post_data['bmc_code'];
        $dcs_code = $dcs_model->getBmcDcsData();
        $sale_detail = $model->productSaleDetail($product_info, $dcs_code);
        $this->response['data'] = $sale_detail;
    }

}
