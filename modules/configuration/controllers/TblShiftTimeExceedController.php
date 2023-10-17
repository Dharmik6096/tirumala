<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblShiftTimeExceed;
use app\modules\configuration\models\TblShiftTimeExceedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\modules\general\models\TblApprovalStagesDetail;

/**
 * TblShiftTimeExceedController implements the CRUD actions for TblShiftTimeExceed model.
 */
class TblShiftTimeExceedController extends \app\controllers\ChildController {

    /**
     * Lists all TblShiftTimeExceed models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblShiftTimeExceedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblShiftTimeExceed model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblShiftTimeExceed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblShiftTimeExceed();
        $this->viewFile = 'create';
        $save_model = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->shift_time_exceed_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->shift_code = ($this->model->shift_code == 'Morning') ? 1 : 2;
            $this->model->date_time_of_collection = date('d-m-Y');
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            if ($this->model->org_type == 'BMC') {
                $this->model->org_code = $this->model->bmc_code;
            } else if ($this->model->org_type == 'MCC') {
                $this->model->org_code = $this->model->mcc_plant_code;
            } else {
                $this->model->org_code = $this->model->dcs_code;
            }
            if ($this->model->validate()) {
                $modelStages = new TblApprovalStagesDetail();
                $modelStages->setApprovalData($this->model->union_code, 'amcs_shift_time_exceed', $this->model->shift_time_exceed_code, $save_model, $approval_stages);
                $this->model->status = empty($approval_stages) ? 'Approve' : 'Register';
                $save_model[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($save_model, ['Shift Time Exceed', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblShiftTimeExceed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->shift_time_exceed_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblShiftTimeExceed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblShiftTimeExceed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblShiftTimeExceed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblShiftTimeExceed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetStandardTime() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new TblShiftTimeExceed();
        $data = $model->getStandardTimeData(Yii::$app->request->post());
        return ['standard_time' => $data,];
    }

}
