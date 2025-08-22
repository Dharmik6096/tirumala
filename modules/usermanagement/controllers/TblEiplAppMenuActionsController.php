<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\models\TblEiplAppMenuActions;
use app\modules\usermanagement\models\TblEiplAppMenuActionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\usermanagement\models\TblEiplAppMenuActionsMapping;

/**
 * TblEiplAppMenuActionsController implements the CRUD actions for TblEiplAppMenuActions model.
 */
class TblEiplAppMenuActionsController extends \app\controllers\ChildController {

    public function actionAppMenuMapping() {
        $this->model = new TblEiplAppMenuActions();
        $menuArray = [];
        $menuArray = $this->model->getActionDetail();
        $mappingModel = new TblEiplAppMenuActionsMapping();
        $mappingModel->load(Yii::$app->request->queryParams);
        $selectedArray = [];
//        $mappingModel->department = $mappingModel->login_type == 'MEMBER' ? 'MEMBER' : $mappingModel->department;
        $selectedArray = $mappingModel->getExistMapingMenu();

        if (Yii::$app->request->post()) {
            $postArray = [];
            $postArray = Yii::$app->request->post('child_routes');
            $loginType = Yii::$app->request->post('login_type');
            $unionCode = Yii::$app->request->post('union_code');
            $department = !empty(Yii::$app->request->post('department')) ? Yii::$app->request->post('department') : NULL;
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
                    $model = new TblEiplAppMenuActionsMapping();
                    $model->action_code = (string) $revoke_widget;
                    $model->login_type = $loginType;
                    $model->department = $department;
                    $model->union_code = $unionCode;
                    $record = $model->getExistMappedmenus();
//                    $historyModel = new TblEiplAppWidgetMappingHistory();
//                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
//                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }

            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_widget) {
                    $model = new TblEiplAppMenuActionsMapping();
                    $model->action_code = $Assign_widget;
                    $model->login_type = $loginType;
                    $model->department = $department;
                    $model->union_code = $unionCode;
                    $master[] = $model;
                    $auto_inc++;
                }
            }

            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Mobile Menu Permission', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];
            if ($transaction == 'customRedirect') {
                return $this->render('create', [
                            'model' => $this->model,
                            'mappingModel' => $mappingModel,
                            'selectedArray' => $selectedArray,
                            'menuArray' => $menuArray
                ]);
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'mappingModel' => $mappingModel,
                    'selectedArray' => $selectedArray,
                    'menuArray' => $menuArray
        ]);
    }

    /**
     * Finds the TblEiplAppMenuActions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblEiplAppMenuActions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEiplAppMenuActions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
