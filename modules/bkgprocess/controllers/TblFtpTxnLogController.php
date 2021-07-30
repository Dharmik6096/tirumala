<?php

namespace app\modules\bkgprocess\controllers;

use Yii;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use app\modules\bkgprocess\models\TblFtpTxnLogSearch;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use app\modules\bkgprocess\models\TblFileCreator;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblFtpTxnLogController implements the CRUD actions for TblFtpTxnLog model.
 */
class TblFtpTxnLogController extends \app\controllers\ChildController {

    public $freeAccessActions = ['import-file'];

    /**
     * Lists all TblFtpTxnLog models.
     * @return mixed
     */
    public function actionList() {
        $searchModel = new TblFtpTxnLogSearch();
        $dataProvider = $searchModel->listsearch(Yii::$app->request->queryParams);

        return $this->render('list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex() {
        $searchModel = new TblFtpTxnLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (isset(\Yii::$app->request->post()['selection']) && !empty(\Yii::$app->request->post()['selection'])) {
            $this->reprocess(\Yii::$app->request->post()['selection']);
            return $this->redirect(['index']);
        }
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBiplPendriveCollection() {
        $model = new TblFtpTxnLog();
        if ($model->load(Yii::$app->request->post())) {
            $error_file = [];
            $path = Yii::$app->basePath . '/web/import/collection/';
            $CollectionData = \Yii::$app->params['biplDirPath'] . 'PORTALPDFILES/';
            if (Yii::$app->general->checkDirectory($CollectionData)) {
                $status = 'success';
                $files = array_filter(explode(',', $model->file_name));
                $cnt = 0;
                foreach ($files as $key => $value) {
                    try {
                        $old_path = $path . $value;
                        $file_path = $CollectionData . $value;
                        if (copy($old_path, $file_path)) {
                            $ftp_txn_model = new TblFtpTxnLog();
                            $ftp_txn_model->txn_type = 'BIPL';
                            $ftp_txn_model->local_path = $file_path;
                            $ftp_txn_model->file_path = NULL;
                            $ftp_txn_model->module_name = 'TblMilkCollection';
                            $ftp_txn_model->union_code = $model->union_code;
                            $ftp_txn_model->total_count = 0;
                            $ftp_txn_model->success_count = 0;
                            $ftp_txn_model->error_count = 0;
                            $ftp_txn_model->file_name = $value;
                            $ftp_txn_model->file_status = 1;
                            $ftp_txn_model->status = 0;
                            if ($ftp_txn_model->save(FALSE)) {
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
            return $this->render('bipl-pendrive-collection', ['model' => $model]);
        }
    }

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/import/collection/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = date('YmdHis') . rand(1000, 9999) . $file->name;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'filename' => $name, 'msg' => $name, 'datefile' => $file->name];
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

    public function reprocess($ids) {
        $master = [];
        foreach ($ids as $id) {
            $model = TblFtpTxnLog::findOne($id);
            $model->status = 4;
            $file_create = new TblFileCreator();
            $file_create->attributes = $model->creatorId->attributes;
            $file_create->activity_type = 'MANUAL';
            $file_create->file_status = $file_create->status = 0;
            $file_create->updated_at = $file_create->updated_by = NULL;
            $master[] = $model;
            $master[] = $file_create;
        }
        $transaction = $this->generalModel->saveTransaction($master, ['File Re-Sent', 'edit']);
    }

    /**
     * Finds the TblFtpTxnLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblFtpTxnLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblFtpTxnLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
