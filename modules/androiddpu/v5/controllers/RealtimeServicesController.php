<?php

namespace app\modules\androiddpu\v5\controllers;

use yii\web\UploadedFile;
use app\modules\document\models\TblAttachment;
use Yii;

class RealtimeServicesController extends \app\modules\androiddpu\v4\controllers\RealtimeServicesController {

    public function actionSaveAttachment() {
        $saveModel = [];
        $message = 'Unable to Saved!';
        $postData = $this->post_data['content'];
        if (!empty($_FILES)) {
            $uploads = UploadedFile::getInstancesByName("attachment");
            $auto_inc = 0;
            $attach_path = Yii::$app->params['document_upload'] . 'material_receipt';
            foreach ($uploads as $key => $file) {
                $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                if (Yii::$app->general->checkDirectory($attach_path)) {
                    $file_name = 'material_receipt' . '_' . $postData['module_code'] . '_' . time() . '.' . $extension;
                    $attachment = $attach_path . '/' . $file_name;
                    if ($file->saveAs($attachment)) {
                        $modelAttachment = new TblAttachment();
                        $modelAttachment->module_name = !empty($postData['module_name']) ? $postData['module_name'] : '';
                        $modelAttachment->module_code = !empty($postData['module_code']) ? $postData['module_code'] : '';
                        $modelAttachment->attachment_type = $extension;
                        $modelAttachment->file_name = $file_name;
                        $modelAttachment->remarks = !empty($postData['remarks']) ? $postData['remarks'] : '';
                        $modelAttachment->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $attachment;
                        $modelAttachment->originating_org_code = !empty($this->post_data['organization_code']) ? $this->post_data['organization_code'] : '';
                        $modelAttachment->originating_org_type = !empty($this->post_data['organization_type']) ? $this->post_data['organization_type'] : '';
                        $modelAttachment->created_by = $modelAttachment->originating_org_code;
                        $modelAttachment->originating_type = 23;
                        $saveModel[] = $modelAttachment;
                        $auto_inc++;
                    }
                }
            }

            $transaction = $this->generalModel->saveTransaction($saveModel, ['Attachment', 'create']);
            if ($transaction == 'customRedirect') {
                $message = 'Successfully Saved!';
            } else {
                $this->response['status'] = 501;
            }
        }
        $this->response['data'] = ['message' => $message];
        return $this->response;
    }

}
