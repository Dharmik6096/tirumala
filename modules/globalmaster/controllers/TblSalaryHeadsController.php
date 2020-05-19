<?php
namespace app\modules\globalmaster\controllers;
use Yii;
use app\modules\globalmaster\models\TblSalaryHeads;
use app\modules\globalmaster\models\TblSalaryHeadsSearch;
use app\modules\globalmaster\models\TblSalaryHeadsHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
/**
 * TblSalaryHeadsController implements the CRUD actions for TblSalaryHeads model.
 */
class TblSalaryHeadsController extends ChildController {
    /**
     * @inheritdoc
     */
    /**
     * Lists all TblSalaryHeads models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSalaryHeadsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
    /**
     * Creates a new TblSalaryHeads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSalaryHeads();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->salary_head_name = ucwords($this->model->salary_head_name);
            $this->model->salary_head_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Salary Head', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    /**
     * Updates an existing TblSalaryHeads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel=new TblSalaryHeadsHistory();
            Yii::$app->operation->history($this->model, $historyModel,UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->salary_head_name = ucfirst($this->model->salary_head_name);
            $transaction = $this->generalModel->saveTransaction([$this->model,$historyModel], ['Salary Head', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    /**
     * Deletes an existing TblSalaryHeads model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblSalaryHeadsHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblSalaryHeads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSalaryHeads the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSalaryHeads::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
