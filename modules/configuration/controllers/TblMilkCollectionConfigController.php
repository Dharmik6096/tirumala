<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblMilkCollectionConfig;
use app\modules\configuration\models\TblMilkCollectionConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblQualityParam;
use app\modules\configuration\models\TblMilkCollectionConfigHistory;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * TblMilkCollectionConfigController implements the CRUD actions for TblMilkCollectionConfig model.
 */
class TblMilkCollectionConfigController extends \app\controllers\ChildController {

    /**
     * Finds the TblMilkCollectionConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollectionConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollectionConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMilkCollection() {
        $params_model = new TblQualityParam();
//        $params = $params_model->getQualityParam();
        $model = new TblMilkCollectionConfig();
        $union_code = Yii::$app->session->get('organizations_code');
        $union = explode(',', $union_code);
        if (count($union) == 1) {
            $model->union_code = $union[0];
            $data = $model->getData();
            if (!empty($data)) {
                $model = $this->findModel($data->code);
            }
        }
        $model->scenario = 'milkCollection';

        if (Yii::$app->request->post()) {
            if (!empty($data)) {
                $historyModel = new TblMilkCollectionConfigHistory();
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
                $this->generalModel->saveTransaction($master_model, [], ['Milk Collection', 'edit']);

                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['tabs', 'tab' => 'w0-tab1']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return json_encode($this->renderAjax('milk_collection', [
                        'model' => $model,
            ]));
        }
    }

    public function actionTabs() {
        $params_model = new TblQualityParam();
        $params = $params_model->getQualityParam();

        $model = new TblMilkCollectionConfig();
        if ($model->load(Yii::$app->request->post())) {
            $model->validate();
        }
        return $this->render('tabs', ['model' => $model, 'params' => $params,]);
    }

}
