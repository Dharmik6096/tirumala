<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsSearch;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\organisation\models\TblDcsVillageMapping;
use app\modules\organisation\models\TblDcsVillageMappingHistory;
use app\modules\organisation\models\TblDcsBmcSearch;
use app\modules\organisation\models\TblDcsMilkType;
use app\modules\organisation\models\TblDcsMilkTypeHistory;
use app\modules\organisation\models\TblMccPlant;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\general\models\TblSocietyVendor;
use app\models\TblUserOrganizationMapping;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\helpers\Json;
use yii\base\Model;
use app\modules\organisation\models\TblSocietyCollection;
use app\modules\organisation\models\TblSocietyCollectionHistory;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilitySearch;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityHistory;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\general\models\TblDpuIncentiveMaster;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblMemberHistory;
use app\modules\details\models\TblBankDetailsHistory;

/**
 * TblDcsController implements the CRUD actions for TblDcs model.
 */
class TblDcsController extends ChildController {

    public $bankDetails;
    public $contactDetails;
    public $freeAccessActions = ['dcs-list', 'get-bmc-dcs', 'merge-dcs-customer-list', 'payment-cycle-dcs-list'];

    /**
     * Lists all TblDcs models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TblDcs();
        $searchModel = new TblDcsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcs model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $dcsBmc = new TblDcsBmcSearch();

        //$dcsBmc->dcs_code = $id;
        $dataProvider = $dcsBmc->search(Yii::$app->request->queryParams);
        $bsearchModel = new TblBankDetailsSearch();
        $bsearchModel->module_name = 'society';
        $bsearchModel->module_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'society';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $dcsBmc, 'dataProvider' => $dataProvider,
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblDcs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($bmc_code = '', $is_bmc = 0) {
        $this->model = new TblDcs();
        $this->viewFile = 'create';
        $this->model->scenario = 'createDcs';
        $this->bankDetails = new TblBankDetails();
        $this->contactDetails = new TblContactDetails();
        $this->contactDetails->form_validation_type = 'dcs-create';
        $this->model->district_code = Yii::$app->session->get('Districts');
        $this->model->valid_from = date('Y-m-d');
        $this->contactDetails->scenario = 'additional';
        $validate = 1;
        $this->model->bmc_code = !empty($bmc_code) ? $bmc_code : $this->model->bmc_code;
        $this->model->is_bmc = $is_bmc;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->dcs_code = $this->model->getCode();

            if ($this->model->street1 != '' && $this->model->street2 != '') {
                $this->model->address = $this->model->fullAddress();
            } elseif ($this->model->street1 == '' && $this->model->street2 != '') {
                $this->model->address = $this->model->street2;
            } else {
                $this->model->address = $this->model->street1;
            }
            $this->setModel();

            /* if ($this->model->is_bmc == 3 && $this->model->destination_code == '') {
              $this->model->destination_code = 0;
              $this->model->destination_type = 0;
              } */


            //set mapping data
            $mapList = [];
            if (!empty($this->model->village_code)) {
                $modelMapping = new TblDcsVillageMapping();
                $this->setMapping($modelMapping);
                array_push($mapList, $modelMapping);
            }

            $modelCodes = new TblSocietyCodes();
            $modelCodes->dcs_code = $this->model->dcs_code;
            $modelCodes->bipl_code = $modelCodes->getBiplCode($this->model->village_code);
            $modelCodes->union_code = $this->model->union_code;
            $modelCodes->bmc_code = $this->model->bmc_code;
            //$modelCodes->pooling_point_code = $modelCodes->getPpCode();
            array_push($mapList, $modelCodes);
            //$mapList[0] = $modelMapping;
            $this->bankDetails->load(Yii::$app->request->post());
            $bankValidate = 1;
            if (!empty($this->bankDetails->bank_code)) {
                $this->bankDetails->setModel('society', $this->model->dcs_code);
                $this->bankDetails->scenario = 'bank_selected';
                array_push($mapList, $this->bankDetails);

                $bankValidate = Yii::$app->warning->codeWarningBankAc($this->bankDetails);
            }
            $this->contactDetails->load(Yii::$app->request->post());
            if (!empty($this->contactDetails->mobile_no)) {
                $this->contactDetails->setModel('society', $this->model->dcs_code);
                array_push($mapList, $this->contactDetails);
            }

            //set milk type data
            $modelMilkType = $this->setMilk();
            $this->model->default_milk_type = !empty($this->model->milk_type_auto) ? 8 : $this->model->setDefaultMilkType($modelMilkType);
            if (!empty($modelMilkType))
                $mapList = array_merge($mapList, $modelMilkType);

//               // $list = $this->model->setSubCenter('I');
//
//                if (!empty($list))
//                    $mapList = array_merge($mapList, $list);
            //var_dump($this->model);exit;
            //set vendor applicability
            if ($this->model->vendor != 'NA') {
                $vendorModel = new TblSocietyVendor();
                $vendorModel->dcs_code = $this->model->dcs_code;
                $vendorModel->vendor_code = $this->model->vendor;
                array_push($mapList, $vendorModel);
            }
            if ($bankValidate == 1 && $_POST['warning'] == 0) {
                $msg = $this->model->dcs_name . ' for dcs/subcenter/collection center';
                $validate = Yii::$app->warning->unique($this->model, 'dcs_name', $this->model->dcs_name, $msg);
            }
            if ($bankValidate == 1 && $validate == 1 && empty($this->model->getErrors())) {
                $this->model->setModelData($this->model, $mapList);
                $member = [];
                if (!empty($this->model->auto_member_create)) {
                    for ($x = 1; $x <= 100; $x += 1) {
                        $memberModel = new TblMember();
                        $memberModel->attributes = $this->model->attributes;
                        $memberModel->ex_member_code = str_pad($x, 4, '0', STR_PAD_LEFT);
                        $memberModel->member_code = $this->model->dcs_code . $memberModel->ex_member_code;
                        $memberModel->ref_code = $memberModel->member_code;
                        $memberModel->animal_type_code = 1;
                        $memberModel->address = $this->model->dcs_name;
                        $memberModel->no_of_buffalo = $memberModel->no_of_cow_cross = $memberModel->no_of_cow_ind = $memberModel->total_animals = 0;
                        $memberModel->member_type_code = '1';
                        $memberModel->member_name = 'No Name';
                        $memberModel->gender_code = 1;
                        $memberModel->caste_category_code = 1;
                        $memberModel->member_type_code = 1;
                        array_push($mapList, $memberModel);
                    }
                }
                $transaction = $this->generalModel->saveTransaction([$this->model], $mapList, ['society', 'create']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
                        if (!empty($vendorModel)) {
                            $orgMap = [];
                            $userModel = new User();
                            $users = $userModel->findByRole([$vendorModel->vendor_code]);
                            foreach ($users as $user) {
                                if (empty($user->user_type_id) || $user->user_type_id == 7) {
                                    //TblUserOrganizationMapping::deleteAll(['user_id' => $user->id]);
                                    if (empty($user->user_type_id)) {
                                        $user->user_type_id = 7;
                                        array_push($orgMap, $user);
                                    }
                                    $modelNew = new TblUserOrganizationMapping();
                                    $modelNew->organization_code = $vendorModel->dcs_code;
                                    $modelNew->organization_type = 'DCS';
                                    $modelNew->user_id = $user->id;
                                    $modelNew->is_active = $user->is_active;
                                    Yii::$app->operation->defaults($modelNew, INSERT);
                                    array_push($orgMap, $modelNew);
                                }
                            }
                            if (strtolower($vendorModel->vendor_code) == 'eipl') {
                                $path = Yii::$app->params['eiplDirPath'] . $vendorModel->dcs_code . '/';
                                if (!file_exists($path) || !is_dir($path)) {
                                    FileHelper::createDirectory($path);
                                }
                            }
                            $this->generalModel->saveTransaction($orgMap, ['society', 'create']);
                        }
                    }
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDcs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'updateDcs';
        $this->model->tmcc_code = $this->model->dcs_code;
        $vendorModel = new TblSocietyVendor();

        $this->viewFile = 'update';
        $oldVillage = $this->model->village_code;
        $validate = 1;
        $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;

        $address = explode(',', $this->model->address);
        if (isset($address)) {
            if (isset($address[0]))
                $this->model->street1 = $address[0];
            if (isset($address[1]))
                $this->model->street2 = $address[1];
        }
        $x_col1 = explode('#', $this->model->x_col1);
        if (isset($x_col1)) {
            if (isset($x_col1[0]) && isset($x_col1[1])) {
                $this->model->same_milk_type = $x_col1[0];
                $this->model->diff_milk_type = $x_col1[1];
            }
        }
        $this->model->vendor = $oldVendor = $vendorModel->getDcsVendor($this->model->dcs_code);
        if (empty($this->model->vendor)) {
            $this->model->vendor = 'NA';
            $oldVendor = 'NA';
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->setModel();

            $this->model->street1 = $_POST['TblDcs']['street1'];
            $this->model->street2 = $_POST['TblDcs']['street2'];
            if ($this->model->street1 != '' && $this->model->street2 != '') {
                $this->model->address = $this->model->fullAddress();
            } elseif ($this->model->street1 == '' && $this->model->street2 != '') {
                $this->model->address = $this->model->street2;
            } else {
                $this->model->address = $this->model->street1;
            }

            $mappingList = [];
            $modelCodes = TblSocietyCodes::findOne(['dcs_code' => $this->model->dcs_code]);
            if (!empty($modelCodes)) {
                //$modelCodes->union_code = $this->model->union_code;
                //$modelCodes->bipl_code = $modelCodes->getBiplCode($this->model->village_code);
            } else {
                $modelCodes = new TblSocietyCodes();
                $modelCodes->dcs_code = $this->model->dcs_code;
                $modelCodes->bipl_code = $modelCodes->getBiplCode($this->model->village_code);
                $modelCodes->union_code = $this->model->union_code;
            }
            /* if ($modelCodes->bmc_code != $this->model->destination_code) {
              $modelCodes->bmc_code = $this->model->destination_code;
              $modelCodes->pooling_point_code = $modelCodes->getPpCode();
              } */
            array_push($mappingList, $modelCodes);

            if ($oldVillage != $this->model->village_code) {
                $oldModel = TblDcsVillageMapping::find()->where(['dcs_code' => $this->model->dcs_code, 'village_code' => $oldVillage])->one();
                if ($oldModel) {
                    $mappingHistory = new TblDcsVillageMappingHistory();
                    Yii::$app->operation->history($oldModel, $mappingHistory, DELETE);
                    array_push($mappingList, $mappingHistory);
                    array_push($mappingList, $oldModel);
                }
                if (!empty($this->model->village_code)) {
                    $newModelMapping = new TblDcsVillageMapping();
                    $this->setMapping($newModelMapping);
                    array_push($mappingList, $newModelMapping);
                }
            }

            // $this->model->milk_type_code = $this->model->milk_type_code[0];
            //milk type
            if (!empty($this->model->milk_type_auto)) {
                $this->model->milk_type_code = [1, 2, 3];
            }
            $milkType = TblDcsMilkType::find()->where(['dcs_code' => $this->model->dcs_code, 'is_active' => 1])->all();
            $returnedArray = \yii\helpers\ArrayHelper::map($milkType, 'milk_type_code', 'milk_type_code');

            $toRevoke = array_diff($returnedArray, $this->model->milk_type_code);
            $toAssign = array_diff($this->model->milk_type_code, $returnedArray);


            foreach ($toRevoke as $value) {
                $milkModel = TblDcsMilkType::find()->where(['dcs_code' => $this->model->dcs_code, 'milk_type_code' => $value])->one();
                $milkHistory = new TblDcsMilkTypeHistory();
                Yii::$app->operation->history($milkModel, $milkHistory, DELETE);
                array_push($mappingList, $milkHistory);
                array_push($mappingList, $milkModel);
            }
            foreach ($toAssign as $value) {
                $milkModel = new TblDcsMilkType();
                $milkModel->dcs_code = $this->model->dcs_code;
                $milkModel->milk_type_code = $value;
                $milkModel->is_active = $this->model->is_active;
                array_push($mappingList, $milkModel);
            }
            $milkTypeArray = TblAnimalType::find()->where(['animal_type_code' => $this->model->milk_type_code, 'is_active' => 1])->all();
            $this->model->default_milk_type = !empty($this->model->milk_type_auto) ? 8 : $this->model->setDefaultMilkType($milkTypeArray, 'animal_type_code');
            if ($_POST['warning'] == 0) {
                $msg = $this->model->dcs_name . ' for Society';
                $validate = Yii::$app->warning->unique($this->model, 'dcs_name', $_POST['TblDcs']['dcs_name'], $msg);
            }
            if ($validate == 1) {
                if ($oldVendor == 'NA' && $this->model->vendor != 'NA') {
                    $vendorModel = new TblSocietyVendor();
                    $vendorModel->dcs_code = $this->model->dcs_code;
                    $vendorModel->vendor_code = $this->model->vendor;
                    array_push($mappingList, $vendorModel);
                    $userModel = new User();
                    $users = $userModel->findByRole([$vendorModel->vendor_code]);
                    foreach ($users as $user) {
                        if (empty($user->user_type_id) || $user->user_type_id == 7) {
                            if (empty($user->user_type_id)) {
                                $user->user_type_id = 7;
                                array_push($mappingList, $user);
                            }
                            $modelNew = new TblUserOrganizationMapping();
                            $modelNew->organization_code = $vendorModel->dcs_code;
                            $modelNew->organization_type = 'DCS';
                            $modelNew->user_id = $user->id;
                            $modelNew->is_active = $user->is_active;
                            Yii::$app->operation->defaults($modelNew, INSERT);
                            array_push($mappingList, $modelNew);
                        }
                    }
                    if (strtolower($vendorModel->vendor_code) == 'eipl') {
                        $path = Yii::$app->params['eiplDirPath'] . $vendorModel->dcs_code . '/';
                        if (!file_exists($path) || !is_dir($path)) {
                            FileHelper::createDirectory($path);
                        }
                    }
                }
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $mappingList, ['society', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblDcs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_dcs', 'tbl_dcs_village', 'tbl_dcs_village_history', Yii::$app->request->post('id'), 'dcs_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblDcsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], ['TblDcsVillageMapping', 'TblDcsVillageMappingHistory'], 'dcs_code');
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblDcs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDestinationList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];

            if ($value[0] == 3) {
                $dcs = new TblDcs();
                $list = $dcs->getDcsForBmc($value[1]);
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
                return;
            } else if ($value[0] != "") {
                $chilling = new TblMccPlant();
                $list = $chilling->getchillingcenter($value[0], $value[1]);
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo \yii\helpers\Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMapVillages($id) {
        $model = new TblDcsVillageMapping;
        $modelDcs = $this->findModel($id);
        $values = $model->getDistrictVillages($id, $modelDcs->union_code);

        if (Yii::$app->request->post()) {
            $district_code = Yii::$app->request->post('TblDcsVillageMapping')['district_code'];
            $village_code = Yii::$app->request->post('TblDcsVillageMapping')['village_code'];


            if (isset($district_code)) {

                $postData = array_filter($village_code);
                if (empty($postData)) {
                    $model->addError('district_code', 'Please select at lease one village.');
                    $district_code = [];
                    return $this->render('_map_villages', [
                                'model' => $model, 'villages' => $values['villages'], 'selected' => [], 'modelDcs' => $modelDcs, 'defaultValue' => $modelDcs->village_code
                    ]);
                }

                $data = $model->find()->where(['dcs_code' => $id, 'is_active' => 1])->all();

                $returnedArray = \yii\helpers\ArrayHelper::map($data, 'village_code', 'village_code');

                $districts = Yii::$app->general->array_flatten($values['villages'], 1);
                $postData = Yii::$app->general->array_flatten($postData);
                $oldAssignments = array_keys($returnedArray);
                $newAssignments = array_intersect(array_flip($districts), $postData);

                $toAssign = array_diff($newAssignments, $oldAssignments);
                $toRevoke = array_diff($oldAssignments, $newAssignments);

                $record = $this->generalModel->mappingTransaction($toRevoke, $toAssign, ['TblDcsVillageMapping', 'TblDcsVillageMappingHistory'], ['dcs_code', 'village_code'], $id);

                if ($record) {
                    Yii::$app->display->message(true, 'dcs village mapping', 'edit');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('_map_villages', [
                    'model' => $model, 'villages' => $values['villages'], 'selected' => $values['selected'], 'modelDcs' => $modelDcs, 'defaultValue' => $modelDcs->village_code
        ]);
    }

    public function actionGetUnionDcs() {

        $finalDcs = [];
        if (!empty($_POST['union'])) {
            $dcs = explode(',', $_POST['union']);
            $model = new TblDcs();
            foreach ($dcs as $row) {
                $dcsList = $model->getDcsList($row);
                $finalDcs = array_merge($finalDcs, $dcsList);
            }
        }

        echo \yii\helpers\Json::encode(['status' => 'success', 'data' => $finalDcs]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'bankDetails' => $this->bankDetails,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->dcs_code]);
    }

    private function setModel() {
        $this->model->dcs_name = ucwords($this->model->dcs_name);
        $this->model->pan_no = strtoupper($this->model->pan_no);
        $this->model->dcs_short_name = ucwords($this->model->dcs_short_name);
        //$this->model->route_code = empty($this->model->route_code) ? null : $this->model->route_code;
        $this->model->registration_date = ($this->model->registration_date == '') ? null : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
        $this->model->effective_date = ($this->model->effective_date == '') ? null : Yii::$app->formatter->asDate($this->model->effective_date, DATE_FORMAT);
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);
        $this->model->mcc_plant_code = Yii::$app->general->getforeignkey($this->model->bmcCode, 'mcc_plant_code');
        $this->model->plant_code = Yii::$app->general->getforeignkey($this->model->mccPlantCode, 'plant_code');
        $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
    }

    private function setMapping(&$modelMapping) {
        $modelMapping->dcs_code = $this->model->dcs_code;
        $modelMapping->village_code = $this->model->village_code;
        $modelMapping->is_active = $this->model->is_active;
    }

    private function setMilk() {
        $milkArray = $this->model->milk_type_code;
        if ($this->model->milk_type_auto == 1) {
            $milkArray = ["1", "2", "3"];
        }
        $list = [];
        foreach ($milkArray as $row) {
            $modelMilk = new TblDcsMilkType();
            $modelMilk->dcs_code = $this->model->dcs_code;
            $modelMilk->milk_type_code = $row;
            $modelMilk->is_active = 1;
            array_push($list, $modelMilk);
        }
        return $list;
    }

    public function actionBankDetails($id) {
        $bankDetails = new TblBankDetails();
        $bankDetails->scenario = 'additional';
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name = 'society';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelDcs = $this->findModel($id);
        return $this->render('../../../details/views/tbl-bank-details/create', [
                    'model' => $bankDetails,
                    'id' => $id,
                    'module' => 'society',
                    'dist' => $modelDcs->district_code,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dist_field' => 'tbldcs-district_code'
        ]);
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'society';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'society',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionVillageList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $dcsCode = $value[0];
            $dcs = TblDcs::findOne($dcsCode);
            $list = $dcs->getVillage();
            foreach ($list['value'] as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
            return;
        }
        return Json::encode(['output' => '']);
    }

    public function actionImeiNumber() {
        $model = new TblSocietyCodes();
        if (Yii::$app->request->post()) {
            $data = $_POST['TblSocietyCodes'];
            $model = $model->findOne(['dcs_code' => $data['dcs_code']]);
            $model->scenario = 'societycode';
            if (!empty($model)) {
                $model->imei_no = $data['imei_no'];
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => 'IMEI Number is saved successfully']);
                    return $this->redirect(['imei-number']);
                }
            }
            return $this->render('imei_update', [
                        'model' => $model
            ]);
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'IMEI Number is not saved successfully. Please try again.']);
            //return $this->redirect(['imei-number']);
        }
        return $this->render('imei_update', [
                    'model' => $model
        ]);
    }

    public function actionMultiImeiNumber() {
        $model = new TblSocietyCodes();
        $model->scenario = 'societycode';

        return $this->render('multi_imei_update', [
                    'model' => $model
        ]);
    }

    public function actionGetImeiNo() {

        $ifsc = '';
        if (!empty($_POST['id'])) {
            $model = new TblSocietyCodes();
            $imei = $model->getImi($_POST['id']);
        }
        return Json::encode(['code' => $imei]);
    }

    public function actionLoadVendorSociety() {
        // $user_code=  Yii::$app->session('UserCode');
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $user_code = $value[0];
            $societies = TblSocietyVendor::find()->joinWith('dcsCode')->where(['vendor_code' => $user_code, 'tbl_dcs.is_active' => 1])->all();
            //var_dump($societies);exit;
            $list = [];
            if (!empty($societies)) {
                foreach ($societies as $society) {
                    $list[] = ['id' => $society->dcs_code, 'name' => $society->dcsCode->dcs_name];
                }
            }
            return Json::encode(['output' => $list]);
            return;
        }
        return Json::encode(['output' => '']);
    }

    public function actionUpdateImeiNumberSociety() {
        $soc = new TblSocietyCodes();
        if (isset($_POST['TblSocietyCodes']['route_code'])) {
            $soc->load(Yii::$app->request->post());
            $route = $_POST['TblSocietyCodes']['route_code'];
            $routeSocieties = TblSocietyCodes::find()->joinWith('dcsCode')->where(['tbl_society_codes.route_code' => $route, 'tbl_dcs.is_active' => 1])->indexBy('code')->all();
            $societies = (!empty(Yii::$app->session->get('Dcs'))) ? explode(',', Yii::$app->session->get('Dcs')) : '';
            $list = [];
            if (!empty($routeSocieties)) {
                foreach ($routeSocieties as $society) {
                    if (empty($societies) || in_array($society->dcs_code, $societies)) {
                        $society->scenario = 'societycode';
                        $list[] = $society;
                    }
                }
                //$list = $routeSocieties;
                if (isset($_POST['TblSocietyCodes'][0]['dcs_code']) && Model::loadMultiple($list, Yii::$app->request->post()) && Model::validateMultiple($list)) {
                    $saveModel = [];
                    foreach ($list as $dcs) {
                        if (!empty($dcs->imei_no)) {
                            $saveModel[] = $dcs;
                        }
                    }
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['IMEI', 'edit']);
                    if ($transaction !== FALSE) {
                        return $this->redirect(['multi-imei-number']);
                    }
                }

                return $this->render('imei_update_dcs', [
                            'models' => $list,
                            'soc' => $soc
                ]);
            }
        }


        return $this->redirect(['multi-imei-number']);
    }

    public function actionLoadSocieties() {

        $union_code = !empty(Yii::$app->request->post('dep_code')) ? Yii::$app->request->post('dep_code') : '';
        $q = Yii::$app->request->post('q');
        $dcs = new TblDcs();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $results = $dcs->loadDcs($union_code, $q);
            $out['results'] = array_values($results);
        }
        $out['results'] = array_values($out['results']);
        return $out;
    }

    public function actionDeactivateUser($id, $password) {
        if (!empty($password)) {
            $user = User::getCurrentUser();
            if (Yii::$app->security->validatePassword($password, $user->password_hash)) {
                $this->model = $this->findModel($id);
                $historyModel = new TblDcsHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->scenario = 'deactivate';
                $this->model->is_active = 0;
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['dcs', 'edit']);
                if ($transaction == 'customRedirect') {
//            if (Yii::$app->general->isVendor($this->model->dcs_code, 'BIPL')) {
//                $this->model->generateBiplMemberFiles();
//            }
                    $record = ['status' => 'success', 'msg' => 'Society Deactivated Successfully.'];
                } else {
                    $record = ['status' => 'error', 'msg' => 'Society Not Deactivated.'];
                }
            } else {
                $record = ['status' => 'error', 'msg' => 'Wrong Password.'];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'Please Enter Password.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionRouteDcsList() {
        $out = NULL;
        if (isset($_POST['depdrop_parents'])) {

            $cnt = 0;
            foreach ($_POST as $key => $val) {
                if ($cnt == 0) {
                    $cnt++;
                    continue;
                }
                $data = explode(',', $key);
            }
            $code = str_replace("'", '', $key);

            $value = $_POST['depdrop_parents'];
            $model = new TblDcs();
            $list = $model->getRouteDcsList($value[0], $code);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        echo \yii\helpers\Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionSocietyStatus($dcs_code, $coll_status) {
        $model = new TblSocietyCollection();
        $model->dcs_code = $dcs_code;
        $model->scenario = 'update_collection';
        if (Yii::$app->request->post()) {
            if ($coll_status == 1) {
                $coll_status = 0;
            } else {
                $coll_status = 1;
            }

            $collection_status = [];

            $model->load(Yii::$app->request->post());
            $from_date = date('Y-m-d', strtotime($model->from_date)) . " " . date('H:i:s');
            $remarks = $model->remarks;
            $model->from_date = $from_date;
            $model->status = $coll_status;

            $exist_data = $model->find(['dcs_code' => $dcs_code])->orderBy('collection_id desc')->one();
            $to_date = date('Y-m-d H:i:s', strtotime('-1' . ' day', strtotime($from_date)));
            $exist_data->to_date = $to_date;
            $exist_data->remarks = $remarks;

            $historyModel = new TblSocietyCollectionHistory();
            Yii::$app->operation->history($exist_data, $historyModel, UPDATE);
            $transaction = $this->generalModel->saveTransaction([$model, $exist_data, $historyModel], ['society collection', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->render('society_status', [
                    'model' => $model,
        ]);
    }

    public function actionDcsList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $mccs = new TblDcs();
                $data = $mccs->getBMCDCSList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetBmcDcs() {
        $mccList = [];
        if (!empty($_POST['bmc'])) {
            $palnt = explode(',', $_POST['bmc']);
            $RLS = $_POST['RLS'];
            $model = new TblDcs();
            $mccList = $model->getBMCDCSList($palnt, $RLS);
        }
        return Json::encode(['status' => 'success', 'data' => $mccList]);
    }

    public function actionRateList($id) {
        $model = new TblDcs();
        $model->dcs_code = $id;
        $model->is_active = !empty($model->tblPurchaseRateApplicabilityBlock) ? 0 : 1;
        $searchModel = new TblPurchaseRateApplicabilitySearch();
        $searchModel->dcs_code = $id;
        $searchModel->is_active = $model->is_active;
        $title = ($model->is_active == 1) ? 'Un-Block Rate' : 'Block Rate';
        $dataProvider = $searchModel->RateList();
        return $this->render('rate_list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => $title
        ]);
    }

    public function actionUpdateRateStatus($id) {
        $this->model = TblPurchaseRateApplicability::findOne($id);
        $historyModel = new TblPurchaseRateApplicabilityHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $this->model->is_active = ($this->model->is_active == 0) ? 1 : 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['rate chart', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Rate Chart Updated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Rate Chart Not Updated.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionMergeDcsCustomerList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $mccs = new TblDcs();
                $bmc = $mccs->getBMCDCSList($parents[0], 'TRUE', $type = 'DCS');
                $model = new TblCustomerMaster();
                $customer = $model->getCustomerList($parents[0]);
                $data = $bmc + $customer;
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionPaymentCycleDcsList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {

                $paymentcycleModel = new TblDcsPaymentCycleApplicability();
                $society_list = $paymentcycleModel->societyList($parents[0]);
                $data = $society_list['list'];
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $val['dcs_code'], 'name' => $val['dcs_name']);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMasterVerification() {
        $searchModel = new \app\modules\organisation\models\TblCustomerMasterSearch();
        $searchModel->scenario = 'verification';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'verify' ? 1 : 2) : 1;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];

                $where = [];
                foreach ($codes as $code) {
                    $data = explode('###', $code);
                    $modelUsed = $data[1];
                    if ($modelUsed == 'MEMBER') {
                        $where['member_code'] = $data[0];
                        $existData = TblMember::find()->where($where)->one();
                        $historyModel = new TblMemberHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->is_verified = $status;
                        $existData->scenario = 'verification';
                        $saveModel[] = $existData;
                    } else if ($modelUsed == 'DCS' || $modelUsed == 'CUSTOMER') {
                        $module = $modelUsed == 'DCS' ? 'society' : 'customer';
                        $code = $data[0];
                        $existData = TblBankDetails::find()->where(['module_code' => $code, 'module_name' => $module, 'is_default' => 1, 'is_active' => 1])->one();
                        $historyModel = new TblBankDetailsHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->is_verified = $status;
                        $existData->scenario = 'verification';
                        $saveModel[] = $existData;
                    }
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, ['Master Verified', 'create']);
                if ($transaction == 'customRedirect') {
//                    return $this->redirect(['index']);
                }
            }
        }
        $dataProvider = $searchModel->verificationsearch(Yii::$app->request->queryParams);

        return $this->render('master_verification', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewVerification() {
        $code = !empty(Yii::$app->request->post('code')) ? Yii::$app->request->post('code') : NULL;
        $type = !empty(Yii::$app->request->post('type')) ? Yii::$app->request->post('type') : NULL;
        if ($type == 'DCS') {
            $model = $this->findModel($code);
        } else if ($type == 'CUSTOMER') {
            $model = TblCustomerMaster::find()->where(['customer_code' => $code])->one();
        } elseif ($type == 'MEMBER') {
            $model = TblMember::find()->where(['member_code' => $code])->one();
        }

        return $this->renderAjax('verification_view', [
                    'model' => $model,
                    'type' => $type,
        ]);
    }

}
