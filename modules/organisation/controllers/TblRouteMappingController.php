<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblRouteMappingSearch;
use app\modules\organisation\models\TblRouteMappingHistory;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\organisation\models\TblRouteMappingSources;
use app\modules\organisation\models\TblRouteMappingSourcesHistory;
use app\modules\organisation\models\TblRouteMappingSourcesSearch;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\organisation\models\TblSocietyCodesHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsSearch;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblCustomerMasterSearch;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblCustomerMasterHistory;
use app\modules\transporter\models\TblVehicleKmInfo;
use app\models\TblUserOrganizationMapping;

/**
 * TblRouteMappingController implements the CRUD actions for TblRouteMapping model.
 */
class TblRouteMappingController extends \app\controllers\ChildController {

    public $bankDetails;
    public $contactDetails;
    public $freeAccessActions = ['route-list', 'all-route-list', 'get-bmc-route', 'all-route-transporter-list', 'user-list'];

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblRouteMapping models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblRouteMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblRouteMapping model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $bsearchModel = new TblBankDetailsSearch();
        $bsearchModel->module_name = 'routeMapping';
        $bsearchModel->module_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'routeMapping';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
    }

    /**
     * Creates a new TblRouteMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblRouteMapping();
        $this->viewFile = 'create';
        $this->contactDetails = new TblContactDetails();
        $this->model->valid_from = date('Y-m-d');
        $this->contactDetails->scenario = 'additional';
        $this->contactDetails->form_validation_type = 'route-create';
        $this->contactDetails->department = 'route_supervisor';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            $this->model->route_code = $this->model->getCode();
            $validate = 1;

            $mapping = [];
            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('routeMapping', $this->model->route_code);
            array_push($mapping, $this->contactDetails);

            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'route_name', $this->model->route_name);

            if ($validate == 1 && empty($this->model->getErrors())) {
                $transaction = $this->generalModel->saveTransaction([$this->model], $mapping, ['Route Mapping', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblRouteMapping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;

        if (Yii::$app->request->post()) {

            $historyModel = new TblRouteMappingHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $mapping = [$this->model, $historyModel];

            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'route_name', $_POST['TblRouteMapping']['route_name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction($mapping, ['Route Mapping', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblRouteMapping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_route_mapping', Yii::$app->request->post('id'), 'route_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblRouteMappingHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
//            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], ['TblRouteMappingSources', 'TblRouteMappingSourcesHistory'], 'route_code');
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], ['TblRouteMappingSources', 'TblRouteMappingSourcesHistory', 'TblContactDetails', 'TblContactDetailsHistory'], ['route_code', 'routeMapping']);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDeleteSource() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_route_mapping_sources', Yii::$app->request->post('id'), 'route_mapping_source_code']);
        $saveModel = [];
        $deleteModel = [];
        if ($valueOut == 0) {
            $this->model = TblRouteMappingSources::findOne(Yii::$app->request->post('id'));
            $historyModel = new TblRouteMappingSourcesHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $saveModel[] = $historyModel;
            $deleteModel[] = $this->model;
            $dcsModel = TblDcs::findOne($this->model->from_dest);
            if (!empty($dcsModel)) {
                $dcsHistoryModel = new TblDcsHistory();
                Yii::$app->operation->history($this->model, $dcsHistoryModel, 'UPDATE');
                $saveModel[] = $dcsHistoryModel;
                $dcsModel->route_code = NULL;
                $dcsModel->scenario = 'routeMapping';
                $saveModel[] = $dcsModel;
            }
            $organizationMappings = TblUserOrganizationMapping::find()->alias('om')
                    ->innerJoin('user u', 'u.id = om.user_id')
                    ->where(['om.organization_code' => $dcsModel->dcs_code, 'u.login_type' => 'route_supervisor', 'om.organization_type' => 'DCS'])
                    ->all();
            if (!empty($organizationMappings)) {
                foreach ($organizationMappings as $orgMapping) {
                    $deleteModel[] = $orgMapping;
                }
            }
            $transaction = $this->generalModel->saveDeleteTransaction([], $saveModel, $deleteModel, ['Mapped Route', 'delete']);
//            $record = $this->generalModel->deleteTransaction([$this->model, $saveModel]);
            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'Record Deleted Successfully.'];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblRouteMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblRouteMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblRouteMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFromDestinationList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $routes = new TblRouteMapping();
            $list = $routes->getDestinations($value[0], $value[1], 'from');
            if (!empty($list)) {
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $r['code'],
                        'name' => $r['name'] . '-' . $r['tname']);
                }
                echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo \yii\helpers\Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionToDestinationList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $routes = new TblRouteMapping();
            $list = $routes->getDestinations($value[0], $value[1], 'to');
            if (!empty($list)) {
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $r['code'],
                        'name' => $r['name'] . '-' . $r['tname'] . '-' . $r['ref_code']);
                }
                return \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        echo \yii\helpers\Json::encode(['output' => '', 'selected' => '']);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->route_code]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    //'bankDetails'=>$this->bankDetails,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    private function setModel() {
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);
        $this->model->route_name = ucwords($this->model->route_name);
    }

    public function actionMapRouteSource($id) {
        $model = new TblRouteMappingSources();
        $modelRouteSource = $this->findModel($id);
        $values = $model->getDestinations($modelRouteSource, $modelRouteSource->route_type, $modelRouteSource->union_code);
        $dest = [];
        $searchModel = new TblRouteMappingSourcesSearch();
        $searchModel->route_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        foreach ($values['destinations'] as $value) {
            $dest[$value['code'] . '-' . $value['tname']] = $value['ref_code'] . ' - ' . $value['name'] . ' - ' . Yii::t('app', $value['tname']);
        }
        if (Yii::$app->request->post()) {
            $routeData = !empty(Yii::$app->request->post('TblRouteMappingSources')) ? Yii::$app->request->post('TblRouteMappingSources') : [];
            $route_code = !empty($routeData['route_code']) ? $routeData['route_code'] : null;
            $from_dest = !empty($routeData['from_dest']) ? $routeData['from_dest'] : null;
            $user_code = !empty($routeData['user_code']) ? $routeData['user_code']: null;
            if (empty($from_dest)) {
                $model->addError('route_code', 'Please select at least one Source.');
            } else {
                $postData = array_filter($from_dest);
                $postData = Yii::$app->general->array_flatten($postData);
                $i = 0;
                $mapping = [];
                foreach ($postData as $data) {

                    $d = explode('-', $data);
                    $src[$i]['from_dest'] = $d[0];
                    $src[$i]['from_type'] = strtolower($d[1]);
                    $src[$i]['to_dest'] = $modelRouteSource->to_dest;
                    $src[$i]['to_type'] = $modelRouteSource->to_type;
                    $src[$i]['route_code'] = $id;

                    if ($modelRouteSource->route_type == 'Can') {
                        $societyCodes = TblSocietyCodes::find()->where(['dcs_code' => $d[0]])->one();
                        if (!empty($societyCodes)) {
                            $historyModel = new TblSocietyCodesHistory();
                            Yii::$app->operation->history($societyCodes, $historyModel, UPDATE);
                            $societyCodes->route_code = $modelRouteSource->route_code;
//                        $societyCodes->pooling_point_code = str_pad((int) $societyCodes->getPpCode() + $i, 3, '0', STR_PAD_LEFT);
//                        $societyCodes->bmc_code = $modelRouteSource->getBmcCode();
                            array_push($mapping, $historyModel);
                            array_push($mapping, $societyCodes);
                        }
                        $dcsCode = TblDcs::findOne($d[0]);
                        $dcsCode->scenario = 'routeMapping';
                        $dcsHistoryModel = new TblDcsHistory();
                        Yii::$app->operation->history($dcsCode, $dcsHistoryModel, UPDATE);
                        $dcsCode->route_code = $modelRouteSource->route_code;

                        array_push($mapping, $dcsCode);
                        array_push($mapping, $dcsHistoryModel);
                    }
                    $i++;
                }

                $record = $this->generalModel->mappingTransactionMultiField([], $src, ['TblRouteMappingSources', 'TblRouteMappingSourcesHistory'], $mapping);

                if (!empty($from_dest) && !empty($user_code)) {
                    $this->addUserOrganizationMapping($from_dest, 'DCS', $user_code, 1);
                }
                if ($record) {
                    Yii::$app->display->message(true, 'route source mapping', 'edit');
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('_map_route_source', [
                    'model' => $model, 'destinations' => $dest,
                    'selected' => $values['selected'],
                    'modelRouteSource' => $modelRouteSource, 'defaultValue' => '', //$modelUnion->district_code,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'routeMapping';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'routeMapping',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'form_validation_type' => 'route-create'
        ]);
    }

    public function actionAllRouteList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $routes = new TblRouteMapping();
                $plant = $parents[0];
                $mcc = !empty($parents[1]) ? $parents[1] : NULL;
                $bmc = !empty($parents[2]) ? $parents[2] : NULL;
                $data = $routes->routeFromDestination($plant, $mcc, $bmc, TRUE, FALSE, TRUE);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionDeleteMapRoute() {
        $searchModel = new TblDcsSearch();
        $getData = Yii::$app->request->get();
        $data = !empty($getData['TblDcsSearch']) ? $getData['TblDcsSearch'] : (!empty($getData['TblCustomerMasterSearch']) ? $getData['TblCustomerMasterSearch'] : []);
        $customer = FALSE;
        $searchModel->setAttributes($data);
        if (!empty($searchModel->customer_type) && $searchModel->customer_type != 'DCS') {
            $searchModel = new TblCustomerMasterSearch();
            $customer = TRUE;
            $searchModel->setAttributes($data);
        }
        $dataProvider = $searchModel->deleteroutemapsearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMapRoute';
        return $this->render('delete_map_route', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'customer' => $customer
        ]);
    }

    public function actionBulkDelete() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $deletedata = Yii::$app->request->post('selection');
                $customerType = !empty(Yii::$app->request->post()['TblDcsSearch']['customer_type']) ? Yii::$app->request->post()['TblDcsSearch']['customer_type'] : '';
                if (!empty($customerType) && $customerType == 'DCS') {
                    foreach ($deletedata as $code) {
                        $c = explode('###', $code);
                        $rateCodes = $c[0];
                        $dcs_codes = $c[1];
                        $this->model = TblRouteMappingSources::find()->where(['route_code' => $rateCodes, 'from_dest' => $dcs_codes, 'from_type' => 'society'])->one();
                        $deleteModel[] = $this->model;
                        $historyModel = new TblRouteMappingSourcesHistory();
                        Yii::$app->operation->history($this->model, $historyModel, DELETE);
                        $saveModel[] = $historyModel;

                        $dcsModel = TblDcs::findOne($dcs_codes);
                        $dcsHistoryModel = new TblDcsHistory();
                        Yii::$app->operation->history($this->model, $dcsHistoryModel, 'UPDATE');
                        $saveModel[] = $dcsHistoryModel;
                        $dcsModel->route_code = NULL;
                        $dcsModel->scenario = 'routeMapping';
                        $saveModel[] = $dcsModel;
                    }
                } else {
                    foreach ($deletedata as $key => $value) {
                        $customerModel = TblCustomerMaster::findOne($value);
                        $historyModel = new TblCustomerMasterHistory();
                        Yii::$app->operation->history($customerModel, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $customerModel->route_code = NULL;
                        $customerModel->scenario = 'deleteRouteMapping';
                        $saveModel[] = $customerModel;
                    }
                }
                $transaction = $this->generalModel->saveDeleteTransaction([], $saveModel, $deleteModel, ['Mapped Route', 'delete']);

                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
    }

    public function actionGetBmcRoute() {
        $bmcList = [];
        if (!empty($_POST['bmc'])) {
            $bmc = explode(',', $_POST['bmc']);
            $plant = explode(',', $_POST['plant']);
            $mcc = explode(',', $_POST['mcc']);
            $RLS = $_POST['RLS'];
            $model = new TblRouteMapping();
            $bmcList = $model->routeFromDestination($plant, $mcc, $bmc, FALSE, TRUE);
        }
        return Json::encode(['status' => 'success', 'data' => $bmcList]);
    }

    public function actionAllRouteTransporterList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $routes = new TblRouteMapping();
                $plant = $parents[0];
                $mcc = !empty($parents[1]) ? $parents[1] : NULL;
                $bmc = !empty($parents[2]) ? $parents[2] : NULL;
                $data = $routes->routeFromDestination($plant, $mcc, $bmc);
                $route_codes_array = array_keys($data);
                $route_codes = [];
                foreach ($route_codes_array as $i => $v) {
                    $route_codes[] = (string) $v;
                }
                $model = new TblVehicleKmInfo();
                $data = $model->getRouteTransporterList($route_codes);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    private function addUserOrganizationMapping($data, $type, $userId, $active) {
        foreach ($data as $value) {
            $dcsCode = explode('-', $value)[0];
            $modelNew = new TblUserOrganizationMapping();
            $modelNew->organization_code = $dcsCode;
            $modelNew->organization_type = $type;
            $modelNew->user_id = $userId;
            $modelNew->is_active = $active;
            if (!$modelNew->save()) {
                Yii::error("Failed to save organization mapping for user ID $userId and DCS $dcsCode.");
            }
        }
    }

    public function actionUserList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $model = new TblRouteMapping();
                $data = $model->getUserList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $val['user_code'], 'name' => $val['name'] . (!empty($val['employee_id']) ? ' - ' . $val['employee_id'] : ''));
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
