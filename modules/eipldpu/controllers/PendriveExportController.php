<?php

namespace app\modules\eipldpu\controllers;

use Yii;
use app\modules\eipldpu\models\TblEiplMasterFileLog;
use app\modules\eipldpu\models\TblEiplMasterFileLogSearch;
use yii\web\NotFoundHttpException;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;

/**
 * PendriveExportController implements the CRUD actions for TblEiplMasterFileLog model.
 */
class PendriveExportController extends \app\controllers\ChildController {

    protected $folder_path;

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
            $dcs_detail = TblDcs::find()->select(['dcs_code', 'ref_code', 'dpu_type', 'mfile_digit'])->where(['dcs_code' => $model->dcs_code])->asArray()->all();
            if ($model->process_type == 'MEMBER') {
                $modelData = [];
                $dpu_32 = [];
                $dpu_8_4 = [];
                $dpu_8_3 = [];
                foreach ($dcs_detail as $dcs) {
                    if ($dcs['dpu_type'] == 32) {
                        $dpu_32[] = $dcs;
                    } else if ($dcs['dpu_type'] == 8) {
                        if ($dcs['mfile_digit'] == 4) {
                            $dpu_8_4[] = $dcs;
                        } else {
                            $dpu_8_3[] = $dcs;
                        }
                    }
                }
                if (!empty($dpu_8_4)) {
                    $dcs_str = implode(',', ArrayHelper::getColumn($dpu_8_4, 'dcs_code'));
                    $ref_str = implode(',', ArrayHelper::getColumn($dpu_8_4, 'ref_code'));
                    $text = '';
                    foreach ($dpu_8_4 as $data) {
                        $text.='F' . str_pad($data['ref_code'], 20, '0', STR_PAD_LEFT) . PHP_EOL;
                        $mresult = \Yii::$app->db->createCommand("{CALL sp_eipldpu_member_file(:dcs_code,:file_type)}")
                                ->bindValue(':dcs_code', $data['dcs_code'])
                                ->bindValue(':file_type', 4);
                        $members = $mresult->queryAll();
                        $members = array_column($members, 'MemberLine');
                        foreach ($members as $m) {
                            $text .= $m . PHP_EOL;
                        }
                    }
                    $path = $this->folder_path . '4DIGIT/';
                    if (\Yii::$app->general->checkDirectory($path)) {
                        $fileName = date('Y-m-d-His') . '.txt';
                        $filePath = $path . "/" . $fileName;
                        $namefile = fopen($filePath, "w");
                        fwrite($namefile, $text);
                        fclose($namefile);
                        $log_model = new TblEiplMasterFileLog();
                        $log_model->attributes = $model->attributes;
                        $log_model->dcs_code = $dcs_str;
                        $log_model->ref_code = $ref_str;
                        $log_model->dpu_type = 8;
                        $log_model->file_type = '4-Digit';
                        $log_model->file_path = $filePath;
                        $log_model->file_name = $fileName;
                        $log_model->file_name_download = 'Name.txt';
                        $log_model->save();
                    }
                }
                if (!empty($dpu_8_3)) {
                    $dcs_str = implode(',', ArrayHelper::getColumn($dpu_8_3, 'dcs_code'));
                    $ref_str = implode(',', ArrayHelper::getColumn($dpu_8_3, 'ref_code'));
                    $text = '';
                    foreach ($dpu_8_3 as $data) {
                        $text.='F' . str_pad($data['ref_code'], 20, '0', STR_PAD_LEFT) . PHP_EOL;
                        $mresult = \Yii::$app->db->createCommand("{CALL sp_eipldpu_member_file(:dcs_code,:file_type)}")
                                ->bindValue(':dcs_code', $data['dcs_code'])
                                ->bindValue(':file_type', 3);
                        $members = $mresult->queryAll();
                        $members = array_column($members, 'MemberLine');
                        foreach ($members as $m) {
                            $text .= $m . PHP_EOL;
                        }
                    }
                    $path = $this->folder_path . '3DIGIT/';
                    if (\Yii::$app->general->checkDirectory($path)) {
                        $fileName = date('Y-m-d-His') . '.txt';
                        $filePath = $path . "/" . $fileName;
                        $namefile = fopen($filePath, "w");
                        fwrite($namefile, $text);
                        fclose($namefile);
                        $log_model = new TblEiplMasterFileLog();
                        $log_model->attributes = $model->attributes;
                        $log_model->dcs_code = $dcs_str;
                        $log_model->ref_code = $ref_str;
                        $log_model->dpu_type = 8;
                        $log_model->file_type = '3-Digit';
                        $log_model->file_path = $filePath;
                        $log_model->file_name = $fileName;
                        $log_model->file_name_download = 'Name.txt';
                        $log_model->save();
                    }
                }
                if (!empty($dpu_32)) {
                    $dcs_str = implode(',', ArrayHelper::getColumn($dpu_32, 'dcs_code'));
                    $ref_str = implode(',', ArrayHelper::getColumn($dpu_32, 'ref_code'));
                    foreach ($dpu_32 as $data) {
                        
                    }
                }
                var_dump($dcs_detail);
                die;
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
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
