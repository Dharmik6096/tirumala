<?php

namespace app\modules\assetmanagement\controllers;

use Yii;
use app\modules\assetmanagement\models\TblAssetBom;
use app\modules\assetmanagement\models\TblAssetBomSearch;
use app\modules\assetmanagement\models\TblAssetBomHistory;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * TblAssetBomController implements the CRUD actions for TblAssetBom model.
 */
class TblAssetBomController extends \app\controllers\ChildController {

    public $freeAccessActions = ['bom-list'];

    /**
     * Lists all TblAssetBom models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAssetBomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAssetBom model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAssetBom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblAssetBom();
        $this->viewFile = 'create';
        $modelSave = [];
        $this->model->asset_code = $id;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->scenario = 'create';
            $asset_bom_code = '';
            if (!empty(Yii::$app->request->post()['TblAssetBom']['asset_bom_code'])) {
                $asset_bom_code = Yii::$app->request->post()['TblAssetBom']['asset_bom_code'];
                $this->model->scenario = 'update';
            }
            if ($this->model->validate()) {
                $update = FALSE;
                if (!empty($asset_bom_code)) {
                    $this->model = $this->findModel($asset_bom_code);
                    $historyModel = new TblAssetBomHistory();
                    Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
                    $modelSave[] = $historyModel;
                    $this->model->load(Yii::$app->request->post());
                    $update = TRUE;
                }
                $modelSave[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Asset Bom ', ($update) ? 'edit' : 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    protected function customRender() {
        $request = Yii::$app->request->queryParams;
        $searchModel = new TblAssetBomSearch();
        $searchModel->asset_code = $request['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }

    public function actionUpdateBom() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['asset_bom_code'])) {
            $modelData = $this->findModel($_POST['asset_bom_code']);
            if (!empty($modelData)) {
                $data['status'] = 'success';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

    /**
     * Updates an existing TblAssetBom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblAssetBomHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Asset Bom', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblAssetBom model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAssetBom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAssetBom the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAssetBom::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBomList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $bom = new TblAssetBom();
                $data = $bom->getBomList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $val['spare_code'], 'name' => $val['asset_name']);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
