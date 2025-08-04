<?php

namespace app\modules\organisation\controllers;

use app\components\WebApi;
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
use app\modules\organisation\models\TblDcsDeactiveSearch;
use app\modules\dcsoperation\models\TblMemberHistory;
use app\modules\details\models\TblBankDetailsHistory;
use app\modules\details\models\TblContactDetailsHistory;
use yii\base\UserException;
use ReflectionClass;
use app\models\ChildModel;
use app\modules\bkgprocess\models\TblOrgFileCreator;
use app\modules\bkgprocess\models\TblOrgFileLog;
use app\modules\document\controllers\TblAttachmentController;
use app\modules\organisation\models\TblBankVerification;
use app\modules\organisation\models\TblBankVerificationLog;
use app\modules\product\models\TblProductSaleRate;
use app\modules\product\models\TblProductSaleRateApplicability;

/**
 * TblDcsController implements the CRUD actions for TblDcs model.
 */
class TblDcsController extends ChildController {

    public $bankDetails;
    public $contactDetails;
    public $freeAccessActions = ['dcs-list', 'get-bmc-dcs', 'merge-dcs-customer-list', 'payment-cycle-dcs-list', 'merge-bmc-dcs-list'];
    public $showIsBMC;

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
        $dsearchModel = new TblDcsDeactiveSearch();
        $dsearchModel->dcs_code = $id;
        $ddataProvider = $dsearchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $dcsBmc, 'dataProvider' => $dataProvider,
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
                    'dsearchModel' => $dsearchModel, 'ddataProvider' => $ddataProvider,
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
        $this->contactDetails->department = 'society_agent';
        $this->contactDetails->form_validation_type = 'dcs-create';
        $this->model->district_code = Yii::$app->session->get('Districts');
        $this->model->valid_from = date('Y-m-d');
        $this->contactDetails->scenario = 'additional';
        $this->showIsBMC = $is_bmc == 1 ? true : false;
        $validate = 1;
        $this->model->bmc_code = !empty($bmc_code) ? $bmc_code : $this->model->bmc_code;
        $this->model->is_bmc = $is_bmc;
        Yii::$app->default->getDefaults($this->model);

        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel();
            $this->model->dcs_code = $this->model->getCode();
            $mapList = [];
            $productSaleRateApplicabilityAuto = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'product_sale_rate_applicability_auto');
            if (!empty($productSaleRateApplicabilityAuto)) {
                $productSaleRateApplicability = new TblProductSaleRateApplicability;
                $productSaleRate = new TblProductSaleRate;
                $productSaleRate = $this->model->getProductSaleRates($this->model->union_code);
                if (!empty($productSaleRate)) {
                    foreach ($productSaleRate as $rate) {
                        $productSaleRateApplicability = new TblProductSaleRateApplicability();
                        $productSaleRateApplicability->attributes = $rate->attributes;
                        $productSaleRateApplicability->applicable_for = 'DCS';
                        $productSaleRateApplicability->applicable_code = $this->model->dcs_code;
                        $productSaleRateApplicability->created_at = date('Y-m-d H:i:s');
                        $productSaleRateApplicability->wef_date = date('Y-m-d H:i:s');
                        $productSaleRateApplicability->created_by = Yii::$app->user->identity->id;
                        array_push($mapList, $productSaleRateApplicability);
                    }
                }
            }

            if ($this->model->street1 != '' && $this->model->street2 != '') {
                $this->model->address = $this->model->fullAddress();
            } elseif ($this->model->street1 == '' && $this->model->street2 != '') {
                $this->model->address = $this->model->street2;
            } else {
                $this->model->address = $this->model->street1;
            }

            /* if ($this->model->is_bmc == 3 && $this->model->destination_code == '') {
              $this->model->destination_code = 0;
              $this->model->destination_type = 0;
              } */


            //set mapping data
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
                $this->model->cutoff = '0000';
                if (!empty($this->model->lower_milk_type) && !empty($this->model->cutoff_val)) {
                    $val = str_replace('.', '', $this->model->cutoff_val);
                    $val = str_pad($val, 3, '0', STR_PAD_LEFT);
                    $milkType = Yii::$app->general->getforeignkey($this->model->lowerMilkType, 'short_name');
                    $cutOffVal = $val . strtoupper($milkType);
                    $this->model->cutoff = substr($cutOffVal, -4);
                }
                $transaction = $this->saveDcs($this->model, $mapList, ['society', 'create']);
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
        $this->showIsBMC = FALSE;
        if (!empty($this->model->address)) {
            $address = explode(',', $this->model->address);
            if (isset($address)) {
                if (isset($address[0]))
                    $this->model->street1 = $address[0];
                if (isset($address[1]))
                    $this->model->street2 = $address[1];
            }
        }
        if (!empty($this->model->x_col1)) {
            $x_col1 = explode('#', $this->model->x_col1);
            if (isset($x_col1)) {
                if (isset($x_col1[0]) && isset($x_col1[1])) {
                    $this->model->same_milk_type = $x_col1[0];
                    $this->model->diff_milk_type = $x_col1[1];
                }
            }
        }
        $this->model->vendor = $oldVendor = $vendorModel->getDcsVendor($this->model->dcs_code);
        if (empty($this->model->vendor)) {
            $this->model->vendor = 'NA';
            $oldVendor = 'NA';
        } else {
            $oldVendor = 'NA';
        }
        if (!empty($this->model->cutoff_val) && !empty($this->model->lower_milk_type)) {
            $this->model->cutoff = 1;
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
                    $vendorModelData = $vendorModel->find()->where(['dcs_code' => $this->model->dcs_code])->one();
                    if (!empty($vendorModelData)) {
                        $vendorModel = $vendorModelData;
                    }
                    $vendorModel->vendor_code = $this->model->vendor;
                    if ($this->model->vendor == 'BIPL') {
                        Yii::$app->general->generateFTPDir($this->model, 'dcs_code', [], $this->model->mcc_plant_code, $this->model->ref_code);
                    }
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
                }
                $this->model->cutoff = '0000';
                if (!empty($this->model->lower_milk_type) && !empty($this->model->cutoff_val)) {
                    $val = str_replace('.', '', $this->model->cutoff_val);
                    $val = str_pad($val, 3, '0', STR_PAD_LEFT);
                    $milkType = Yii::$app->general->getforeignkey($this->model->lowerMilkType, 'short_name');
                    $cutOffVal = $val . strtoupper($milkType);
                    $this->model->cutoff = substr($cutOffVal, -4);
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
                    'contactDetails' => $this->contactDetails,
                    'showIsBMC' => $this->showIsBMC
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
        $this->model->security_return_date = ($this->model->security_return_date == '') ? null : Yii::$app->formatter->asDate($this->model->security_return_date, DATE_FORMAT);
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
            return \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
//            return;
        }
        return \yii\helpers\Json::encode(['output' => '', 'selected' => '']);
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
                $rls = isset($parents[1]) && $parents[1] == 'false' ? 'FALSE' : 'TRUE';
                $data = $mccs->getBMCDCSList($parents[0], $rls);
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
            $route = !empty($_POST['route']) ? explode(',', $_POST['route']) : '';
            $RLS = $_POST['RLS'];
            $model = new TblDcs();
            $mccList = $model->getBMCDCSList($palnt, $RLS, '', '', $route);
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
                $for = !empty($parents[1]) ? $parents[1] : '';
                $route = !empty($parents[2]) ? $parents[2] : '';
                $mccs = new TblDcs();
                $bmc = $mccs->getBMCDCSList($parents[0], 'TRUE', $type = 'DCS', '', $route);
                $model = new TblCustomerMaster();
                $customer = $model->getCustomerList($parents[0], $route);
                if (empty($for)) {
                    $data = $bmc + $customer;
                } elseif ($for == 1) {
                    $data = $bmc;
                } elseif ($for == 2) {
                    $data = $customer;
                }
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
        $eiplCode = \Yii::$app->session->get('eiplCode');
        $searchModel->scenario = $eiplCode == 'MMD' ? 'mmd-verification' : 'verification';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'verify' ? 1 : 2) : 1;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $msg = $status == 1 ? 'Verified' : 'Rejected';
                $where = [];
                $remarkPost = Yii::$app->request->post()['TblCustomerMasterSearch'];
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
                        $existData->bank_remarks = !empty($remarkPost[$data[0] . '@@' . $data[1]]['remark']) ? $remarkPost[$data[0] . '@@' . $data[1]]['remark'] : '';
                        if ($status == 2) {
                            $existData->is_active = 0;
                            $existData->is_default = 0;
                        }
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
                        $existData->remarks = !empty($remarkPost[$data[0] . '@@' . $data[1]]['remark']) ? $remarkPost[$data[0] . '@@' . $data[1]]['remark'] : '';
                        if ($status == 2) {
                            $existData->is_active = 0;
                            $existData->is_default = 0;
                        }
                        $existData->scenario = 'verification';
                        $saveModel[] = $existData;
                    }
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, ['Master ' . $msg, 'create']);
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

    public function actionContactVerification() {
        $searchModel = new \app\modules\organisation\models\TblCustomerMasterSearch();
        $eiplCode = \Yii::$app->session->get('eiplCode');
        $searchModel->scenario = $eiplCode == 'MMD' ? 'mmd-verification' : 'verification';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'verify' ? 1 : 2) : 1;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];

                $where = [];
                $remarkPost = Yii::$app->request->post()['TblCustomerMasterSearch'];
                foreach ($codes as $code) {
                    $data = explode('###', $code);
                    $modelUsed = $data[1];
                    if ($modelUsed == 'MEMBER') {
                        $where['member_code'] = $data[0];
                        $existData = TblMember::find()->where($where)->one();
                        $historyModel = new TblMemberHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->is_contact_verified = $status;
                        $existData->contact_remarks = !empty($remarkPost[$data[0] . '@@' . $data[1]]['remark']) ? $remarkPost[$data[0] . '@@' . $data[1]]['remark'] : '';
                        if ($status == 2) {
                            $existData->is_active = 0;
                        }
                        $existData->scenario = 'verification';
                        $saveModel[] = $existData;
                    } else if ($modelUsed == 'DCS' || $modelUsed == 'CUSTOMER') {
                        $module = $modelUsed == 'DCS' ? 'society' : 'customer';
                        $code = $data[0];
                        $existData = TblContactDetails::find()->where(['module_code' => $code, 'module_name' => $module, 'is_default' => 1, 'is_active' => 1])->one();
                        $historyModel = new TblContactDetailsHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->is_contact_verified = $status;
                        $existData->remarks = !empty($remarkPost[$data[0] . '@@' . $data[1]]['remark']) ? $remarkPost[$data[0] . '@@' . $data[1]]['remark'] : '';
                        if ($status == 2) {
                            $existData->is_active = 0;
                        }
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
        $dataProvider = $searchModel->contactverificationsearch(Yii::$app->request->queryParams);

        return $this->render('contact_verification', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImportAttachements() {
        $model = new TblDcs();
        if (isset($_POST['code'])) {
            $model->dcs_code = $_POST['code'];
        }
        $saveModel = [];
        if ($model->load(Yii::$app->request->post())) {
            $files = !empty(Yii::$app->request->post()['TblDcs']['file_name']) ? Yii::$app->request->post()['TblDcs']['file_name'] : '';
            Yii::$app->general->setAttachment($saveModel, $files, $model->dcs_code, 'TblDcs');
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Image Uploaded', 'edit']);
            return $this->redirect(['index']);
        }
        return $this->renderAjax('_dcs_attachment_upload_popup', ['model' => $model]);
    }

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/upload/images/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = date('YmdHis') . rand(1000, 9999) . str_replace(' ', '_', $file->name);
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'filename' => $name, 'msg' => $name];
            } else {
                $record = ['status' => 'error', 'filename' => $name, 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function actionGetAttachments() {
        $type = $_POST['type'];
        $code = $_POST['code'];
        $module_name = '';
        if (strtoupper($type) == 'MEMBER') {
            $module_name = 'TblMember';
        }
        if (strtoupper($type) == 'CUSTOMER') {
            $module_name = 'TblCustomerMaster';
        }
        if (strtoupper($type) == 'DCS') {
            $module_name = 'TblDcs';
        }
        $attachment = Yii::$app->general->getAttachment($module_name, $code, TRUE, TRUE);
        return $this->renderAjax('_attachment_popup', ['attachment' => $attachment]);
    }

    public function saveDcs($model, $childModel, $message) {
        //var_dump($model);var_dump($childModel);exit;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $master[] = $model->save();
            if (!in_array(FALSE, $master)) {
                foreach ($childModel as $key => $m) {
                    if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                        $master[] = $m->delete();
                    } else {
                        $name = (new ReflectionClass($m))->getShortName();
                        if ($name == 'TblContactDetails' || $name == 'TblBankDetails')
                            $master[] = $m->save();
                        else
                            $master[] = $m->save(FALSE);
                    }
                }
            }

            if (!in_array(FALSE, $master)) {
                if (!empty($model->auto_member_create)) {
                    $model->autoGenerateMember($master);
                }
            }

            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                Yii::$app->display->message(true, $message[0], $message[1]);
                return 'customRedirect';
            }
            $child = new ChildModel();
            foreach ($childModel as $key => $m) { //this code to get validation msgs of child table when matster validation fails
                if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                    
                } else {
                    $child->decryptModel($m);
                    $master[] = $m->validate();
                }
            }
            //exit;

            $model->decryptModel($m);
            foreach ($childModel as $m) {
                $child->decryptModel($m);
            }
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Your transaction is not saved successfully']);
            return 'customRender';
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function actionExportSentbox($id) {
        $this->model = $this->findModel($id);

        $MemberModel = new TblMember();
        $memberArray = $MemberModel->getMembers($id);

        $jsonData = [];
        ob_clean();
        foreach ($memberArray as $member) {
            $operation = !empty($member->updated_at) ? 'UPDATE' : 'INSERT';
            $sentbox = $member->sentboxModel($id, 'VLC');
            $sentboxData = $sentbox->setSentboxDownload($member, $operation);
            $jsonData[] = Json::encode($sentbox->jsonModel($sentboxData), JSON_UNESCAPED_UNICODE);
        }
        if (true || count($memberArray) == count($jsonData)) {
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
    }

    public function actionUploadFtpFile($id) {
        $dcsModel = $this->findModel($id);
        $saveModel = [];
        for ($x = 1; $x <= 2; $x += 1) {
            $file_type = $x == 1 ? 'MEMBER' : 'RATE';
            $model = new TblOrgFileCreator();
            $model->file_status = 1;
            $model->status = 2;
            $model->vendor_code = 'BIPL';
            $model->module_code = $dcsModel->dcs_code;
            $model->ref_code = $dcsModel->ref_code;
            $model->module_name = 'TblDcs';
            $model->file_type = $file_type;
            $model->value1 = '';
            if ($file_type == 'RATE') {
                $applicability = new TblPurchaseRateApplicability();
                $applicableData = $applicability->getDcsApplicability($dcsModel->dcs_code, date('Y-m-d'));
                $purchaseRate = !empty($applicableData) ? $applicableData->purchase_rate_code : '';
                if (!empty($purchaseRate)) {
                    $org_model = new TblOrgFileLog();
                    $org_model->module_code = $model->module_code;
                    $model->value1 = $purchaseRate;
                    $org_model->generateBiplFiles($model->module_code, $model->file_type, $model->value1);
                    $model->status = 2;
                    $model->file_status = 1;
                    $saveModel[] = $model;
                }
            } else {
                $org_model = new TblOrgFileLog();
                $org_model->module_code = $model->module_code;
                $org_model->generateBiplFiles($model->module_code, $model->file_type, $model->value1);
                $model->status = 2;
                $model->file_status = 1;
                $saveModel[] = $model;
            }
        }
        $transaction = $this->generalModel->saveTransaction($saveModel, ['NAME/RATE Uploaded', 'create']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(['index']);
        }
    }

    public function actionMergeBmcDcsList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $dcs = new TblDcs();
                $mccCode = !empty($parents[1]) ? $parents[1] : '';
                $bmcCode = !empty($parents[2]) ? $parents[2] : '';
                $is_call = true;
                if ($parents[0] == 2 && empty($bmcCode)) {
                    $is_call = false;
                } else if ($parents[0] == 1 && empty($mccCode)) {
                    $is_call = false;
                }
                if ($is_call) {
                    $data = $dcs->getMergeBmcDcsList($parents[0], $mccCode, $bmcCode);
                    foreach ($data as $key => $val) {
                        $out[] = array('id' => $key, 'name' => $val);
                    }
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionDcsDocumentUpload($id) {
        $model = $this->findModel($id);
        $module_code = $model->dcs_code;
        $module_name = 'tbl_dcs';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('dcs', $id, $model, $module_code, $module_name);
    }

    public function actionKycVerification() {
        $logModel = New TblBankVerificationLog();
        $getData = Yii::$app->request->get();
        $code = !empty($getData['code']) ? $getData['code'] : NULL;
        $type = !empty($getData['type']) ? $getData['type'] : NULL;
        $model = [];
        $responseJson = '';
        $responseApi = '';
        if (strtolower($type) == 'dcs') {
            $module_name = 'DCS';
            $customer_type = 'society';
            $model = TblBankDetails::find()->where(['module_code' => $code, 'module_name' => $customer_type, 'is_default' => 1, 'is_active' => 1])->one();
        } else if (strtolower($type) == 'customer') {
            $module_name = 'CUS';
            $customer_type = 'customer';
            $model = TblBankDetails::find()->where(['module_code' => $code, 'module_name' => 'customer', 'is_default' => 1, 'is_active' => 1])->one();
        } elseif (strtolower($type) == 'member') {
            $module_name = 'MEM';
            $customer_type = 'member';
            $model = TblMember::find()->where(['member_code' => $code])->one();
        }
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $saveModel = [];
            $hisModel = [];
            $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'verify' ? 1 : 2) : 0;
            if (!empty($status)) {
                $model->scenario = 'kycVerify';
                $history = $model->className() . 'History';
                $historyModel = new $history();
                Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                $hisModel[] = $historyModel;
                $model->is_kyc_verified = $status;
                if ($status == 1) {
                    $cashFreeRegistrationConfig = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_cash_free_registration', 'PORTAL');
                    if ($cashFreeRegistrationConfig == 1) {
                        $verifymodelData = TblBankVerification::find()->where(['customer_code' => $code, 'customer_type' => $customer_type, 'bank_account_no' => $model['bank_account_no'], 'ifsc' => $model['ifsc']])->one();
                        $client_code = Yii::$app->session->get('eiplCode');
                        $model->beneficiary_id = $client_code . $code . $module_name . rand(1000, 9999);
                        $this->cashFreeRegistrationApi($type, $model, $responseApi, $verifymodelData);

                        if (!empty($responseApi) && strtolower($responseApi->beneficiary_status) == 'verified') {
                            $model->is_verified = 1;
                        }
                    } else {
                        $model->is_verified = 1;
                    }
                }
                $saveModel[] = $model;

                if ($model->validate() && empty($model->getErrors())) {
                    $logModel = New TblBankVerificationLog();
                    $verificationModel = New TblBankVerification();
                    if (strtolower($type) == 'dcs') {
                        $dcsdata = $this->findModel($code);
                        $this->setLogHierarchy($logModel, $dcsdata, $saveModel, $type);
                        if ($status == 1 && $cashFreeRegistrationConfig == 1 && empty($verifymodelData)) {
                            $this->setLogHierarchy($verificationModel, $dcsdata, $saveModel, $type);
                        }
                    } else if (strtolower($type) == 'customer') {
                        $customerdata = TblCustomerMaster::find()->where(['customer_code' => $code])->one();
                        $this->setLogHierarchy($logModel, $customerdata, $saveModel, $type);
                        if ($status == 1 && $cashFreeRegistrationConfig == 1 && empty($verifymodelData)) {
                            $this->setLogHierarchy($verificationModel, $customerdata, $saveModel, $type);
                        }
                    } else if (strtolower($type) == 'member') {
                        $memberdata = TblMember::find()->where(['member_code' => $code])->one();
                        $this->setLogHierarchy($logModel, $memberdata, $saveModel, $type);
                        if ($status == 1 && $cashFreeRegistrationConfig == 1 && empty($verifymodelData)) {
                            $this->setLogHierarchy($verificationModel, $memberdata, $saveModel, $type);
                        }
                    }

                    $logModel->setLogData(Yii::$app->request->post(), $model, $saveModel);
                    if ($status == 1 && $cashFreeRegistrationConfig == 1) {
                        $verificationModel->setBankregistrationData($responseApi, $model, $saveModel, $verifymodelData, $hisModel, $code, $customer_type);
                    }
                    $transaction = $this->generalModel->saveTransaction($saveModel, $hisModel, ['KYC Verified', 'create']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(\yii\helpers\Url::previous());
                    } else {
                        return $this->redirect(\yii\helpers\Url::previous());
                    }
                } else {
                    $msg = '';
                    foreach ($model->getErrors() as $errorkey => $value) {
                        $msg .= $value[0] . '<br/>';
                    }
                    Yii::$app->session->setFlash('error', $msg);
                    return $this->redirect(\yii\helpers\Url::previous());
                }
            }
        }
        if (!empty($getData) && !empty($getData['bank_account_no']) && !empty($getData['ifsc'])) {
            $base_url = \Yii::$app->params['cashfree_bank_integration']['verfication_url'];
            $body = array(
                'bank_account' => $getData['bank_account_no'],
                'ifsc' => $getData['ifsc']
            );
            $api = new WebApi();
            $api->header_info['Content-Type'] = 'application/json';
            $api->header_info['Content-length'] = strlen(json_encode($body));
            $api->header_info['x-client-id'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-id'];
            $api->header_info['x-client-secret'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-secret'];
            $api->is_header_merge = false;
            $api->return_actual = true;
            $api->serverUrl = $base_url;
            $api->body = $body;
            try {
                $result = $api->GuzzleCURL();
                $httpCode = $result->getStatusCode();
                $response = $result->getBody()->getContents();
                $response = !empty($response) ? json_decode($response) : [];
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $responseData = $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents()) : '';
                $response = $responseData->error;
                $response->account_status = 'failed at bank.';
                $response->account_status_code = 'failed_at_bank';
            }
            $response->bank_account_no = $body['bank_account'];
            $response->ifsc = $body['ifsc'];
            $responseJson = json_encode($response);
        }
        return $this->renderAjax('kyc_verification_view', [
                    'logModel' => $logModel,
                    'model' => $model,
                    'type' => $type,
                    'response' => $response,
                    'responseJson' => $responseJson,
        ]);
    }

    public function setLogHierarchy($logModel, $model, &$saveModel, $type) {
        if (!empty($model)) {
            $logModel->union_code = $model->union_code;
            $logModel->dcs_code = $model->dcs_code;
            if (strtolower($type) == 'member') {
                $dcsData = $model->dcsCode;
                $logModel->plant_code = $dcsData->plant_code;
                $logModel->mcc_plant_code = $dcsData->mcc_plant_code;
                $logModel->bmc_code = $dcsData->bmc_code;
            } else {
                $logModel->plant_code = $model->plant_code;
                $logModel->mcc_plant_code = $model->mcc_plant_code;
                $logModel->bmc_code = $model->bmc_code;
            }
            $saveModel[] = $logModel;
        }
    }

    public function cashFreeRegistrationApi($type, &$model, &$responseApi, $verifymodelData) {
        if (!empty($model) && !empty($model['bank_account_no']) && !empty($model['ifsc']) && !empty($model['beneficiary_name'])) {
            $base_url = \Yii::$app->params['cashfree_bank_integration']['registration_url'];

            if (!empty($verifymodelData)) {
                $body = [];
            } else {
                if (strtolower($type) == 'dcs' || strtolower($type) == 'customer') {
                    $contactDetailData = TblContactDetails::find()->where(['module_code' => $model['module_code'], 'module_name' => $model['module_name'], 'is_default' => 1, 'is_active' => 1])->one();
                    $phone_no = !empty($contactDetailData) ? $contactDetailData->mobile_no : '';
                } elseif (strtolower($type) == 'member') {
                    $phone_no = $model->mobile_no;
                }

                $body = array(
                    'beneficiary_id' => $model['beneficiary_id'],
                    'beneficiary_name' => $model['beneficiary_name'],
                    'beneficiary_instrument_details' => array(
                        'bank_account_number' => $model['bank_account_no'],
                        'bank_ifsc' => !empty($model['ifsc']) ? $model['ifsc'] : '',
                    ),
                    'beneficiary_contact_details' => array(
                        'beneficiary_phone' => $phone_no,
                    ),
                    'beneficiary_status' => '',
                    'added_on' => ''
                );
            }
            $api = new WebApi();
            $api->header_info['x-api-version'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-api-version'];
            $api->header_info['x-client-id'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-id'];
            $api->header_info['x-client-secret'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-secret'];
            $api->return_actual = true;
            $api->serverUrl = $base_url;
            $api->body = $body;
            try {
                if (!empty($verifymodelData) && (strtolower($verifymodelData->res_beneficiary_status) == 'initiated' || strtolower($verifymodelData->res_beneficiary_status) == 'verified')) {
                    $api->apiurl = '?beneficiary_id=' . urlencode($verifymodelData->beneficiary_id);
                    $result = $api->GuzzleCURL('GET');
                    $api->is_header_merge = false;
                } else {
                    $result = $api->GuzzleCURL();
                    $api->is_header_merge = true;
                }
                $httpCode = $result->getStatusCode();
                $responseApi = $result->getBody()->getContents();
                $responseApi = !empty($responseApi) ? json_decode($responseApi) : [];
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $responseData = $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents()) : '';
                $responseApi = new \stdClass();
                $responseApi->beneficiary_status = 'failed at bank.';
                $responseApi->bank_account_number = $model['bank_account_no'];
                $responseApi->bank_ifsc = $model['ifsc'];
            }
            $jsonResponse = json_encode($responseApi);
        }
    }

}
