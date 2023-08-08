<?php

namespace app\modules\document\controllers;

use Yii;
use app\modules\document\models\TblAttachment;
use app\modules\document\models\TblAttachmentSearch;
use app\modules\document\models\TblDocumentMapping;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\document\models\TblAttachmentHistory;
use yii\web\Response;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * TblAttachmentController implements the CRUD actions for TblAttachment model.
 */
class TblAttachmentController extends \app\controllers\ChildController {

    public $freeAccessActions = ['document-upload', 'attachment-delete'];

    /**
     * Lists all TblAttachment models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAttachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDocumentUpload($master_type, $id, $model, $module_code, $module_name) {
        $doc_mapping = TblDocumentMapping::find()->where(['master_type' => $master_type])->all();
        $attachment = new TblAttachment();
        $doc_model = [];
        foreach ($doc_mapping as $doc) {
            $attachments = $doc->uploadDocument;
            $master_doc = $doc->docId;
            if (empty($attachments)) {
                $attachments = new TblAttachment();
                $attachments->module_code = $module_code;
                $attachments->doc_id = $doc->doc_id;
            }
            $attachments->mapping_id = $doc->mapping_id;
            $attachments->attachment_type = $master_doc->doc_ext;
            $attachments->doc_name = $master_doc->doc_name .= ($doc->is_mandate == 1) ? ' *' : '';
            $doc_model[] = $attachments;
        }
        if (Yii::$app->request->post()) {
            $doc_path = Yii::$app->params['document_upload'] . $master_type;
            if (!is_dir($doc_path)) {
                Yii::$app->general->CreateDirectory($doc_path);
            }

            if (Yii::$app->general->checkDirectory($doc_path)) {
                $error_msg = '';
                $save_model = [];
                foreach ($doc_model as $key => $d) {
                    if (!empty($d->attachment_code)) {
                        $historyModel = new TblAttachmentHistory();
                        Yii::$app->operation->history($d, $historyModel, UPDATE);
                        $save_model[] = $historyModel;
                    }
                }
                Model::loadMultiple($doc_model, Yii::$app->request->post());
                foreach ($doc_model as $key => $d) {
                    $d->file_name = UploadedFile::getInstance($d, '[' . $key . ']file_name');
                    if (!empty($d->file_name)) {
                        $file_name = $id . '_' . $d->doc_id . '_' . $d->file_name->baseName . '.' . $d->file_name->extension;
                        $d->attachment = $doc_path . '/' . $file_name;
                        if (!$d->file_name->saveAs($d->attachment)) {
                            $error_msg .= $d->doc_name . '<br/>';
                        }
                        $d->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $d->attachment;
                        $d->module_name = $module_name;
                        $d->file_name = $file_name;
                        $save_model[] = $d;
                    }
                }

                if (empty($error_msg)) {
                    $transaction = $this->generalModel->saveTransaction($save_model, ['Document Upload', 'create']);
                    if ($transaction == 'customRedirect') {
                        $record = ['status' => 'success', 'msg' => $this->redirect(['index'])];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'msg' => $msg];
                    }
                } else {
                    $record = ['status' => 'error', 'msg' => 'Please Upload Following Document <br/><br/>' . $error_msg];
                }
            } else {
                $record = ['status' => 'error', 'msg' => 'Error while create directory.'];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $module_code]),
        ]);
        return Yii::$app->controller->render('/../../document/views/tbl-attachment/document_upload', [
                    'model' => $model,
                    'doc_model' => $doc_model,
                    'attachment' => $attachment,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAttachmentDelete() {
        $attachment = Yii::$app->request->post('id');
        $savedelModel = [];
        if (!empty($attachment)) {
            $attachmentModel = TblAttachment::find()->where(['attachment_code' => $attachment])->one();
            if (!empty($attachmentModel)) {
                $attachmentHistoryModel = new TblAttachmentHistory();
                Yii::$app->operation->history($attachmentModel, $attachmentHistoryModel, DELETE);
                $savedelModel[] = $attachmentModel;
                $savedelModel[] = $attachmentHistoryModel;
            }
            $record = $this->generalModel->deleteTransaction($savedelModel);
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
