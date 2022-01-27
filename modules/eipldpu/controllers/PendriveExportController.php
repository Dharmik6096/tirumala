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

/**
 * PendriveExportController implements the CRUD actions for TblEiplMasterFileLog model.
 */
class PendriveExportController extends \app\controllers\ChildController {

    protected $folder_path;
    public $freeAccessActions = ['load-society'];

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
                                    $enc_text .= \Yii::$app->EIPLSecurity->Encrypt($d['line_text'], $dpu_key, $model->dpu_type) . PHP_EOL;
                                }
                                $file = "NAME.txt";
                                $txt = fopen($file, "w");
                                fwrite($txt, $text);
                                //fwrite($txt, $enc_text);
                                fclose($txt);

                                header('Content-Description: File Transfer');
                                header('Content-Disposition: attachment; filename=' . basename($file));
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($file));
                                header("Content-Type: text/plain");
                                readfile($file);
                                exit;
                            } elseif ($model->dpu_type == 32) {
                                $folder_name = Yii::$app->session->get('UserCode') . '_' . date('Ymdhis');
                                $folder_path = $this->folder_path . $folder_name . '/';
                                if (!is_dir($folder_path)) {
                                    $oldmask = umask(0);
                                    mkdir($folder_path, 0777, TRUE);
                                    umask($oldmask);
                                } else {
                                    $files = glob($folder_path . '*'); // get all file names
                                    foreach ($files as $file) { // iterate files
                                        if (is_file($file))
                                            unlink($file); // delete file
                                    }
                                }
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
                                    fwrite($txt, $text);
                                    //fwrite($txt, $enc_text);
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

}
