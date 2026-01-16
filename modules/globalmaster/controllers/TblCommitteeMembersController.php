<?php

namespace app\modules\globalmaster\controllers;

use Yii;
use app\modules\globalmaster\models\TblCommitteeMembers;
use app\modules\globalmaster\models\TblCommitteeMembersSearch;
use app\modules\globalmaster\models\TblCommitteeMembersHistory;
use app\modules\dcsoperation\models\TblMember;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblCommitteeMembersController implements the CRUD actions for TblCommitteeMembers model.
 */
class TblCommitteeMembersController extends \app\controllers\ChildController {

    /**
     * Lists all TblCommitteeMembers models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCommitteeMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCommitteeMembers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblCommitteeMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCommitteeMembers();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->election_date = Yii::$app->formatter->asDate($this->model->election_date, DATE_FORMAT);
            $this->model->tenure_from_date = Yii::$app->formatter->asDate($this->model->tenure_from_date, DATE_FORMAT);
            $this->model->tenure_to_date = Yii::$app->formatter->asDate($this->model->tenure_to_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Committee Members', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblCommitteeMembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblCommitteeMembersHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->election_date = Yii::$app->formatter->asDate($this->model->election_date, DATE_FORMAT);
            $this->model->tenure_from_date = Yii::$app->formatter->asDate($this->model->tenure_from_date, DATE_FORMAT);
            $this->model->tenure_to_date = Yii::$app->formatter->asDate($this->model->tenure_to_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Committee Members', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblCommitteeMembers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblCommitteeMembers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCommitteeMembers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetMember() {

        $model = new TblMember();
        $model->member_code = Yii::$app->request->post('member_code');
        $data = $model->getmember();
        $response['status'] = $data ? 'success' : 'error';
        $response['data'] = !empty($data->member_name) ? $data->member_name : '';
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

}
