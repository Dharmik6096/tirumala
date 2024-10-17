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
use app\modules\document\models\TblAttachment;

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
                if (empty($value->feedback_message)) {
                    $value->eipl_app_feedback_master_txn_code = (string) $value->eipl_app_feedback_master_txn_code;
                    $value->file_path = Yii::$app->general->getAttachment('feedback_txn', $value->eipl_app_feedback_master_txn_code, FALSE, '', '', TRUE);
                }
            }
        }
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'feedbackMasterTxn' => $model->feedbackMasterTxn
        ]);
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
        $name = isset(explode('#', Yii::$app->session->get('UserName'))[1]) ? explode('#', Yii::$app->session->get('UserName'))[1] : '';
        $this->model = new TblEiplAppFeedbackMasterTxn();
        $this->model->load(Yii::$app->request->post());
        $this->model->eipl_app_feedback_master_code = $post['eipl_app_feedback_master_code'];
        $this->model->feedback_message = $post['message'];
        $this->model->feedback_message_datetime = date('Y-m-d H:i:s');
        $this->model->name = $name;
        $UserType = Yii::$app->session->get('UserType');
        $this->model->replier_type = isset($UserType) ? $UserType : '';
        $UserCode = Yii::$app->session->get('UserCode');
        $this->model->replier_code = isset($UserCode) ? $UserCode : '';
        $this->model->originator_type = 'admin';
        $this->model->originator_code = isset($UserCode) ? $UserCode : '';
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
        $path = Yii::$app->basePath . Yii::$app->params['feedback_upload'];
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $user_code = Yii::$app->session->get('UserCode');
            $name = 'feedback' . '_' . $user_code . '_' . time() . '.' . $file->extension;
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

    public function actionUploadAttachmentFile() {
        try {
            $post = Yii::$app->request->post();
            $attachment_file = $post['filename'];
            $name = isset(explode('#', Yii::$app->session->get('UserName'))[1]) ? explode('#', Yii::$app->session->get('UserName'))[1] : '';
            $this->model = new TblEiplAppFeedbackMasterTxn();
            $saveModel = [];
            $this->model->load(Yii::$app->request->post());
            $this->model->eipl_app_feedback_master_code = $post['eipl_app_feedback_master_code'];
            $this->model->feedback_message = '';
            $this->model->feedback_message_datetime = date('Y-m-d H:i:s');
            $this->model->name = $name;
            $UserType = Yii::$app->session->get('UserType');
            $this->model->replier_type = isset($UserType) ? $UserType : '';
            $UserCode = Yii::$app->session->get('UserCode');
            $this->model->replier_code = isset($UserCode) ? $UserCode : '';
            $this->model->originator_type = 'admin';
            $this->model->originator_code = isset($UserCode) ? $UserCode : '';

            $saveModel[] = $this->model;
            $file = '';

            if (!empty($attachment_file)) {
                $attachment = new TblAttachment();
                $attachment->module_name = 'feedback_txn';
                $ext = (explode(".", $attachment_file));
                $file = Yii::$app->urlManager->createAbsoluteUrl('') . Yii::$app->params['feedback_upload'] . $attachment_file;
                $attachment->attachment = $file;
                $attachment->file_name = $attachment_file;
                $attachment->attachment_type = $ext[1];
                $saveModel[] = $attachment;
                $auto_key_config['TblAttachment'][] = ['self_key' => 'module_code', 'parent_key' => 'eipl_app_feedback_master_txn_code', 'parent_index' => 0];
            }

            $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['feedback', 'create'], $auto_key_config);
            $attachment->module_code = (string) $attachment->module_code;
            $file = Yii::$app->general->getAttachment('feedback_txn', $attachment->module_code, FALSE, '', '', TRUE);
            $data = ['name' => $name, 'file' => $file];
            $record = ['status' => 'success', 'msg' => Yii::t('app', 'Uploaded file successfully'), 'data' => $data];
        } catch (\Exception $ex) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
