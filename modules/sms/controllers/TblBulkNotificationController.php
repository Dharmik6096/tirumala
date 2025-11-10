<?php

namespace app\modules\sms\controllers;

use Yii;
use app\modules\sms\models\TblBulkNotification;
use app\modules\sms\models\TblBulkNotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblBulkNotificationHistory;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\sms\models\TblBulkNotificationApplicability;
use app\modules\sms\models\TblBulkNotificationApplicabilityHistory;
use yii\widgets\ActiveForm;
use app\modules\payment\models\TblPaymentCycle;

/**
 * TblBulkNotificationController implements the CRUD actions for TblBulkNotification model.
 */
class TblBulkNotificationController extends \app\controllers\ChildController {

    /**
     * Lists all TblBulkNotification models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBulkNotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBulkNotification model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBulkNotification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBulkNotification();
        $this->viewFile = 'create';
        $this->model->app_type = 1;
        $saveModel = [];
        $allowedEiplCodes = [
            'BANAS',
            'EMILKPRO',
            'GYAN',
//            'VRS_GLT',
//            'VRS_MLP',
//            'VRS_SBD',
//            'VRS_NEWASA',
            'AMULAMCS'
        ];

        if (Yii::$app->request->post()) {
            if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
                $postData = Yii::$app->request->post();
                $files = !empty($postData['TblFtpTxnLog']['file_name']) ? $postData['TblFtpTxnLog']['file_name'] : '';
                $dcsCodes = $this->model->getBMCDCS();
                $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
                $this->model->entry_datetime = date('Y-m-d H:i:s');
                $this->model->content_id = Yii::$app->general->getforeignkey($this->model->apiMaster, 'api_master_id');
                $this->model->union_code = !empty(Yii::$app->session->get('Unions') && count(explode(',', Yii::$app->session->get('Unions'))) == 1) ? Yii::$app->session->get('Unions') : NULL;
                $filesInDir = [];
                $i = 1;
                $auto_key_config = [];
                $notification_type = $this->model->notification_type;

                if ($notification_type == 1) {
                    Yii::$app->default->getDefaults($this->model);
                }
                if ($notification_type == 2) {
                    $this->model->filename = $files;
                    $this->model->file_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/images/' . $this->model->filename;
                }
                if ($notification_type == 3) {
                    $this->model->filename = $files;
                    $filesArray = explode('.', $files);
                    $filename = $filesArray[0];
                    $old_directory = \Yii::getAlias('@webroot') . '/web/upload/images/';
                    $new_directory = \Yii::getAlias('@webroot') . '/web/upload/';
                    if (!empty($this->model->filename) && Yii::$app->general->checkDirectory($new_directory)) {
                        rename($old_directory . $this->model->filename, $new_directory . $this->model->filename);
                    }
                    $file_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/';
                    $this->model->from_date = !empty($this->model->from_date) ? date('Y-m-d', strtotime($this->model->from_date)) . ' ' . (!empty($this->model->from_shift_code) ? Yii::$app->general->getshift($this->model->from_shift_code) : '00:00:00') : '';
                    $this->model->to_date = !empty($this->model->to_date) ? date('Y-m-d', strtotime($this->model->to_date)) . ' ' . (!empty($this->model->to_shift_code) ? Yii::$app->general->getshift($this->model->to_shift_code) : '23:59:59') : '';
                    $this->model->app_type = NULL;
                    $this->model->login_type = NULL;
                    $this->model->department = NULL;
                    $this->model->filename = !empty($filename) ? $filename . '.pdf' : '';
                    $this->model->file_path = !empty($this->model->filename) ? $file_path . $this->model->filename : '';
                }
                if ($notification_type == 4 || $notification_type == 8) {
                    $eipl_code = Yii::$app->session->get('eiplCode');
                    if (in_array($eipl_code, $allowedEiplCodes)) {
                        $this->model->filename = $files;
                        $filesArray = explode('.', $files);
                        $filename = $filesArray[0];
                        $old_directory = \Yii::getAlias('@webroot') . '/web/upload/images/';
                        $new_directory = \Yii::getAlias('@webroot') . '/web/upload/' . $this->model->bmc_code . $filename . '/';
                        if (Yii::$app->general->checkDirectory($new_directory)) {
                            rename($old_directory . $this->model->filename, $new_directory . $this->model->filename);
                        }
                        $file_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/' . $this->model->bmc_code . $filename . '/';
                        $command = 'java -jar pdf-splitter-1.0.jar' . ' ' . $eipl_code . ' ' . $new_directory . $this->model->filename;
                        $utility_path = \Yii::getAlias('@webroot') . '/web/utility/pdf-splitter/';
                        $crnt_dir = getcwd();
                        chdir($utility_path);
                        exec($command);
                        chdir($crnt_dir);
                    } else {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return Json::encode(['status' => 'success', 'msg' => 'Pdf split utility not available for this client (' . $eipl_code . ').']);
                    }
                }
                if ($notification_type == 5) {
                    $this->model->filename = $files;
                    $filesArray = explode('.', $files);
                    $filename = $filesArray[0];
                    $old_directory = \Yii::getAlias('@webroot') . '/web/upload/images/';
                    $new_directory = \Yii::getAlias('@webroot') . '/web/upload/' . $this->model->bmc_code . $filename . '/';
                    if (!empty($this->model->filename) && Yii::$app->general->checkDirectory($new_directory)) {
                        rename($old_directory . $this->model->filename, $new_directory . $this->model->filename);
                    }
                    $file_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/' . $this->model->bmc_code . $filename . '/';
                    $command = 'java -jar pdf-splitter-bactaria-1.0.jar "' . $new_directory . $this->model->filename . '"';
                    $utility_path = \Yii::getAlias('@webroot') . '/web/utility/pdf-splitter/';
                    $crnt_dir = getcwd();
                    chdir($utility_path);
                    exec($command);
                    chdir($crnt_dir);

                    foreach (scandir($new_directory) as $filePdf) {
                        if (strlen($filePdf) > 2 && strpos($filePdf, '.pdf') > 0) {
                            array_push($filesInDir, (int) explode('.', $filePdf)[0]);
                        }
                    }
                }
                if ($notification_type == 6) {
                    $this->model->filename = $files;
                    $filesArray = explode('.', $files);
                    $filename = $filesArray[0];
                    $old_directory = \Yii::getAlias('@webroot') . '/web/upload/images/';
                    $new_directory = \Yii::getAlias('@webroot') . '/web/upload/' . $this->model->bmc_code . $filename . '/';
                    if (!empty($this->model->filename) && Yii::$app->general->checkDirectory($new_directory)) {
                        rename($old_directory . $this->model->filename, $new_directory . $this->model->filename);
                    }
                    $file_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/' . $this->model->bmc_code . $filename . '/';
                    $command = 'java -jar pdf-splitter-amul-invoice-1.0.jar "' . $new_directory . $this->model->filename . '"';
                    $utility_path = \Yii::getAlias('@webroot') . '/web/utility/pdf-splitter/';
                    $crnt_dir = getcwd();
                    chdir($utility_path);
                    exec($command);
                    chdir($crnt_dir);
                }
                if ($notification_type == 7) {
                    $this->model->filename = $files;
                    $filesArray = explode('.', $files);
                    $filename = $filesArray[0];
                    $old_directory = \Yii::getAlias('@webroot') . '/web/upload/images/';
                    $new_directory = \Yii::getAlias('@webroot') . '/web/upload/' . $this->model->bmc_code . $filename . '/';
                    if (!empty($this->model->filename) && Yii::$app->general->checkDirectory($new_directory)) {
                        rename($old_directory . $this->model->filename, $new_directory . $this->model->filename);
                    }
                    $file_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/' . $this->model->bmc_code . $filename . '/';
                    $command = 'java -jar pdf-splitter-multi-1.0.jar "' . $new_directory . $this->model->filename . '"';
                    $utility_path = \Yii::getAlias('@webroot') . '/web/utility/pdf-splitter/';
                    $crnt_dir = getcwd();
                    chdir($utility_path);
                    exec($command);
                    chdir($crnt_dir);

                    foreach (scandir($new_directory) as $filePdf) {
                        if (strlen($filePdf) > 2 && strpos($filePdf, '.pdf') > 0) {
                            array_push($filesInDir, (int) explode('.', $filePdf)[0]);
                        }
                    }
                }
                if ($notification_type != 1 || $notification_type != 3) {
                    $from_date = "";
                    $to_date = "";
                    if (isset($this->model->payment_cycle_code) && $this->model->payment_cycle_code != '') {
                        $paymentCycle = TblPaymentCycle::find()->where(['payment_cycle_code' => $this->model->payment_cycle_code])->one();

                        $from_date = $paymentCycle->from_date;
                        $to_date = $paymentCycle->to_date;
                    }
                }
                if ($notification_type == 1 || $notification_type == 2 || $notification_type == 3) {
                    $this->model->receiver_type = 'APP_NOTIFICATION';
                    $this->model->dcs_code = !empty($this->model->dcs_code) ? $this->model->dcs_code[0] : '';
                    if (!empty($this->model->payment_cycle_code) && $notification_type == 2) {
                        $this->model->from_date = $from_date;
                        $this->model->to_date = $to_date;
                    }
                    $saveModel[] = $this->model;
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Bulk Notification', 'create']);
                } else {
                    foreach ($dcsCodes as $k => $dcs_data) {
                        $model = new TblBulkNotification();
                        $model->union_code = $dcs_data['union_code'];
                        $model->plant_code = $dcs_data['plant_code'];
                        $model->mcc_plant_code = $dcs_data['mcc_plant_code'];
                        $model->bmc_code = $dcs_data['bmc_code'];
                        $model->dcs_code = $dcs_data['dcs_code'];
                        $model->notification_type = $this->model->notification_type;
                        $model->login_type = $this->model->login_type;
                        $model->department = $this->model->department;
                        if ($model->notification_type == 4) {
                            $model->filename = !empty($this->model->filename) ? ((int) $dcs_data['ref_code']) . '.pdf' : '';
                            $model->file_path = !empty($model->filename) ? $file_path . $model->filename : '';
                        }
                        if ($model->notification_type == 5 || $model->notification_type == 7) {
                            $model->filename = !empty($this->model->filename) ? ((int) $dcs_data['dcs_code_ex']) . '.pdf' : '';
                            $model->file_path = !empty($model->filename) ? $file_path . $model->filename : '';
                        }
                        if ($model->notification_type == 6) {
                            $model->filename = !empty($this->model->filename) ? ((int) $dcs_data['dcs_code_ex']) . '.pdf' : '';
                            $model->file_path = !empty($model->filename) ? $file_path . $model->filename : '';
                        }
                        if ($model->notification_type == 8) {
                            $model->filename = !empty($this->model->filename) ? ((int) $dcs_data['dcs_code_ex']) . '.pdf' : '';
                            $model->file_path = !empty($model->filename) ? $file_path . $model->filename : '';
                        }
                        $model->payment_cycle_code = !empty($this->model->payment_cycle_code) ? $this->model->payment_cycle_code : '';
                        if (!empty($model->payment_cycle_code)) {
                            $model->from_date = $from_date;
                            $model->to_date = $to_date;
                        }
                        $model->campaign_name = $this->model->campaign_name;
                        $model->title = $this->model->title;
                        $model->message = $this->model->message;
                        $model->receiver_type = 'APP_NOTIFICATION';
                        $model->content_id = Yii::$app->general->getforeignkey($this->model->apiMaster, 'api_master_id');
                        $model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
                        $model->entry_datetime = date('Y-m-d H:i:s');

                        if ($model->notification_type == 5 || $model->notification_type == 7) {
                            if (in_array(((int) $dcs_data['dcs_code_ex']), $filesInDir)) {
                                $this->createApplicabilityData($saveModel, $auto_key_config, $model, $i);
                            } else if (empty($model->filename)) {
                                $this->createApplicabilityData($saveModel, $auto_key_config, $model, $i);
                            }
                        } else {
                            $this->createApplicabilityData($saveModel, $auto_key_config, $model, $i);
                        }
                    }
                    $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($saveModel, ['Bulk Notification', 'create'], $auto_key_config);
                }
                if ($transaction == 'customRedirect') {
                    if (!empty($this->model->login_type) && strtoupper($this->model->login_type) == 'ALL') {
                        $appModel = new TblBulkNotificationApplicability();
                        $appModel->attributes = $this->model->attributes;
                        $appModel->applicable_for = 'ALL';
                        $appModel->applicable_code = '0';
                        $appModel->save();
                    }
                    return $this->{$transaction}();
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    public function createApplicabilityData(&$saveModel, &$auto_key_config, $model, &$i, $scenario = 'milkBillApplicability') {
        $saveModel[$i] = $model;
        $i++;
        $appModel = new TblBulkNotificationApplicability();
        $appModel->attributes = $model->attributes;
        $appModel->applicable_for = 'DCS';
        $appModel->applicable_code = $model->dcs_code;
        $appModel->scenario = $scenario;
        $saveModel[$i] = $appModel;
        $auto_key_config[$i] = ['self_key' => 'bulk_notification_id', 'parent_key' => 'bulk_notification_id', 'parent_index' => $i - 1];
        $i++;
    }

    /**
     * Updates an existing TblBulkNotification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblBulkNotificationHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $this->model->load(Yii::$app->request->post());
            $this->model->dcs_code = !empty($this->model->dcs_code) ? $this->model->dcs_code[0] : '';
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $this->model->entry_datetime = !empty($this->model->entry_datetime) ? date('Y-m-d', strtotime($this->model->entry_datetime)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Bulk Notification', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblBulkNotification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $deleteModel = [];
        $saveModel = [];
        $historyModel = new TblBulkNotificationHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Bulk Notification', 'delete']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBulkNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBulkNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBulkNotification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBulkNotificationApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblBulkNotificationApplicability();
        $value = [];
        if ($model->notification_type == 3) {
            $value['DCS'] = 'DCS';
            $value['BMC'] = 'BMC';
            $appModel->options = ['tanker_rate'];
            $appModel->model->status = 2;
            $appModel->with_wef_date = FALSE;
            $appModel->with_applicable_code = true;
        } else {
            if (in_array(strtolower($model->login_type), ['farmer', 'vsp', 'MEMBER', 'DCS'])) {
                $value['DCS'] = 'VLCC';
            } else {
                $value['USER'] = 'USER';
            }
            $appModel->options = ['dcs_mcc_user'];
            $appModel->is_bulk_notification = true;
        }
        $appModel->model->wef_date = $model->wef_date;
        $appModel->model->union_code = $model->union_code;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'bulk_notification_id';
        $appModel->field_value = $id;
        $appModel->trans_label = 'Bulk Notification Applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->header_title = ' (' . $model->login_type . ':' . $model->message . ')';
        $appModel->dcs_filters = $value;
        $appModel->login_type = $model->login_type;
        $appModel->department = $model->department;
        $appModel->fields = [
            'applicable_for' => ['view' => ['grid'], 'value' => 'applicable_for'],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'applicable_name' => ['view' => ['grid'], 'label' => Yii::t('app', 'Applicable Name'), 'value' => function($model) {
                    if (strtolower($model->applicable_for) == 'dcs') {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    } else if (strtolower($model->applicable_for) == 'mcc') {
                        return Yii::$app->general->getforeignkey($model->mccCode, 'name');
                    } else if (strtolower($model->applicable_for) == 'bmc') {
                        return Yii::$app->general->getforeignkey($model->bmcCodes, 'bmc_name');
                    } else {
                        return Yii::$app->general->getforeignkey($model->userCode, 'name');
                    }
                }],
            'status' => ['view' => ['grid'], 'value' => 'status', 'value' => function($model) {
                    return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->status] : '';
                }, 'filter' => FALSE],
        ];
        if ($model->notification_type == 3) {
            $appModel->fields['wef_date'] = ['view' => ['grid'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }, 'filter' => FALSE];
        } else {
            $appModel->fields['wef_date'] = ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }, 'filter' => FALSE];
        }
        $appModel->actions = [
            'delete' => ['option' => 'applicable_code,bulk_notification_app_code,tbl-bulk-notification/delete-mapping'],
        ];


        return $appModel->createApp();
    }

    public function actionDeleteMapping() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblBulkNotificationApplicabilityHistory();
            $record = TblBulkNotificationApplicability::find()->where(['bulk_notification_app_code' => Yii::$app->request->post('id')])->one();
            Yii::$app->operation->history($record, $detailHistory, DELETE);
            $master[] = $detailHistory->save(FALSE);
            $master[] = $record->delete();
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            } else {
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
            }
        } catch (UserException $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/upload/images/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = date('YmdHis') . rand(1000, 9999) . str_replace(' ', '_', $file->name);
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'filename' => $name, 'msg' => $name];
            } else {
                $record = ['status' => 'error', 'filename' => $name, 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

}
