<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblQualityCollection;
use app\modules\collection\models\TblQualityCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblQualityCollectionController implements the CRUD actions for TblQualityCollection model.
 */
class TblQualityCollectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblQualityCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblQualityCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblQualityCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblQualityCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblQualityCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreate() {
        $this->model = new TblQualityCollection();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $datetime = date('Y-m-d H:i:s');
            $this->model->uuid = Yii::$app->general->getUuid();
            $this->model->collection_date = ($this->model->collection_date) ? date('Y-m-d', strtotime($this->model->collection_date)) : '';
            $this->model->collection_date = $this->model->collection_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $this->model->bmc_code = $this->model->mcc_code;
            $this->model->quality_datetime = $datetime;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['BMC Testing Data', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

}
