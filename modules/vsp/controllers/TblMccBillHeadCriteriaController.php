<?php

namespace app\modules\vsp\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\modules\vsp\models\TblMccBillHeadCriteriaSearch;
use app\modules\vsp\models\TblMccBillHeadCriteria;
use app\modules\vsp\models\TblMccBillHeadCriteriaHistory;
use app\modules\vsp\models\TblMccBillHeadCriteriaSlabs;
use app\modules\vsp\models\TblMccBillHeadCriteriaSlabsSearch;
use app\modules\vsp\models\TblCriteriaKeywordMapping;
use app\modules\vsp\models\TblMccBillHeadCriteriaSlabsHistory;
use app\modules\vsp\models\TblMccBillHeadCriteriaApplicability;
use app\modules\vsp\models\TblMccBillHeadCriteriaApplicabilityHistory;
use app\modules\globalmaster\models\TblCustomerType;

class TblMccBillHeadCriteriaController extends \app\controllers\ChildController
{

    public function actionIndex()
    {
        $searchModel = new TblMccBillHeadCriteriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $bsearchModel = new TblMccBillHeadCriteriaSlabsSearch();
        $bsearchModel->criteria_code = $id;
        $bdataProvider = $bsearchModel->createsearch(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = TblMccBillHeadCriteria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreate()
    {
        $this->model = new TblMccBillHeadCriteria();
        $searchModel = new TblMccBillHeadCriteriaSlabsSearch();
        $dataProvider = $searchModel->createsearch([]);
        $dataProvider->sort = false;
        $txModel = new TblMccBillHeadCriteriaSlabs();
        $this->viewFile = 'create';
        $txModel->scenario = 'create';
        $modelSave = [];
        $message = 'Mcc Bill Head Criteria';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $txModel->load(Yii::$app->request->post());
            if ($txModel->validate()) {
                if (empty(Yii::$app->request->post()['TblMccBillHeadCriteria']['criteria_code'])) {
                    $this->model->scenario = 'create';
                    $modelSave[] = $this->model;
                    $auto_key_config['TblMccBillHeadCriteriaSlabs'][] = ['self_key' => 'criteria_code', 'parent_key' => 'criteria_code', 'parent_index' => 0];
                    $txModel->union_code = $this->model->union_code;
                    $txModel->general_formula_code = $this->model->general_formula_code;
                    $txModel->mcc_bill_head_code = $this->model->mcc_bill_head_code;
                }

                if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                    if (!empty(Yii::$app->request->post()['TblMccBillHeadCriteria']['criteria_code'])) {
                        $this->model = Yii::$app->request->post()['TblMccBillHeadCriteria'];
                        $txModel->criteria_code = $this->model['criteria_code'];
                        $txModel->union_code = $this->model['union_code'];
                        $txModel->general_formula_code = $this->model['general_formula_code'];
                        $txModel->mcc_bill_head_code = $this->model['mcc_bill_head_code'];
                    }
                    $modelSave[] = $txModel;
                    $transaction = empty($this->model['criteria_code']) ? $this->generalModel->saveTransactionAutoIncForeignKey($modelSave, [$message, $type], $auto_key_config) : $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $err = [];
                    foreach ($this->model->getErrors() as $key => $value) {
                        $err[$key] = $value;
                    }
                    foreach ($txModel->getErrors() as $key => $value) {
                        $err[$key] = $value;
                    }
                    return Json::encode($err);
                }
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $pk_code = empty($this->model->criteria_code) ? $txModel->criteria_code : $this->model->criteria_code;
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $pk_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $err = [];
                foreach ($txModel->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                return Json::encode($err);
            }
        }
        return $this->render('create', [
            'model' => $this->model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'txModel' => $txModel,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->model = TblMccBillHeadCriteria::findOne(['criteria_code' => $id]);

        if (!$this->model) {
            throw new NotFoundHttpException('The requested record does not exist.');
        }

        $searchModel = new TblMccBillHeadCriteriaSlabsSearch();
        $dataProvider = $searchModel->createsearch([]);
        $dataProvider->sort = false;

        $txModel = new TblMccBillHeadCriteriaSlabs();
        $this->viewFile = 'create';
        $txModel->scenario = 'create';
        $modelSave = [];
        $message = 'Mcc Bill Head Criteria';
        $type = 'create';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $txModel->load(Yii::$app->request->post());

            if ($txModel->validate()) {
                // You can add any specific update logic here, if needed.
                $modelSave[] = $this->model;
                $modelSave[] = $txModel;
            }

            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);

                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->criteria_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }

                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $err = [];
                foreach ($this->model->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                foreach ($txModel->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                return Json::encode($err);
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'txModel' => $txModel,
            ]);
        }
    }

    public function actionMccBillHeadCriteriaApplicability($id)
    {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblMccBillHeadCriteriaApplicability();
        $value = [];
        $value['BMC'] = 'BMC';
        $appModel->model->from_date = date('Y-m-d');
        $appModel->model->mcc_bill_head_code = $model->mcc_bill_head_code;
        $appModel->model->to_date = date('Y-m-d', strtotime('+ 1 year'));
        $appModel->periodic_applicability = TRUE;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'criteria_code';
        $appModel->field_value = $id;
        $appModel->options = ['tanker_rate'];
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->model->bmc_code = 'applicable_code';
        $appModel->trans_label = Yii::t('app', 'MCC bill head criteria applicabilities');
        $appModel->model->bill_head_for = 'BMC';
        $appModel->header_title = 'Mcc Bill Head Criteria: ' . $model->criteria_name;
        $appModel->fields = [
            'from_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->from_date);
            }],
            'to_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->to_date);
            }],
            // 'applicable_for' => ['filter'=>false,'view' => ['grid', 'create'], 'value' => function ($model) {
            //     return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
            // }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],

            'mcc_name' => ['view' => ['grid'], 'value' => function ($model) {
                if ($model->applicable_for == 'PLANT') {
                    return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                } else if ($model->applicable_for == 'MCC') {
                    return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                } else if ($model->applicable_for == 'BMC') {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }
            }],
        ];
        $appModel->actions = ['delete' => ['option' => 'applicable_code,criteria_applicability_code,tbl-mcc-bill-head-criteria/delete-applicability']];
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

    public function actionDeleteApplicability()
    {
     
        $model = new TblMccBillHeadCriteriaApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblMccBillHeadCriteriaApplicabilityHistory();
        $historyModel->attributes = $model->attributes;
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionSlabList()
    {
        $searchModel = new TblMccBillHeadCriteriaSlabsSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblMccBillHeadCriteria'));

        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_slab_list', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionKeyword()
    {
        $keyword_model = new TblCriteriaKeywordMapping();
        $keyword_data = $keyword_model->getKeywordData();
        $data = ArrayHelper::getColumn($keyword_data, function ($array) {
            return $array["keyword"];
        });
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($data);
    }

    public function actionDeleteSlab()
    {
        $deleteModel = new TblMccBillHeadCriteriaSlabs();
        $id = Yii::$app->request->get('id');
        $existData = $deleteModel::find()->where(['criteria_slab_code' => $id])->one();
        $saveModel = [];
        $deletedata = [];
        $historyModel = new TblMccBillHeadCriteriaSlabsHistory();
        Yii::$app->operation->history($existData, $historyModel, DELETE);
        $deletedata[] = $existData;
        $saveModel[] = $historyModel;
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deletedata, ['Bill Head Criteria', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Record Deleted Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Record Not Deleted.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionGetFromValue()
    {
        $Model = new TblMccBillHeadCriteriaSlabs();
        $existdata = $Model::find()
            ->select('max(to_val) as to_val')
            ->where(['criteria_code' => Yii::$app->request->post('id')])
            ->one();

        $data = !empty($existdata->to_val) ? $existdata->to_val + 0.01 : 0;
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($data);
    }

    public function actionUpdateToDate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $historyModel = new TblMccBillHeadCriteriaHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $model->load(Yii::$app->request->post());
            if (!empty($model->to_date)) {
                $model->to_date = Yii::$app->formatter->asDate($model->to_date, DATE_FORMAT);
                $historyModel->criteria_name = $historyModel->criteria_name . '-' . $model->to_date;
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($historyModel->save()) {
                        Yii::$app->db->createCommand("update tbl_mcc_bill_head_criteria_applicability set to_date = :to_date where criteria_code = :criteria_code  and from_date <= :from_date")
                            ->bindValue(':to_date', $model->to_date)
                            ->bindValue(':criteria_code', $model->criteria_code)
                            ->bindValue(':from_date', $model->to_date)
                            ->execute();
                        $transaction->commit();
                        Yii::$app->display->message(true, 'Mcc Criteria Applicability To Date', 'edit');
                        return $this->customRedirect();
                    } else {
                        $transaction->rollback();
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'error',
                            'message' => 'Your transaction is not saved successfully'
                        ]);
                    }
                } catch (yii\base\UserException $e) {
                    $transaction->rollback();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'error',
                        'message' => $e->getMessage()
                    ]);
                } catch (\yii\db\Exception $e) {
                    $transaction->rollback();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'error',
                        'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')
                    ]);
                }
            } else {
                $model->addError('to_date', \Yii::t('app', 'To Date can not be blank'));
            }
        }
        return $this->render('update_to_date', [
            'model' => $model,
        ]);
    }

    public function actionUpdateToDateApplicability($id)
    {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $value['BMC'] = 'BMC';
        $appModel->model = new TblMccBillHeadCriteriaApplicability();
        $appModel->model->scenario = 'updateToDate';
        $appModel->update_applicability = TRUE;
        $appModel->check_wef_date = TRUE;
        $appModel->model->to_date = date('Y-m-d');
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'criteria_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'Mcc bill head criteria applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $billHead = $model->mccBillHead;
        $appModel->assignStaticData = [
            'bill_head_for' => $billHead->bill_head_for,
            'mcc_bill_head_code' => $model->mcc_bill_head_code,
        ];
        $appModel->header_title = ' To Date Update Criteria: ' . $model->criteria_name . '] ';
        $appModel->fields = [
            'from_date' => ['view' => ['grid'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->from_date);
            }],
            'to_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->to_date);
            }],
           // 'applicable_for' => ['view' => ['grid', 'create'], 'value' => 'applicable_for','filter'=>false],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'mcc_name' => ['view' => ['grid'], 'value' => function ($model) {
                if ($model->applicable_for == 'PLANT') {
                    return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                } else if ($model->applicable_for == 'MCC') {
                    return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                } else if ($model->applicable_for == 'BMC') {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }
            }],

        ];

        // if ($billHead->bill_head_for == 'MEMBER') {
        //     $value = ['DCS' => Yii::t('app', 'DCS')];
        // } else {
        //     $customerType = new TblCustomerType();
        //     $customerType->union_code = $model->union_code;
        //     $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        // }
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

}
