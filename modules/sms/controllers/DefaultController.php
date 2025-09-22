<?php

namespace app\modules\sms\controllers;

use Yii;
use yii\web\Controller;
use app\modules\sms\models\TblAlertNotification;
use app\controllers\ChildController;
use app\modules\sms\models\TblBulkNotification;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\models\GeneralModel;
use app\modules\sms\models\TblBulkNotificationApplicability;
use app\modules\sms\models\TblAlertNotificationPortal;
use app\modules\syncutility\models\TblSentbox;

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
                        $status = 2;
                        if ($row->receiver_type == 'SMS') {
                            $send = Yii::$app->alertnotification->sendSms($row->content_id, $row->receiver_detail, $row->message, $row->template_id, $status);
                        } else if ($row->receiver_type == 'APP_NOTIFICATION') {
                            $server_key = Yii::$app->general->getforeignkey($row->apiMasterCode, 'token');
                            $url = Yii::$app->general->getforeignkey($row->apiMasterCode, 'url');
                            $send = Yii::$app->alertnotification->sendNotification($url, $server_key, $row->receiver_detail, $row->header_info, $row->message, $row->parent_code);
                        } else if ($row->receiver_type == 'EMAIL' && $row->send_mail == 1) {
                            $filename = '';
                            $filepath = '';
                            $from = Yii::$app->general->getforeignkey($row->apiMasterCode, 'url');
                            $pwd = Yii::$app->general->getforeignkey($row->apiMasterCode, 'api_password');
                            $to = $row->receiver_detail;
                            $otherReceiver = $row->other_receiver_detail;
                            $token = Yii::$app->general->getforeignkey($row->apiMasterCode, 'token');
                            $cc = !empty($otherReceiver) ? $otherReceiver : $token;
                            $bcc = '';
                            if ($row->has_attachment == 1) {
                                if (empty($row->file_param)) {
                                    $controls = [];
                                    $controls['dcs_milk_dispatch_code'] = $row->parent_code;
                                    $controls['p_report_name'] = $row->filename;
                                    $filename = $row->filename . '-' . $row->parent_code . '.pdf';
                                } else {
                                    $controls = json_decode($row->file_param, TRUE);
                                    $filename = $row->filename;
                                }
                                $path = $row->file_path;
                                $attachment = ChildController::printDocument($controls, $path, $filename, 'pdf', 'mail', FALSE);
                            } elseif ($row->has_attachment == 2) {
                                $filename = $row->filename;
                                $filepath = $row->file_path;
//                                $cc = '';
                                $attachment = FALSE;
                            } else {
                                $attachment = FALSE;
                            }
                            $send = Yii::$app->alertnotification->sendEmail($from, $to, $cc, $row->header_info, $row->message, $attachment, $filename, $filepath, $bcc, $pwd);
                        }
                        $row->response_datetime = date('Y-m-d H:i:s');
                        $row->response_status = $send;
                        $row->send_status = $status;
                        $row->save(FALSE);
                    } catch (\yii\db\Exception $e) {
                        $row->send_status = 3;
                        $row->save(FALSE);
                    } catch (\Throwable $ex) {
                        $row->response_datetime = date('Y-m-d H:i:s');
                        $row->response_status = substr($ex->getMessage(), 0, 254);
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

    public function actionGetLatestNotification() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;

        $notifications = TblAlertNotificationPortal::find()
                ->where([
            'receiver_type' => 'PORTAL_NOTIFICATION',
            'receiver_detail' => $userId,
        ]);
        if (!empty($_GET['shown'])) {
            $notifications = $notifications->andWhere(['NOT IN', 'alert_notification_id', $_GET['shown']]);
        }
        $notifications = $notifications->andWhere(['between', 'cast(entry_datetime as date)', date('Y-m-d', strtotime('-10 days')), date('Y-m-d')])
                ->andWhere(['in', 'send_status', [0, 1]])
                ->orderBy(['entry_datetime' => SORT_DESC])
                ->limit(20)
                ->all();

        $response = array_map(function ($n) {
            return [
                'id' => $n->alert_notification_id,
                'message' => $n->message,
                'datetime' => $n->entry_datetime,
                'send_status' => $n->send_status
            ];
        }, $notifications);

        if (Yii::$app->request->isPost) {
            foreach ($notifications as $noti) {
                $noti->send_status = 1;
                $noti->save(false);
            }
        }
        return $response;
    }

    public function actionDeleteNotification($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $notification = TblAlertNotificationPortal::findOne($id);
        if ($notification && $notification->receiver_detail == Yii::$app->user->id) {
            $notification->send_status = 2;
            $notification->response_datetime = date('Y-m-d H:i:s');
            $notification->save(false);
            return ['success' => true];
        }
        return ['success' => false];
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
                    $row->status = 2;
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
                    $result = \Yii::$app->general->getSpData('sp_generate_bulk_notification', $param, false);
                    $sp_result = !empty($result[0]['result']) ? $result[0]['result'] : 0;
                    if ($sp_result == 1) {
                        $row->status = 2;
                        $row->resp_desc = 'generated';
                    } else {
                        $row->status = 3;
                        $row->resp_desc = 'error';
                    }
                    if ($row->status == 2) {
                        if (in_array($notification->notification_type, [2, 3, 5, 7, 8])) {
                            $sentboxArray = [];
                            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $row->dcs_code);

                            foreach ($sentboxArray as $sent) {
                                $flag = 'INSERT';
                                sleep(10);
                                $sentbox = $this->sentboxModel($sent['code'], $sent['type'], $row);
                                if (TRUE) {
                                    if (!($sentbox->setSentbox($row, $flag))) {
                                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                                    }
                                }
                            }
                        }
                    }
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

    private function sentboxModel($code, $type, $row) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $row->union_code;
        $sentbox->dest_org_type = $type;
        $sentbox->table_name = 'tbl_bulk_notification';
        $dateTime = date('Y-m-d H:i:s');
        $microtime = date('Y-m-d H:i:s', strtotime($dateTime . ' +5 minute')) . '.' . gettimeofday()["usec"];
        $sentbox->posting_timestamp = $microtime;
        return $sentbox;
    }

}
