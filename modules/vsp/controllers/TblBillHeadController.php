<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblBillHead;
use app\modules\vsp\models\TblBillHeadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\vsp\models\TblBillHeadHistory;
use app\modules\vsp\models\TblBillHeadApplicability;
use app\modules\vsp\models\TblBillHeadApplicabilityHistory;
use app\modules\vsp\models\TblCriteriaKeywordMapping;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\vsp\models\TblBillHeadReleaseApplicability;
use app\modules\vsp\models\TblBillHeadReleaseApplicabilityHistory;
use app\modules\vsp\models\TblVspBillHeadCriteriaApplicability;

/**
 * TblBillHeadController implements the CRUD actions for TblBillHead model.
 */
class TblBillHeadController extends \app\controllers\ChildController {

    public $freeAccessActions = ['list-dcswise', 'list-unionwise', 'bill-head-list', 'save-applicability'];

    /**
     * Lists all TblBillHead models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBillHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBillHead model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBillHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblBillHead();
        $this->viewFile = 'create';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());

            $this->model->bill_head_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->general_formula_comma = $this->AddCommaFormula($this->model->general_formula);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Bill Head', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblBillHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblBillHeadHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $master[] = $historyModel;
            $model->load(Yii::$app->request->post());
            $model->general_formula_comma = $this->AddCommaFormula($model->general_formula);
            $master[] = $model;
            $transaction = $this->generalModel->saveTransaction($master, ['Bill Head', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblBillHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblBillHeadHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBillHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBillHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBillHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function AddCommaFormula($formula) {
        $operator = ['+', '-', '*', '/', '(', ')'];
        foreach ($operator as $key => $val) {
            $formula = str_replace($val, ',' . $val . ',', $formula);
        }
        return $formula;
    }

    public function actionBillHeadApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblBillHeadApplicability();
        $appModel->model->wef_date = $appModel->model->from_date = date('Y-m-d');
        $appModel->model->to_date = date('Y-m-d', strtotime('+ 1 year'));
        $appModel->periodic_applicability = TRUE;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'bill_head_code';
        $appModel->field_value = $id;
        $appModel->options = ['tanker_rate'];
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->trans_label = Yii::t('app', 'bill head applicabilities');
        $appModel->model->bill_head_for = $model->bill_head_for;
        $appModel->header_title = ' [Bill Head: ' . $model->bill_head_name . ', Type: ' . Yii::$app->dropdown->getRecords('calc_type')['data'][$model->bill_head_type] . '] ';
        $appModel->fields = ['from_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->from_date);
                }],
            'to_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->to_date);
                }],
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC Ref. Code'), 'value' => function($model) {
                    if ($model->applicable_for == 'DCS') {
                        return Yii::$app->general->getmultiforeignkey($model->dcsName, ['bmcCode'], 'ref_code');
                    } else {
                        return Yii::$app->general->getmultiforeignkey($model->mainCustomerCode, ['bmcCode'], 'ref_code');
                    }
                }],
            'bmc_name' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC Name'), 'value' => function($model) {
                    if ($model->applicable_for == 'DCS') {
                        return Yii::$app->general->getmultiforeignkey($model->dcsName, ['bmcCode'], 'bmc_name');
                    } else {
                        return Yii::$app->general->getmultiforeignkey($model->mainCustomerCode, ['bmcCode'], 'bmc_name');
                    }
                }],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, FALSE, TRUE);
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                }],
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
                        return Yii::$app->general->getforeignkey($model->mainCustomerCode, 'customer_name');
                    }
                }],
                //'dcs_name' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],           
        ];
        if ($model->bill_head_for == 'MEMBER') {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType = new TblCustomerType();
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        }
        $appModel->actions = ['delete' => ['option' => 'bill_head_applicabilty_code,bill_head_applicabilty_code,tbl-bill-head/delete-applicability']];
//        $appModel->dcs_filters = ['society' => 'Society', 'routes' => 'Routes', 'mcc' => 'MCC'];
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $model = TblBillHeadApplicability::find()->where(['bill_head_applicabilty_code' => Yii::$app->request->post('id')])->one();
        $localHistory = new TblBillHeadApplicabilityHistory();
        Yii::$app->operation->history($model, $localHistory, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $localHistory]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionListDcswise() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $dcsCode = $value[0];
            $model = new TblBillHead();
            $list = $model->billHeadData($dcsCode);
            $list = array_unique($list);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionListUnionwise() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $model = new TblBillHead();
            $list = $model->billHeadData('', $unionCode);
            $list = array_unique($list);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
            return;
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionKeyword() {
        $keyword_model = new TblCriteriaKeywordMapping();
        $keyword_data = $keyword_model->getKeywordData();
        $data = ArrayHelper::getColumn($keyword_data, function($array) {
                    return $array["keyword"];
                });
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($data);
    }

    public function actionBillHeadList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $type = $value[1];
            $code = $value[2];
            $headFor = $value[3];
            $model = new TblBillHead();
            $list = $model->billHeadTypeWise($unionCode, $type, $code, $headFor);
            $list = array_unique($list);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
            return;
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionDcsWiseBillHead() {
        $model = new TblBillHead();
        $model->scenario = 'dcsWiseHead';

        $dcs = [];
        $head = [];
        $criteria = [];
        if (!empty(Yii::$app->request->get('TblBillHead'))) {
            $data = Yii::$app->request->get('TblBillHead');
            $model->union_code = $data['union_code'];
            $model->plant_code = $data['plant_code'];
            $model->mcc_plant_code = $data['mcc_plant_code'];
            $model->bmc_code = $data['bmc_code'];
            $model->customer_type = !empty($data['customer_type']) ? $data['customer_type'] : 'DCS';
            $data['customer_type'] = $model->customer_type;
            $model->from_date = !empty($data['from_date']) ? $data['from_date'] : '';
            $model->to_date = !empty($data['to_date']) ? $data['to_date'] : '';
            $model->bill_head_for = $data['bill_head_for'];
            if ($model->validate()) {
                $head = $model->getBillHead($model);
                $dcs = $model->getDcs($data);
                $criteria = $model->getCriteria($model);
            }
        } else {
            $model->from_date = date('Y-m-d');
            $model->to_date = date('Y-m-d', strtotime('+ 1 year'));
        }
        return $this->render('dcs_bill_head', [
                    'model' => $model,
                    'dcs' => $dcs,
                    'head' => $head,
                    'criteria' => $criteria,
        ]);
    }

    public function actionSaveApplicability() {
        $data = Yii::$app->request->post();
        $count = 0;
        if (!empty($data['bill_head_codes'])) {
            foreach ($data['bill_head_codes'] as $key => $codes) {
                foreach ($codes as $code) {
                    $model = new TblBillHeadApplicability();
                    $bill_head = explode('###', $code);
                    if (!empty($bill_head[1])) {
                        $model = new TblVspBillHeadCriteriaApplicability();
                        $model->vsp_criteria_code = $bill_head[1];
                    }
                    $model->setAttributes($data);
                    $model->bill_head_code = $bill_head[0];
                    $model->applicable_code = $key;
                    $model->from_date = Yii::$app->formatter->asDate($model->from_date, DATE_FORMAT);
                    $model->to_date = Yii::$app->formatter->asDate($model->to_date, DATE_FORMAT);
                    $model->wef_date = $model->from_date;
                    if ($model->validate()) {
                        $model->save();
                        $count++;
                    } else {
                        return Json::encode(['status' => 'error']);
                    }
                }
            }
            $msg = 'Bill Head Applicability ' . $count;
            Yii::$app->display->message(true, $msg, 'create');
            return $this->redirect(['dcs-wise-bill-head']);
        }
    }

    public function actionUpdateToDate($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $historyModel = new TblBillHeadHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $model->load(Yii::$app->request->post());
            if (!empty($model->to_date)) {
                $model->to_date = Yii::$app->formatter->asDate($model->to_date, DATE_FORMAT);
                $historyModel->bill_head_name = $historyModel->bill_head_name . '-' . $model->to_date;
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($historyModel->save()) {
                        Yii::$app->db->createCommand("update tbl_bill_head_applicability set to_date = :to_date where bill_head_code = :bill_head_code and from_date <= :from_date")
                                ->bindValue(':to_date', $model->to_date)
                                ->bindValue(':bill_head_code', $model->bill_head_code)
                                ->bindValue(':from_date', $model->to_date)
                                ->execute();
                        $transaction->commit();
                        Yii::$app->display->message(true, 'Bill Head Applicability To Date', 'edit');
                        return $this->customRedirect();
                    } else {
                        $transaction->rollback();
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => 'Your transaction is not saved successfully']);
                    }
                } catch (yii\base\UserException $e) {
                    $transaction->rollback();
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => $e->getMessage()]);
                } catch (\yii\db\Exception $e) {
                    $transaction->rollback();
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
                }
            } else {
                $model->addError('to_date', \Yii::t('app', 'To Date can not be blank'));
            }
        }
        return $this->render('update_to_date', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateToDateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblBillHeadApplicability();
        $appModel->model->scenario = 'updateToDate';
        $appModel->update_applicability = TRUE;
        $appModel->check_wef_date = TRUE;
        $appModel->model->to_date = date('Y-m-d');
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'bill_head_code';
        $appModel->field_value = $id;
        $appModel->options = ['tanker_rate'];
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->trans_label = Yii::t('app', 'bill head applicabilities');
        $appModel->model->bill_head_for = $model->bill_head_for;
        $appModel->header_title = ' To Date Upadte [Bill Head: ' . $model->bill_head_name . ', Type: ' . Yii::$app->dropdown->getRecords('calc_type')['data'][$model->bill_head_type] . '] ';
        $appModel->fields = ['from_date' => ['view' => ['grid'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->from_date);
                }],
            'to_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->to_date);
                }],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                }],
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
                        return Yii::$app->general->getforeignkey($model->mainCustomerCode, 'customer_name');
                    }
                }],
        ];
        if ($model->bill_head_for == 'MEMBER') {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType = new TblCustomerType();
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        }
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

    public function actionHoldReleaseApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblBillHeadReleaseApplicability();
        $appModel->model->from_date = date('Y-m-d');
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'bill_head_code';
        $appModel->field_value = $id;
        $appModel->check_wef_date = TRUE;
        $appModel->options = ['tanker_rate'];
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->trans_label = Yii::t('app', 'bill head release applicabilities');
        $appModel->model->bill_head_for = $model->bill_head_for;
        $appModel->header_title = ' Hold Release [Bill Head: ' . $model->bill_head_name . ', Type: ' . Yii::$app->dropdown->getRecords('calc_type')['data'][$model->bill_head_type] . '] ';
        $appModel->fields = ['from_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->from_date);
                }],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                }],
            'mcc_name' => ['view' => ['grid'], 'value' => function($model) {
                    if ($model->applicable_for == 'PLANT') {
                        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                    } else if ($model->applicable_for == 'MCC') {
                        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                    } else if ($model->applicable_for == 'BMC') {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    } else if ($model->applicable_for == 'DCS') {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    } else {
                        return Yii::$app->general->getforeignkey($model->mainCustomerCode, 'customer_name');
                    }
                }],
        ];
        if ($model->bill_head_for == 'MEMBER') {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType = new TblCustomerType();
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        }
        $appModel->actions = ['delete' => ['option' => 'bill_head_release_applicabilty_code,bill_head_release_applicabilty_code,tbl-bill-head/delete-hold-release-applicability,deleteCheck()']];
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

    public function actionDeleteHoldReleaseApplicability() {
        $model = TblBillHeadReleaseApplicability::find()->where(['bill_head_release_applicabilty_code' => Yii::$app->request->post('id')])->one();
        $localHistory = new TblBillHeadReleaseApplicabilityHistory();
        Yii::$app->operation->history($model, $localHistory, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $localHistory]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
