<?php

namespace app\modules\assetmanagement\controllers;

use Yii;
use app\modules\assetmanagement\models\TblAssetSet;
use app\modules\assetmanagement\models\TblAssetSetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\assetmanagement\models\TblAssetSetHistory;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\organisation\models\TblDcs;
use yii\widgets\ActiveForm;
use app\modules\assetmanagement\models\TblAssetTransaction;
use app\modules\assetmanagement\models\TblAssetTransactionHistory;

/**
 * TblAssetSetController implements the CRUD actions for TblAssetSet model.
 */
class TblAssetSetController extends \app\controllers\ChildController {

    public $freeAccessActions = ['org-aap-code-list', 'get-dcs-data', 'union-sap-code-list'];

    /**
     * Lists all TblAssetSet models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAssetSetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAssetSet model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAssetSet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblAssetSet();
        $model->scenario = 'create_main';
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            $model->load($postData);
            $master = [];
            $history = [];
            if ($model->from_type == 3) {
                $dcsRecords = !empty($postData['dcs']) ? $postData['dcs'] : [];
                foreach ($dcsRecords as $dcs => $dcsRecord) {
                    if (!empty($dcsRecord['sap_code']) && !empty($dcsRecord['store_location_code'])) {
                        $assetSetModel = new TblAssetSet();
                        $assetSetModel->scenario = 'create_dcs';
                        $assetSetModel->store_location_code = $dcsRecord['store_location_code'];
                        $slocModelData = $assetSetModel->storeLocCode;
                        if (!empty($slocModelData)) {
                            if (!empty($dcsRecord['asset_set_code'])) {
                                $assetSetModelData = $assetSetModel->findOne($dcsRecord['asset_set_code']);
                                $assetSetModelData->scenario = 'update_dcs';
                                if (!empty($assetSetModelData)) {
                                    $historyModel = new TblAssetSetHistory();
                                    Yii::$app->operation->history($assetSetModelData, $historyModel, UPDATE);
                                    $history[] = $historyModel;
                                    $assetSetModel = $assetSetModelData;
                                }
                            }
                            $assetSetModel->setAttributes($slocModelData->attributes);
                            $assetSetModel->setAttributes($dcsRecord);
                            $assetSetModel->status = 2;
                            if ($assetSetModel->validate()) {
                                $master[] = $assetSetModel;
                            } else {
                                Yii::$app->response->format = Response::FORMAT_JSON;
                                return Json::encode(ActiveForm::validate($assetSetModel));
                            }
                        }
                    }
                }
            }
            if ($model->from_type == 1 || $model->from_type == 2 || $model->from_type == 4) {
                $postDetail = $postData['TblAssetSet'];
                $assetSetModel = new TblAssetSet();
                $assetSetModel->scenario = 'create_single';
                if (!empty($postDetail['sap_code']) && !empty($postDetail['from_dest'])) {
                    $assetSetModel->store_location_code = $postData['TblAssetSet']['from_dest'];
                    $assetSetModel->store_location_type = $postData['TblAssetSet']['from_type'];
                    $slocModelData = $assetSetModel->storeLocCode;
                    $assetSetModel->setAttributes($slocModelData->attributes);
                    $assetSetModel->setAttributes($postDetail);
                    if ($assetSetModel->status) {
                        $assetSetModel->status = 2;
                    } else {
                        $assetSetModel->status = 0;
                    }
                    if ($assetSetModel->validate()) {
                        $master[] = $assetSetModel;
                    } else {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return Json::encode(ActiveForm::validate($assetSetModel));
                    }
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode(ActiveForm::validate($assetSetModel));
                }
            }
            if ($model->validate()) {
                $transaction = $this->generalModel->saveTransaction($master, $history, ['Asset SAP Code', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($model));
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblAssetSet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'update_single';
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblAssetSetHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            if ($this->model->status) {
                $this->model->status = 2;
            } else {
                $this->model->status = 0;
            }
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Asset SAP Code', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblAssetSet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAssetSet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAssetSet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAssetSet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetDcsData() {
        $model = new TblAssetSet();
        $mcc = Yii::$app->request->post('from_mcc');
        $dcsmodel = new TblDcs();
        $data = $dcsmodel->dcsData($mcc);
        return $this->renderAjax('_dcs_form', [
                    'model' => $model,
                    'data' => $data,
                    'dataProvider' => $data
        ]);
    }

    public function actionMovement() {
        $model = new TblAssetTransaction();
        $model->scenario = 'setmovement';
        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->transaction_date = Yii::$app->formatter->asDate($model->transaction_date, DATE_FORMAT);
                $asset_set = !empty(Yii::$app->request->post()['TblAssetTransaction']['asset_set']) ? Yii::$app->request->post()['TblAssetTransaction']['asset_set'] : [];
                $saveModel = [];
                $HisModel = [];
                if (!empty($asset_set)) {
                    if ($model->to_type == 3 || $model->in_ward) {
                        $check_data = TblAssetSet::find()->where(['store_location_type' => $model->to_type, 'store_location_code' => $model->to_dest, 'status' => [2]])
                                ->count();
                        $check_data += TblAssetTransaction::find()->where(['to_type' => $model->to_type, 'to_dest' => $model->to_dest, 'status' => [2]])
                                ->count();
                        if ($check_data > 0) {
                            $record = ['status' => 'success', 'msg' => Yii::t('app', 'Other Asset Set already availabe on destination.')];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return Json::encode($record);
                        }
                    }
                    $sap_code = TblAssetSet::findOne($model->sap_code);
                    $historyModel = new TblAssetSetHistory();
                    Yii::$app->operation->history($sap_code, $historyModel, UPDATE);
                    $HisModel[] = $historyModel;
                    $sap_code->status = 1;
                    $sap_code->is_active = 0;
                    $set = new TblAssetSet();
                    $set->store_location_code = $model->to_dest;
                    $set->attributes = $set->storeLocCode->attributes;
                    $set->sap_code = $sap_code->sap_code;
                    if ($model->in_ward || $model->to_type == 3) {
                        $set->status = 2;
                    } else {
                        $set->status = 0;
                    }
                    $saveModel[] = $sap_code;
                    $saveModel[] = $set;
                    foreach ($asset_set as $data) {
                        $old_trn = TblAssetTransaction::findOne($data['asset_transaction_code']);
                        $historyModel = new TblAssetTransactionHistory();
                        Yii::$app->operation->history($old_trn, $historyModel, UPDATE);
                        $HisModel[] = $historyModel;
                        if ($data['is_serial_number'] == '0' || $old_trn->serial_number == $data['serial_number']) {
                            $old_trn->remarks = $model->remarks;
                            $old_trn->transaction_date = $model->transaction_date;
                            $old_trn->status = 1;
                            $new_trn = new TblAssetTransaction();
                            $new_trn->attributes = $old_trn->attributes;
                            $new_trn->from_type = $model->from_type;
                            $new_trn->from_dest = $model->from_dest;
                            $new_trn->to_type = $model->to_type;
                            $new_trn->to_dest = $model->to_dest;
                            $new_trn->put_to_use_date = $model->transaction_date;
                            if ($model->to_type == 3 || $model->in_ward) {
                                $new_trn->status = 2;
                            } else {
                                $new_trn->status = 0;
                            }
                            $new_trn->received_date = $new_trn->received_by = $new_trn->updated_at = $new_trn->updated_by = NULL;
                            if ($data['is_serial_number'] == '0') {
                                $old_trn->remain_qty = 0;
                            }
                            $saveModel[] = $old_trn;
                            $saveModel[] = $new_trn;
                        } else {
                            $msg = $old_trn->assetCode->asset_name . ' 	Serial Number does not match.';
                            $record = ['status' => 'success', 'msg' => $msg];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return Json::encode($record);
                        }
                    }
                    $transaction = $this->generalModel->saveTransaction($saveModel, $HisModel, ['Asset Set Movement', 'create']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index']);
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'success', 'msg' => $msg];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return Json::encode($record);
                    }
                } else {
                    $record = ['status' => 'success', 'msg' => Yii::t('app', 'Asset Set can not be blank.')];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                $msg = '';
                foreach ($model->getErrors() as $error) {
                    $msg .= implode('<br/>', $error) . '<br/>';
                }
                $record = ['status' => 'success', 'msg' => $msg];
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }
        return $this->render('movement', [
                    'model' => $model,
        ]);
    }

    public function actionSetDetail() {
        $model = new TblAssetTransaction();
        $assetset = $model->getAssetSet(Yii::$app->request->post()['asset_set_code']);
        return $this->renderAjax('_asset_set_form', ['model' => $model, 'assetset' => $assetset]);
    }

    public function actionOrgSapCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) || (isset($parents[0]) && $parents[0] == 0)) {
                $slocType = $parents[0];
                $code = '';
                if ($slocType == 1) {
                    $code = !empty($parents[1]) ? $parents[1] : '';
                } else if ($slocType == 2) {
                    $code = !empty($parents[2]) ? $parents[2] : '';
                } else if ($slocType == 3) {
                    $code = !empty($parents[3]) ? $parents[3] : '';
                }
                $problems = new TblAssetSet();
                $data = $problems->getAssetSAPCodeList($code, $slocType);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionUnionSapCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $list = [];
            if (!empty($value[0])) {
                $assetSet = new TblAssetSet();
                $list = $assetSet->getSapCodeList($value[0]);
            }
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out, 'selected' => '']);
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
