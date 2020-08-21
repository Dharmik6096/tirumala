<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffMember;
use app\modules\staffmanagement\models\TblStaffMemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblBranch;
use app\modules\staffmanagement\models\TblStaffMemberDesignation;
use app\modules\staffmanagement\models\TblStaffMemberHistory;
use app\modules\staffmanagement\models\TblStaffMemberDesignationHistory;
use yii\web\Response;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use app\modules\staffmanagement\models\TblStaffMemberFamilyDetails;
use app\modules\staffmanagement\models\TblStaffMemberFamilyDetailsSearch;
use app\modules\staffmanagement\models\TblStaffMemberDesignationSearch;

/**
 * TblStaffMemberController implements the CRUD actions for TblStaffMember model.
 */
class TblStaffMemberController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-ifsc-code'];

    /**
     * Lists all TblStaffMember models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStaffMemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffMember model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $dsearchModel = new TblStaffMemberDesignationSearch();
        $dsearchModel->staff_member_code = $id;
        $ddataProvider = $dsearchModel->search(Yii::$app->request->queryParams);

        $fsearchModel = new TblStaffMemberFamilyDetailsSearch();
        $fsearchModel->staff_member_code = $id;
        $fdataProvider = $fsearchModel->search(Yii::$app->request->queryParams);
        $isaction = FALSE;

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dsearchModel' => $dsearchModel, 'ddataProvider' => $ddataProvider,
                    'fsearchModel' => $fsearchModel, 'fdataProvider' => $fdataProvider,
        ]);
    }

    /**
     * Creates a new TblStaffMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblStaffMember();
        $desigModel = new TblStaffMemberDesignation;

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->setStaffFields($this->model);
            $this->model->staff_member_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $master[] = $this->model;
            $desigModel->staff_member_designation_code = (string) Yii::$app->general->getCodeAutoIncrement($desigModel);
            $desigModel->attributes = $this->model->attributes;
            $master[] = $desigModel;
            $transaction = $this->generalModel->saveTransaction($master, ['Staff Member', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    private function setStaffFields($model) {
        $model->birth_date = !empty($this->model->birth_date) ? date('Y-m-d', strtotime($this->model->birth_date)) : NULL;
        $model->tenure_from_date = !empty($this->model->tenure_from_date) ? date('Y-m-d', strtotime($this->model->tenure_from_date)) : NULL;
        $model->tenure_to_date = !empty($this->model->tenure_to_date) ? date('Y-m-d', strtotime($this->model->tenure_to_date)) : NULL;
        $model->bank_code = !empty($this->model->bank_code) ? $this->model->bank_code : NULL;
        $model->branch_code = !empty($this->model->branch_code) ? $this->model->branch_code : NULL;
    }

    /**
     * Updates an existing TblStaffMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblStaffMemberHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $master[] = $historyModel;
            $model->load(Yii::$app->request->post());
            $model->birth_date = !empty($model->birth_date) ? date('Y-m-d', strtotime($model->birth_date)) : NULL;
            $model->tenure_to_date = !empty($model->tenure_to_date) ? date('Y-m-d', strtotime($model->tenure_to_date)) : NULL;
            $master[] = $model;
            $transaction = $this->generalModel->saveTransaction($master, ['Staff Member', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblStaffMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblStaffMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStaffMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetIfscCode() {

        $ifsc = '';
        if (!empty($_POST['id']) && $_POST['id'] != 'null') {
            $model = new TblBranch();
            $ifsc = $model->getIfcs($_POST['id']);
        }
        return Json::encode(['code' => $ifsc]);
    }

    public function actionStaffMemberDesignation($id) {
        $this->model = new TblStaffMemberDesignation();
        $this->model->staff_member_code = $id;
        $existData = $this->model->getExistData($id);
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $update = FALSE;
            if (!empty($this->model->staff_member_designation_code)) {
                $this->model = $this->model->findOne($this->model->staff_member_designation_code);
                $historyModel = new TblStaffMemberDesignationHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $master[] = $historyModel;
                $this->model->load(Yii::$app->request->post());
                $master[] = $this->model;
                $update = TRUE;
            } else {
                $this->model->staff_member_designation_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
                $prevData = $this->model->getPreviousData($this->model->staff_member_code);
                if (!empty($prevData && empty($prevData->tenure_to_date))) {
                    $oldData = TblStaffMemberDesignation::findOne($prevData->staff_member_designation_code);
                    $historyModel = new TblStaffMemberDesignationHistory();
                    Yii::$app->operation->history($oldData, $historyModel, UPDATE);
                    $master[] = $historyModel;
                    $date = $this->model->tenure_from_date;
                    $oldData->tenure_to_date = date('Y-m-d', strtotime($date . (-1) . 'days'));
                    $master[] = $oldData;
                }
            }
            $this->model->tenure_from_date = !empty($this->model->tenure_from_date) ? date('Y-m-d', strtotime($this->model->tenure_from_date)) : NULL;
            $this->model->tenure_to_date = !empty($this->model->tenure_to_date) ? date('Y-m-d', strtotime($this->model->tenure_to_date)) : NULL;
            $this->model->staff_member_code = $this->model->staff_member_code;
            $this->model->designation_code = $this->model->designation_code;
            $master[] = $this->model;

            $model = new TblStaffMember();
            $model = $model->findOne($id);
            $historyModel = new TblStaffMemberHistory();
            Yii::$app->operation->history($model, $historyModel, UPDATE);
            $master[] = $historyModel;
//            $model->tenure_from_date = !empty($this->model->tenure_from_date) ? date('Y-m-d', strtotime($this->model->tenure_from_date)) : NULL;
//            $model->tenure_to_date = !empty($this->model->tenure_to_date) ? date('Y-m-d', strtotime($this->model->tenure_to_date)) : NULL;
            $model->designation_code = $this->model->designation_code;
            $master[] = $model;
            $transaction = $this->generalModel->saveTransaction($master, ['Staff Member Designation', ($update) ? 'edit' : 'create']);
            $result = 'error';
            if ($transaction == 'customRedirect') {
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = Url::to(['staff-member-designation', 'id' => $this->model->staff_member_code]);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($this->model);
            }
        }
        return $this->render('staff_design_create', [
                    'model' => $this->model,
                    'existData' => $existData,
        ]);
    }

    public function actionFamilyDetail($id) {
        $mainModel = new TblStaffMemberFamilyDetails();
        $searchModel = new TblStaffMemberFamilyDetailsSearch();
        $searchModel->staff_member_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $isaction = TRUE;
        return $this->render('@app/modules/staffmanagement/views/tbl-staff-member-family-details/create', [
                    'model' => $mainModel,
                    'id' => $id,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'isaction' => $isaction
        ]);
    }

}
