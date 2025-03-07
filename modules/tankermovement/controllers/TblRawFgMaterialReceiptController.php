<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblRawFgMaterialReceiptSearch;
use app\modules\document\models\TblAttachment;
use app\modules\tankermovement\models\TblRawFgMaterialReceipt;

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

    public function actionView($id) {
        $this->model = $this->findModel($id);
        $attachment = new TblAttachment();
        $receipt_attachment = $attachment->getAttachmentDataProvider($id, 'tbl_raw_fg_material_receipt');
        return $this->render('view', [
                    'model' => $this->model,
                    'receipt_attachment' => $receipt_attachment,
                    'attachment' => $attachment,
        ]);
    }

    protected function findModel($id) {
        if (($model = TblRawFgMaterialReceipt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
