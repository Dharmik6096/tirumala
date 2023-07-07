<?php

namespace app\modules\assetmanagement\controllers;

use Yii;
use app\modules\assetmanagement\models\TblAssetMaster;
use app\modules\assetmanagement\models\TblAssetMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\assetmanagement\models\TblAssetMasterHistory;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\assetmanagement\models\TblAssetTransaction;

/**
 * TblAssetMasterController implements the CRUD actions for TblAssetMaster model.
 */
class TblAssetMasterController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-product-code', 'check-serial-number', 'get-serial-number', 'sr-no-asset-list', 'with-sr-no-asset-list'];

    /**
     * Lists all TblAssetMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAssetMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAssetMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAssetMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAssetMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->asset_code = (String) Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Asset Master', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblAssetMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblAssetMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Asset Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblAssetMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAssetMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblAssetMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAssetMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetSerialNumber($dcs_code, $asset_code, $sloc_type = 3) {
        $sno = TblAssetMaster::getSerialNo($dcs_code, $asset_code, $sloc_type);
        return $sno;
    }

    public function actionGetProductCode() {
        $data = [];
        $data['product_code'] = '';
        if (!empty($_POST)) {
            $model = new TblAssetMaster();
            $model->setAttributes($_POST);
            $modelData = $model->getAssetData();
            if (!empty($modelData)) {
                $data['product_code'] = $modelData->cmpl_product_code;
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($data);
    }

    public function actionSrNoAssetList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $ref_code = '';
            $slocType = '';
            if (!empty($parents[1])) {
                $ref_code = $parents[1];
                $slocType = 3;
            } else if (!empty($parents[0])) {
                $ref_code = $parents[0];
                $slocType = 2;
            }
            if (!empty($ref_code)) {
                $asset_txn = new TblAssetTransaction();
                $data = $asset_txn->getSrNoAssets($ref_code, $slocType);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionWithSrNoAssetList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $ref_code = '';
            $slocType = '';
            if (!empty($parents[1])) {
                $ref_code = $parents[1];
                $slocType = 3;
            } else if (!empty($parents[0])) {
                $ref_code = $parents[0];
                $slocType = 2;
            }
            if (!empty($ref_code)) {
                $asset_txn = new TblAssetTransaction();
                $data = $asset_txn->getSrNoAssets($ref_code, $slocType, true);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
