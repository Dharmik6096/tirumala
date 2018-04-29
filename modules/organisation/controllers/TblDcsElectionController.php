<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\organisation\models\TblDcsElection;
use app\modules\organisation\models\TblDcsElectionHistory;
use app\modules\organisation\models\TblDcsElectionSearch;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

class TblDcsElectionController extends ChildController {

    public function actionCreate() {
        $this->model = new TblDcsElection();
        $this->viewFile = 'create';
        if (Yii::$app->request->isAjax) {
            $this->model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($this->model);
        } else if ($this->model->load(Yii::$app->request->post())) {
            $this->model->election_date = Yii::$app->formatter->asDate($this->model->election_date, DATE_FORMAT);
            $this->model->tenure_from = Yii::$app->formatter->asDate($this->model->tenure_from, DATE_FORMAT);
            $this->model->tenure_to = !empty($this->model->tenure_to) ? Yii::$app->formatter->asDate($this->model->tenure_to, DATE_FORMAT) : NULL;
            $childModel = [];
            $model = $this->model->getRecord();
            if (!empty($model) && empty($model->tenure_to)) {
                $historyModel = new TblDcsElectionHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
                $model->tenure_to = date('Y-m-d', strtotime('-1 day', strtotime($this->model->tenure_from)));
                $childModel[] = $model;
                $childModel[] = $historyModel;
            }
            $transaction = $this->generalModel->saveTransaction([$this->model], $childModel, ['Election Details', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->isAjax) {
            $this->model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($this->model);
        } else if (Yii::$app->request->post()) {
            $historyModel = new TblDcsElectionHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->election_date = Yii::$app->formatter->asDate($this->model->election_date, DATE_FORMAT);
            $this->model->tenure_from = Yii::$app->formatter->asDate($this->model->tenure_from, DATE_FORMAT);
            $this->model->tenure_to = !empty($this->model->tenure_to) ? Yii::$app->formatter->asDate($this->model->tenure_to, DATE_FORMAT) : NULL;
            $transaction = $this->generalModel->saveTransaction([$this->model], [$historyModel], ['Election Details', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    protected function customRender() {
        $request = Yii::$app->request->queryParams;
        $searchModel = new TblDcsElectionSearch();
        $searchModel->dcs_code = $request['dcs_code'];
        $this->model->dcs_code = $request['dcs_code'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($this->viewFile, ['model' => $this->model, 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider]);
    }

    protected function customRedirect() {
        $request = Yii::$app->request->queryParams;
        return $this->redirect(['create', 'dcs_code' => $request['dcs_code']]);
        //return $this->redirect(Url::previous());
    }

    protected function findModel($id) {
        if (($model = TblDcsElection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
