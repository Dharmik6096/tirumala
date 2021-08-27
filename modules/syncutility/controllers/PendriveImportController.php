<?php

namespace app\modules\syncutility\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\EiplPacketFileLog;
use yii\web\Response;
use yii\helpers\Json;
use app\models\EiplPacketFileLogSearch;
use app\modules\eipldpu\models\TblEiplPacketProcessSearch;

class PendriveImportController extends \app\controllers\ChildController {

    public $freeAccessActions = ['import-file'];

    public function actionIndex() {
        $searchModel = new EiplPacketFileLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
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
                        $file->dcs_code = explode('_', $value)[0];
                        $file->file_path = str_replace('\\', '/', $file_path);
                        $file->file_name = $value;
                        $file->file_status = 0;
                        $file->source_type = 2;
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

    public function actionView($id) {
        $searchModel = new TblEiplPacketProcessSearch();
        $searchModel->file_name = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new EiplPacketFileLog();
        return $this->render('view', [
                    'model' => $model->findOne($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

}
