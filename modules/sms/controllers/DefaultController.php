<?php

namespace app\modules\sms\controllers;

use Yii;
use yii\web\Controller;
use app\modules\sms\models\TblAlertNotification;
use app\controllers\ChildController;
use app\modules\sms\models\TblBulkNotification;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\models\GeneralModel;

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
            $update = $model->updateSmsStatus($noti_ids);
            foreach ($modelData as $row) {
                if (!empty($row->content_id) && !empty($row->receiver_detail)) {
                    try {
                        $send = '';
                        if ($row->receiver_type == 'SMS') {
                            $send = Yii::$app->alertnotification->sendSms($row->content_id, $row->receiver_detail, $row->message, $row->template_id);
                        } else if ($row->receiver_type == 'APP_NOTIFICATION') {
                            $server_key = Yii::$app->general->getforeignkey($row->apiMasterCode, 'token');
                            $url = Yii::$app->general->getforeignkey($row->apiMasterCode, 'url');
                            $send = Yii::$app->alertnotification->sendNotification($url, $server_key, $row->receiver_detail, $row->header_info, $row->message, $row->parent_code);
                        } else if ($row->receiver_type == 'EMAIL' && $row->send_mail == 1) {
                            $filename = '';
                            $filepath = '';
                            $from = Yii::$app->general->getforeignkey($row->apiMasterCode, 'url');
                            $to = $row->receiver_detail;
                            $otherReceiver = $row->other_receiver_detail;
                            $token = Yii::$app->general->getforeignkey($row->apiMasterCode, 'token');
                            $cc = !empty($otherReceiver) ? $otherReceiver : $token;
                            $bcc = '';
                            if ($row->has_attachment == 1) {
                                $controls = [];
                                $controls['dcs_milk_dispatch_code'] = $row->parent_code;
                                $controls['p_report_name'] = $row->filename;
                                $path = $row->file_path;
                                $filename = $row->filename . '-' . $row->parent_code . '.pdf';
                                $attachment = ChildController::printDocument($controls, $path, $filename, 'pdf', 'mail');
                            } elseif ($row->has_attachment == 2) {
                                $filename = $row->filename;
                                $filepath = $row->file_path;
//                                $cc = '';
                                $attachment = FALSE;
                            } else {
                                $attachment = FALSE;
                            }
                            $send = Yii::$app->alertnotification->sendEmail($from, $to, $cc, $row->header_info, $row->message, $attachment, $filename, $filepath, $bcc);
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

    public function actionBulkNotification() {
        $model = new TblBulkNotificationApplicability();
        $model->status = 0;
        $modelData = $model->getPickRecords();

        if (!empty($modelData)) {
            foreach ($modelData as $row) {
                $row->updatePickStatus();
            }
            foreach ($modelData as $row) {
                try {
                    $message = [];
                    $header = [];
                    $notification = $row->bulkNotification;
                    $message[] = ['attributeAlias' => 'MESSAGE', 'attributeValue' => $notification->message];
                    $messageJson = json_encode($message);
                    $header['apiFor'] = 'default';
                    $header['channel'] = 'default';
                    $header['templateAlias'] = 'GENERAL_PUSH_NOTIFICATION';
                    $header['templateFor'] = 'default';
                    $headerJson = json_encode($header);
                    $param = [];
                    $param['bulk_notification_id'] = $row->bulk_notification_id;
                    $param['applicable_for'] = $row->applicable_for;
                    $param['wef_date'] = date('Y-m-d H:i:s', strtotime($row->wef_date));
                    $param['login_type'] = $notification->login_type;
                    $param['message_json'] = $messageJson;
                    $param['header_json'] = $headerJson;

                    \Yii::$app->general->getSpData('sp_generate_bulk_notification', $param, TRUE);
                    $row->status = 2;
                    $row->resp_desc = 'generated';
                    $row->updateProcessStatus();
                } catch (\Throwable $e) {
                    var_dump($e);
                    $row->status = 3;
                    $row->resp_desc = 'error';
                    $row->updateProcessStatus();
                } catch (\yii\db\Exception $e) {
                    var_dump($e);
                    $row->status = 3;
                    $row->resp_desc = 'error';
                    $row->updateProcessStatus();
                }
            }
        }
    }

}
