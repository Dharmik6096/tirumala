<?php

namespace app\modules\tms\controllers;

use Yii;
use app\modules\tms\models\TblUserTrackingMovement;
use app\modules\tms\models\TblUserTrackingMovementSearch;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use app\controllers\ChildController;

/**
 * TblUserTrackingMovementController implements the CRUD actions for TblUserTrackingMovement model.
 */
class TblUserTrackingMovementController extends ChildController {

    /**
     * Lists all TblUserTrackingMovement models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblUserTrackingMovementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $output = $searchModel->search(Yii::$app->request->queryParams, 'latLong');

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'onlineData' => $output,
        ]);
    }

    public function actionIndexOther() {
        $searchModel = new TblUserTrackingMovementSearch();
        $searchModel->scenario = 'indexOther';
        $output = $searchModel->searchUser(Yii::$app->request->queryParams, 'latLong');
        $userDetailData = $searchModel->getUserDetail(Yii::$app->request->queryParams, $output);
        return $this->render('index_other', [
                    'searchModel' => $searchModel,
                    'onlineData' => $output,
                    'userDetailData' => $userDetailData,
        ]);
    }

    /**
     * Finds the TblUserTrackingMovement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUserTrackingMovement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblUserTrackingMovement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUserList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $model = new TblUserTrackingMovement();
                $data = $model->getUserList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}