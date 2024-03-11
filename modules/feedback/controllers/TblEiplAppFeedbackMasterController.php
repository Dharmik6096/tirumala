<?php

namespace app\modules\feedback\controllers;

use Yii;
use app\modules\feedback\models\TblEiplAppFeedbackMaster;
use app\modules\feedback\models\TblEiplAppFeedbackMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\feedback\models\TblEiplAppFeedbackMasterTxn;
use yii\helpers\Json;
use yii\web\Response;
//use app\models\MongoFsFiles;
//use app\models\MongoFsChunks;

/**
 * TblEiplAppFeedbackMasterController implements the CRUD actions for TblEiplAppFeedbackMaster model.
 */
class TblEiplAppFeedbackMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblEiplAppFeedbackMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblEiplAppFeedbackMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblEiplAppFeedbackMaster model.
     * @param integer $eipl_app_feedback_master_code
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        if (!empty($model)) {
            foreach ($model->feedbackMasterTxn as $key => $value) {
                $value->file_path = $this->getViewFile($value->file_code);
            }
        }
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'feedbackMasterTxn' => $model->feedbackMasterTxn
        ]);
    }

    public function getViewFile($id) {
        return '';
//        $data = MongoFsFiles::find()
//                ->where(['_id' => $id])
//                ->one();
//        $file = '';
//        if (!empty($data->chunk) && isset($data->chunk['data'])) {
//            $chunk_data = $data->chunk['data'];
//            $chunk_data = json_decode(json_encode($chunk_data), true);
//            $ext = pathinfo($data->filename, PATHINFO_EXTENSION);
//            $file = '<img src="data:image/jpeg;base64,' . $chunk_data['$binary'] . '">';
//            if ($ext == 'pdf') {
//                $file = '<a href="data:application/pdf;base64,' . $chunk_data['$binary'] . '" download="' . $data->filename . '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> ' . $data->filename . ' <i class="fa fa-download" aria-hidden="true"></i></a>';
//            }
//        }
//        return $file;
    }

    /**
     * Finds the TblEiplAppFeedbackMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblEiplAppFeedbackMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEiplAppFeedbackMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSendMessage() {
        $post = Yii::$app->request->post();
        $name = isset(explode('#', $_SESSION['UserName'])[1]) ? explode('#', $_SESSION['UserName'])[1] : '';
        $this->model = new TblEiplAppFeedbackMasterTxn();
        $this->model->load(Yii::$app->request->post());
        $this->model->eipl_app_feedback_master_code = $post['eipl_app_feedback_master_code'];
        $this->model->feedback_message = $post['message'];
        $this->model->feedback_message_datetime = date('Y-m-d H:i:s');
        $this->model->name = $name;
        $this->model->replier_type = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : '';
        $this->model->replier_code = isset($_SESSION['UserCode']) ? $_SESSION['UserCode'] : '';
        $this->model->originator_type = 'admin';
        $this->model->originator_code = isset($_SESSION['UserCode']) ? $_SESSION['UserCode'] : '';
        $this->model->save();
        $is_close = false;
        $masterModel = $this->findModel($post['eipl_app_feedback_master_code']);
        if ($post['is_close'] == '1' || $post['is_close'] == 1) {
            $masterModel->feedback_status = 2;
            $masterModel->save();
            $is_close = true;
        } else {
            if ($masterModel->feedback_status == 0) {
                $masterModel->feedback_status = 1;
                $masterModel->save();
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = ['name' => $name, 'is_close' => $is_close];
        $record = ['status' => 'success', 'msg' => Yii::t('app', 'Message sent successfully'), 'data' => $data];
        return Json::encode($record);
    }

    public function actionAttachmentFile() {
        $path = Yii::$app->basePath . '/web/attachment/';
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = time() . '.' . $file->extension;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'msg' => $name];
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

//    public function actionUploadAttachmentFile() {
//        try {
//            $path = Yii::$app->basePath . '/web/attachment/';
//            $post = Yii::$app->request->post();
//            $file_with_path = Yii::getAlias($path . $post['filename']);
//
//            $model = new \app\modules\feedback\models\MongoGridFs();
//            $model->file = Yii::getAlias($path . $post['filename']);
//            $model->save();
//            $last_id = json_decode(json_encode($model->_id), true);
//
//            $name = isset(explode('#', $_SESSION['UserName'])[1]) ? explode('#', $_SESSION['UserName'])[1] : '';
//            $this->model = new TblEiplAppFeedbackMasterTxn();
//            $this->model->load(Yii::$app->request->post());
//            $this->model->eipl_app_feedback_master_code = $post['eipl_app_feedback_master_code'];
//            $this->model->file_code = $last_id['$oid'];
//            $this->model->file_name = $post['filename'];
//            $this->model->feedback_message = '';
//            $this->model->feedback_message_datetime = date('Y-m-d H:i:s');
//            $this->model->name = $name;
//            $this->model->replier_type = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : '';
//            $this->model->replier_code = isset($_SESSION['UserCode']) ? $_SESSION['UserCode'] : '';
//            $this->model->originator_type = 'admin';
//            $this->model->originator_code = isset($_SESSION['UserCode']) ? $_SESSION['UserCode'] : '';
//            $this->model->save();
//            unlink($file_with_path);
//
//            $file = $this->getViewFile($last_id['$oid']);
//            $data = ['name' => $name, 'file' => $file];
//            $record = ['status' => 'success', 'msg' => Yii::t('app', 'Uploaded file successfully'), 'data' => $data];
//        } catch (\Exception $ex) {
//            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
//        }
//        Yii::$app->response->format = trim(Response::FORMAT_JSON);
//        return Json::encode($record);
//    }

}
