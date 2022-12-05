<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeCriteria;
use app\modules\welfarescheme\models\TblSchemeCriteriaSearch;
use app\modules\welfarescheme\models\TblSchemeCriteriaHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\helpers\Json;


/**
 * TblSchemeCriteriaController implements the CRUD actions for TblSchemeCriteria model.
 */
class TblSchemeCriteriaController extends \app\controllers\ChildController {

    /**
     * Lists all TblSchemeCriteria models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSchemeCriteriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeCriteria model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSchemeCriteria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblSchemeCriteria();
        $this->viewFile = 'create';
        $this->model->scheme_id = $id;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $this->model->union_code = Yii::$app->general->getforeignkey($this->model->schemeId, 'union_code');
            if (!$this->model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            } else {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Scheme Criteria', 'create']);
                if ($transaction == 'customRedirect') {
                    $record = ['status' => 'success', 'msg' => ''];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }

        return $this->customRender();
    }

    /**
     * Updates an existing TblSchemeCriteria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblSchemeCriteriaHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $this->model->union_code = Yii::$app->general->getforeignkey($this->model->schemeId, 'union_code');
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Scheme Criteria', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    /**
     * Finds the TblSchemeCriteria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeCriteria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeCriteria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function customRender() {
        $request = Yii::$app->request->queryParams;
        $searchModel = new TblSchemeCriteriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($this->viewFile, ['model' => $this->model, 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'id' => $request['id'], 'dist' => '']);
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }
    
    
}
