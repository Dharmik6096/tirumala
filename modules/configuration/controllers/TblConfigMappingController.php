<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblConfigMapping;
use app\modules\configuration\models\TblConfigMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\configuration\models\TblConfigSearch;
use app\modules\configuration\models\TblConfigMappingHistory;

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
            $master = [];
            $auto_inc = 1;
            $newAssignments = [];
            if (!empty($postArray)) {
                $newAssignments = $postArray;
            }
            $oldAssignments = [];
            if (!empty($selectedArray)) {
                $oldAssignments = array_keys($selectedArray);
            }

            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_values(array_diff($oldAssignments, $newAssignments));
            $delete = [];
            $code = $this->model->org_type == 'MCC' ? $this->model->mcc_plant_code : $this->model->bmc_code;
            if (!empty($toRevoke)) {
                foreach ($toRevoke as $revoke_widget) {
                    $model = new TblConfigMapping();
                    $model->attributes = $this->model->attributes;
                    $model->config_code = $revoke_widget;
                    $model->union_code = $id;
                    $model->org_type = $this->model->org_type;
                    $model->org_code = $code;
                    $record = $model->getExistMappedControl();
                    $historyModel = new TblConfigMappingHistory();
                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }

            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_widget) {
                    $model = new TblConfigMapping();
                    $model->attributes = $this->model->attributes;
                    $model->config_code = $Assign_widget;
                    $model->union_code = $id;
                    $model->org_type = $this->model->org_type;
                    $model->org_code = $code;
                    $model->scenario = 'savemapping';
                    $master[] = $model;
                    $auto_inc++;
                }
            }

            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Control Mapping', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];
            if ($transaction == 'customRedirect') {
                return $this->render('create', [
                            'model' => $this->model,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'selectedArray' => $selectedArray
                ]);
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'selectedArray' => $selectedArray
        ]);
    }

    public function actionIndex() {
        $searchModel = new TblConfigMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMappingView($union, $plant, $mcc, $bmc, $for, $process) {
        $searchModel = new TblConfigMappingSearch();
        $searchModel->union_code = $union;
        $searchModel->plant_code = $plant;
        $searchModel->mcc_plant_code = $mcc;
        $searchModel->bmc_code = $bmc;
        $searchModel->config_for = $for;
        $searchModel->process_name = $process;
        $dataProvider = $searchModel->detailsearch(Yii::$app->request->queryParams);

        return $this->render('_mapping_view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
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
