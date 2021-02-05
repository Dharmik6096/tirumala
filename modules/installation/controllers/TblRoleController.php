<?php

namespace app\modules\installation\controllers;

use Yii;
use app\modules\installation\models\TblRole;
use app\modules\installation\models\TblRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\installation\models\TblAction;
use app\modules\installation\models\TblRoleActionMapping;

/**
 * TblRoleController implements the CRUD actions for TblRole model.
 */
class TblRoleController extends \app\controllers\ChildController {

    /**
     * Lists all TblRole models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblRole model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblRole();
        $this->viewFile = "create";
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Role', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);


        $this->viewFile = "update";
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Role', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAppMenuMapping($id) {
        $this->model = new TblAction();
        $menuArray = [];
        $menuArray = $this->model->getActionDetail();
        $mappingModel = new TblRoleActionMapping();
        $selectedArray = [];
        $selectedArray = $mappingModel->getExistMapingMenu($id);
        if (Yii::$app->request->post()) {
            $postArray = [];
            $postArray = Yii::$app->request->post('child_routes');
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
                foreach ($toRevoke as $revoke_key) {
                    $model = new TblRoleActionMapping();
                    $model->action_code = (string) $revoke_key;
                    $model->role_code = $id;
                    $record = $model->getExistMappedmenus();
//                    $historyModel = new TblRoleActionMappingHistory();
//                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
//                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }

            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_key) {
                    $model = new TblRoleActionMapping();
                    $model->action_code = $Assign_key;
                    $model->role_code = $id;
                    $master[] = $model;
                    $auto_inc++;
                }
            }

            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Role Menu Mapping', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];
            if ($transaction == 'customRedirect') {
                return $this->render('_action_map', [
                            'model' => $this->model,
                            'mappingModel' => $mappingModel,
                            'selectedArray' => $selectedArray,
                            'menuArray' => $menuArray,
                            'id' => $id
                ]);
            }
        }
        return $this->render('_action_map', [
                    'model' => $this->model,
                    'mappingModel' => $mappingModel,
                    'selectedArray' => $selectedArray,
                    'menuArray' => $menuArray,
                    'id' => $id
        ]);
    }

}
