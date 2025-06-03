<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmcSearch;
use app\modules\organisation\models\TblDcsBmcHistory;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\organisation\models\TblRouteMappingSourcesSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\organisation\models\TblBmcMilkType;
use app\modules\organisation\models\TblBmcMilkTypeHistory;
use app\modules\organisation\models\TblBmcGroupMapping;
use app\modules\organisation\models\TblBmcGroupMappingSearch;
use yii\helpers\ArrayHelper;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\organisation\models\TblBmcGroupMappingHistory;
use app\modules\organisation\models\TblMccPlantGroupMapping;
use app\modules\organisation\models\TblMccPlantGroupMappingSearch;
use app\modules\organisation\models\TblBmcSilosInfo;
use app\modules\organisation\models\TblBmcSilosInfoSearch;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\document\controllers\TblAttachmentController;
use app\modules\organisation\models\TblBmcChillerInfo;
use app\modules\organisation\models\TblBmcChillerInfoSearch;
use app\modules\organisation\models\TblBmcChillerInfoHistory;
use app\modules\organisation\models\TblPlant;
use app\modules\tankermovement\models\TblPartyMaster;

/**
 * TblDcsBmcController implements the CRUD actions for TblDcsBmc model.
 */
class TblDcsBmcController extends \app\controllers\ChildController
{

    public $bankDetails;
    public $contactDetails;
    public $freeAccessActions = ['bmc-list', 'bmc-list-union', 'get-mcc-bmc', 'poured-bmc-list', 'channel-bmc-list', 'get-plant-bmc', 'union-bmc-list', 'get-plant-bmc-with-party'];

    /**
     * Lists all TblDcsBmc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new TblDcsBmc();
        $searchModel = new TblDcsBmcSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcsBmc model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $bsearchModel = new TblBankDetailsSearch();
        $bsearchModel->module_name = 'bmc';
        $bsearchModel->module_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);

        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'bmc';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);

        $ssearchModel = new TblRouteMappingSourcesSearch();
        $ssearchModel->from_type = 'society';
        $ssearchModel->to_type = 'bmc';
        $ssearchModel->to_dest = $id;
        $sdataProvider = $ssearchModel->search(Yii::$app->request->queryParams);

        $snsearchModel = new TblBmcSilosInfoSearch();
        $snsearchModel->module_name = 'BMC';
        $snsearchModel->module_code = $id;
        $sndataProvider = $snsearchModel->search(Yii::$app->request->queryParams);
        $isaction = FALSE;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
            'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
            'sdataProvider' => $sdataProvider, 'ssearchModel' => $ssearchModel,
            'sndataProvider' => $sndataProvider, 'snsearchModel' => $snsearchModel, 'isaction' => $isaction
        ]);
    }

    /**
     * Creates a new TblDcsBmc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->viewFile = 'create';
        $this->model = new TblDcsBmc();
        $this->bankDetails = new TblBankDetails();
        $this->contactDetails = new TblContactDetails();
        $this->model->valid_from = date('Y-m-d');
        $this->contactDetails->scenario = 'additional';
        $this->contactDetails->form_validation_type = 'bmc-create';
        $this->contactDetails->department = 'bmc_operator';
        $validate = 1;
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            $this->model->bmc_code = $this->model->getCode();
            $this->model->bmc_name = ucwords($this->model->bmc_name);
            $this->setModel($this->model);
            $this->bankDetails->load(Yii::$app->request->post());
            if (!empty($this->bankDetails->bank_code)) {
                $this->bankDetails->setModel('bmc', $this->model->bmc_code);
                $this->bankDetails->scenario = 'bank_selected';
                array_push($master, $this->bankDetails);
            }
            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('bmc', $this->model->bmc_code);
            $master[] = $this->model;
            $modelMilkType = $this->setMilk();
            if (!empty($modelMilkType)) {
                $master = array_merge($master, $modelMilkType);
            }

            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'bmc_name', $this->model->bmc_name);
            if ($validate == 1 && empty($this->model->getErrors())) {
                $transaction = $this->generalModel->saveTransaction($master, [$this->contactDetails], [Yii::t('app', 'Society BMC'), 'create']);
                if ($transaction == 'customRedirect') {
                    if ($this->model->bmc_type_code == '2') {
                        return $this->redirect(['tbl-dcs/create', 'bmc_code' => $this->model->bmc_code, 'is_bmc' => 1]);
                    } else {
                        return $this->{$transaction}();
                    }
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDcsBmc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;
        $master = [];
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsBmcHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $this->model->bmc_name = ucwords($this->model->bmc_name);

            $milkType = TblBmcMilkType::find()->where(['bmc_code' => $this->model->bmc_code, 'is_active' => 1])->all();
            $returnedArray = \yii\helpers\ArrayHelper::map($milkType, 'milk_type_code', 'milk_type_code');

            $toRevoke = array_diff($returnedArray, $this->model->milk_type_code);
            $toAssign = array_diff($this->model->milk_type_code, $returnedArray);


            foreach ($toRevoke as $value) {
                $milkModel = TblBmcMilkType::find()->where(['bmc_code' => $this->model->bmc_code, 'milk_type_code' => $value])->one();
                $milkHistory = new TblBmcMilkTypeHistory();
                Yii::$app->operation->history($milkModel, $milkHistory, DELETE);
                array_push($master, $milkHistory);
                array_push($master, $milkModel);
            }
            foreach ($toAssign as $value) {
                $milkModel = new TblBmcMilkType();
                $milkModel->bmc_code = $this->model->bmc_code;
                $milkModel->milk_type_code = $value;
                $milkModel->is_active = $this->model->is_active;
                array_push($master, $milkModel);
            }
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'bmc_name', $_POST['TblDcsBmc']['bmc_name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $master, [Yii::t('app', 'Society BMC'), 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblDcsBmc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_bmc', Yii::$app->request->post('id'), 'bmc_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblDcsBmcHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            //            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], [false, 'TblContactDetails', 'TblContactDetailsHistory'], ['bmc_code', 'bmc']);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionBankDetails($id)
    {
        $bankDetails = new TblBankDetails();
        $bankDetails->scenario = 'additional';
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name = 'bmc';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelDcs = $this->findModel($id);
        return $this->render('../../../details/views/tbl-bank-details/create', [
            'model' => $bankDetails,
            'id' => $id,
            'module' => 'bmc',
            'dist' => $modelDcs->district_code,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dist_field' => 'tbldcsbmc-district_code'
        ]);
    }

    public function actionContactDetails($id)
    {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'bmc';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
            'model' => $contactDetails,
            'id' => $id,
            'module' => 'bmc',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    protected function customRender()
    {
        return $this->render($this->viewFile, [
            'model' => $this->model,
            'bankDetails' => $this->bankDetails,
            'contactDetails' => $this->contactDetails
        ]);
    }

    /**
     * Finds the TblDcsBmc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcsBmc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblDcsBmc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function setModel()
    {
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);
    }

    protected function customRedirect()
    {
        return $this->redirect(['index', 'dcs' => Yii::$app->request->get('dcs'), 'dcsname' => Yii::$app->request->get('dcsname'), 'subcenter' => Yii::$app->request->get('subcenter'), 'subname' => Yii::$app->request->get('subname')]);
    }

    public function actionBmcList()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $mccs = new TblDcsBmc();
                $rls = isset($parents[1]) && $parents[1] == 'false' ? 'FALSE' : 'TRUE';
                $data = $mccs->getBMCList($parents[0], $rls, TRUE);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionBmcListUnion()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $transporter_code = $parents[0];
                $plants = new TblDcsBmc();
                $out = $plants->bmcUnion($parents[0]);

                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetMccBmc()
    {
        $mccList = [];
        if (!empty($_POST['mcc'])) {
            $palnt = explode(',', $_POST['mcc']);
            $RLS = $_POST['RLS'];
            $model = new TblDcsBmc();
            $mccList = $model->getBMCList($palnt, $RLS);
        }
        return Json::encode(['status' => 'success', 'data' => $mccList]);
    }

    private function setMilk()
    {
        $milkArray = $this->model->milk_type_code;
        $list = [];
        foreach ($milkArray as $row) {
            $modelMilk = new TblBmcMilkType();
            $modelMilk->bmc_code = $this->model->bmc_code;
            $modelMilk->milk_type_code = $row;
            $modelMilk->is_active = 1;
            array_push($list, $modelMilk);
        }
        return $list;
    }

    public function actionBmcMapping($id)
    {
        $DcsBmcModel = new TblDcsBmc();
        $bmc_data = $DcsBmcModel->getBMCList([], TRUE, FALSE, TRUE);
        unset($bmc_data[$id]);
        $model = new TblBmcGroupMapping();
        $searchModel = new TblBmcGroupMappingSearch();
        $searchModel->bmc_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $exist_data = ArrayHelper::map($dataProvider->getModels(), 'p_bmc_code', 'p_bmc_code');
        $bmc_data = array_diff_key($bmc_data, $exist_data);
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblBmcGroupMapping'])) {
            $bmc_code = Yii::$app->request->post()['TblBmcGroupMapping']['p_bmc_code'];
            $main_mcc_code = $searchModel->mainBmcCode->mcc_plant_code;
            $mcc_codes = [];
            $master = [];
            if (!empty($bmc_code)) {
                foreach ($bmc_code as $mapped_bmc_code) {
                    $model_bmc = new TblBmcGroupMapping();
                    $model_bmc->bmc_code = $id;
                    $model_bmc->p_bmc_code = $mapped_bmc_code;
                    $mcc_codes[] = $model_bmc->bmcCode->mcc_plant_code;
                    $master[] = $model_bmc;
                    $mainBmc = $model_bmc->mainBmcCode;
                    $groupBmc = $model_bmc->bmcCode;
                    $main_org_data = ['union_code' => $mainBmc->union_code, 'plant_code' => $mainBmc->plant_code, 'mcc_plant_code' => $mainBmc->mcc_plant_code, 'bmc_code' => $id];
                    $group_org_data = ['union_code' => $groupBmc->union_code, 'plant_code' => $groupBmc->plant_code, 'mcc_plant_code' => $groupBmc->mcc_plant_code, 'bmc_code' => $mapped_bmc_code];
                    Yii::$app->general->generateGroupMappingSetBox($master, $main_org_data, $group_org_data);
                }
            }
            if (!empty($mcc_codes)) {
                $searchMcc = new TblMccPlantGroupMappingSearch();
                $searchMcc->mcc_plant_code = $main_mcc_code;
                $dataProviderMcc = $searchMcc->search(Yii::$app->request->queryParams);
                $exist_data = ArrayHelper::map($dataProviderMcc->getModels(), 'p_mcc_plant_code', 'p_mcc_plant_code');
                $mcc_data = array_unique(array_diff_key($mcc_codes, $exist_data));
                foreach ($mcc_data as $mapped_mcc_code) {
                    $model_mcc = new TblMccPlantGroupMapping();
                    $model_mcc->mcc_plant_code = $main_mcc_code;
                    $model_mcc->p_mcc_plant_code = $mapped_mcc_code;
                    $master[] = $model_mcc;
                    $mainMcc = $model_mcc->mainMccCode;
                    $groupMcc = $model_mcc->mccCode;
                    $main_org_data = ['union_code' => $mainMcc->union_code, 'plant_code' => $mainMcc->plant_code, 'mcc_plant_code' => $main_mcc_code];
                    $group_org_data = ['union_code' => $groupMcc->union_code, 'plant_code' => $groupMcc->plant_code, 'mcc_plant_code' => $mapped_mcc_code];
                    Yii::$app->general->generateGroupMappingSetBox($master, $main_org_data, $group_org_data, 'mcc_plant_code', 'MCC');
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, ['BMC Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }

        return $this->render('_bmc_mapping', [
            'model' => $model, 'bmc_data' => $bmc_data,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDeleteBmc()
    {
        $model = TblBmcGroupMapping::findOne(Yii::$app->request->post('id'));
        $record = [];
        $historyModel = new TblBmcGroupMappingHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionSilosInfo($id)
    {
        $contactDetails = new TblBmcSilosInfo();
        $searchModel = new TblBmcSilosInfoSearch();
        $searchModel->module_name = 'BMC';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $isaction = TRUE;
        return $this->render('@app/modules/organisation/views/tbl-bmc-silos-info/create', [
            'model' => $contactDetails,
            'id' => $id,
            'module' => 'BMC',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isaction' => $isaction
        ]);
    }

    public function actionPouredBmcList()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $self = isset($parents[1]) ? $parents[1] : TRUE;
                $mccs = new TblBmcGroupMapping();
                $data = $mccs->getBMCList($parents[0], 'TRUE', TRUE, $self);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionExportSentbox($id)
    {
        $this->model = $this->findModel($id);

        $dcsModel = new TblDcs();
        $dcsArray = $dcsModel->getSocietys($id);

        $vendorModel = new TblCustomerMaster();
        $vendorArray = $vendorModel->getvendor($id);
        //        $master = array_merge($dcsArray, $vendorArray);
        $jsonData = [];
        ob_clean();
        foreach ($dcsArray as $dcs) {
            $operation = !empty($dcs->updated_at) ? 'UPDATE' : 'INSERT';
            $sentbox = $dcs->sentboxModel($id, 'BMC');
            $sentboxData = $sentbox->setSentboxDownload($dcs, $operation);
            $jsonData[] = Json::encode($sentbox->jsonModel($sentboxData), JSON_UNESCAPED_UNICODE);
        }
        foreach ($vendorArray as $customer) {
            $operation = !empty($customer->updated_at) ? 'UPDATE' : 'INSERT';
            $sentbox = $customer->sentboxModel($id, 'BMC');
            $sentboxData = $sentbox->setSentboxDownload($customer, $operation);
            $jsonData[] = Json::encode($sentbox->jsonModel($sentboxData), JSON_UNESCAPED_UNICODE);
        }
        $extention = 'txt';
        $header = [
            'mime' => 'text/plain',
            'extension' => $extention,
            'writer' => 'Excel2007',
        ];

        $labelT = $id . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'] .
            header('Content-Type: ' . $header['mime']);
        //        header('Content-Type: text/plain');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        //        header("Content-Type: application/xls");
        //        header("Content-Disposition: attachment; filename={$fileName}");
        //        header("Pragma: no-cache");
        //        header("Expires: 0");
        foreach ($jsonData as $json) {
            $key = Yii::$app->general->SetSecurityEncryptionKey('UNION', $this->model->union_code);
            Yii::$app->encrypter->setGlobalPassword($key);
            echo Yii::$app->general->encryptData($json) . PHP_EOL;
        }
        exit();
    }

    public function actionChannelBmcList()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $bmc = new TblDcsBmc();
                $data = $bmc->getBMCList('', 'TRUE', TRUE, FALSE, $parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetPlantBmc()
    {
        $plantList = [];
        $mappedPartyList = [];
        if (!empty($_POST['plant_code'])) {
            $plant = explode(',', $_POST['plant_code']);
            $model = new TblDcsBmc();
            $plantList = $model->getBMCList([], 'TRUE', false, false, [], $plant, 'BMC');
        }
        $partyList = [];
        if (!empty($_POST['action_type']) && $_POST['action_type'] == 'party' && !empty($_POST['union_code'])) {
            $union_code = $_POST['union_code'];
            $model = new TblPartyMaster();
            $partyList = $model->getUnionPartyList($union_code);
            $mappedPartyList = $model->getMappedPartyList($union_code);
        }
        $result = $plantList + $partyList + $mappedPartyList;
        return Json::encode(['status' => 'success', 'data' => $result]);
    }

    public function actionGetPlantBmcWithParty()
    {
        $plantList = [];
        $mappedPartyList = [];
        if (!empty($_POST['plant_code'])) {
            $plant = explode(',', $_POST['plant_code']);
            $model = new TblPlant();
            $plantList = $model->getPlantData($plant);
        }
        $bmcList = [];
        if (!empty($_POST['plant_code'])) {
            $plant = explode(',', $_POST['plant_code']);
            $model = new TblDcsBmc();
            $bmcList = $model->getBMCList([], 'FALSE', false, false, [], $plant, 'BMC');
        }
        $partyList = [];
        if (!empty($_POST['action_type']) && $_POST['action_type'] == 'party' && !empty($_POST['union_code'])) {
            $union_code = $_POST['union_code'];
            $model = new TblPartyMaster();
            $partyList = $model->getUnionPartyList($union_code);
            $mappedPartyList = $model->getMappedPartyList($union_code);
        }
        $result = $plantList + $bmcList + $partyList + $mappedPartyList;
        return Json::encode(['status' => 'success', 'data' => $result]);
    }

    public function actionUnionBmcList()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) || (isset($parents[1]) && $parents[1] == true)) {
                $rls = isset($parents[1]) && $parents[1] == 'false' ? 'FALSE' : 'TRUE';
                $mccs = new TblDcsBmc();
                $data = $mccs->getUnionBMCList($parents[0], $rls);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionBmcDocumentUpload($id)
    {
        $model = $this->findModel($id);
        $module_code = $model->bmc_code;
        $module_name = 'tbl_bmc';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('bmc', $id, $model, $module_code, $module_name);
    }

    public function actionBmcChillerInfo($id)
    {
        $model = new TblBmcChillerInfo();
        $searchModel = new TblBmcChillerInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblBmcChillerInfo'])) {
            $update = FALSE;
            if (!empty(Yii::$app->request->post()['TblBmcChillerInfo']['chiller_info_code'])) {
                $model = TblBmcChillerInfo::findOne(Yii::$app->request->post()['TblBmcChillerInfo']['chiller_info_code']);
                $historyModel = new TblBmcChillerInfoHistory();
                Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                $model->load(Yii::$app->request->post());
                $modelSave[] = $historyModel;
                $update = TRUE;
            } else {
                $model->scenario = 'createChillerInfo';
                $bmc_data = TblDcsBmc::findOne($id);
                $data = Yii::$app->request->post()['TblBmcChillerInfo'];
                $model->attributes = $data;
                $model->bmc_code = $bmc_data->bmc_code;
                $model->mcc_plant_code = $bmc_data->mcc_plant_code;
                $model->plant_code = $bmc_data->plant_code;
                $model->union_code = $bmc_data->union_code;
            }
            $model->installation_date = !empty($model->installation_date) ? date('Y-m-d', strtotime($model->installation_date)) : '';
            $model->agreement_from_date = !empty($model->agreement_from_date) ? date('Y-m-d', strtotime($model->agreement_from_date)) : '';
            $model->agreement_to_date = !empty($model->agreement_to_date) ? date('Y-m-d', strtotime($model->agreement_to_date)) : '';
            $modelSave[] = $model;

            $transaction = $this->generalModel->saveTransaction($modelSave, ['BMC Chiller Info', ($update) ? 'edit' : 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['tbl-dcs-bmc/bmc-chiller-info', 'id' => $id]);
            }
        }

        return $this->render('@app/modules/organisation/views/tbl-bmc-chiller-info/create', [
            'model' => $model,
            'id' => $id,
            'module' => 'BMC',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateChillerInfo()
    {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['chiller_info_code'])) {
            $modelData = TblBmcChillerInfo::findOne($_POST['chiller_info_code']);
            if (!empty($modelData)) {
                $data['status'] = 'success';
                $modelData->installation_date = !empty($modelData->installation_date) ? date('d-m-Y', strtotime($modelData->installation_date)) : '';
                $modelData->agreement_from_date = !empty($modelData->agreement_from_date) ? date('d-m-Y', strtotime($modelData->agreement_from_date)) : '';
                $modelData->agreement_to_date = !empty($modelData->agreement_to_date) ? date('d-m-Y', strtotime($modelData->agreement_to_date)) : '';
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

    public function actionDeactivateBmcChiller($id) {
        $model = TblBmcChillerInfo::findOne($id);
        $historyModel = new TblBmcChillerInfoHistory();
        Yii::$app->operation->history($model, $historyModel, UPDATE);
        $model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$model, $historyModel], ['BMC Chiller Info', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'BMC Chiller Deactivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'BMC Chiller Not Deactivated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }
}
