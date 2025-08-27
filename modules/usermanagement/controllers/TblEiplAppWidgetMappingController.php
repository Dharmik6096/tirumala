<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\models\TblEiplAppWidgetMapping;
use app\modules\usermanagement\models\TblEiplAppWidgetMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\usermanagement\models\TblEiplAppWidgetSearch;
use app\modules\usermanagement\models\TblEiplAppWidgetMappingHistory;

/**
 * TblEiplAppWidgetMappingController implements the CRUD actions for TblEiplAppWidgetMapping model.
 */
class TblEiplAppWidgetMappingController extends \app\controllers\ChildController {

    public function actionAppWidgetMapping() {
        $this->model = new TblEiplAppWidgetMapping();
        $this->model->load(Yii::$app->request->queryParams);
        $searchModel = new TblEiplAppWidgetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderOther = $searchModel->search(Yii::$app->request->queryParams, 2);
        $selectedArray = [];
//        $this->model->department = $this->model->login_type == 'MEMBER' ? 'MEMBER' : $this->model->department;
        $selectedArray = $this->model->getWidgets();

        if (Yii::$app->request->post()) {
//          if (isset($_REQUEST['selection'])) {
            $postArray = [];
            $postArray = Yii::$app->request->post('selection');
//           if (!empty($postArray)) {
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
                    $model = new TblEiplAppWidgetMapping();
                    $model->widget_id = (string) $revoke_widget;
                    $model->app_type = $this->model->app_type;
                    $model->login_type = $this->model->login_type;
                    $model->department = $this->model->department;
                    $model->union_code = $this->model->union_code;
                    $record = $model->getExistMappedWidgets();
                    $historyModel = new TblEiplAppWidgetMappingHistory();
                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }

            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_widget) {
                    $model = new TblEiplAppWidgetMapping();
                    $model->widget_id = $Assign_widget;
                    $model->app_type = $this->model->app_type;
                    $model->login_type = $this->model->login_type;
                    $model->department = $this->model->department;
                    $model->union_code = $this->model->union_code;
                    $master[] = $model;
                    $auto_inc++;
                }
            }
           
            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Mobile Dashboard Permission', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];
            if ($transaction == 'customRedirect') {
                return $this->render('widget_mapping', [
                            'model' => $this->model,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'dataProviderOther' => $dataProviderOther,
                            'selectedArray' => $selectedArray
                ]);
            }
//                }
//            }
        }
        return $this->render('widget_mapping', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderOther' => $dataProviderOther,
                    'selectedArray' => $selectedArray
        ]);
    }

    /**
     * Finds the TblEiplAppWidgetMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblEiplAppWidgetMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEiplAppWidgetMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
