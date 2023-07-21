<?php

namespace app\modules\assetmanagement\controllers;

use Yii;
use app\modules\assetmanagement\models\TblAssetBom;
use app\modules\assetmanagement\models\TblAssetDetailBom;
use app\modules\assetmanagement\models\TblAssetDetailBomSearch;
use app\modules\assetmanagement\models\TblAssetDetailBomHistory;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;

/**
 * TblAssetDetailBomController implements the CRUD actions for TblAssetDetailBom model.
 */
class TblAssetDetailBomController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-asset-is-serial', 'old-sr-no'];

    /**
     * Lists all TblAssetDetailBom models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAssetDetailBomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAssetDetailBom model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAssetDetailBom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblAssetDetailBom();
        $this->viewFile = 'create';
        $modelSave = [];
        $this->model->asset_detail_code = $id;
        $this->model->asset_code = Yii::$app->general->getforeignkey($this->model->assetDetailCode, 'asset_code');
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->scenario = 'create';
            $asset_detail_bom_code = '';

            if (!empty(Yii::$app->request->post()['TblAssetDetailBom']['asset_detail_bom_code'])) {
                $asset_detail_bom_code = Yii::$app->request->post()['TblAssetDetailBom']['asset_detail_bom_code'];
                $this->model->scenario = 'update';
            }
            if ($this->model->validate()) {
                $update = FALSE;
                if (!empty($asset_detail_bom_code)) {
                    $this->model = $this->findModel($asset_detail_bom_code);
                    $historyModel = new TblAssetDetailBomHistory();
                    Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
                    $modelSave[] = $historyModel;
                    $this->model->load(Yii::$app->request->post());
                    $update = TRUE;
                }
                $modelSave[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Asset Detail Bom ', ($update) ? 'edit' : 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    protected function customRender() {

        $request = Yii::$app->request->queryParams;
        $searchModel = new TblAssetDetailBomSearch();
        $searchModel->asset_detail_code = $request['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $isaction = TRUE;
        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }

    public function actionGetAssetIsSerial() {
        $data = [];
        $data['status'] = 'error';
        if (!empty($_POST)) {

            $model = new TblAssetBom();
            $model->spare_code = $_POST['spare_code'];
            $model->asset_code = $_POST['asset_code'];
            $asset_serial = $model->getAssetData();

            if (!empty($asset_serial)) {
                $data['status'] = 'success';
                $data['is_serial_number'] = $asset_serial->is_serial_number;
            }
        }
        return Json::encode($data);
    }

    public function actionUpdateBom() {

        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['asset_detail_bom_code'])) {

            $modelData = $this->findModel($_POST['asset_detail_bom_code']);
            if (!empty($modelData)) {
                $data['status'] = 'success';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

    /**
     * Updates an existing TblAssetDetailBom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->asset_detail_bom_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblAssetDetailBom model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAssetDetailBom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAssetDetailBom the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAssetDetailBom::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

//    public function actionOldSrNo() {
//        $out = [];
//        if (isset($_POST['depdrop_parents'])) {
//            $parents = $_POST['depdrop_parents'];
//            $asset = explode('##', $parents[0]);
//            $ref_code = '';
//            $slocType = $parents[1];
//            if ($slocType == 1 && !empty($parents[2])) {
//                $ref_code = $parents[2];
//            } else if ($slocType == 2 && !empty($parents[3])) {
//                $ref_code = $parents[3];
//            } else if ($slocType == 3 && !empty($parents[4])) {
//                $ref_code = $parents[4];
//            }
//            $spare_code = $parents[5];
//            if (!empty($parents[0])) {
//                $bom = new TblAssetDetailBom();
//                $data = $bom->getOldSrNo($asset[0], $slocType, $ref_code, $spare_code);
//                foreach ($data as $key => $val) {
//                    $out[] = array('id' => $val['spare_code'], 'name' => $val['serial_number']);
//                }
//                return Json::encode(['output' => $out, 'selected' => '']);
//            }
//        }
//        return Json::encode(['output' => '', 'selected' => '']);
//    }

    public function actionOldSrNo() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $asset = explode('##', $parents[0]);
            $sr_no = '';
            $spare_code = '';
            if ($parents[1] && $parents[1] != 'Loading ...') {
                $sr_no = $parents[1];
            }
            if ($parents[2] && $parents[2] != 'Loading ...') {
                $spare_code = $parents[2];
            }
            if (!empty($parents[0]) && $parents[0] != 'Loading ...') {
                $bom = new TblAssetDetailBom();
                $data = $bom->getOldSrNo($asset[0], $sr_no, $spare_code);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $val['serial_number'], 'name' => $val['serial_number']);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

//    public function actionNewSrNo() {
//        $out = [];
//        if (isset($_POST['depdrop_parents'])) {
//            $parents = $_POST['depdrop_parents'];
//            $asset = explode('##', $parents[0]);
////            $ref_code = '';
////            $slocType = $parents[1];
////            if ($slocType == 1 && !empty($parents[2])) {
////                $ref_code = $parents[2];
////            } else if ($slocType == 2 && !empty($parents[3])) {
////                $ref_code = $parents[3];
////            } else if ($slocType == 3 && !empty($parents[4])) {
////                $ref_code = $parents[4];
////            }
////            $spare_code = $parents[5];
//            if (!empty($parents[0])) {
//                $bom = new TblAssetDetailBom();
//                $data = $bom->getNewSrNo($asset[0]);
//                foreach ($data as $key => $val) {
//                    $out[] = array('id' => $val['spare_code'], 'name' => $val['serial_number']);
//                }
//                return Json::encode(['output' => $out, 'selected' => '']);
//            }
//        }
//        return Json::encode(['output' => '', 'selected' => '']);
//    }
}
