<?php

namespace app\modules\eipldpu\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\eipldpu\models\TblEiplPacketFileLog;
use app\modules\eipldpu\models\TblEiplPacketProcess;
use app\modules\eipldpu\models\TblEiplPacketProcessSearch;

class PendriveImportController extends \app\controllers\ChildController {

    public $freeAccessActions = ['import-file', 'process-files'];

    public function actionCreate() {
        $model = new TblEiplPacketFileLog();
        if ($model->load(Yii::$app->request->post())) {
            $error_file = [];
            $path = Yii::$app->basePath . '/web/import/collection/';
            $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'];
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
                        }
                        $modelSave = [];
                        while ($line = fgets($fh)) {
                            $line_no++;
                            if (!empty($dpu_config['endline']) && strpos($line, $dpu_config['endline']) !== false) {
                                $header_line = ($file->dpu_type == 8) ? TRUE : FALSE;
                                continue;
                            }
                            $dec_text = \Yii::$app->EIPLSecurity->Decrypt($line, $dpu_key, $file->dpu_type);
                            $packet = ($dec_text) ? $dec_text : $line;
                            $packet = $header_line ? trim($packet) : $packet;
                            $p_len = strlen($packet);
                            if ($file->dpu_type == 8 && !$header_line) {
                                $char = preg_match('/^[a-zA-Z ]+$/', substr($packet, 3, 1)) ? TRUE : FALSE;
                                $p_len .= ($char) ? '#3' : '#4';
                            }
                            $packet_config = !empty($dpu_config[$p_len]) ? $dpu_config[$p_len] : FALSE;
                            if ($p_len >= 25 && empty($packet_config)) {
                                $packet = substr($packet, -1, 0) == '-' ? $packet : $packet . '-';
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
                            $main_data_model = new TblEiplPacketProcess();
                            $main_data_model->attributes = $model->attributes;
                            if (!empty($packet_config)) {
                                if (!isset($packet_config['savelog'])) {
                                    try {
                                        $data_array = $this->PacketData($packet, $packet_config);
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
                                        $model->save();
                                        $success_cnt += 1;
                                    } catch (\Throwable $ex) {
                                        $main_data_model->response_msg = 'Not OK';
                                        $main_data_model->save();
                                        $error_cnt += 1;
                                    }
                                } else {
                                    unset($packet_config['savelog']);
                                    $data_array = $this->PacketData($packet, $packet_config);
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
                        $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'] . 'archive/';
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
        return $this->render('file_preview', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/import/collection/';
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

            Yii::$app->db->createCommand("insert into txfarmer (farmerid,farmername,farmermo,vlccid,mccid,sampleno,txflag,qty,amt,rate,fat,snf,water,dtdate,shift,milktype,qtymode,sampletime,createdtime,packet,uuid) "
                            . "SELECT farmerid,farmername,farmermo,vlccid,mccid,sampleno,txflag,qty,amt,rate,fat,snf,water,dtdate,shift,milktype,qtymode,sampletime,'$current_datetime',line_text,'$uuid' from tbl_eipl_packet_process where file_name in ($file_id) and main_table=1")
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

}
