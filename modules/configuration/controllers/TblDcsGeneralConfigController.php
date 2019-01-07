<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblDcsGeneralConfig;
use app\modules\configuration\models\TblDcsGeneralConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\ChildController;
use app\modules\dcsoperation\models\TblQualityParam;
use yii\web\Response;
use app\modules\configuration\models\TblDcsGeneralConfigHistory;
use yii\widgets\ActiveForm;

/**
 * TblDcsGeneralConfigController implements the CRUD actions for TblDcsGeneralConfig model.
 */
class TblDcsGeneralConfigController extends ChildController {

    /**
     * Finds the TblDcsGeneralConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDcsGeneralConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsGeneralConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionShareManagement() {
        $model = new TblDcsGeneralConfig();
        $union_code = Yii::$app->session->get('organizations_code');
        $union = explode(',', $union_code);
        if (count($union) == 1) {
            $model->union_code = $union[0];
            $data = $model->getData();
            if (!empty($data)) {
                $model = $this->findModel($data->code);
            }
        }
        $model->scenario = 'shares';
        if (Yii::$app->request->post()) {
            if (!empty($data)) {
                $historyModel = new TblDcsGeneralConfigHistory();
                Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            } else {
                $historyModel = [];
            }
            $model->load(Yii::$app->request->post());
            if ($model->validate() && empty($model->getErrors())) {
                $master_model[] = $model;
                if (!empty($historyModel)) {
                    $master_model[] = $historyModel;
                }

                $this->generalModel->saveTransaction($master_model, ['Share Management', 'edit']);
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['/configuration/tbl-milk-collection-config/tabs', 'tab' => 'w0-tab2']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return json_encode($this->renderAjax('share_management', [
                        'model' => $model,
            ]));
        }
    }

    public function actionProductSalePurchase() {
        $model = new TblDcsGeneralConfig();
        $union_code = Yii::$app->session->get('organizations_code');
        $union = explode(',', $union_code);
        if (count($union) == 1) {
            $model->union_code = $union[0];
            $data = $model->getData();
            if (!empty($data)) {
                $model = $this->findModel($data->code);
            }
        }
        $model->scenario = 'productSale';

        if (Yii::$app->request->post()) {
            if (!empty($data)) {
                $historyModel = new TblDcsGeneralConfigHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
            } else {
                $historyModel = [];
            }
            $model->load(Yii::$app->request->post());

            if ($model->validate() && empty($model->getErrors())) {
                $master_model[] = $model;
                if (!empty($historyModel)) {
                    $master_model[] = $historyModel;
                }

                $this->generalModel->saveTransaction($master_model, ['Product Sale/Purchase', 'edit']);
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['/configuration/tbl-milk-collection-config/tabs', 'tab' => 'w0-tab3']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return json_encode($this->renderAjax('product_sale_purchase', [
                        'model' => $model,
            ]));
        }
    }

    public function actionElectionConfig() {
        $model = new TblDcsGeneralConfig();
        $union_code = Yii::$app->session->get('organizations_code');
        $union = explode(',', $union_code);
        if (count($union) == 1) {
            $model->union_code = $union[0];
            $data = $model->getData();
            if (!empty($data)) {
                $model = $this->findModel($data->code);
            }
        }
        $model->scenario = 'electionConfig';

        if (Yii::$app->request->post()) {
            if (!empty($data)) {
                $historyModel = new TblDcsGeneralConfigHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
            } else {
                $historyModel = [];
            }
            $model->load(Yii::$app->request->post());

            if ($model->validate() && empty($model->getErrors())) {

                $master_model[] = $model;
                if (!empty($historyModel)) {
                    $master_model[] = $historyModel;
                }

                $this->generalModel->saveTransaction($master_model, ['Election Config', 'edit']);
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['/configuration/tbl-milk-collection-config/tabs', 'tab' => 'w0-tab4']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return json_encode($this->renderAjax('election_config', [
                        'model' => $model,
            ]));
        }
    }

    public function actionBackup() {
        $model = new TblDcsGeneralConfig();
        $union_code = Yii::$app->session->get('organizations_code');
        $union = explode(',', $union_code);
        if (count($union) == 1) {
            $model->union_code = $union[0];
            $data = $model->getData();
            if (!empty($data)) {
                $model = $this->findModel($data->code);
            }
        }
        $model->scenario = 'backup';

        if (Yii::$app->request->post()) {
            if (!empty($data)) {
                $historyModel = new TblDcsGeneralConfigHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
            } else {
                $historyModel = [];
            }
            $model->load(Yii::$app->request->post());

            if ($model->validate() && empty($model->getErrors())) {
                $master_model[] = $model;
                if (!empty($historyModel)) {
                    $master_model[] = $historyModel;
                }

                $this->generalModel->saveTransaction($master_model, ['Backup', 'edit']);
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['/configuration/tbl-milk-collection-config/tabs', 'tab' => 'w0-tab5']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return json_encode($this->renderAjax('backup', [
                        'model' => $model,
            ]));
        }
    }

    public function actionDispatchAndReceipt() {
        $params_model = new TblQualityParam();
//        $params = $params_model->getParams();
        $model = new TblDcsGeneralConfig();
        $union_code = Yii::$app->session->get('organizations_code');
        $union = explode(',', $union_code);
        if (count($union) == 1) {
            $model->union_code = $union[0];
            $data = $model->getData();
            if (!empty($data)) {
                $model = $this->findModel($data->code);
            }
        }
        $model->scenario = 'disprecp';
        if (Yii::$app->request->post()) {
            if (!empty($data)) {
                $historyModel = new TblDcsGeneralConfigHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
            } else {
                $historyModel = [];
            }
            $model->load(Yii::$app->request->post());

            if ($model->validate() && empty($model->getErrors())) {

                $master_model[] = $model;
                if (!empty($historyModel)) {
                    $master_model[] = $historyModel;
                }

                $this->generalModel->saveTransaction($master_model, [], ['Dispatch and Receipt', 'edit']);
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['/configuration/tbl-milk-collection-config/tabs', 'tab' => 'w0-tab0']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return json_encode($this->renderAjax('dispatch_and_receipt', [
                        'model' => $model,
            ]));
        }
    }

}
