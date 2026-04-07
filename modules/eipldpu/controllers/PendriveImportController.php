<?php

namespace app\modules\eipldpu\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\eipldpu\models\TblEiplPacketFileLog;
use app\modules\eipldpu\models\TblEiplPacketProcess;
use app\modules\eipldpu\models\TblEiplPacketProcessSearch;

class PendriveImportController extends \app\controllers\ChildController {

    public $freeAccessActions = ['import-file', 'process-files', 'import-zip', 'add-zip'];

    public function actionCreate() {
        $model = new TblEiplPacketFileLog();
        if ($model->load(Yii::$app->request->post())) {
            $postData = Yii::$app->request->post();
            $folderPath = !empty($postData['file_folder']) ? $postData['file_folder'] . '/' : '';
            $error_file = [];
            $path = Yii::$app->basePath . '/web/import/collection/' . $folderPath;
            $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'] . $folderPath;
            if (Yii::$app->general->checkDirectory($CollectionData . 'archive/')) {
                $status = 'success';
                $files = array_filter(explode(',', $model->file_name));
                $cnt = 0;
                $file_id = [];
                foreach ($files as $key => $value) {
                    try {
                        $old_path = $path . $value;
                        $file_path = $CollectionData . $value;
                        if (copy($old_path, $file_path)) {
                            $file = new TblEiplPacketFileLog();
                            $file->attributes = $model->attributes;
                            $file->file_path = str_replace('\\', '/', $file_path);
                            $file->file_name = $value;
                            $file->file_status = 0;
                            $file->source_type = 1;
                            $file->status = 1;
                            $file->dpu_type = $this->validateFileName($file->file_name);
                            if ($file->save(FALSE)) {
                                $file_id[] = $file->file_id;
                                $cnt++;
                                unlink($old_path);
                            } else {
                                $error_file[] = $value;
                            }
                        } else {
                            $error_file[] = $value;
                        }
                    } catch (\Throwable $ex) {
                        
                    }
                }
                if (!empty($file_id)) {
                    return $this->redirect(['process-files', 'file_id' => implode(',', $file_id)]);
                }
                $msg = $cnt . ' Files Uploaded Successfully<br/>';
                if (!empty($error_file)) {
                    $msg .= 'Following files not uploaded' . implode('<br/>', $error_file);
                }
            } else {
                $status = 'error';
                $msg = 'Error While Save data';
            }
            $result = ['status' => $status, 'data' => $msg];
            echo (Json::encode($result));
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionProcessFiles($file_id) {
        $file_id = explode(',', $file_id);
        if (Yii::$app->request->post()) {
            if ($this->savePacketData($file_id)) {
                return $this->redirect(['create']);
            }
        }
        $modelData = TblEiplPacketFileLog::find()->where(['file_id' => $file_id, 'status' => 1])->all();
        foreach ($modelData as $file) {
            if (file_exists($file->file_path)) {
                if ($fh = fopen($file->file_path, 'r')) {
                    $file_string = explode('.', $file->file_name);
                    $file_ext = $file_string[1];
                    $dateshift = substr(str_replace('_', '', $file_string[0]), -7);
                    $dpu_key = Yii::$app->general->getforeignkey($file->unionDpuConfig, 'dpu_key');
                    if (!empty($dpu_key)) {
                        $dpu_config = \Yii::$app->EIPLPacketConfig->ConfigList($file->dpu_type);
                        $cnt = 1;
                        $line_no = 0;
                        $error_cnt = 0;
                        $success_cnt = 0;
                        $no_of_lines = count(file($file->file_path));
                        $attributes = [];
                        $header_line = FALSE;
                        if ($file->dpu_type == 8) {
                            $attributes = [
                                'dtdate' => '',
                                'shift' => '',
                                'vlccid' => '',
                            ];
                            $attributes['dtdate'] = substr($dateshift, 0, 6);
                            $attributes['shift'] = substr($dateshift, 6, 1);
                            $header_line = TRUE;
                        } else if ($file->dpu_type == 32) {
                            $attributes['shift'] = substr($dateshift, 6, 1);
                        }
                        $modelSave = [];
                        while ($line = fgets($fh)) {
                            $line_no++;
                            $dec_text = \Yii::$app->EIPLSecurity->Decrypt($line, $dpu_key, $file->dpu_type);
                            $packet = ($dec_text) ? $dec_text : $line;
                            $packet = $header_line ? trim($packet) : $packet;
                            if (!empty($dpu_config['endline']) && strpos($packet, $dpu_config['endline']) !== false) {
                                $header_line = ($file->dpu_type == 8) ? TRUE : FALSE;
                                continue;
                            }
                            $p_len = strlen($packet);
                            if ($file->dpu_type == 8 && !$header_line) {
                                $char = preg_match('/^[a-zA-Z ]+$/', substr($packet, 3, 1)) ? TRUE : FALSE;
                                if ($p_len >= 33 && $p_len <= 44) {
                                    $p_len = 44;
                                }
                                $p_len .= ($char) ? '#3' : '#4';
                            }
                            $packet_config = !empty($dpu_config[$p_len]) ? $dpu_config[$p_len] : FALSE;
                            if ($p_len >= 25 && empty($packet_config)) {
                                $dec_text = \Yii::$app->EIPLSecurity->Decrypt($line . '-', $dpu_key, $file->dpu_type);
                                $packet = ($dec_text) ? $dec_text : $line . '-';
//                                $packet = substr($packet, -1, 0) == '-' ? $packet : $packet . '-';
                                $p_len = strlen($packet);
                                if ($file->dpu_type == 8 && !$header_line) {
                                    $char = preg_match('/^[a-zA-Z ]+$/', substr($packet, 3, 1)) ? TRUE : FALSE;
                                    $p_len .= ($char) ? '#3' : '#4';
                                }
                                $packet_config = !empty($dpu_config[$p_len]) ? $dpu_config[$p_len] : FALSE;
                            }
                            $header_line = FALSE;
                            $model = new TblEiplPacketProcess();
                            $model->attributes = $attributes;
                            $model->dcs_code = $model->vlccid;
                            $model->sampleno = ($file->dpu_type == 8) ? $cnt : NULL;
                            $model->file_name = $file->file_id;
                            $model->line_text = $packet;
                            $model->line_no = $line_no;
                            $model->is_decrypted = ($dec_text) ? 1 : 0;
                            $model->main_table = 0;
                            $model->source_type = $file->source_type;
                            $model->created_at = date('Y-m-d H:i:s');
                            $model->created_by = isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : null;
                            $main_data_model = new TblEiplPacketProcess();
                            $main_data_model->attributes = $model->attributes;
                            if (!empty($packet_config)) {
                                if (!isset($packet_config['savelog'])) {
                                    try {
                                        $data_array = self::PacketData($packet, $packet_config);
                                        $model->attributes = $data_array;
                                        $model->dcs_code = $model->vlccid;
                                        $model->main_table = 1;
                                        $dtdate = \DateTime::createFromFormat('dmy', $model->dtdate);
                                        $model->dtdate = $dtdate->format('Y-m-d');
                                        $model->sampletime = date('Y-m-d', strtotime($model->dtdate)) . (!empty($data_array['sampletime']) ? (' ' . $data_array['sampletime']) : '');
                                        $model->mccid = substr($model->vlccid, 0, 6);
                                        $model->response_msg = 'OK';
                                        //$modelSave[] = $model;
                                        $model->rate = (empty($model->rate) && !empty($model->qty)) ? ($model->amt / $model->qty) : $model->rate;
                                        if ($model->shift != 'M' || $model->shift != 'E') {
                                            if (isset($attributes['shift'])) {
                                                $model->shift = $attributes['shift'];
                                            }
                                        }
                                        $model->save();
                                        $success_cnt += 1;
                                    } catch (\Throwable $ex) {

                                        try {
                                            $main_data_model->response_msg = 'Not OK';
                                            $main_data_model->save();
                                            $error_cnt += 1;
                                        } catch (\Throwable $th) {
                                            $main_data_model->dcs_code = $this->utfValidStr($main_data_model->dcs_code);
                                            $main_data_model->vlccid = $this->utfValidStr($main_data_model->vlccid);
                                            $main_data_model->line_text = $this->utfValidStr($main_data_model->line_text);
                                            $main_data_model->response_msg = 'Not OK(Junk)';
                                            $main_data_model->save();
                                            $error_cnt += 1;
                                        }
                                    }
                                } else {
                                    unset($packet_config['savelog']);
                                    $data_array = self::PacketData($packet, $packet_config);
                                    $attributes = array_merge($attributes, $data_array);
                                    continue;
                                }
                            } else {
                                $model->response_msg = 'Packet Config Missing';
                                //$modelSave[] = $model;
                                $model->save();
                                $error_cnt += 1;
                            }
                            $cnt++;
                        }
                        fclose($fh);
                        if (!empty($file->zip_name)) {
                            $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'] . $file->dcs_code . '/archive/';
                        } else {
                            $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'] . 'archive/';
                        }
                        Yii::$app->general->checkDirectory($CollectionData);
                        if (copy($file->file_path, $CollectionData . $file->file_name)) {
                            unlink($file->file_path);
                        }
                        $file->file_status = 2; //success read
                        $file->total_record = $success_cnt + $error_cnt;
                        $file->processed_record = $success_cnt;
                        $file->status = 2;
                        $file->response_msg = 'SUCCESS';
                    } else {
                        $file->file_status = 3; //currupted
                        $file->status = 3; //error
                        $file->response_msg = 'DPU Key Missing';
                    }
                } else {
                    $file->file_status = 3; //currupted
                    $file->status = 3; //error
                    $file->response_msg = 'File currupted';
                }
            } else {
                $file->status = 3; //error
                $file->file_status = 3; //not found
                $file->response_msg = 'File Not Found';
            }
            $file->save(FALSE);
            Yii::$app->display->message(true, 'Pendrive Data', 'create');
            //$modelSave[] = $file;
            //$transaction = $this->generalModel->saveTransaction($modelSave, ['Pendrive File', 'create']);
        }
        $model = new TblEiplPacketProcess();
        $model->file_name = $file_id;
        $dataProvider = $model->getFileData();
        try {
            return $this->render('file_preview', ['model' => $model, 'dataProvider' => $dataProvider]);
        } catch (\Throwable $ex) {
            return '';
        }
    }

    public function actionImportFile($fileFolder = '') {
        $path = Yii::$app->basePath . '/web/import/collection/';
        $path .= $fileFolder . '/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = $file->name;
            if ($this->validateFileName($name)) {
                if ($file->saveAs($path . $name)) {
                    $record = ['status' => 'success', 'filename' => $name, 'msg' => $name];
                } else {
                    $record = ['status' => 'error', 'filename' => $name, 'msg' => 'File Not Uploaded Due to Error'];
                }
            } else {
                $record = ['status' => 'error', 'filename' => $name, 'msg' => 'Invalid File ' . $name];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function PacketData($packet, $config) {
        $p_data = [];
        foreach ($config as $k => $c) {
            $val = '';
            $vc = explode('#', $c);
            foreach ($vc as $dc) {
                $fc = explode('=', $dc);
                $vt = $fc[0];
                if ($vt == 'pckt') {
                    $pc = explode('-', $fc[1]);
                    $val .= substr($packet, $pc[0], $pc[1]);
                } else if ($vt == 'fix') {
                    $val .= $fc[1];
                }
            }
            $p_data[$k] = $val;
        }
        return $p_data;
    }

    public function savePacketData($file_id) {
        $transaction = \Yii::$app->db->beginTransaction();
        $file_id = implode(',', $file_id);
        $current_datetime = date('Y-m-d H:i:s');
        try {
            $command = Yii::$app->getDb()->createCommand('SELECT NEWID() as id')->queryOne();
            $uuid = $command['id'];
            Yii::$app->db->createCommand("UPDATE tbl_eipl_packet_file_log SET response_msg=:response_msg,updated_at=:updated_at WHERE file_id in ($file_id)")
                    ->bindValue(':response_msg', 'PROCESSED')
                    ->bindValue(':updated_at', date('Y-m-d H:i:s'))
                    ->execute();

            Yii::$app->db->createCommand("insert into txfarmer (farmerid,farmername,farmermo,vlccid,mccid,sampleno,txflag,qty,amt,rate,fat,snf,water,dtdate,shift,milktype,qtymode,sampletime,createdtime,packet,uuid,qltyauto,qtyauto) "
                            . "SELECT farmerid,farmername,farmermo,vlccid,mccid,sampleno,txflag,qty,amt,rate,fat,snf,water,dtdate,shift,milktype,qtymode,sampletime,'$current_datetime',line_text,'$uuid',
                                CASE WHEN ISNULL(txflag,'')='' THEN 0 ELSE SUBSTRING(txflag, 1, 1) END AS qltyauto,
                                CASE WHEN ISNULL(txflag,'')='' THEN 0 ELSE SUBSTRING(txflag, 2, 1) END AS qtyauto
                                from tbl_eipl_packet_process where file_name in ($file_id) and main_table=1")
                    ->execute();

            if ($transaction->isActive) {
                $transaction->commit();
                \Yii::$app->db->createCommand("{CALL GPRS_PD_DATA_PROCESS (:uuid)}")
                        ->bindValue(':uuid', $uuid)->execute();
                Yii::$app->display->message(true, 'Pendrive Data', 'create');
                return TRUE;
            } else {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => Yii::t('app', 'Your transaction is not saved successfully')]);
            }
        } catch (\Throwable $ex) {
            $transaction->rollback();
            // var_dump($ex->getMessage());
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => Yii::t('app', 'Exception : Your transaction is not saved successfully')]);
        }
        return FALSE;
    }

    public function validateFileName($filename) {
        $filename = str_replace('_', '', $filename);
        $fname_len = strlen($filename);
        if (in_array($fname_len, [11, 14]) && strtoupper(substr($filename, -3)) == 'EIP') {
            $dpu_type = 8;
            if ($fname_len == 14) {
                $dpu_type = 32;
                if (!in_array(substr($filename, 0, 3), ['MST'])) {
                    return FALSE;
                }
            }
            $file_string = explode('.', $filename);
            $dateshift = substr(str_replace('_', '', $file_string[0]), -7);
            if (in_array(substr($dateshift, -1), ['M', 'E'])) {
                $day = (int) substr($dateshift, 0, 2);
                $month = (int) substr($dateshift, 2, 2);
                if (($day >= 1 && $day <= 31) && ($month >= 1 && $month <= 12)) {
                    return $dpu_type;
                }
            }
        }
        return FALSE;
    }

    public function utfValidStr($string) {
        return preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $string);
    }

    public function actionAddZip() {
        $model = new TblEiplPacketFileLog();

        if ($model->load(Yii::$app->request->post())) {
            $status = '';
            $msg = '';
            $msgArr = [];
            $fromDate = '';
            $fromShift = '';
            $toDate = '';
            $toShift = '';
            $allowFileArray = [];
            if (empty($model->from_date)) {
                $status = 'date_error';
                $msgArr[] = 'From Date cannot be blank.';
            }
            if (empty($model->from_shift)) {
                $status = 'date_error';
                $msgArr[] = 'From Shift cannot be blank.';
            }
            if (empty($model->to_date)) {
                $status = 'date_error';
                $msgArr[] = 'To Date cannot be blank.';
            }
            if (empty($model->to_shift)) {
                $status = 'date_error';
                $msgArr[] = 'To Shift cannot be blank.';
            }
            if (empty($status)) {
                $fromDate = date('Y-m-d', strtotime($model->from_date)) . ' ' . Yii::$app->general->getshift($model->from_shift);
                $toDate = date('Y-m-d', strtotime($model->to_date)) . ' ' . Yii::$app->general->getshift($model->to_shift);
                $fromDate = date('Y-m-d H:i:s', strtotime($fromDate));
                $toDate = date('Y-m-d H:i:s', strtotime($toDate));
                $model->from_date = $fromDate;
                $model->to_date = $toDate;
                if ($fromDate > $toDate) {
                    $status = 'date_error';
                    $msgArr[] = 'To Date must be greater then or equal to From Date.';
                } else {
                    $toDate = date('Y-m-d H:i:s', strtotime($toDate) + 43200);
                    $begin = new \DateTime($fromDate);
                    $end = new \DateTime($toDate);

                    $interval = \DateInterval::createFromDateString('12 hours');
                    $period = new \DatePeriod($begin, $interval, $end);

                    foreach ($period as $dt) {
                        $shift = $dt->format("H:i:s");
                        $date = $dt->format("dmy");
                        $setKey = 'MST_' . $date . '_';
                        $setKey .= $shift == '06:00:00' ? 'M' : 'E';
                        $allowFileArray[] = strtolower($setKey . '.EIP');
                        $msgArr[] = $setKey;
                    }
                }
            }
//            $status = 'date_error';
//            $msgArr[] = 'teeste';
            if (!empty($status)) {
                $msg = implode('<br>', $msgArr);
            } else {
                $zipPath = Yii::$app->basePath . '/web/import/collection/zip/';
                $ExtractPath = $zipPath . explode('.', $model->file_name)[0];
                $zipPath .= $model->file_name;
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

                if (Yii::$app->general->ZipOperation($zipPath, FALSE, $ExtractPath)) {
                    $folders = scandir($ExtractPath);
                    $main_folder = '';
                    foreach ($folders as $folder) {
                        if (in_array($folder, array(".", "..")))
                            continue;
                        $main_folder = $folder;
                    }
                    if (!empty($main_folder) && is_dir($ExtractPath . '/' . $main_folder)) {
                        $sub_folder = $ExtractPath . '/' . $main_folder . '/';
                        $dcs_folder = scandir($sub_folder);
                        $cnt = 0;
                        $file_id = [];
                        $error_file = [];
                        $considerBulkFolder = '';
                        foreach ($dcs_folder as $dFolder) {
                            $considerBulkFolder = '';
                            if (!in_array($dFolder, array(".", ".."))) {
                                $considerBulkFolder = $dFolder;

                                if (!empty($considerBulkFolder) && is_dir($sub_folder . '/' . $considerBulkFolder)) {
                                    $sub_bulk_folder = $sub_folder . $considerBulkFolder . '/';
                                    $bulkFolder = scandir($sub_bulk_folder);
                                    foreach ($bulkFolder as $folder) {
                                        if (!in_array($folder, array(".", "..", "Log"))) {
                                            $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'] . $dFolder . '/' . $folder . '/';
                                            if (Yii::$app->general->checkDirectory($CollectionData . 'archive/')) {
                                                if ($dh = opendir($sub_bulk_folder . $folder)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if (strtoupper(pathinfo($file, PATHINFO_EXTENSION)) == 'EIP') {
                                                            $status = 'success';
                                                            try {
                                                                $old_path = $sub_bulk_folder . $folder . '/' . $file;
                                                                $file_path = $CollectionData . $file;
                                                                if (in_array(strtolower($file), $allowFileArray)) {
                                                                    if (copy($old_path, $file_path)) {
                                                                        $file_log = new TblEiplPacketFileLog ();
                                                                        $file_log->attributes = $model->attributes;
                                                                        $file_log->zip_name = $model->file_name;
                                                                        $file_log->file_path = str_replace('\\', '/', $file_path);
                                                                        $file_log->file_name = $file;
                                                                        $file_log->file_status = 0;
                                                                        $file_log->source_type = 1;
                                                                        $file_log->status = 0;
                                                                        $file_log->dpu_type = $this->validateFileName($file_log->file_name);
                                                                        $file_log->dcs_code = substr($folder, -12);
                                                                        if ($file_log->save(FALSE)) {
                                                                            $file_id[] = $file_log->file_id;
                                                                            $cnt++;
                                                                            unlink($old_path);
                                                                        } else {
                                                                            $error_file[] = $file;
                                                                        }
                                                                    } else {
                                                                        $error_file[] = $file;
                                                                    }
                                                                }
                                                            } catch (\Throwable $ex) {
                                                                $status = 'error';
                                                                $msg = 'Error While Save data';
                                                            }
                                                        } else {
                                                            $status = 'error';
                                                            $msg = 'Error While Save data';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        Yii::$app->general->RemoveDirectory($ExtractPath);
                        $msg = $cnt . ' Files Uploaded Successfully<br/>';
                        if (!empty($error_file)) {
                            $msg .= 'Following files not uploaded' . implode('<br/>', $error_file);
                        }
                    } else {
                        $status = 'error';
                        $msg = 'Empty Zip Found';
                    }
                } else {
                    $status = 'error';
                    $msg = 'Error While Zip Extract';
                }
            }
            $result = ['status' => $status, 'data' => $msg];
            echo (Json::encode($result));
        } else {
            $model->from_date = date('d-m-Y');
            $model->from_shift = '1';
            $model->to_date = date('d-m-Y');
            $model->to_shift = '2';
            return $this->render('import_zip', ['model' => $model]);
        }
    }

    public function actionImportZip() {
        $path = Yii::$app->basePath . '/web/import/collection/zip/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = Yii::$app->session->get('UserCode') . '_' . date('Ymdhis') . '.zip';
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
