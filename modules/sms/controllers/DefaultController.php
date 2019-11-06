<?php

namespace app\modules\sms\controllers;

use Yii;
use yii\web\Controller;
use app\modules\sms\models\TblAlertNotification;

/**
 * Default controller for the `sms` module
 */
class DefaultController extends Controller {

    public function actionSendAlert() {
        try {
            $model = new TblAlertNotification();
            $modelData = $model->getData();
            $noti_ids = array_map(function($e) {
                return $e->alert_notification_id;
            }, $modelData);
            //$update = $model->updateSmsStatus($noti_ids);
            foreach ($modelData as $row) {
                if (!empty($row->content_id) && !empty($row->receiver_detail)) {
                    try {
                        $send = '';
                        if ($row->receiver_type == 'SMS') {
                            $send = Yii::$app->alertnotification->sendSms($row->content_id, $row->receiver_detail, $row->message);
                        } else if ($row->receiver_type == 'APP_NOTIFICATION') {
                            $server_key = Yii::$app->general->getforeignkey($row->apiMasterCode, 'token');
                            $url = Yii::$app->general->getforeignkey($row->apiMasterCode, 'url');
                            $send = Yii::$app->alertnotification->sendNotification($url, $server_key, $row->receiver_detail, $row->header_info, $row->message, $row->parent_code);
                        } else if ($row->receiver_type == 'EMAIL' && $row->send_mail == 1) {
                            $from = Yii::$app->general->getforeignkey($row->apiMasterCode, 'url');
                            $to = $row->receiver_detail;
                            $cc = Yii::$app->general->getforeignkey($row->apiMasterCode, 'token');
                            $send = Yii::$app->alertnotification->sendEmail($from, $to, $cc, $row->header_info, $row->message, $attachment, $filename);
                        }
                        $row->response_datetime = date('Y-m-d H:i:s');
                        $row->response_status = $send;
                        $row->send_status = 2;
                        $row->save(FALSE);
                    } catch (\yii\db\Exception $e) {
                        $row->send_status = 3;
                        $row->save(FALSE);
                    }
                } else {
                    $row->send_status = 3;
                    $row->save(FALSE);
                }
            }
        } catch (\yii\db\Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
    }

}
