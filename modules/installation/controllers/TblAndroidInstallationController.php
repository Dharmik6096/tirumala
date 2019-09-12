<?php

namespace app\modules\installation\controllers;

use Yii;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\installation\models\TblAndroidInstallationDetails;
use yii\helpers\Json;

/**
 * TblAndroidInstallationController implements the CRUD actions for TblAndroidInstallation model.
 */
class TblAndroidInstallationController extends \app\controllers\ChildController {

    public $freeAccessActions = ['device-list'];

    /**
     * Lists all TblAndroidInstallation models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAndroidInstallationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAndroidInstallation model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAndroidInstallation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblAndroidInstallation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->android_installation_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblAndroidInstallation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->android_installation_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblAndroidInstallation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAndroidInstallation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblAndroidInstallation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAndroidInstallation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeviceList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if (!empty($parents[0]) && !empty($parents[1])) {
                    $device = new TblAndroidInstallationDetails();
                    $rows = $device->getActiveDeviceData($parents[0], $parents[1]);
                    foreach ($rows as $value) {
                        $out[] = array('id' => $value->device_id, 'name' => $value->device_id);
                    }
                    echo Json::encode(['output' => $out, 'selected' => '']);
                    return;
                }
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

}
