<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TblProductStockController implements the CRUD actions for TblProductStock model.
 */
class TblProductStockController extends \app\controllers\ChildController {

    public $freeAccessActions = ['product-batch-list'];

    public function actionProductBatchList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1] && !empty($parents[2]))) {
                $check_is_mcc = !empty($parents[3]) ? TRUE : FALSE;
                $product = new TblProductStock();
                $data = $product->getProductBatchList($parents[0], $parents[1], $parents[2], $check_is_mcc);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }
    
    public function actionProductBatchListLastSixMonth() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1] && !empty($parents[2]))) {
                $check_is_mcc = !empty($parents[3]) ? TRUE : FALSE;
                $product = new TblProductStock();
                $data = $product->getProductBatchListLastSixMonth($parents[0], $parents[1], $parents[2], $check_is_mcc);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    protected function findModel($id) {
        if (($model = TblProductStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
