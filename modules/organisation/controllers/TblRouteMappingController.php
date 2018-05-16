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
use app\modules\organisation\models\TblDcsHistory;

/**
 * TblRouteMappingController implements the CRUD actions for TblRouteMapping model.
 */
class TblRouteMappingController extends \app\controllers\ChildController {

    public $bankDetails;
    public $contactDetails;
    public $freeAccessActions = ['route-list'];

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
            if ($validate == 1) {
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
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_route_mapping_sources', Yii::$app->request->post('id'), 'route_code']);
        if ($valueOut == 0) {
            $this->model = TblRouteMappingSources::findOne(Yii::$app->request->post('id'));
            $historyModel = new TblRouteMappingSourcesHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
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
                        'name' => $r['name'] . '-' . $r['tname']);
                }
                echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
                return;
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
            $dest[$value['code'] . '-' . $value['tname']] = $value['name'] . '-' . $value['tname'];
        }
        if (Yii::$app->request->post()) {
            $route_code = Yii::$app->request->post('TblRouteMappingSources')['route_code'];
            $from_dest = Yii::$app->request->post('TblRouteMappingSources')['from_dest'];
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
                        $historyModel = new TblSocietyCodesHistory();
                        Yii::$app->operation->history($societyCodes, $historyModel, UPDATE);
                        $societyCodes->bmc_code = $modelRouteSource->getBmcCode();
                        $societyCodes->route_code = $modelRouteSource->route_code;
                        $societyCodes->pooling_point_code = str_pad((int) $societyCodes->getPpCode() + $i, 3, '0', STR_PAD_LEFT);

                        $dcsCode = TblDcs::findOne($d[0]);
                        $dcsCode->scenario = 'routeMapping';
                        $dcsHistoryModel = new TblDcsHistory();
                        Yii::$app->operation->history($dcsCode, $dcsHistoryModel, UPDATE);
                        $dcsCode->route_code = $modelRouteSource->route_code;

                        array_push($mapping, $societyCodes);
                        array_push($mapping, $historyModel);
                        array_push($mapping, $dcsCode);
                        array_push($mapping, $dcsHistoryModel);
                    }
                    $i++;
                }

                $record = $this->generalModel->mappingTransactionMultiField([], $src, ['TblRouteMappingSources', 'TblRouteMappingSourcesHistory'], $mapping);

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
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionRouteList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $transporter_code = $parents[0];
                $routes = new TblRouteMapping();
                $out = $routes->rlsRoutes($parents[0]);

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

}
