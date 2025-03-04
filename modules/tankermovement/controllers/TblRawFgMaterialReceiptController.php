<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblRawFgMaterialReceiptSearch;

/**
 * TblRawFgMaterialReceiptController implements the CRUD actions for TblRawFgMaterialReceipt model.
 */
class TblRawFgMaterialReceiptController extends \app\controllers\ChildController {

    /**
     * Lists all TblRawFgMaterialReceipt models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblRawFgMaterialReceiptSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
