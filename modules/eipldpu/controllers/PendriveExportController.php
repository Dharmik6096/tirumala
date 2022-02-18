<?php

namespace app\modules\eipldpu\controllers;

use Yii;
use app\modules\eipldpu\models\TblEiplMasterFileLog;
use app\modules\eipldpu\models\TblEiplMasterFileLogSearch;
use yii\web\NotFoundHttpException;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * PendriveExportController implements the CRUD actions for TblEiplMasterFileLog model.
 */
class PendriveExportController extends \app\controllers\ChildController {

    protected $folder_path;
    public $freeAccessActions = ['load-society', 'view-rate'];

    public function init() {
        parent::init();
        $this->folder_path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/eipldpu-files/';
    }

    /**
     * Lists all TblEiplMasterFileLog models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblEiplMasterFileLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblEiplMasterFileLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblEiplMasterFileLog();
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = $model->file_type;
            if ($model->validate()) {
                $dpu_key = Yii::$app->general->getforeignkey($model->unionDpuConfig, 'dpu_key');
                if (!empty($dpu_key)) {
                    if ($model->file_type == 'MEMBER') {
                        $dcs_code_multi = ',' . implode(',', $model->dcs_code_multi) . ',';
                        $result = \Yii::$app->db->createCommand("{CALL eipldpu_member_file(:dcs_code,:dpu_type)}")
                                ->bindValue(':dcs_code', $dcs_code_multi)
                                ->bindValue(':dpu_type', $model->dpu_type);
                        $data = $result->queryAll();
                        if (!empty($data)) {
                            $model->dcs_code = $dcs_code_multi;
                            $model->save();
                            if ($model->dpu_type == 8) {
                                $text = '';
                                $enc_text = '';
                                foreach ($data as $d) {
                                    $text .= $d['line_text'] . PHP_EOL;
                                    $enc_text .= \Yii::$app->EIPLSecurity->Encrypt($d['line_text'], $dpu_key, $model->dpu_type, ' ') . PHP_EOL;
                                }
                                Yii::$app->general->checkDirectory($this->folder_path);
                                $file = $this->folder_path . 'MEMBER_' . Yii::$app->session->get('UserCode') . '_' . date('Ymdhis') . '.txt';
                                $txt = fopen($file, "w");
                                ($model->is_encrypted == 1) ? fwrite($txt, $enc_text) : fwrite($txt, $text);
                                fclose($txt);
                                $this->DownloadFile($file, 'NAME.txt', filesize($file));
                            } elseif ($model->dpu_type == 32) {
                                $folder_name = 'MEMBER_' . Yii::$app->session->get('UserCode') . '_' . date('Ymdhis');
                                $folder_path = $this->folder_path . $folder_name . '/';
                                Yii::$app->general->CreateDirectory($folder_path);
                                $text = '';
                                $enc_text = '';
                                foreach ($data as $d) {
                                    if ($d['is_new_file'] == 1) {
                                        if (isset($txt)) {
                                            fclose($txt);
                                        }
                                        $file_path = $folder_path . $d['ref_code'] . '/';
                                        Yii::$app->general->checkDirectory($file_path);
                                        $file = $file_path . "member.txt";
                                        $txt = fopen($file, "w");
                                    }
                                    $text = $d['line_text'] . PHP_EOL;
                                    $enc_text = \Yii::$app->EIPLSecurity->Encrypt($d['line_text'], $dpu_key, $model->dpu_type) . PHP_EOL;
                                    ($model->is_encrypted == 1) ? fwrite($txt, $enc_text) : fwrite($txt, $text);
                                }
                                if (isset($txt)) {
                                    fclose($txt);
                                }
                                Yii::$app->general->ZipOperation($this->folder_path . $folder_name, TRUE, '', '', '*', 'zip', FALSE);
                                Yii::$app->general->RemoveDirectory($folder_path);
                                header('Content-Disposition: attachment; filename=' . $folder_name . '.zip');
                                readfile($this->folder_path . $folder_name . '.zip');
                                exit;
                            }
                        } else {
                            Yii::$app->display->message(TRUE, 'No Data Available.', 'info');
                        }
                    } else if ($model->file_type == 'RATE') {
                        $ratedetail = explode('-', $model->rate_id);
                        $model->rate_id = $ratedetail[0];
                        if ($model->dpu_type == 8) {
                            $result = \Yii::$app->db->createCommand("{CALL eipldpu_rate_file(:dpu_type,:rate_id,:milk_type_code)}")
                                    ->bindValue(':dpu_type', $model->dpu_type)
                                    ->bindValue(':rate_id', $model->rate_id)
                                    ->bindValue(':milk_type_code', 1);
                            $crate = $result->queryAll();
                            $result = \Yii::$app->db->createCommand("{CALL eipldpu_rate_file(:dpu_type,:rate_id,:milk_type_code)}")
                                    ->bindValue(':dpu_type', $model->dpu_type)
                                    ->bindValue(':rate_id', $model->rate_id)
                                    ->bindValue(':milk_type_code', 2);
                            $nrate = $result->queryAll();
                            $nrate = empty($nrate) ? $crate : $nrate;
                            $crate = empty($crate) ? $nrate : $crate;
                            if (!empty($crate)) {
                                $model->save();
                                $folder_name = 'RATE_' . Yii::$app->session->get('UserCode') . '_' . date('Ymdhis');
                                $folder_path = $this->folder_path . $folder_name . '/';
                                Yii::$app->general->CreateDirectory($folder_path);
                                $hline = str_pad('j000000' . $ratedetail[1], 32, 0, STR_PAD_RIGHT);
                                $file = $folder_path . "crate.txt";
                                $this->CreateRateFile8Bit($file, $crate, $model, $dpu_key, $hline);
                                $file = $folder_path . "nrate.txt";
                                $this->CreateRateFile8Bit($file, $nrate, $model, $dpu_key, $hline);
                                Yii::$app->general->ZipOperation($this->folder_path . $folder_name, TRUE, '', '', '*', 'zip', FALSE);
                                Yii::$app->general->RemoveDirectory($folder_path);
                                header('Content-Disposition: attachment; filename=' . $folder_name . '.zip');
                                readfile($this->folder_path . $folder_name . '.zip');
                                exit;
                            } else {
                                Yii::$app->display->message(TRUE, 'No Data Available.', 'info');
                            }
                        }
                    }
                } else {
                    Yii::$app->display->message(TRUE, 'DPU Key Not Available.', 'info');
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionLoadSociety() {
        $bmc_code = Yii::$app->request->post('bmc_code');
        $dpu_type = Yii::$app->request->post('dpu_type');
        $file_type = Yii::$app->request->post('file_type');
        $dcs_model = new TblDcs();
        if ($file_type == 'MEMBER') {
            $dcs = $dcs_model->getDpuTypeWiseDCS($bmc_code, $dpu_type, TRUE);
            $dcs_list = ArrayHelper::map($dcs, 'dcs_code', function($dcs) {
                        return $dcs->ref_code . '-' . $dcs->dcs_name . '(' . $dcs->dpu_type . ')';
                    });
        } else {
            $dcs_model->dcs_code = $bmc_code;
            $dcs_list = $dcs_model->tblDcs->dpu_type;
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $dcs_list]);
    }

    public function actionViewRate() {
        $rate_id = explode('-', Yii::$app->request->post('rate_id'));
        $url = Url::to(['/dcsoperation/tbl-purchase-rate-details/rate-chart', 'id' => $rate_id[0], 'milk_type' => 1, 'rate_class' => 0, 'milk_quality' => 1]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $url]);
    }

    /**
     * Finds the TblEiplMasterFileLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblEiplMasterFileLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEiplMasterFileLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function CreateRateFile8Bit($file, $ratedata, $model, $dpu_key, $first_line) {
        $txt = fopen($file, "w");
        ($model->is_encrypted == 1) ? fwrite($txt, \Yii::$app->EIPLSecurity->Encrypt($first_line, $dpu_key, $model->dpu_type) . PHP_EOL) : fwrite($txt, $first_line . PHP_EOL);
        foreach ($ratedata as $r) {
            $snf = substr(str_replace('.', '', '0' . number_format($r['snf'], 1)), -3);
            $fx = 'j';
            $line = '';
            $fatrtpl = explode(',', $r['fatrtpl']);
            $totalcnt = count($fatrtpl);
            $i = 0;
            $cnt = 1;
            foreach ($fatrtpl as $d) {
                $fr = explode('=', $d);
                $ft = $fr[0];
                $rt = $fr[1];
                $fat = substr(str_replace('.', '', '0' . number_format($ft, 1)), -3);
                $rtpl = substr(str_replace('.', '', '00' . number_format($rt, 2)), -4);
                $line .= $snf . $fat . $rtpl;
                if ($i == 2 || $cnt == $totalcnt) {
                    $line = 'j' . $line . '0';
                    $line = str_pad($line, 32, 0, STR_PAD_RIGHT);
                    ($model->is_encrypted == 1) ? fwrite($txt, \Yii::$app->EIPLSecurity->Encrypt($line, $dpu_key, $model->dpu_type) . PHP_EOL) : fwrite($txt, $line . PHP_EOL);
                    $i = -1;
                    $line = '';
                }
                $i++;
                $cnt++;
            }
        }
        fclose($txt);
    }

    public function DownloadFile($file, $fname, $fsize) {
        ob_clean();
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $fname);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $fsize);
        header("Content-Type: text/plain");
        readfile($file);
        exit;
    }

}
