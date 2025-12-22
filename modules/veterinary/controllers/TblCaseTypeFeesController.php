<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\veterinary\models\TblCaseTypeFees;
use app\modules\veterinary\models\TblCaseTypeFeesSearch;
use yii\web\NotFoundHttpException;

/**
 * TblCaseTypeFeesController implements the CRUD actions for TblCaseTypeFees model.
 */
class TblCaseTypeFeesController extends ChildController {

    /**
     * Lists all TblCaseTypeFees models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCaseTypeFeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCaseTypeFees model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblCaseTypeFees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCaseTypeFees();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Case Type Fees', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblCaseTypeFees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblCaseTypeFees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCaseTypeFees::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
