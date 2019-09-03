<?php

namespace app\modules\syncutility\controllers;

use yii;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\syncutility\models\TblPendriveImportExport;
use ZipArchive;
use PHPExcel;
use PHPExcel_Cell;
use app\modules\installation\models\TblIdentity;
use app\models\TblSyncHistory;
use app\modules\syncutility\models\TblSentbox;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use app\modules\syncutility\models\TblSyncLog;
use app\modules\setting\models\TblInbox;
use app\modules\syncutility\models\TblPendriveImportExportSearch;
use yii\helpers\Url;
use app\modules\hardwareconfiguration\models\TblUnionConfig;
use app\models\TblSentboxClone;
use app\components\FTPConnection;
use app\modules\installation\models\TblAndroidInstallation;

class PendriveSyncController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblPendriveImportExportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSaveZip() {
        $path = Yii::$app->basePath . '/web/import/PDS/';
        if (!is_dir($path)) {
            mkdir($path, 0777, TRUE);
        }
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = $file->name;
            $save = $file->saveAs($path . $name);
            if ($save) {
                $record = ['status' => 'success', 'msg' => $name];
            } else {
                $record = ['status' => 'error', 'msg' => Yii::t('app', 'File Not Uploaded Due to Error')];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => Yii::t('app', 'File Not Uploaded Due to Error')];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function actionImport() {
        $rename = \Yii::$app->request->get('file');
        $zipname = '';
        $path = Yii::$app->basePath . '/web/import/PDS/';
        $ExtractPath = Yii::$app->basePath . '/web/import/PDS/zip/';
        if (!is_dir($ExtractPath)) {
            $oldmask = umask(0);
            mkdir($ExtractPath, 0777, TRUE);
            umask($oldmask);
        } else {
            $files = glob($ExtractPath . '*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file))
                    unlink($file); // delete file
            }
        }
        $zipPath = $path . $rename;
        $record = ['status' => 'error', 'msg' => Yii::t('app', 'File Not uploaded.')];
        $unionconfig = new TblUnionConfig();
        $ftp_config = $unionconfig->getConfig();
        if (!empty($ftp_config)) {
            $ftp = new FTPConnection();
            $ftp->ftp_type = ($ftp_config->x_col2 == 1) ? 'SFTP' : 'FTP'; // ($ftp_config->is_sftp) ? 'SFTP' : 'FTP';
            $ftp->ftp_host = $ftp_config->sftp_host;
            $ftp->ftp_username = $ftp_config->user;
            $ftp->ftp_password = $ftp_config->password_hash;
            $ftp->ftp_port = $ftp_config->port;
            $ftp->ftp_path = $ftp_config->x_col3 . '/';
            $ftp->local_path = $path;
            $ftp->file_name = $rename;
            if ($ftp->UploadFile()) {
                $model = new TblPendriveImportExport();
                $model->file_name = $rename;
                $model_data = $model->getExistData();
                if (!empty($model_data)) {
                    $model = $model_data;
                }
                $model->scenario = 'log';
                $model->mode = 0;
                $model->no_of_records = 0;
                $model->no_of_records_ignore = 0;
                $model->dcs_code = substr($rename, 2, 7);
                $model->x_col1 = 'PEN_DRIVE';
                $model->x_col2 = 1.1;
                $model->x_col3 = $rename;
                $model->operation = 'UPDATE';
                $model->is_sentbox = TRUE;
                $model->download_counter = 0;
                $model->save();
                $record = ['status' => 'success', 'msg' => Yii::t('app', 'File Uploaded Successfully.')];
            }
        }

//        if (Yii::$app->general->ZipOperation($zipPath, FALSE, $ExtractPath)) {
//            $filearray = [];
//            if (is_dir($ExtractPath)) {
//                if ($dh = opendir($ExtractPath)) {
//                    $cnt = 0;
//                    $skip_cnt = 0;
//                    while (($file = readdir($dh)) !== false) {
//                        if (pathinfo($file, PATHINFO_EXTENSION) == 'csv') {
//                            $file = explode('-', $file);
//                            if ($zipname == '') {
//                                $zipname = $file[0];
//                            }
//                            $file = explode('.', $file[1]);
//                            $filearray[] = $file[0];
//                        }
//                    }
//                    sort($filearray);
//                    foreach ($filearray as $key => $value) {
//                        $file = $zipname . '-' . $value . '.csv';
//                        $objPHPExcel = \PHPExcel_IOFactory::load($ExtractPath . $file);
//                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
//                            $sheetTitle = strtolower($worksheet->getTitle());
//                            $i = 0;
//                            $coloumnname = [];
//                            $data = [];
//                            $HighestColumn = $worksheet->getHighestColumn();
//                            $HighestcolumnIndex = PHPExcel_Cell::columnIndexFromString($HighestColumn);
//                            $HighestColumnplus = PHPExcel_Cell::stringFromColumnIndex($HighestcolumnIndex);
//                            $addName = TRUE;
//                            $headings = $worksheet->rangeToArray('A1:' . $HighestColumn . 1, NULL, TRUE, FALSE);
//                            $uuid_index = chr(array_search('uuid', $headings[0]) + 65);
//                            for ($row = 2; $row <= $worksheet->getHighestRow(); $row ++) {
//                                $cnt++;
//                                $uuid = $worksheet->getCell($uuid_index . $row)->getValue();
////                                $sync_log = TblSyncLog::find()->where(['uuid' => $uuid, 'message_type' => 'RECORD', 'sync_status' => 'U'])->one();
////                                if (!empty($sync_log)) {
////                                    $skip_cnt++;
////                                    continue;
////                                }
////                                $sync_log = TblInbox::find()->where(['uuid' => $uuid, 'message_type' => 'RECORD', 'sync_status' => 'U'])->one();
////                                if (!empty($sync_log)) {
////                                    $skip_cnt++;
////                                    continue;
////                                }
//                                for ($col = 'A'; $col != $HighestColumnplus; $col ++) {
//                                    if ($addName) {
//                                        preg_match('/[A-Z]/', $worksheet->getCell($col . '1')->getValue(), $matches);
//                                        if (count($matches) > 0) {
//                                            $cname = $worksheet->getCell($col . '1')->getValue();
//                                            while (count($matches) > 0) {
//                                                $cname = str_replace($matches[0][0], '_' . strtolower($matches[0][0]), $cname);
//                                                preg_match('/[A-Z]/', $cname, $matches);
//                                            }
//                                            $coloumnname[] = $cname;
//                                        } else {
//                                            $coloumnname[] = $worksheet->getCell($col . '1')->getValue();
//                                        }
//                                    }
//                                    $cell = $worksheet->getCell($col . $row)->getValue();
//                                    $data[$i][] = !empty($cell) ? str_replace(':",', ':null,', $cell) : $cell;
//                                }
//                                //    $data[$i][] = 0;
//                                $addName = FALSE;
//                                $i++;
//                            }
//                            // $coloumnname[] = 'status';
//
//                            if (!empty($data) && !empty($coloumnname)) {
//                                //  $transaction = \Yii::$app->db->beginTransaction();
//                                try {
//                                    //   foreach ($data as $d) {
//                                    $db = Yii::$app->db;
//                                    $sql = $db->queryBuilder->batchInsert('tbl_inbox_pendrive', $coloumnname, $data);
//                                    $db->createCommand(str_replace("INSERT INTO ", "REPLACE INTO", $sql))->execute();
//                                    //  }
//                                    // if ($transaction->isActive) {
//                                    //       $transaction->commit();
//                                    //   }
//                                } catch (Exception $e) {
//                                    //    $transaction->rollback();
//                                    $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
//                                    Yii::$app->response->format = trim(Response::FORMAT_JSON);
//                                    return Json::encode($record);
//                                }
//                            }
//                        }
//                        unlink($ExtractPath . $file);
//                    }
//                    $model = new TblPendriveImportExport();
//                    $model->scenario = 'log';
//                    $model->mode = 0;
//                    //    $model->union_code = Yii::$app->session->get('organizations_code');
//                    $model->file_name = $rename;
//                    $model->no_of_records = $cnt;
//                    $model->no_of_records_ignore = $skip_cnt;
//                    $model->dcs_code = substr($zipname, 2, 7);
//                    $model->x_col1 = 'PEN_DRIVE';
//                    $model->x_col2 = 3;
//                    $model->x_col3 = $name;
//                    //    $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
//                    //   $model->created_by = $user;
//                    $model->operation = 'UPDATE';
//                    $model->is_sentbox = TRUE;
//                    $model->save();
//                }
//                closedir($dh);
//                rmdir($ExtractPath);
//                $record = ['status' => 'success',
//                    'msg' => ($cnt - $skip_cnt) . ' ' . Yii::t('app', 'Records Successfuly Uploaded.') . '<br/>' . $skip_cnt . ' ' . Yii::t('app', 'Records Ignored.')
//                ];
//            }
//        } else {
//            $record = ['status' => 'error', 'msg' => Yii::t('app', 'Security Key Is Not Available.')];
//        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionExport() {
        $this->model = new TblPendriveImportExport();
        if ($this->model->load(Yii::$app->request->post())) {
            $path = Yii::$app->basePath . '/web/export/PDS/';
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $model = new TblAndroidInstallation();
            // $modelSync = new TblSyncHistory();
            $sentBox = new TblSentbox();
            $dcsdata = $model->find()->select(['organization_code'])->distinct()->where(['organization_code' => $this->model->dcs_code, 'organization_type' => 'VLC'])->all();
            if ($dcsdata) {
                $language_code = NULL;
                $DcsTypes = ArrayHelper::getColumn($dcsdata, 'organization_code');
                $dcscode = implode(',', $DcsTypes);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => Yii::t('app', 'Society Not Activated')]);
                return $this->redirect(['export']);
            }
            $dcs_array = explode(',', $dcscode);

            /* $config = new TblUnionConfig();
              $interval = $config->getInterval();
              $hours = date('Y-m-d H:i:s', strtotime("-" . $interval . " hours")); //take from union config (sync_interval)
              $oldData = $modelSync->getExportData($dcs_array, $hours);
              $sentBoxData = $sentBox->getExportDataDcs($dcs_array, $language_code, $hours, $oldData); */
            $sentBoxData = $sentBox->getExportDataDcsNew($dcs_array, $language_code);
            $exportList = $sentBoxData->getModels();
            if ($exportList) {
                $cnt = 1;
                $fcnt = 1;
                $totcnt = 1;
                $name = $this->model->union_code . $this->model->dcs_code;
                $zipname = $name . '-' . date('dmYHis');
                $zipfolder = $path . $zipname;
                mkdir($zipfolder, 0777, TRUE);
                $headers = array('destOrgId', 'destOrgType', 'errorLog', 'errorTimestamp', 'jsonText', 'messageType', 'operation', 'originatingOrgId', 'originatingOrgType', 'postingTimestamp', 'sequenceNo', 'sourceDeviceMac', 'sourceOrgId', 'sourceOrgType', 'syncStatus', 'syncTimestamp', 'tableName', 'uuid', 'versionNo');
                foreach ($exportList as $data) {
                    if ($cnt == 1) {
                        $records = [];
                    }
                    if ($data['error_timestamp'] != '') {
                        $dt = new \DateTime($data['error_timestamp']);
                        $data['error_timestamp'] = $dt->format('Y-m-d\TH:i:s.u');
                    }
                    if ($data['posting_timestamp'] != '') {
                        $dt = new \DateTime($data['posting_timestamp']);
                        $data['posting_timestamp'] = $dt->format('Y-m-d\TH:i:s.u');
                    }
                    if ($data['sync_timestamp'] != '') {
                        $dt = new \DateTime($data['sync_timestamp']);
                        $data['sync_timestamp'] = $dt->format('Y-m-d\TH:i:s.u');
                    }
                    $records[][] = $data;
                    if ($cnt == 2000 || $totcnt == count($exportList)) {
                        $fd = fopen($zipfolder . '/' . $name . '-' . $fcnt . '.csv', 'w');
                        if (false === $fd) {
                            die('Failed to create temporary file');
                        }
                        fputcsv($fd, $headers);
                        foreach ($records as $record) {
                            fputcsv($fd, $record[0]);
                        }
                        fclose($fd);
                        $fcnt++;
                        $cnt = 1;
                    } else {
                        $cnt++;
                    }
                    $totcnt++;
                }
                //    $result = Yii::$app->general->ZipOperation($zipfolder, TRUE);
                $result = Yii::$app->general->ZipOperation($zipfolder, TRUE, '', '', 'csv', 'zip', TRUE);

                $files = glob($zipfolder . '/*'); // get all file names
                foreach ($files as $file) { // iterate files
                    if (is_file($file))
                        unlink($file); // delete file
                }
                rmdir($zipfolder);
                if ($result) {
                    // $this->model->scenario = 'log';
                    $this->model->mode = TRUE;
                    $this->model->x_col1 = 'PEN_DRIVE';
                    $this->model->union_code = $this->model->union_code;
                    $this->model->file_name = $zipname . '.zip';
                    $this->model->no_of_records = $totcnt - 1;
                    $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                    $this->model->created_by = $user;
                    $transaction = $this->generalModel->saveTransaction([$this->model], ['data export', 'create']);
                    if ($transaction !== FALSE) {
                        if ($transaction != 'customRender') {
                            return $this->redirect(['index']);
                        }
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => Yii::t('app', 'Security Key Is Not Available.')]);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => Yii::t('app', 'Everything Updated')]);
            }
            return $this->redirect(['export']);
        }
        return $this->render('export', ['model' => $this->model]);
    }

    private function setSyncHistory($sentBox, $dcs_array, $type, $file_name) {
        $list = [];
        $delete_list = [];
        foreach ($sentBox as $row) {
            if ($row['dest_org_id'] == 0 && $row['sync_status'] == 'U') {
                foreach ($dcs_array as $key => $value) {
                    $modelSync = TblSyncHistory::find()->where(['uuid' => $row['uuid'], 'dest_org_id' => $value])->one();
                    if (empty($modelSync)) {
                        $modelSync = new TblSyncHistory(); // insert or update
                        $modelSync->uuid = $row['uuid'];
                        $modelSync->dest_org_id = $value;
                    }
                    $modelSync->sync_status = 'S';
                    $modelSync->dest_org_type = $type;
                    $modelSync->own_org_id = Yii::$app->session->get('organizations_code');
                    $modelSync->own_type = Yii::$app->session->get('organizations_type');
                    $modelSync->sync_timestamp = date('Y-m-d H:i:s');
                    array_push($list, $modelSync);
                }
            } else if ($row['dest_org_id'] != 0) {
                $modelSync = TblSentbox::find()->where(['uuid' => $row['uuid']])->all();
                foreach ($modelSync as $model) {
                    $clone = new TblSentboxClone();
                    $clone->attributes = $model->attributes;
                    $clone->file_name = $file_name;
                    // $model->transmitted = 1;
                    //   $model->sync_timestamp = date('Y-m-d H:i:s');
                    array_push($list, $clone);
                    array_push($delete_list, $model);
                }
            }
        }
        return [$list, $delete_list];
    }

    public function actionDownload() {
        $id = \Yii::$app->request->post('id');
        $path = Yii::$app->basePath . '/web/export/PDS/';
        $model = TblPendriveImportExport::findOne($id);
        if (!empty($model) && file_exists($path . $model->file_name)) {
            $name = $model->file_name;
            $zipname = '';
            $ExtractPath = Yii::$app->basePath . '/web/export/PDS/zip/';
            if (!is_dir($ExtractPath)) {
                $oldmask = umask(0);
                mkdir($ExtractPath, 0777, TRUE);
                umask($oldmask);
            } else {
                $files = glob($ExtractPath . '*'); // get all file names
                foreach ($files as $file) { // iterate files
                    if (is_file($file))
                        unlink($file); // delete file
                }
            }
            $zipPath = $path . $name;
            if (Yii::$app->general->ZipOperation($zipPath, FALSE, $ExtractPath)) {
                $filearray = [];
                if ($dh = opendir($ExtractPath)) {
                    while (($file = readdir($dh)) !== false) {
                        if (pathinfo($file, PATHINFO_EXTENSION) == 'csv') {
                            $file = explode('-', $file);
                            if ($zipname == '') {
                                $zipname = $file[0];
                            }
                            $file = explode('.', $file[1]);
                            $filearray[] = $file[0];
                        }
                    }
                    sort($filearray);
                    $i = 0;
                    $data = [];
                    foreach ($filearray as $key => $value) {
                        $file = $zipname . '-' . $value . '.csv';
                        $objPHPExcel = \PHPExcel_IOFactory::load($ExtractPath . $file);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $sheetTitle = strtolower($worksheet->getTitle());
                            $HighestColumn = $worksheet->getHighestColumn();
                            $HighestcolumnIndex = PHPExcel_Cell::columnIndexFromString($HighestColumn);
                            $HighestColumnplus = PHPExcel_Cell::stringFromColumnIndex($HighestcolumnIndex);
                            for ($row = 2; $row <= $worksheet->getHighestRow(); $row ++) {
                                // $data[$i]['uuid'] = $worksheet->getCell($HighestColumn . $row)->getValue();
                                $data[$i]['uuid'] = $worksheet->getCell('W' . $row)->getValue();
                                $data[$i]['dest_org_id'] = $worksheet->getCell('B' . $row)->getValue();
                                $data[$i]['sync_status'] = $worksheet->getCell('S' . $row)->getValue();
                                $i++;
                            }
                        }
                        unlink($ExtractPath . $file);
                    }
                }
                closedir($dh);
                rmdir($ExtractPath);
                if (!empty($data) && $model->no_of_records == count($data)) {
                   /* $TblIdentity = new TblIdentity();
                    $dcsdata = $TblIdentity->find()->select(['dcs_code'])->distinct()->where(['main_sub_center_code' => $model->sub_center_code, 'is_active' => 1, 'is_delete' => 0])->all();
                    $DcsTypes = ArrayHelper::getColumn($dcsdata, 'dcs_code');
                    $dcscode = implode(',', $DcsTypes);
                    $dcs_array = explode(',', $dcscode);
                    $mapList = $this->setSyncHistory($data, $dcs_array, 'DCS', $model->file_name);*/
                    $model->download_counter = 1;
                    Yii::$app->operation->defaults($model, UPDATE);
                    $master[] = $model;
                    // $transaction = $this->generalModel->saveDeleteTransaction($master, $mapList[0], $mapList[1], ['file download', 'edit']);
                    $transaction = $this->generalModel->saveTransaction($master,  ['file download', 'create']);
                    if (!in_array($transaction, [FALSE, 'customRender'])) {
                        $record = ['status' => 'success', 'url' => Url::to(['send-file', 'file' => $name])];
                    } else {
                        return $this->redirect(['index']);
                    }
                } else {
                    $record = ['status' => 'error', 'msg' => Yii::t('app', 'File is corrupted.')];
                }
            } else {
                $record = ['status' => 'error', 'msg' => Yii::t('app', 'Security Key Is Not Available.')];
            }
        } else {
            $record = ['status' => 'error', 'msg' => Yii::t('app', 'File not available.')];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDownloadMultiple() {
        $id = Yii::$app->request->post('id');
        $type = Yii::$app->request->post('type');
        $path = Yii::$app->basePath . '/web/export/PDS/';
        $model = TblPendriveImportExport::findOne($id);
        $record = ['status' => 'error', 'msg' => Yii::t('app', 'File not available.')];
        if (!empty($model)) {
            if (file_exists($path . $model->file_name)) {
                if ($type == 'R') {
                    Yii::$app->operation->defaults($model, DELETE);
                } else {
                    $model->download_counter += 1;
                    Yii::$app->operation->defaults($model, UPDATE);
                }
                $transaction = $this->generalModel->saveTransaction([$model], ['file download', 'edit']);
                if (!in_array($transaction, [FALSE, 'customRender'])) {
                    if ($type == 'R') {
                        unlink($path . $model->file_name);
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'message' => Yii::t('app', 'File deleted successfully.'),
                        ]);
                        $record = ['status' => 'success', 'msg' => Yii::t('app', 'File deleted successfully.')];
                    } else {
                        $record = ['status' => 'success', 'url' => Url::to(['send-file', 'file' => $model->file_name])];
                    }
                } else {
                    return $this->redirect(['index']);
                }
            } else if ($type == 'R') {
                Yii::$app->operation->defaults($model, DELETE);
                $transaction = $this->generalModel->saveTransaction([$model], ['file', 'delete']);
                if (!in_array($transaction, [FALSE, 'customRender'])) {
                    $record = ['status' => 'success', 'msg' => Yii::t('app', 'File deleted successfully.')];
                }
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionSendFile($file) {
        $path = Yii::$app->basePath . '/web/export/PDS/';
        \Yii::$app->response->sendFile($path . $file);
    }

}
