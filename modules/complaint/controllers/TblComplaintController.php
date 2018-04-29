<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblComplaint;
use app\modules\complaint\models\TblComplaintSearch;
use app\modules\complaint\models\TblComplaintHistory;
use app\modules\complaint\models\TblComplaintActivitySearch;
use app\modules\complaint\models\TblComplaintActivity;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use ReflectionClass;

/**
 * TblComplainController implements the CRUD actions for TblComplain model.
 */
class TblComplaintController extends \app\controllers\ChildController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblComplain models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblComplaintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblComplain model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblComplaintActivitySearch();
        $searchModel->complaint_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider, 'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new TblComplain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblComplaint();
        $complaint_activity_model = new TblComplaintActivity();
        $this->viewFile = 'create';
        $this->model->date = date('Y-m-d');

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->complaint_code = $this->model->getCode();
            $this->setModel($this->model);
            $this->model->attachment = Yii::$app->request->post('attachment');
            $this->setComplaintActivityModel($complaint_activity_model);
            $transaction = $this->generalModel->saveTransaction([$this->model, $complaint_activity_model], ['Complaint', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblComplain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {

//            $historyModel = new TblComplaintHistory();
//            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Complaint', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblComplain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        //echo Yii::$app->request->post('id');exit;
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_complaint', Yii::$app->request->post('id'), 'complain_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblComplaintHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    private function setModel() {
        $this->model->date = ($this->model->date == '') ? null : Yii::$app->formatter->asDate($this->model->date, DATE_FORMAT);
        $this->model->resolve_date = ($this->model->resolve_date == '') ? null : Yii::$app->formatter->asDate($this->model->resolve_date, DATE_FORMAT);
    }

    private function setComplaintActivityModel($complaint_activity_model) {
        $complaint_activity_model->complaint_code = $this->model->complaint_code;
        $complaint_activity_model->status = $this->model->status;
        $complaint_activity_model->contact_person = $this->model->contact_person;
        $complaint_activity_model->remarks = $this->model->remarks;
        $complaint_activity_model->issue_type = $this->model->complaint_type;
        $complaint_activity_model->date = $this->model->date;
        $complaint_activity_model->affects_data = $this->model->affects_data;
        $complaint_activity_model->attachment = $this->model->attachment;
    }

    /**
     * Finds the TblComplain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblComplain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblComplaint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUploadFile() {

        $path = Yii::$app->params['complaint_dir_path'];
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        $this->model = new TblComplaint();

        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $fn = $this->model->uploadFile($file);
            if ($file->saveAs($path . $fn)) {
                $record = ['status' => 'success', 'msg' => $fn];
            } else {
                $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function actionRemove() {
        $old_attachment = Yii::$app->request->post('value');
        $path = Yii::$app->params['complaint_dir_path'];
        if(!empty($old_attachment)){
            if (file_exists($path . $old_attachment)) {
                if($old_attachment != Yii::$app->request->post('old_value')) {
                    unlink($path . $old_attachment);
                }
                return 1;
            }
        } else {
            return 0;
        }
    }

}
