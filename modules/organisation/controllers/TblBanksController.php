<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBanksSearch;
use app\modules\organisation\models\TblBanksHistory;
use app\modules\organisation\models\TblBanksDistrictsMapping;
use app\modules\organisation\models\TblBanksDistrictsMappingHistory;
use app\modules\geo\models\TblDistricts;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblBanksController implements the CRUD actions for TblBanks model.
 */
class TblBanksController extends \app\controllers\ChildController {

    public $disable,$map_model,$district;

    /**
     * Lists all TblBanks models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TblBanks();
        $searchModel = new TblBanksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBanks model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBanks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBanks();
        $districtModel=new TblDistricts();
        $this->map_model=[];
        $this->district=$districtModel->getDistrict(Yii::$app->session->get('States'));
        
        $this->viewFile = 'create';
        $this->disable = 0;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->bank_name = ucwords($this->model->bank_name);
            $this->model->bank_code = $this->model->getCode();

            $mapList = [];
            if (!$this->model->nationalized_bank) {
//                echo $this->model->nationalized_bank;
                $modelDistricts = $this->setDistricts();
                if (!empty($modelDistricts))
                    $mapList = array_merge($mapList, $modelDistricts);
            }
            $transaction = $this->generalModel->saveTransaction([$this->model], $mapList, ['bank', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblBanks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->disable = $this->generalModel->callSp('sp_delete_master_org', ['tbl_banks', 'tbl_bank_district', 'tbl_bank_district_history', Yii::$app->request->post('id'), 'bank_code']);
        $districtModel=new TblDistricts();
        $mapModel=new TblBanksDistrictsMapping();
        $mapModel->bank_code=$this->model->bank_code;
        $this->map_model=  \yii\helpers\ArrayHelper::map($mapModel->find()->select(['district_code'])->where(['bank_code' => $id])->all(),'district_code','district_code');
        $this->district=$districtModel->getDistrict(Yii::$app->session->get('States'));

        if (Yii::$app->request->post()) {
//            var_dump(Yii::$app->request->post());exit;
            $historyModel = new TblBanksHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->model->bank_name = ucwords($this->model->bank_name);

            $mappingList = [];
            if (!$this->model->nationalized_bank && !empty($this->model->district)) {
                $milkType = TblBanksDistrictsMapping::find()->where(['bank_code' => $this->model->bank_code, 'is_active' => 1])->all();
                $returnedArray = \yii\helpers\ArrayHelper::map($milkType, 'district_code', 'district_code');

                $toRevoke = array_diff($returnedArray, $this->model->district);
                $toAssign = array_diff($this->model->district, $returnedArray);


                foreach ($toRevoke as $value) {
                    $districtModel = TblBanksDistrictsMapping::find()->where(['bank_code' => $this->model->bank_code, 'district_code' => $value])->one();
                    $districtHistory = new TblBanksDistrictsMappingHistory();
                    Yii::$app->operation->history($districtModel, $districtHistory, DELETE);
                    array_push($mappingList, $districtHistory);
                    array_push($mappingList, $districtModel);
                }

                foreach ($toAssign as $value) {
                    $districtModel = new TblBanksDistrictsMapping();
                    $districtModel->bank_code = $this->model->bank_code;
                    $districtModel->district_code = $value;
                    $districtModel->is_active = $this->model->is_active;
                    array_push($mappingList, $districtModel);
                }
            }           
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $mappingList, ['bank', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    private function setDistricts() {
        $districtArray = $this->model->district;
        $list = [];
        if ($districtArray) {
            foreach ($districtArray as $row) {
                $modelDistrict = new TblBanksDistrictsMapping();
                $modelDistrict->bank_code = $this->model->bank_code;
                $modelDistrict->district_code = $row;
                $modelDistrict->is_active = $this->model->is_active;
                array_push($list, $modelDistrict);
            }
        }
        return $list;
    }

    public function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model, 'disable' => $this->disable,'map_model'=>  $this->map_model,'district'=>  $this->district]);
    }

    /**
     * Deletes an existing TblBanks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_banks', 'tbl_bank_district', 'tbl_bank_district_history', Yii::$app->request->post('id'), 'bank_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblBanksHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel, 'importCsv'], ['TblBanksDistrictsMapping', 'TblBanksDistrictsMappingHistory'], 'bank_code');
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBanks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBanks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBanks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Description: return branch array
     * By: Dhara
     * DAte: 8-11-2016
     * @return type
     */
    public function actionDistrictList() {
        $out = NULL;

        if (Yii::$app->request->post()) {
            $model = new TblBanks();
            $model->state = Yii::$app->request->post('id');
            $model->bank_code = Yii::$app->request->post('code');
            $district_list = $model->getDistrict();
            $selected = '';
            echo \yii\helpers\Json::encode(['status' => 'success', 'output' => $district_list['value'], 'selected' => $district_list['selected'], 'checked' => $district_list['checked'], 'disabled' => $district_list['disabled'], 'disableState' => $district_list['disableState']]);
            return;
        }
        echo \yii\helpers\Json::encode(['status' => 'error', 'output' => '', 'selected' => '', 'checked' => '', 'disabled' => '', 'disableState' => '']);
    }

    public function actionBankList() {
        $out = NULL;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $district = new TblBanksDistrictsMapping();
            $list = $district->getBankList($value[0]);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
            return;
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionMapDistricts($id) {
        $this->layout = "@app/themes/nddb/layouts/dashboardLayout.php";
        $model = new TblBanksDistrictsMapping();
        $this->model = $this->findModel($id);
        $values = $model->getDistrict($id);

        if (Yii::$app->request->post()) {
            $bank_code = Yii::$app->request->post('TblBanksDistrictsMapping')['bank_code'];
            $district_code = Yii::$app->request->post('TblBanksDistrictsMapping')['district_code'];
            $postData = array_filter($district_code);

            if (empty($postData)) {
                $model->addError('bank_code', 'Please select at lease one district.');
                return $this->render('_map_districts', [
                            'model' => $model, 'district_list' => $values['district_list'], 'selected' => [], 'bank_name' => $this->model->bank_name
                ]);
            }

            $data = $model->find()->where(['bank_code' => $id, 'is_active' => 1, 'is_delete' => 0])->all();

            $returnedArray = \yii\helpers\ArrayHelper::map($data, 'district_code', 'district_code');

            $districts = Yii::$app->general->array_flatten($values['district_list'], 1);
            $postData = Yii::$app->general->array_flatten($postData);

            $oldAssignments = array_keys($returnedArray);
            $newAssignments = array_intersect(array_flip($districts), $postData);

            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_diff($oldAssignments, $newAssignments);

            $record = $this->generalModel->mappingTransaction($toRevoke, $toAssign, ['TblBanksDistrictsMapping', 'TblBanksDistrictsMappingHistory'], ['bank_code', 'district_code'], $id);

            if ($record) {
                Yii::$app->display->message(true, 'bank district mapping', 'edit');
                return $this->redirect(['index']);
            }
        }
        return $this->render('_map_districts', [
                    'model' => $model, 'district_list' => $values['district_list'], 'selected' => $values['selected'], 'bank_name' => $this->model->bank_name
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->bank_code]);
    }

    public function actionBankDistrictList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $bankCode = $value[1];
            $stateCode = $value[0];
            if ($bankCode) {
                $bank = $this->findModel($bankCode);

                if ($bank->nationalized_bank == 1) {
                    $district = new TblDistricts();
                    $list = $district->getDistrict($stateCode);
                } else {
                    $district = new TblBanksDistrictsMapping();
                    $list = $district->getDistrictArray($bankCode, $stateCode);
                }
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                return Json::encode(['output' => $out]);
                return;
            }
        }
        return Json::encode(['output' => '']);
    }

}
