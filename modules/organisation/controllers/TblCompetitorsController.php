<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblCompetitors;
use app\modules\organisation\models\TblCompetitorsSearch;
use yii\web\NotFoundHttpException;
use app\modules\organisation\models\TblCompetitorsApplicability;
use app\modules\organisation\models\TblCompetitorsApplicabilityHistory;
use app\modules\organisation\models\TblDcs;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * TblCompetitorsController implements the CRUD actions for TblCompetitors model.
 */
class TblCompetitorsController extends \app\controllers\ChildController {

    /**
     * Lists all TblCompetitors models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCompetitorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCompetitorMapping($id) {
        $this->model = $this->findModel($id);
        $model = new TblCompetitorsApplicability();
        $model->competitor_id = $id;
        $model->load(Yii::$app->request->get());
        $existDataModels = $model->getExistData();
        $existData = ArrayHelper::map($existDataModels, 'customer_code', 'customer_code');
        $dcsModel = new TblDcs();
        $dcsModel->attributes = $model->attributes;
        $dcsModelData = $dcsModel->getRecords();
        $saveModel = [];
        foreach ($dcsModelData as $dcs) {
            $m = new TblCompetitorsApplicability();
            $m->competitor_id = $id;
            $m->union_code = $dcs->union_code;
            $m->plant_code = $dcs->plant_code;
            $m->mcc_plant_code = $dcs->mcc_plant_code;
            $m->bmc_code = $dcs->bmc_code;
            $m->customer_code = $dcs->dcs_code;
            $m->customer_type = 'DCS';
            $saveModel[] = $m;
        }
        if (Model::loadMultiple($saveModel, Yii::$app->request->post()) && Model::validateMultiple($saveModel)) {
            $existingCodes = ArrayHelper::getColumn($existDataModels, 'customer_code');
            $selectedCodes = ArrayHelper::getColumn($saveModel, 'customer_code');
            $codesToDelete = array_diff($existingCodes, $selectedCodes);
            $recordsToDelete = [];
            $master = [];
            foreach ($existDataModels as $item) {
                if (in_array($item->customer_code, $codesToDelete)) {
                    $historyModel = new TblCompetitorsApplicabilityHistory();
                    Yii::$app->operation->history($item, $historyModel, 'DELETE');
                    $recordsToDelete[] = $item;
                    $master[] = $historyModel;
                }
            }
            foreach ($saveModel as $m) {
                if (!empty($m->customer_code) && !isset($existData[$m->customer_code])) {
                    $master[] = $m;
                }
            }
            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $recordsToDelete, ['Competitor Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
                    'competitor' => $this->model,
                    'searchModel' => $model,
                    'model' => $dcsModelData,
                    'saveModel' => $saveModel,
                    'dcsModelData' => $dcsModelData,
                    'existData' => $existData,
        ]);
    }

    /**
     * Finds the TblCompetitors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblCompetitors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCompetitors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
