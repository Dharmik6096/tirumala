<?php

namespace app\modules\syncutility\controllers;

use yii;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\syncutility\models\TblPendriveImportExport;
use ZipArchive;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPExcel_Cell;
use app\modules\installation\models\TblIdentity;
use app\models\TblSyncHistory;
use app\modules\syncutility\models\TblSentbox;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use app\modules\syncutility\models\TblSyncLog;
use app\modules\syncutility\models\TblInbox;
use app\modules\syncutility\models\TblPendriveImportExportSearch;
use yii\helpers\Url;
use app\modules\hardwareconfiguration\models\TblUnionConfig;
use app\models\TblSentboxClone;
use app\components\FTPConnection;
use app\modules\installation\models\TblAndroidInstallation;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PendriveSyncController extends \app\controllers\ChildController {

    public $import_dir = 'import/';
    public $export_dir = 'export/';

    public function actionIndex() {
        $searchModel = new TblPendriveImportExportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSaveZip() {
        $path = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->import_dir;
        Yii::$app->general->checkDirectory($path, '0777');
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
        $path = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->import_dir;
        $ExtractPath = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->import_dir . 'zip/';
        if (!is_dir($ExtractPath)) {
            $oldmask = umask(0);
            Yii::$app->general->checkDirectory($ExtractPath, '0777');
            umask($oldmask);
        } else {
            $files = glob($ExtractPath . '*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file))
                    unlink($file); // delete file
            }
        }
        $zipPath = $path . $rename;
        if (Yii::$app->general->ZipOperation($zipPath, FALSE, $ExtractPath)) {
            $filearray = [];
            if (is_dir($ExtractPath)) {
                if ($dh = opendir($ExtractPath)) {
                    $cnt = 0;
                    $skip_cnt = 0;
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
                    foreach ($filearray as $key => $value) {
                        $file = $zipname . '-' . $value . '.csv';
                        $objPHPExcel = IOFactory::load($ExtractPath . $file);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $sheetTitle = strtolower($worksheet->getTitle());
                            $i = 0;
                            $coloumnname = [];
                            $data = [];
                            $HighestColumn = $worksheet->getHighestColumn();
                            $HighestcolumnIndex = PHPExcel_Cell::columnIndexFromString($HighestColumn);
                            $HighestColumnplus = PHPExcel_Cell::stringFromColumnIndex($HighestcolumnIndex);
                            $addName = TRUE;
                            $headings = $worksheet->rangeToArray('A1:' . $HighestColumn . 1, NULL, TRUE, FALSE);
                            $uuid_index = chr(array_search('uuid', $headings[0]) + 65);
                            for ($row = 2; $row <= $worksheet->getHighestRow(); $row ++) {
                                $cnt++;
                                $uuid = $worksheet->getCell($uuid_index . $row)->getValue();
                                $sync_log = TblSyncLog::find()->where(['uuid' => $uuid, 'message_type' => 'RECORD', 'sync_status' => 'U'])->one();
                                if (!empty($sync_log)) {
                                    $skip_cnt++;
                                    continue;
                                }
                                $sync_log = TblInbox::find()->where(['uuid' => $uuid, 'message_type' => 'RECORD', 'sync_status' => 'U'])->one();
                                if (!empty($sync_log)) {
                                    $skip_cnt++;
                                    continue;
                                }
                                for ($col = 'A'; $col != $HighestColumnplus; $col ++) {
                                    if ($addName) {
                                        preg_match('/[A-Z]/', $worksheet->getCell($col . '1')->getValue(), $matches);
                                        if (count($matches) > 0) {
                                            $cname = $worksheet->getCell($col . '1')->getValue();
                                            while (count($matches) > 0) {
                                                $cname = str_replace($matches[0][0], '_' . strtolower($matches[0][0]), $cname);
                                                preg_match('/[A-Z]/', $cname, $matches);
                                            }
                                            $coloumnname[] = $cname;
                                        } else {
                                            $coloumnname[] = $worksheet->getCell($col . '1')->getValue();
                                        }
                                    }
                                    $cell = $worksheet->getCell($col . $row)->getValue();
                                    $data[$i][] = !empty($cell) ? str_replace(':",', ':null,', $cell) : $cell;
                                }
                                //    $data[$i][] = 0;
                                $addName = FALSE;
                                $i++;
                            }
                            // $coloumnname[] = 'status';
                            if (!empty($data) && !empty($coloumnname)) {
                                $transaction = \Yii::$app->db->beginTransaction();
                                try {
                                    //   foreach ($data as $d) {
                                    $db = Yii::$app->db;
                                    $sql = $db->queryBuilder->batchInsert('tbl_inbox', $coloumnname, $data);
                                    $db->createCommand($sql)->execute();
                                    //$db->createCommand(str_replace("INSERT INTO ", "REPLACE INTO", $sql))->execute();
                                    //  }
                                    // if ($transaction->isActive) {
                                    $transaction->commit();
                                    //   }
                                } catch (Exception $e) {
                                    $transaction->rollback();
                                    $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
                                    Yii::$app->response->format = trim(Response::FORMAT_JSON);
                                    return Json::encode($record);
                                }
                            }
                        }
                        unlink($ExtractPath . $file);
                    }
                    $file_name = explode('-', $zipname);
                    $file_name = $file_name[0];
                    $model = new TblPendriveImportExport();
                    $model->scenario = 'log';
                    $model->mode = 0;
                    $model->file_name = $rename;
                    $model->no_of_records = $cnt;
                    $model->no_of_records_ignore = $skip_cnt;
                    $model->dcs_code = substr($file_name, 3);
                    if (!empty($model->dcsCode)) {
                        $model->union_code = $model->dcsCode->union_code;
                        $model->plant_code = $model->dcsCode->plant_code;
                        $model->mcc_plant_code = $model->dcsCode->mcc_plant_code;
                        $model->bmc_code = $model->dcsCode->bmc_code;
                    }
                    $model->dest_org_id = $model->dcs_code;
                    $model->dest_org_type = 'VLC';
                    $model->x_col1 = 'PEN_DRIVE';
                    //  $model->x_col2 = 3;
                    // $model->x_col3 = $name;
                    // $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                    // $model->created_by = $user;
                    $model->save();
                }
                closedir($dh);
                rmdir($ExtractPath);
                $record = ['status' => 'success',
                    'msg' => ($cnt - $skip_cnt) . ' ' . Yii::t('app', 'Records Successfuly Uploaded.') . '<br/>' . $skip_cnt . ' ' . Yii::t('app', 'Records Ignored.')
                ];
            }
        } else {
            $record = ['status' => 'error', 'msg' => Yii::t('app', 'Security Key Is Not Available.')];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionExport() {
        $this->model = new TblPendriveImportExport();
        if ($this->model->load(Yii::$app->request->post())) {
            $path = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->export_dir;
            $name = $this->model->union_code . $this->model->dcs_code;
            $zipname = $name . '-' . date('dmYHis');
            $zipfolder = $path . $zipname;
            if (Yii::$app->general->checkDirectory($zipfolder, '0777')) {
                $this->model->dest_org_id = $this->model->dcs_code;
                $this->model->dest_org_type = 'VLC';
                $sentBox = new TblSentbox();
                $sentBoxData = $sentBox->getExportDataDcsNew($this->model->device_id, $this->model->dest_org_id, $this->model->dest_org_type);
                $exportList = $sentBoxData->getModels();
                if ($exportList) {
                    $cnt = 1;
                    $fcnt = 1;
                    $totcnt = 1;
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
                    $result = Yii::$app->general->ZipOperation($zipfolder, TRUE, '', '', 'csv', 'zip', TRUE);
                    $files = glob($zipfolder . '/*'); // get all file names
                    foreach ($files as $file) { // iterate files
                        if (is_file($file))
                            unlink($file); // delete file
                    }
                    rmdir($zipfolder);
                    if ($result) {
                        $this->model->mode = TRUE;
                        $this->model->x_col1 = 'PEN_DRIVE';
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
        $path = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->export_dir;
        $model = TblPendriveImportExport::findOne($id);
        if (!empty($model) && file_exists($path . $model->file_name)) {
            $name = $model->file_name;
            $zipname = '';
            $ExtractPath = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->export_dir . 'zip/';
            if (!is_dir($ExtractPath)) {
                $oldmask = umask(0);
                Yii::$app->general->checkDirectory($ExtractPath, '0777');
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
                        $objPHPExcel = IOFactory::load($ExtractPath . $file);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            //   $HighestColumn = $worksheet->getHighestColumn();
                            //  $HighestcolumnIndex = PHPExcel_Cell::columnIndexFromString($HighestColumn);
                            //   $HighestColumnplus = PHPExcel_Cell::stringFromColumnIndex($HighestcolumnIndex);
                            for ($row = 2; $row <= $worksheet->getHighestRow(); $row ++) {
                                // $data[$i]['uuid'] = $worksheet->getCell($HighestColumn . $row)->getValue();
                                $data[$i]['uuid'] = $worksheet->getCell('R' . $row)->getValue();
                                //$data[$i]['dest_org_id'] = $worksheet->getCell('A' . $row)->getValue();
                                // $data[$i]['dest_org_type'] = $worksheet->getCell('B' . $row)->getValue();
                                // $data[$i]['sync_status'] = $worksheet->getCell('O' . $row)->getValue();
                                $i++;
                            }
                        }
                        unlink($ExtractPath . $file);
                    }
                }
                closedir($dh);
                rmdir($ExtractPath);
                if (!empty($data) && $model->no_of_records == count($data)) {
                    $uuid = ArrayHelper::getColumn($data, 'uuid');
                    $model->download_counter = 1;
                    Yii::$app->operation->defaults($model, UPDATE);
                    $master[] = $model;
                    $transaction = $this->generalModel->deleteMapping(['TblSentbox', 'TblSentboxClone'], 'uuid', $uuid);
                    if (!in_array($transaction, [FALSE, 'customRender'])) {
                        $this->generalModel->saveTransaction($master, ['file download', 'create']);
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
        $path = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->export_dir;
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
        $path = Yii::$app->basePath . Yii::$app->params['pds_path'] . $this->export_dir;
        \Yii::$app->response->sendFile($path . $file);
    }

}
