<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\EiplPacketFolderLog;
use app\models\EiplPacketFileLog;
use app\models\EiplPacketProcess;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblDpuCollectionHoData;

class EiplPacketController extends Controller {

    public $freeAccessActions = ['read-folder', 'read-file', 'import-file', 'read-dpu-collection-data', 'create', 'check-packet'];

    public function behaviors() {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionReadFolder() {
        $EiplFtp = Yii::$app->params['eiplDirPath'];
        $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'];
        if (Yii::$app->general->checkDirectory($CollectionData . 'archive/')) {
            $folders = scandir($EiplFtp);
            foreach ($folders as $folder) {
                if (in_array($folder, array(".", "..")))
                    continue;
                $sub_folder = $EiplFtp . $folder . '/';
                if (is_dir($sub_folder)) {
                    $dcs_folder = scandir($sub_folder);
                    $cnt = 0;
                    foreach ($dcs_folder as $dcs_file) {
                        if (in_array($dcs_file, array(".", "..")))
                            continue;
                        $old_path = $EiplFtp . $folder . '/' . $dcs_file;
                        $file_path = $CollectionData . $dcs_file;
                        if (copy($old_path, $file_path)) {
                            $file = new EiplPacketFileLog();
                            $file->dcs_code = $folder;
                            $file->file_path = str_replace('\\', '/', $file_path);
                            $file->file_name = $dcs_file;
                            $file->file_status = 0;
                            $file->source_type = 0;
                            if ($file->save(FALSE)) {
                                unlink($old_path);
                                $cnt++;
                            }
                        }
                    }
                    if ($cnt > 0) {
                        $model = new EiplPacketFolderLog();
                        $model->no_of_files = $cnt;
                        $model->dcs_code = $folder;
                        $model->datetime = date('Y-m-d H:i:s');
                        $model->save(FALSE);
                    }
                }
            }
        }
    }

    public function actionReadFile() {
        $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'] . 'archive/';
        if (Yii::$app->general->checkDirectory($CollectionData)) {
            $model = new EiplPacketFileLog();
            $modelData = $model->getRecords();
            $file_ids = array_map(function($e) {
                return $e->file_id;
            }, $modelData);
            $update = $model->updateStatus($file_ids);
            foreach ($modelData as $file) {
                if (file_exists($file->file_path)) {
                    if ($fh = fopen($file->file_path, 'r')) {
                        $file_ext = explode('.', $file->file_name)[1];
                        $file->dpu_type = strtoupper($file_ext) == 'EIP' ? 8 : 32;
                        $dpu_key = Yii::$app->general->getforeignkey($file->unionDpuConfig, 'dpu_key');
                        $cnt = 0;
                        $error_cnt = 0;
                        $success_cnt = 0;
                        $no_of_lines = count(file($file->file_path));
                        if ($file->source_type == 0) {
                            $dateshift = explode('.', $file->file_name)[0];
                            $dtdate = \DateTime::createFromFormat('dmy', substr($dateshift, 12, 6));
                            $dtdate = $dtdate->format('Y-m-d');
                            $vlccid = $file->dcs_code;
                        } else if ($file->source_type == 2) {
                            $vlccid = $file->dcs_code;
                        } else {
                            $line = file($file->file_path)[0];
                            $dec_text = \Yii::$app->EIPLSecurity->Decrypt($line, $dpu_key);
                            $main_line = ($dec_text) ? $dec_text : $line;
                            $vlccid = ($dec_text) ? substr($main_line, 14, 12) : substr($main_line, 9, 12);
                            $dateshift = explode('.', $file->file_name)[0];
                            $dtdate = \DateTime::createFromFormat('dmy', substr($dateshift, 0, 6));
                            $dtdate = $dtdate->format('Y-m-d');
                            //$shift = (substr($dateshift, 6, 1) == 'M') ? 1 : 2;
                            $shift = substr($dateshift, 6, 1);
                        }
                        $new_dcs_data = FALSE;
                        while ($line = fgets($fh)) {
                            $cnt++;
                            if ($file->source_type == 1 && strpos($line, 'ENDENDEND') !== false) {
                                $new_dcs_data = TRUE;
                                continue;
                            }
                            if ($new_dcs_data) {
                                $dec_text = \Yii::$app->EIPLSecurity->Decrypt($line, $dpu_key);
                                $main_line = ($dec_text) ? $dec_text : $line;
                                $vlccid = ($dec_text) ? substr($main_line, 14, 12) : substr($main_line, 9, 12);
                                $new_dcs_data = FALSE;
                                continue;
                            }
                            if (in_array($file->source_type, [0, 2]) || !in_array($cnt, [1, $no_of_lines])) {
                                $dec_text = TRUE;
                                if (in_array($file->source_type, [0, 1])) {
                                    $dec_text = \Yii::$app->EIPLSecurity->Decrypt($line, $dpu_key);
                                    $line = ($dec_text) ? $dec_text : $line;
                                }
                                $packet = new EiplPacketProcess();
                                $packet->dcs_code = $vlccid;
                                $packet->file_name = $file->file_id;
                                $packet->line_text = $line;
                                $packet->line_no = $cnt;
                                $packet->is_decrypted = ($dec_text) ? 1 : 0;
                                $packet->main_table = 0;
                                $packet->source_type = $file->source_type;
                                try {
                                    $packet->save(FALSE);
                                    if ($packet->is_decrypted == 1) {
                                        try {
                                            $eiplpacket = $packet->line_text;
                                            if ($file->source_type == 2) {
                                                $main_line = $packet->line_text;
                                                $main_line = explode(',', $main_line);
                                                $result = $this->saveAmcsCollectionDetails($vlccid, $main_line);
                                            } else {
                                                if ($file->source_type == 0) {
                                                    $main_line = substr($packet->line_text, 0, 29);
                                                    $eiplpacket = substr($packet->line_text, 29);
                                                    $main_line = explode(',', $main_line);
                                                    $main_line = array_reverse($main_line);
                                                    //$shift = ($main_line[2] == 'M') ? 1 : 2;
                                                    $shift = $main_line[2];
                                                }
                                                $result = $this->saveCollection($vlccid, $dtdate, $shift, $cnt, $eiplpacket, $file->source_type);
                                            }
                                            if ($result) {
                                                $packet->main_table = 1;
                                                $success_cnt +=1;
                                                $packet->save(FALSE);
                                            } else {
                                                //var_dump($packet->line_text);
                                                $error_cnt += 1;
                                            }
                                        } catch (yii\base\Exception $e) {
                                            //var_dump($packet->line_text);
                                            $error_cnt += 1;
                                        }
                                    } else {
                                        $packet->is_decrypted = 0;
                                        $packet->line_text = $line;
                                        $packet->save(FALSE);
                                        $error_cnt += 1;
                                    }
                                } catch (yii\base\Exception $e) {
                                    $packet->is_decrypted = 0;
                                    $packet->line_text = $line;
                                    $packet->save(FALSE);
                                    $error_cnt += 1;
                                }
                            }
                        }
                        fclose($fh);
                        if (copy($file->file_path, $CollectionData . $file->file_name)) {
                            unlink($file->file_path);
                        }
                        $file->file_status = 1; //success read
                        $file->total_record = $success_cnt + $error_cnt;
                        $file->processed_record = $success_cnt;
                        $file->status = 2; //error
                    } else {
                        $file->file_status = 3; //currupted
                        $file->status = 3; //error
                    }
                } else {
                    $file->status = 3; //error
                    $file->file_status = 2; //not found
                }
                $file->save(FALSE);
            }
        }
    }

    public function actionCreate() {
        $model = new EiplPacketFileLog();
        if ($model->load(Yii::$app->request->post())) {
            $error_file = [];
            $path = Yii::$app->basePath . '/web/import/collection/';
            $CollectionData = Yii::$app->basePath . Yii::$app->params['collection_dir_path'];
            if (Yii::$app->general->checkDirectory($CollectionData . 'archive/')) {
                $status = 'success';
                $files = array_filter(explode(',', $model->file_name));
                $cnt = 0;
                foreach ($files as $key => $value) {
                    $old_path = $path . $value;
                    $file_path = $CollectionData . $value;
                    if (copy($old_path, $file_path)) {
                        $file = new EiplPacketFileLog();
                        $file->attributes = $model->attributes;
                        $file->file_path = str_replace('\\', '/', $file_path);
                        $file->file_name = $value;
                        $file->file_status = 0;
                        $file->source_type = 1;
                        if ($file->save(FALSE)) {
                            $cnt++;
                            unlink($old_path);
                        } else {
                            $error_file[] = $value;
                        }
                    } else {
                        $error_file[] = $value;
                    }
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

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/import/collection/';
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = $file->name;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'msg' => $name];
            } else {
                $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    private function saveCollection($vlccid, $dtdate, $shift, $sampleno, $packet, $source) {

        $farmerid = substr($packet, 0, 4);
        $milktype = substr($packet, 4, 1);
        $fat = (float) ((substr($packet, 5, 2)) . '.' . (substr($packet, 7, 1))); // . after 2
        $snf = (float) ((substr($packet, 8, 2)) . '.' . (substr($packet, 10, 1)));  // . after 2
        $water = (float) (substr($packet, 11, 2));  // . after 2
        $qty = (float) ((substr($packet, 13, 3)) . '.' . (substr($packet, 16, 2)));    // . after 3
        $amt = (float) ((substr($packet, 18, 5)) . '.' . (substr($packet, 23, 2))); // . after 5
        if ($source == 0) {
            $rate = (float) ((substr($packet, 25, 2)) . '.' . (substr($packet, 27, 2))); // . after 2
            $sampletime = $dtdate . ' ' . substr($packet, 31, 2) . ':' . substr($packet, 29, 2) . ':00';
        } else {
            $rate = (float) ((substr($packet, 29, 2)) . '.' . (substr($packet, 31, 2))); // . after 2
            $sampletime = $dtdate . ' ' . substr($packet, 27, 2) . ':' . substr($packet, 25, 2) . ':00';
        }
        $txflag = ($source == 0) ? substr($packet, 36, 3) : substr($packet, 33, 3);
        $farmername = ($source == 0) ? substr($packet, 39, 13) : substr($packet, 36, 13);
        $farmermo = NULL;
        $mccid = substr($vlccid, 0, 6);
        $createdtime = date('Y-m-d H:i:s');
        $createdtime = date('Y-m-d H:i:s');
        //  $type = ($source == 0) ? 'FTP' : 'PD';
        $type = 'PD';
        if (!in_array($farmerid, ['2097', '2098'])) {
            $result = \Yii::$app->db_rmrd->createCommand("sp_txfarmer_ho_data '$farmerid',
'$farmername',
'$farmermo',
'$vlccid',
'$mccid',
'$sampleno',
'$txflag',
'$qty',
'$amt',
'$rate',
'$fat',
'$snf',
'$water',
'$dtdate',
'$shift',
'$milktype',
'$sampletime',
'$createdtime',
'$type','0'
");
            $query = $result->queryScalar();
            if ($query == '1') {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function actionReadDpuCollectionData() {
        $model = new TblDpuCollectionHoData();
        $modelData = $model->getData();
        if (!empty($modelData)) {
            $update_ids = array_column($modelData, 'dpu_collection_ho_data_id');
            $model->updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['dpu_collection_ho_data_id' => $update_ids]);
            foreach ($modelData as $data) {
                if (!empty($data->dcsCode) && !empty($data->dcsCode->unionDpuConfig)) {
                    $string = $data->encrypted_string;
                    $dpu_key = $data->dcsCode->unionDpuConfig->dpu_key;
                    $string = Yii::$app->EIPLSecurity->Decrypt($string, $dpu_key);
                    $cnt = $data->dpu_collection_ho_data_id;
                    $this->saveCollectionData($data, $string, $cnt);
                }
            }
        }
    }

    private function saveCollectionData($data, $packet, $sampleno) {
        try {
            $vlccid = substr($packet, 0, 12);
            //echo substr($packet, 13, 8);

            $dtdate = \DateTime::createFromFormat('d/m/y', substr($packet, 13, 8));
            $dtdate = $dtdate->format('Y-m-d');
            // $dtdate = substr($packet, 13, 8);
            $shift = substr($packet, 21, 1);
            $farmerid = substr($packet, 23, 4);
            $milktype = substr($packet, 27, 1);
            $fat = (float) ((substr($packet, 28, 2)) . '.' . (substr($packet, 30, 1))); // . after 2
            $snf = (float) ((substr($packet, 31, 2)) . '.' . (substr($packet, 33, 1)));  // . after 2
            $water = (float) (substr($packet, 34, 2));  // . after 2
            $qty = (float) ((substr($packet, 36, 3)) . '.' . (substr($packet, 39, 2)));    // . after 3
            $amt = (float) ((substr($packet, 41, 5)) . '.' . (substr($packet, 46, 2))); // . after 5
            $sampletime = $dtdate . ' ' . substr($packet, 50, 2) . ':' . substr($packet, 48, 2) . ':00';
            $rate = (float) ((substr($packet, 52, 2)) . '.' . (substr($packet, 54, 2))); // . after 2
            $txflag = substr($packet, 56, 3);
            $farmername = trim(substr($packet, 59, 13));
            $farmermo = NULL;
            $mccid = substr($vlccid, 0, 6);
            $createdtime = date('Y-m-d H:i:s');
            $createdtime = date('Y-m-d H:i:s');
            $type = 'HTTP';
            if (!in_array($farmerid, ['2097', '2098'])) {
                $result = \Yii::$app->db_rmrd->createCommand("sp_txfarmer_ho_data '$farmerid',
'$farmername',
'$farmermo',
'$vlccid',
'$mccid',
'$sampleno',
'$txflag',
'$qty',
'$amt',
'$rate',
'$fat',
'$snf',
'$water',
'$dtdate',
'$shift',
'$milktype',
'$sampletime',
'$createdtime',
'$type','0'
");
                $query = $result->queryScalar();
                if ($query == '1') {
                    $data->status = 2;
                } else {
                    $data->status = 3;
                }
            }
        } catch (\Throwable $ex) {
            $data->status = 3;
        }
        $data->save();
    }

    private function saveAmcsCollectionDetails($dcs, $packet) {
        try {
            $collectionDate = date('Y-m-d', strtotime(str_replace('/', '-', $packet[1])));
            $shift = isset($packet[2]) ? $packet[2] : '';
            $member = isset($packet[3]) ? $packet[3] : '';
            $milktype = isset($packet[4]) ? $packet[4] : '';
            $fat = isset($packet[5]) ? (float) ($packet[5]) : '';
            $snf = isset($packet[6]) ? (float) ($packet[6]) : '';
            $qty = isset($packet[7]) ? (float) ($packet[7]) : '';
            $rtpl = isset($packet[8]) ? (float) ($packet[8]) : '';
            $amnt = isset($packet[9]) ? (float) ($packet[9]) : '';
            $qltyAut = isset($packet[10]) ? ($packet[10]) : '';
            $qtyAut = isset($packet[11]) ? ($packet[11]) : '';
            $result = \Yii::$app->db->createCommand("sp_txfarmer_amcs_data '$dcs',
'$collectionDate',
'$shift',
'$member',
'$milktype',
'$fat',
'$snf',
'$qty',
'$rtpl',
'$amnt',
'$qltyAut',
'$qtyAut','0'
");

            $query = $result->queryScalar();
            if ($query == '1') {
                return TRUE;
            }
            return FALSE;
        } catch (\Throwable $ex) {
            return false;
        }
    }

    public function actionCheckPacket() {
        $dateshift = '201219M';
        $dtdate = \DateTime::createFromFormat('dmy', substr($dateshift, 0, 6));
        $dtdate = $dtdate->format('Y-m-d');
        $shift = (substr($dateshift, 6, 1) == 'M') ? 1 : 2;
        $source = 1;
        $packet = '0004C0420850000620001935640073122055INDHRA.S    9585468310      ';
        $vlccid = '000000000021';
        $sampleno = 1;
        $farmerid = substr($packet, 0, 4);
        $milktype = substr($packet, 4, 1);
        $fat = (float) ((substr($packet, 5, 2)) . '.' . (substr($packet, 7, 1))); // . after 2
        $snf = (float) ((substr($packet, 8, 2)) . '.' . (substr($packet, 10, 1)));  // . after 2
        $water = (float) (substr($packet, 11, 2));  // . after 2
        $qty = (float) ((substr($packet, 13, 3)) . '.' . (substr($packet, 16, 2)));    // . after 3
        $amt = (float) ((substr($packet, 18, 5)) . '.' . (substr($packet, 23, 2))); // . after 5
        if ($source == 0) {
            $rate = (float) ((substr($packet, 25, 2)) . '.' . (substr($packet, 27, 2))); // . after 2
            $sampletime = $dtdate . ' ' . substr($packet, 31, 2) . ':' . substr($packet, 29, 2) . ':00';
        } else {
            $rate = (float) ((substr($packet, 29, 2)) . '.' . (substr($packet, 31, 2))); // . after 2
            $sampletime = $dtdate . ' ' . substr($packet, 27, 2) . ':' . substr($packet, 25, 2) . ':00';
        }
        $txflag = ($source == 0) ? substr($packet, 36, 3) : substr($packet, 33, 3);
        $farmername = ($source == 0) ? substr($packet, 39, 13) : substr($packet, 36, 13);
        $farmermo = NULL;
        $mccid = substr($vlccid, 0, 6);
        $createdtime = date('Y-m-d H:i:s');
        $createdtime = date('Y-m-d H:i:s');
        //  $type = ($source == 0) ? 'FTP' : 'PD';
        $type = 'HTTP';
        if (!in_array($farmerid, ['2097', '2098'])) {
            $result = \Yii::$app->db_rmrd->createCommand("sp_txfarmer_ho_dat '$farmerid',
'$farmername',
'$farmermo',
'$vlccid',
'$mccid',
'$sampleno',
'$txflag',
'$qty',
'$amt',
'$rate',
'$fat',
'$snf',
'$water',
'$dtdate',
'$shift',
'$milktype',
'$sampletime',
'$createdtime',
'$type','0'
");
            $query = $result->queryScalar();
            var_dump($query);
        }
    }

}
