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
        $this->model->load(Yii::$app->request->queryParams);
        $searchModel = new TblConfigSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->config_type = 'CONTROL';
        $searchModel->union_code = $id;

        $dataProvider = $searchModel->mappingsearch(Yii::$app->request->queryParams);
        $this->model->config_for = $searchModel->config_for;
        $this->model->process_name = $searchModel->process_name;
        $selectedArray = [];

        $selectedArray = $this->model->getExistingMapping();

        if (Yii::$app->request->post()) {
            $postArray = [];
            $postArray = Yii::$app->request->post('selection');
            $detail = Yii::$app->request->post()['TblConfigMapping'];

            if ($this->model->config_for == 'BMC') {
                $this->model->scenario = 'bmc';
                $code = $detail['bmc_code'];
            } else {
                $code = $detail['mcc_plant_code'];
            }
           
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
            if (!empty($toRevoke)) {
                foreach ($toRevoke as $revoke_widget) {
                    $model = new TblConfigMapping();
                    $model->config_code = $revoke_widget;
                    $model->union_code = $id;
                    $model->org_type = $this->model->config_for;
                    $model->org_code = $code;
                    $record = $model->getExistingMapping();
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
                    $model->config_code = $Assign_widget;
                    $model->union_code = $id;
                    $model->org_type = $this->model->config_for;
                    $model->org_code = $code;
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
