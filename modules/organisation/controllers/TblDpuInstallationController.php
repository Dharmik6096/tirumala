<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblDpuInstallation;
use app\modules\organisation\models\TblDpuInstallationSearch;
use app\modules\organisation\models\TblDpuInstallationHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use ReflectionClass;

/**
 * TblDpuInstallationController implements the CRUD actions for TblDpuInstallation model.
 */
class TblDpuInstallationController extends \app\controllers\ChildController {

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
     * Lists all TblDpuInstallation models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDpuInstallationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDpuInstallation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDpuInstallation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblDpuInstallation();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model, $id);
            $this->model->attachment = Yii::$app->request->post('attachment');
//            $attachment = UploadedFile::getInstance($this->model, 'attachment');
//            $fn = $this->model->uploadFile($attachment);
//            $path = Yii::$app->params['dpu_docs_path'] . $this->model->attachment;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Dpu Installation', 'create']);
            if ($transaction !== FALSE) {
                if (isset($attachment)) {
                    $attachment->saveAs($path);
                }
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDpuInstallation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
//            $attachment = UploadedFile::getInstance($this->model, 'attachment');
            $old_attachment = $this->model->oldattributes['attachment'];
//            $fn = $this->model->uploadFile($attachment);
//            $this->model->attachment = isset($fn) ? $fn : $this->model->attachment;
//            $path = Yii::$app->params['dpu_docs_path'] . $this->model->attachment;
            $this->setUpdateModel($this->model);

            $historyModel = new TblDpuInstallationHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->attachment = Yii::$app->request->post('attachment');
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Dpu Installation', 'edit']);
            if ($transaction !== FALSE) {
                if ($this->model->attachment != $old_attachment && !empty($old_attachment)) {
                    unlink(Yii::$app->params['dpu_docs_path'] . $old_attachment);
                }
//                if (isset($attachment)) {
//                    $attachment->saveAs($path);
//                }
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblDpuInstallation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    private function setModel($model, $id) {
        $model->inst_date = ($model->inst_date == '') ? null : Yii::$app->formatter->asDate($model->inst_date, DATE_FORMAT);
        $model->dcs_code = $id;
        $model->union_code = $model->dcsCode->union_code;
    }

    private function setUpdateModel($model) {
        $model->inst_date = ($model->inst_date == '') ? null : Yii::$app->formatter->asDate($model->inst_date, DATE_FORMAT);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->inst_code]);
    }

    /**
     * Finds the TblDpuInstallation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDpuInstallation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDpuInstallation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUploadFile() {

        $path = Yii::$app->params['dpu_docs_path'];
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        $this->model = new TblDpuInstallation();

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
        $path = Yii::$app->params['dpu_docs_path'];
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
