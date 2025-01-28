<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblDispatchCenterType;
use app\modules\product\models\TblDispatchCenterTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblDispatchCenter;
use yii\helpers\Json;

/**
 * TblDispatchCenterTypeController implements the CRUD actions for TblDispatchCenterType model.
 */
class TblDispatchCenterTypeController extends \app\controllers\ChildController {
    public $freeAccessActions = ['get-dispatch-center'];
    /**
     * Lists all TblDispatchCenterType models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDispatchCenterTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDispatchCenterType model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDispatchCenterType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblDispatchCenterType();

        if ($model->load(Yii::$app->request->post())) {
            $model->dispatch_center_type_code = $model->getCode();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->dispatch_center_type_code]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing TblDispatchCenterType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dispatch_center_type_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblDispatchCenterType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblDispatchCenterType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDispatchCenterType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDispatchCenterType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetDispatchCenter() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            if (!empty($value[0])) {
                $dispatchModel = new TblDispatchCenter();
                $list = $dispatchModel->getDispatchCenters($value[0]);
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key, 'name' => $r);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
