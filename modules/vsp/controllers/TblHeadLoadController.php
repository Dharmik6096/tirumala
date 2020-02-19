<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblHeadLoad;
use app\modules\vsp\models\TblHeadLoadSearch;
use app\modules\vsp\models\TblHeadLoadHistory;
use app\modules\vsp\models\TblHeadLoadApplicability;
use app\modules\vsp\models\TblHeadLoadApplicabilityHistory;
use app\modules\vsp\models\TblHeadLoadApplicabilitySearch;
use app\modules\vsp\models\TblHeadLoadTransactionSearch;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblRoutes;
use app\modules\vsp\models\TblHeadLoadTransaction;
use app\modules\vsp\models\TblHeadLoadTransactionHistory;
use app\models\TblSentbox;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * TblHeadLoadController implements the CRUD actions for TblHeadLoad model.
 */
class TblHeadLoadController extends \app\controllers\ChildController {

    public $headLoad;
    protected $transaction;
    public $jsonEncoded;

    /**
     * Lists all TblHeadLoad models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblHeadLoadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblHeadLoad model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblHeadLoadTransactionSearch();
        $searchModel->head_load_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $appsearchModel = new TblHeadLoadApplicabilitySearch();
        $appsearchModel->head_load_code = $id;
        $appdataProvider = $appsearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'appsearchModel' => $appsearchModel,
                    'appdataProvider' => $appdataProvider,
        ]);
    }

    /**
     * Creates a new TblHeadLoad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblHeadLoad();
        $this->transaction = new TblHeadLoadTransaction();
        $this->viewFile = 'create';
        $this->jsonEncoded = [];
        $this->jsonEncoded = Json::encode($this->jsonEncoded);
        $this->model->scenario = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            //$this->model->head_load_code = $this->model->getCode();
            $this->model->head_load_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            // $this->model->union_code = Yii::$app->session->get('organizations_code');
            $this->model->is_active = 1;
            //   $this->model->entry_type = Yii::$app->general->getEntryType();
            $transaction = Json::decode($_POST['load_transaction']);
            $list = [];
            for ($i = 0; $i < count($transaction); $i++) {
                $modelNew = new TblHeadLoadTransaction();
                //$code = str_pad($modelNew->getCode() + $i, 2, '0', STR_PAD_LEFT);
                // $trcode = $modelNew->getCode($this->model->head_load_code) + $i;
                $trcode = (string) (Yii::$app->general->getCodeAutoIncrement($modelNew) + $i);
                //  $code = $this->model->head_load_code . 'T' . $trcode;
                $modelNew->head_load_transaction_code = $trcode;
                $modelNew->head_load_code = $this->model->head_load_code;
                $modelNew->from_km = $transaction[$i]['from_km'];
                $modelNew->to_km = $transaction[$i]['to_km'];
                $modelNew->from_qty = $transaction[$i]['from_qty'];
                $modelNew->to_qty = $transaction[$i]['to_qty'];
                $modelNew->value = $transaction[$i]['value'];
                $modelNew->km_value = $transaction[$i]['km_value'];
                $modelNew->union_code = $this->model->union_code;
                $list[] = $modelNew;
            }

            $transaction = $this->generalModel->saveTransaction([$this->model], $list, ['Head Load', 'create']);

            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
        //return $this->render($this->viewFile, ['model' => $this->model, 'transaction' => $this->transaction]);
    }

    /* public function customRedirect() {
      //  if ($this->model->is_active == 1)
      //   return $this->redirect(['tbl-head-load-transaction/create', 'id' => $this->model->head_load_code]);
      //  else
      //return $this->redirect(['tbl-head-load/view', 'id' => $this->model->head_load_code]);
      return $this->redirect(['tbl-head-load/index']);
      } */

    public function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model, 'transaction' => $this->transaction, 'jsonEncoded' => $this->jsonEncoded]);
    }

    /**
     * Updates an existing TblHeadLoad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->transaction = new TblHeadLoadTransaction();
        $transaction = $this->transaction->getData($id);
        $this->jsonEncoded = [];
        for ($i = 0; $i < count($transaction); $i++) {
            $this->jsonEncoded[] = $transaction[$i];
        }
        $this->jsonEncoded = Json::encode($this->jsonEncoded);
        if (Yii::$app->request->post()) {
            $historyModel = new TblHeadLoadHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = Json::decode($_POST['load_transaction']);
            $list = [];
            $cnt = 0;
            for ($i = 0; $i < count($transaction); $i++) {
                if ($transaction[$i]['head_load_transaction_code'] == '0') {
                    $modelNew = new TblHeadLoadTransaction();
                    //  $code = str_pad($modelNew->getCode() + $cnt, 2, '0', STR_PAD_LEFT);
                    $trcode = $modelNew->getCode($this->model->head_load_code) + $cnt;
                    $code = $this->model->head_load_code . 'T' . $trcode;
                    $modelNew->head_load_transaction_code = $code;
                    $modelNew->head_load_code = $this->model->head_load_code;
                    $cnt ++;
                } else {
                    $modelNew = $this->transaction->getRecord($transaction[$i]['head_load_transaction_code']);
                    $detailHistory = new TblHeadLoadTransactionHistory();
                    Yii::$app->operation->history($modelNew, $detailHistory, UPDATE);
                    $list[] = $detailHistory;
                }
                $modelNew->from_km = $transaction[$i]['from_km'];
                $modelNew->to_km = $transaction[$i]['to_km'];
                $modelNew->from_qty = $transaction[$i]['from_qty'];
                $modelNew->to_qty = $transaction[$i]['to_qty'];
                $modelNew->value = $transaction[$i]['value'];
                $list[] = $modelNew;
            }
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $list, ['Head Load', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblHeadLoad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_head_load', 'tbl_head_load_transaction', '', Yii::$app->request->post('id'), 'head_load_code']);
        if ($valueOut == 0) {
            $transaction = \Yii::$app->db->beginTransaction();
            $head_code = Yii::$app->request->post('id');
            try {
                $master = [];
                $this->model = $this->findModel($head_code);
                $historyModel = new TblHeadLoadHistory();
                Yii::$app->operation->history($this->model, $historyModel, DELETE);
                $master[] = $historyModel->save(FALSE);
                $details = TblHeadLoadTransaction::find()->where(['head_load_code' => $head_code])->all();
                foreach ($details as $key => $id) {
                    $detailHistory = new TblHeadLoadTransactionHistory();
                    Yii::$app->operation->history($id, $detailHistory, DELETE);
                    $master[] = $detailHistory->save(FALSE);
                    $master[] = $details[$key]->delete();
                }
                $master[] = $this->model->delete();
                if (in_array(FALSE, $master)) {
                    $transaction->rollback();
                    $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
                } else {
                    $transaction->commit();
                    $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
                }
            } catch (UserException $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => $e->getMessage()];
            } catch (\yii\db\Exception $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionTransactionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_head_load_transaction', '', '', Yii::$app->request->post('id'), 'head_load_transaction_code']);
        if ($valueOut == 0) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $master = [];
                $detailHistory = new TblHeadLoadTransactionHistory();
                $record = new TblHeadLoadTransaction();
                $record = $record->getRecord(Yii::$app->request->post('id'));
                Yii::$app->operation->history($record, $detailHistory, DELETE);
                $master[] = $detailHistory->save(FALSE);
                $master[] = $record->delete();
                if (in_array(FALSE, $master)) {
                    $transaction->rollback();
                    $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
                } else {
                    $transaction->commit();
                    $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
                }
            } catch (UserException $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => $e->getMessage()];
            } catch (\yii\db\Exception $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionInActive() {
        $this->model = $this->findModel(Yii::$app->request->get('id'));
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model], ['Head Load', 'edit']);
        return $this->redirect(['index']);
    }

    private function sentboxModel($model) {
        $sentbox = new \app\models\TblSentbox();
        //  if (empty($model->sub_center_code)) {
        $sentbox->dest_org_id = $model->dcs_code;
        // } else {
        //     $sentbox->dest_org_id = $model->sub_center_code;
        //  }
        return $sentbox;
    }

    /**
     * Finds the TblHeadLoad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblHeadLoad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblHeadLoad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMapDcs($id) {

        $this->model = new TblHeadLoadApplicability();
        $this->headLoad = $this->findModel($id);


        $data = $this->model->getHeadLoadApplicability($id, $this->headLoad->union_code);
        $this->model->organization = $data['orgFlag'];
        $this->model->wef_date = $data['wefDate'];

        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                $org = ($this->model->organization == 0) ? 'dcs_code' : 'sub_center_code';

                if ($this->model->organization == 0) {
                    $org = 'dcs_code';
                    $crossOrg = 'sub_center_code';
                    $relationalModel = new TblDcs();
                } else {
                    $org = 'sub_center_code';
                    $crossOrg = 'dcs_code';
                    $relationalModel = new TblSubCenter();
                }

                $oldModel = new TblHeadLoadApplicability();
                $dataold = $oldModel->find()->where(['head_load_code' => $id])->all();
                $returnedArray = \yii\helpers\ArrayHelper::map($dataold, $org, $org);

                $toRevoke = array_diff($returnedArray, $this->model->dcs_code);
                $toAssign = array_diff($this->model->dcs_code, $returnedArray);
                $mappingList = [];

                foreach ($toRevoke as $value) {
                    $milkModel = TblHeadLoadApplicability::find()->where([$org => $value, 'head_load_code' => $id])->one();
                    $milkModel->scenario = 'applicability';
                    $milkHistory = new TblHeadLoadApplicabilityHistory();
                    Yii::$app->operation->history($milkModel, $milkHistory, UPDATE);
                    Yii::$app->operation->defaults($milkModel, DELETE);
                    array_push($mappingList, $milkHistory);
                    array_push($mappingList, $milkModel);
//                    echo 'revike = '.$value.' code = '.$milkModel->rate_app_code.' = '.$milkModel->is_delete.'<br>';
                }

                $code = $this->model->getCode();
                foreach ($this->model->dcs_code as $value) {
                    $milkModel = TblHeadLoadApplicability::find()->where([$org => $value, 'head_load_code' => $id])->one();
                    if ($milkModel) {
                        if ($milkModel->is_delete == 1) {
                            $milkHistory = new TblHeadLoadApplicabilityHistory();
                            Yii::$app->operation->history($milkModel, $milkHistory, UPDATE);
                        }
                    } else {
                        $milkModel = new TblHeadLoadApplicability();
                        $milkModel->code = $code;
                        $milkModel->$org = $value;
                        $milkModel->head_load_code = $id;
                    }
                    $milkModel->scenario = 'applicability';
                    $milkModel->is_delete = 0;
                    $milkModel->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
                    $check = $milkModel->checkDuplicate();
                    if ($check == 1) {
                        $this->model->addError('wef_date', date('d-m-Y', strtotime($milkModel->wef_date)) . ' date already taken by dcs.');
                        return $this->render('_map_dcs', [
                                    'headLoad' => $this->headLoad, 'model' => $this->model, 'routes' => $data['routes'], 'selectedRoutes' => $data['selectedRoutes'],
                                    'selectedOrganization' => $data['selectedOrganization'], 'selectedAllOrg' => $data['selectedAllOrg'],
                        ]);
                    }
                    $code = str_pad($code + 1, 2, '0', STR_PAD_LEFT);
                    array_push($mappingList, $milkModel);
                }
                $transaction = $this->generalModel->saveTransaction($mappingList, ['purchase rate applicability', 'create']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRender')
                        return $this->render('_map_dcs', [
                                    'headLoad' => $this->headLoad, 'model' => $this->model, 'routes' => $data['routes'], 'selectedRoutes' => $data['selectedRoutes'],
                                    'selectedOrganization' => $data['selectedOrganization'], 'selectedAllOrg' => $data['selectedAllOrg'],
                        ]);
                    else
                        Yii::$app->display->message(true, 'head load applicability', 'edit');
                    return $this->redirect(['index']);
                }

                /* foreach ($dataold as $d) {
                  Yii::$app->operation->history($d, new TblHeadLoadApplicabilityHistory(), 'Edit', FALSE);
                  }
                  $oldModel->deleteAll(['head_load_code' => $id]);

                  foreach ($this->model->dcs_code as $dcs){
                  $modelNew = new TblHeadLoadApplicability();
                  $modelNew->code = $this->model->getCode();
                  $modelNew->$org = $dcs;
                  $modelNew->head_load_code=$id;
                  $modelNew->wef_date = Yii::$app->formatter->asDate($this->model->wef_date,DATE_FORMAT);
                  $check = $modelNew->checkDuplicate();

                  if($check==1){
                  $this->model->addError('wef_date',$modelNew->wef_date.' date already taken by dcs.');

                  return $this->render('_map_dcs', [
                  'headLoad' => $this->headLoad,'model'=>$this->model,'routes'=>$data['routes'],'selectedRoutes'=>$data['selectedRoutes'],
                  'selectedOrganization'=>$data['selectedOrganization'],'selectedAllOrg'=>$data['selectedAllOrg'],
                  ]);
                  }

                  $relationalModel->$org = $dcs;
                  $relationalCode = $relationalModel->getMainSubCenter();
                  $modelNew->$crossOrg = ($relationalCode)?$relationalCode->$crossOrg:null;
                  //
                  Yii::$app->operation->defaults($modelNew, UPDATE);
                  if($modelNew->save(false)){
                  $sentbox = new \app\models\TblSentbox();
                  if ($sentbox->setSentbox($modelNew, UPDATE)) {
                  $modelNew->flg_sentbox_entry = 'Y';
                  $modelNew->save();
                  }
                  }
                  } */
            }
        }
        return $this->render('_map_dcs', [
                    'headLoad' => $this->headLoad, 'model' => $this->model, 'routes' => $data['routes'], 'selectedRoutes' => $data['selectedRoutes'],
                    'selectedOrganization' => $data['selectedOrganization'], 'selectedAllOrg' => $data['selectedAllOrg'],
        ]);
    }

    public function actionGetDcs() {

        if (!empty(Yii::$app->request->post('route'))) {
            $routeAry = explode(',', Yii::$app->request->post('route'));


            $finalArray = [];
            if (Yii::$app->request->post('org') == 0) {
                $dcs = new TblDcs();
                foreach ($routeAry as $route) {
                    $dcsAry = $dcs->getRouteDcs($route);
                    $records = ArrayHelper::map($dcsAry, 'dcs_code', 'dcs_name');
                    $finalArray = array_merge($finalArray, $records);
                }
            } else {
                $subCenter = new TblSubCenter();
                foreach ($routeAry as $route) {
                    $dcsAry = $subCenter->getRouteSubcenter($route);
                    $records = ArrayHelper::map($dcsAry, 'sub_center_code', 'sub_center_name');
                    $finalArray = array_merge($finalArray, $records);
                }
            }

            return \yii\helpers\Json::encode(['data' => $finalArray, 'status' => 'success']);
        }
    }

    public function actionHeadLoadApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblHeadLoadApplicability();
        $appModel->field_name = 'head_load_code';
        $appModel->field_value = $id;
        $appModel->trans_label = Yii::t('app', 'head load applicabilities');
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->actions = ['delete' => ['option' => 'code,code,tbl-head-load/delete-applicability']];
        $appModel->fields = ['wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
            'shift_code' => ['view' => ['grid', 'create'], 'type' => 'dropdown', 'flag' => 'shift_applicability', 'value' => 'shiftCode.shift'],
            'shift_for' => ['view' => ['grid', 'create'], 'type' => 'dropdown', 'flag' => 'shift_applicability', 'value' => 'shiftCodeFor.shift', 'class' => ''],
            'applicable_for' => ['view' => ['grid'], 'value' => 'applicable_for'],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'mcc_name' => ['view' => ['grid'], 'value' => function($model) {
                    if ($model->applicable_for == 'PLANT') {
                        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                    } else if ($model->applicable_for == 'MCC') {
                        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                    } else if ($model->applicable_for == 'BMC') {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    } else if ($model->applicable_for == 'DCS') {
                        return Yii::$app->general->getforeignkey($model->dcsName, 'dcs_name');
                    } else {
                        return Yii::$app->general->getforeignkey($model->customerMasterCode, 'customer_name');
                    }
                }],
//             'dcs_code' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],
        ];
        $appModel->options = ['tanker_rate'];
        $appModel->mcc_field_name = 'applicable_code';
        $customerType = new TblCustomerType();
        $value = $customerType->getCustomerType();
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $model = TblHeadLoadApplicability::find()->where(['code' => Yii::$app->request->post('id')])->one();
        $localHistory = new TblHeadLoadApplicabilityHistory();
        Yii::$app->operation->history($model, $localHistory, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $localHistory]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionMapRoute($id) {

        $this->model = new TblHeadLoadApplicability();
        $this->headLoad = $this->findModel($id);


        $data = $this->model->getHeadLoadApplicability($id, $this->headLoad->union_code);
        $this->model->organization = $data['orgFlag'];
        $this->model->wef_date = $data['wefDate'];

        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                $org = ($this->model->organization == 0) ? 'dcs_code' : 'sub_center_code';

                if ($this->model->organization == 0) {
                    $org = 'dcs_code';
                    $crossOrg = 'sub_center_code';
                    $relationalModel = new TblDcs();
                } else {
                    $org = 'sub_center_code';
                    $crossOrg = 'dcs_code';
                    $relationalModel = new TblSubCenter();
                }

                $oldModel = new TblHeadLoadApplicability();
                $dataold = $oldModel->find()->where(['head_load_code' => $id])->all();
                $returnedArray = \yii\helpers\ArrayHelper::map($dataold, $org, $org);

                $toRevoke = array_diff($returnedArray, $this->model->dcs_code);
                $toAssign = array_diff($this->model->dcs_code, $returnedArray);
                $mappingList = [];

                foreach ($toRevoke as $value) {
                    $milkModel = TblHeadLoadApplicability::find()->where([$org => $value, 'head_load_code' => $id])->one();
                    $milkModel->scenario = 'applicability';
                    $milkHistory = new TblHeadLoadApplicabilityHistory();
                    Yii::$app->operation->history($milkModel, $milkHistory, UPDATE);
                    Yii::$app->operation->defaults($milkModel, DELETE);
                    array_push($mappingList, $milkHistory);
                    array_push($mappingList, $milkModel);
//                    echo 'revike = '.$value.' code = '.$milkModel->rate_app_code.' = '.$milkModel->is_delete.'<br>';
                }

                $code = $this->model->getCode();
                foreach ($this->model->dcs_code as $value) {
                    $milkModel = TblHeadLoadApplicability::find()->where([$org => $value, 'head_load_code' => $id])->one();
                    if ($milkModel) {
                        if ($milkModel->is_delete == 1) {
                            $milkHistory = new TblHeadLoadApplicabilityHistory();
                            Yii::$app->operation->history($milkModel, $milkHistory, UPDATE);
                        }
                    } else {
                        $milkModel = new TblHeadLoadApplicability();
                        $milkModel->code = $code;
                        $milkModel->$org = $value;
                        $milkModel->head_load_code = $id;
                    }
                    $milkModel->scenario = 'applicability';
                    $milkModel->is_delete = 0;
                    $milkModel->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
                    $check = $milkModel->checkDuplicate();
                    if ($check == 1) {
                        $this->model->addError('wef_date', date('d-m-Y', strtotime($milkModel->wef_date)) . ' date already taken by dcs.');
                        return $this->render('_map_dcs', [
                                    'headLoad' => $this->headLoad, 'model' => $this->model, 'routes' => $data['routes'], 'selectedRoutes' => $data['selectedRoutes'],
                                    'selectedOrganization' => $data['selectedOrganization'], 'selectedAllOrg' => $data['selectedAllOrg'],
                        ]);
                    }
                    $code = str_pad($code + 1, 2, '0', STR_PAD_LEFT);
                    array_push($mappingList, $milkModel);
                }
                $transaction = $this->generalModel->saveTransaction($mappingList, ['purchase rate applicability', 'create']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRender')
                        return $this->render('_map_dcs', [
                                    'headLoad' => $this->headLoad, 'model' => $this->model, 'routes' => $data['routes'], 'selectedRoutes' => $data['selectedRoutes'],
                                    'selectedOrganization' => $data['selectedOrganization'], 'selectedAllOrg' => $data['selectedAllOrg'],
                        ]);
                    else
                        Yii::$app->display->message(true, 'head load applicability', 'edit');
                    return $this->redirect(['index']);
                }

                /* foreach ($dataold as $d) {
                  Yii::$app->operation->history($d, new TblHeadLoadApplicabilityHistory(), 'Edit', FALSE);
                  }
                  $oldModel->deleteAll(['head_load_code' => $id]);

                  foreach ($this->model->dcs_code as $dcs){
                  $modelNew = new TblHeadLoadApplicability();
                  $modelNew->code = $this->model->getCode();
                  $modelNew->$org = $dcs;
                  $modelNew->head_load_code=$id;
                  $modelNew->wef_date = Yii::$app->formatter->asDate($this->model->wef_date,DATE_FORMAT);
                  $check = $modelNew->checkDuplicate();

                  if($check==1){
                  $this->model->addError('wef_date',$modelNew->wef_date.' date already taken by dcs.');

                  return $this->render('_map_dcs', [
                  'headLoad' => $this->headLoad,'model'=>$this->model,'routes'=>$data['routes'],'selectedRoutes'=>$data['selectedRoutes'],
                  'selectedOrganization'=>$data['selectedOrganization'],'selectedAllOrg'=>$data['selectedAllOrg'],
                  ]);
                  }

                  $relationalModel->$org = $dcs;
                  $relationalCode = $relationalModel->getMainSubCenter();
                  $modelNew->$crossOrg = ($relationalCode)?$relationalCode->$crossOrg:null;
                  //
                  Yii::$app->operation->defaults($modelNew, UPDATE);
                  if($modelNew->save(false)){
                  $sentbox = new \app\models\TblSentbox();
                  if ($sentbox->setSentbox($modelNew, UPDATE)) {
                  $modelNew->flg_sentbox_entry = 'Y';
                  $modelNew->save();
                  }
                  }
                  } */
            }
        }
        return $this->render('_map_dcs', [
                    'headLoad' => $this->headLoad, 'model' => $this->model, 'routes' => $data['routes'], 'selectedRoutes' => $data['selectedRoutes'],
                    'selectedOrganization' => $data['selectedOrganization'], 'selectedAllOrg' => $data['selectedAllOrg'],
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->head_load_code]);
    }

}
