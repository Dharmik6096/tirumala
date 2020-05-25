<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblConfigMapping;
use app\modules\configuration\models\TblConfigMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\configuration\models\TblConfigSearch;

/**
 * TblConfigMappingController implements the CRUD actions for TblConfigMapping model.
 */
class TblConfigMappingController extends \app\controllers\ChildController {

    /**
     * Lists all TblConfigMapping models.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblConfigMapping();
        $this->model->union_code = $id;
        $this->model->load(Yii::$app->request->queryParams);
        $searchModel = new TblConfigSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->config_type = 'CONTROL';
        $searchModel->union_code = $id;
        $dataProvider = $searchModel->mappingsearch(Yii::$app->request->queryParams);
        $this->model->org_type = $searchModel->config_for;
        $this->model->process_name = $searchModel->process_name;
        $selectedArray = [];
        $selectedArray = $this->model->getExistingMapping();
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postArray = !empty($data['configCodes']) ? $data['configCodes'] : [];
         
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'selectedArray' => $selectedArray
        ]);
    }

    /**
     * Finds the TblConfigMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblConfigMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblConfigMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
