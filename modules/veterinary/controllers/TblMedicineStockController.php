<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\veterinary\models\TblMedicineStock;
use app\modules\veterinary\models\TblMedicineStockSearch;
use app\modules\veterinary\models\TblMedicineStockTransaction;
use app\modules\veterinary\models\TblMedicineStockTransactionSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMedicineStockController implements the CRUD actions for TblMedicineStock model.
 */
class TblMedicineStockController extends ChildController {

    public $freeAccessActions = ['user-list'];

    /**
     * Lists all TblMedicineStock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMedicineStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMedicineStock model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $searchModel = new TblMedicineStockTransactionSearch();
        $searchModel->attributes = $model->attributes;
        $dataProvider = $searchModel->search([]);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $this->model = new TblMedicineStock();
        $searchModel = new TblMedicineStockTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->sort = false;
        $txModel = new TblMedicineStockTransaction();

        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
          
            if (!empty($post['medicine_wise'])) {
                
            } else {
               
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => 'success', 'msg' => 'Medicine stock transfer saved successfully.'];
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Finds the TblMedicineStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMedicineStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMedicineStock::findOne($id)) !== null) {
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
                if (!empty(!empty($parents[4]))) {
                    $type = ['DCS'];
                    $code = $parents[4];
                } else if (!empty($parents[3])) {
                    $type = ['BMC'];
                    $code = $parents[3];
                } else if (!empty($parents[2])) {
                    $type = ['MCC'];
                    $code = $parents[2];
                } else if (!empty($parents[1])) {
                    $type = ['PLANT'];
                    $code = $parents[1];
                } else {
                    $type = ['UNION'];
                    $code = $parents[0];
                }
                $model = new TblMedicineStock();
                $data = $model->getUserList($type, $code);
                foreach ($data as $key => $val) {
                    $out[] = ['id' => $key, 'name' => $val];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionAllUserList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $model = new TblMedicineStock();
                $data = $model->getAllUserList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = ['id' => $key, 'name' => $val];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
