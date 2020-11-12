<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblBranch;
use app\modules\organisation\models\TblBranchSearch;
use app\modules\organisation\models\TblBranchHistory;
use yii\web\NotFoundHttpException;
use app\controllers\ChildController;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblBranchController implements the CRUD actions for TblBranch model.
 */
class TblBranchController extends ChildController {

    /**
     * Lists all TblBranch models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TblBranch();
        $searchModel = new TblBranchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBranch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBranch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBranch();
        $this->viewFile = 'create';
        $old_ifsc = '';
        $old_bank = '';
        $validate = 1;
        $this->model->valid_from = date('Y-m-d');
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            $this->model->branch_name = ucwords($this->model->branch_name);
            $old_ifsc = $this->model->ifsc;
            $this->model->ifsc = strtoupper($this->model->ifsc);
            $this->model->branch_code = $this->model->getCode();
            $old_bank = $this->model->bank_code;
            //$this->model->union_code=Yii::$app->session->get('Unions');

            if ($_POST['warning'] == 0) {
                $detail = $this->model->getExistingIfsc();
                $bankName = '';
                $branchName = '';
                if (!empty($detail)) {
                    $branchName = $detail->branch_name;
                    $this->model->bank_code = $detail->bank_code;
                    $bankName = Yii::$app->general->getforeignkey($this->model->bankCode, 'bank_name');
                }
                $msg = 'IFSC has alreday been taken in ' . '<b>' . $bankName . '</b> and <b>' . $branchName . '</b> Are you sure you want to continue?';
                $validate = Yii::$app->warning->unique($this->model, 'ifsc', $this->model->ifsc, '', $msg);
            }
            $this->model->bank_code = $old_bank;
            if ($validate == 1 && empty($this->model->getErrors())) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['branch', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        $this->model->ifsc = $old_ifsc;
        return $this->customRender();
    }

    /**
     * Updates an existing TblBranch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $old_ifsc = $this->model->ifsc;
        $this->model->state_code = $this->model->subDistrictCode->districtCode->stateCode->state_code;
        $this->model->district_code = $this->model->subDistrictCode->districtCode->district_code;
        $validate = 1;
        if (Yii::$app->request->post()) {
            $historyModel = new TblBranchHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $this->model->branch_name = ucwords($this->model->branch_name);
            $old_ifsc = $this->model->ifsc;
            $this->model->ifsc = strtoupper($this->model->ifsc);
            $old_bank = $this->model->bank_code;
            if ($_POST['warning'] == 0) {
                $detail = $this->model->getExistingIfsc();
                $bankName = '';
                $branchName = '';
                if (!empty($detail)) {
                    $branchName = $detail->branch_name;
                    $this->model->bank_code = $detail->bank_code;
                    $bankName = Yii::$app->general->getforeignkey($this->model->bankCode, 'bank_name');
                }
                $msg = 'IFSC has alreday been taken in ' . '<b>' . $bankName . '</b> and <b>' . $branchName . '</b> Are you sure you want to continue?';
                $validate = Yii::$app->warning->unique($this->model, 'ifsc', $this->model->ifsc, '', $msg);
            }
            $this->model->bank_code = $old_bank;
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['branch', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        $this->model->ifsc = $old_ifsc;
        return $this->customRender();
    }

    /**
     * Deletes an existing TblBranch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_branch', '', '', Yii::$app->request->post('id'), 'branch_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblBranchHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBranch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBranch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBranch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Description: return branch array
     * By: Dhara
     * DAte: 8-11-2016
     * @return type
     */
    public function actionGetIfscCode() {

        $ifsc = '';
        if (!empty($_POST['id'])) {
            $model = new TblBranch();
            $ifsc = $model->getIfcs($_POST['id']);
        }
        return Json::encode(['code' => $ifsc]);
    }

    private function setModel() {
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->branch_code]);
    }

}
