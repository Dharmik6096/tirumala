<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblAppLockConfig;
use app\modules\configuration\models\TblAppLockConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\configuration\models\TblAppLockConfigResult;
use app\modules\configuration\models\TblAppLockConfigResultHistory;

/**
 * TblAppLockConfigController implements the CRUD actions for TblAppLockConfig model.
 */
class TblAppLockConfigController extends \app\controllers\ChildController {

    /**
     * Lists all TblAppLockConfig models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAppLockConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAppLockConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAppLockConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $configModel = new TblAppLockConfig();
        $masterData = $configModel->getConfigData();
        $model = [];
        foreach ($masterData as $m) {
            $saveModel = new TblAppLockConfigResult();
            $saveModel->config_code = $m->config_code;
            $saveModel->device_id = $id;
            $saveModelData = $saveModel->getExistConfig();
            if (!empty($saveModelData)) {
                $model[] = $saveModelData;
            } else {
                $model[] = $saveModel;
            }
        }
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postConfigData = $data['TblAppLockConfigResult'];

            $master = [];
            $childModel = [];

            foreach ($postConfigData as $value) {

                $saveModel = new TblAppLockConfigResult();
                if (!empty($value['config_result_code'])) {
                    $trnsModel = $saveModel->findOne($value['config_result_code']);
                    if (!empty($trnsModel)) {
                        $historyModel = new TblAppLockConfigResultHistory();
                        Yii::$app->operation->history($trnsModel, $historyModel, UPDATE);
                        $childModel[] = $historyModel;
                        $saveModel = $trnsModel;
                    }
                }

                $saveModel->device_id = $id;
                $saveModel->config_code = $value['config_code'];
                $saveModel->config_name = $saveModel->configCode->config_name;
                $saveModel->config_key = $saveModel->configCode->config_key;
                $saveModel->config_detail_key = empty($value['config_detail_key']) ? '0' : $value['config_detail_key'];
                $dropName = Yii::$app->general->getforeignkey($saveModel->configDetailCode, 'config_detail');
                $droptKey = Yii::$app->general->getforeignkey($saveModel->configDetailCode, 'config_detail_code');
                $textKey = Yii::$app->general->getforeignkey($saveModel->configDetail, 'config_detail_code');
                $saveModel->config_detail_code = !empty($droptKey) ? $droptKey : $textKey;
                $saveModel->config_detail = !empty($droptKey) ? $dropName : $value['config_type_code'];
                $master[] = $saveModel;
                
            }

            if ($saveModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($master, $childModel, ['Config', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['/globalmaster/tbl-device-master/index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($saveModel));
            }
        }
        return $this->render('create', [
                    'masterData' => $masterData,
                    'saveModel' => $saveModel,
                    'model' => $model,
        ]);
    }

    /**
     * Finds the TblAppLockConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAppLockConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAppLockConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
