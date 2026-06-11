<?php

namespace app\modules\sms\controllers;

use Yii;
use app\modules\sms\models\TblAlertNotificationPortalSearch;
use app\modules\sms\models\TblAlertNotificationPortal;
use app\modules\sms\models\TblAlertNotification;
use app\modules\sms\models\TblAlertTemplate;
use app\controllers\ChildController;
use app\modules\sms\models\TblApiMaster;
use app\components\ActiveForm;
use yii\web\Response;

class TblAlertNotificationPortalController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblAlertNotificationPortalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSendMail($alert_notification_id = '') {
        $this->model = TblAlertNotificationPortal::findOne($alert_notification_id);
        $this->model->scenario = 'sendMail';

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($this->model);
        }

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post('TblAlertNotificationPortal');
            $this->model->mail_receiver_detail = trim($post['mail_receiver_detail'] ?? '');

            $apiMaster = TblApiMaster::find()->where(['receiver_type' => 'EMAIL', 'is_active' => 1])->one();
            $alertNotification = new TblAlertNotification();
            $alertNotification->receiver_detail = $this->model->mail_receiver_detail;
            $alertNotification->receiver_type = 'EMAIL';
            $alertNotification->message = "<p>Dear Sir,</p><br/>" . $this->model->message . "<br/><br/><p>Regards,</p>";
            $alertNotification->header_info = $this->model->header_info;
            $alertNotification->send_mail = 1;
            $alertNotification->has_attachment = 1;
            $alertNotification->file_param = $this->model->report_param;
            $alertNotification->file_path = $this->model->report_path;
            $alertNotification->refecence_code = $this->model->refecence_code;
            $alertNotification->module_type = $this->model->module_type;
            $alertNotification->entry_datetime = date('Y-m-d H:i:s');
            $alertNotification->send_status = 0;
            if ($apiMaster) {
                $alertNotification->content_id = $apiMaster->api_master_id;
            }
            $template = TblAlertTemplate::find()->where(['module_type' => $this->model->module_type, 'receiver_type' => 'PORTAL_NOTIFICATION'])->one();
            if ($template) {
                $alertNotification->template_id = $template->alert_template_id;
            }

            if ($alertNotification->save()) {
                try {
                    $filename = '';
                    $filepath = '';
                    $attachment = false;
                    if ($apiMaster) {
                        $from = $apiMaster->url;
                        $pwd = $apiMaster->api_password;
                        $token = $apiMaster->token;
                    } else {
                        $from = $pwd = $token = '';
                    }
                    $to = $alertNotification->receiver_detail;
                    $cc = $token;
                    $bcc = '';
                    if (!empty($this->model->report_param)) {
                        $controls = json_decode($this->model->report_param, true);
                        $path = $this->model->report_path;
                        $filename = (!empty($controls['p_report_name']) ? $controls['p_report_name'] : 'attachment') . '.pdf';
                        $attachment = ChildController::printDocument($controls, $path, $filename, 'pdf', 'mail', false);
                    }
                    $send = Yii::$app->alertnotification->sendEmail($from, $to, $cc, $alertNotification->header_info, $alertNotification->message, $attachment, $filename, $filepath, $bcc, $pwd);
                    $alertNotification->response_datetime = date('Y-m-d H:i:s');
                    $alertNotification->response_status = $send;
                    $alertNotification->send_status = 2;
                    $alertNotification->save();
                } catch (\Throwable $ex) {
                    $alertNotification->response_datetime = date('Y-m-d H:i:s');
                    $alertNotification->response_status = substr($ex->getMessage(), 0, 254);
                    $alertNotification->send_status = 3;
                    $alertNotification->save();
                }
                $this->model->send_status = 1;
                $this->model->send_mail = $this->model->send_mail + 1;
                $this->model->save();
                return $this->redirect(['index']);
            }
        }
        return $this->renderAjax('send_mail', [
                    'model' => $this->model,
        ]);
    }

}
