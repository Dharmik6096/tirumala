<?php

namespace app\modules\general\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\general\models\TblDepartment;
use app\modules\general\models\TblDepartmentSearch;
use yii\web\NotFoundHttpException;

/**
 * TblDepartmentController implements the CRUD actions for TblDepartment model.
 */
class TblDepartmentController extends ChildController {

    /**
     * Lists all TblDepartment models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblDepartment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDepartment();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->department_id = strtolower(str_replace(' ', '_', $this->model->department));
            $this->model->seq_no = $this->model->find()->andWhere(['not in', 'department_id', ['farmer', 'vsp']])->max('seq_no') + 1 ?: 1;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Department', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDepartment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->department_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblDepartment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblDepartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDepartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDepartment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionChangeDepartmentSeq() {
        $departmentModel = new TblDepartment();
        $departments = $departmentModel->find()->andWhere(['not in', 'department_id', ['farmer', 'vsp']])->orderBy('seq_no', SORT_ASC)->all();

        if (Yii::$app->request->isPost) {
            $order = Yii::$app->request->post('order', '');
            if (!empty($order)) {
                $orderArray = explode(',', $order);
                $saveModel = [];
                foreach ($orderArray as $index => $id) {
                    if ($department = TblDepartment::findOne($id)) {
                        $department->seq_no = $index + 1;
                        $saveModel[] = $department;
                    }
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Change Department', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }

        return $this->render('change_department_seq', [
                    'model' => $departmentModel,
                    'departments' => $departments,
        ]);
    }

}
