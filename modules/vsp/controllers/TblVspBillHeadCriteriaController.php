<?php

namespace app\modules\vsp\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\modules\vsp\models\TblVspBillHeadCriteriaSearch;
use app\modules\vsp\models\TblVspBillHeadCriteria;
use app\modules\vsp\models\TblVspBillHeadCriteriaSlabs;
use app\modules\vsp\models\TblVspBillHeadCriteriaApplicability;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\vsp\models\TblVspBillHeadCriteriaApplicabilityHistory;
use app\modules\vsp\models\TblCriteriaKeywordMapping;
use app\modules\vsp\models\TblVspBillHeadCriteriaSlabsHistory;
use app\modules\vsp\models\TblVspBillHeadCriteriaSlabsSearch;
use app\modules\vsp\models\TblVspBillHeadCriteriaHistory;
use app\modules\vsp\models\TblBillHeadHistory;

/**
 * TblBillHeadController implements the CRUD actions for TblBillHead model.
 */
class TblVspBillHeadCriteriaController extends \app\controllers\ChildController
{

    public $freeAccessActions = ['keyword', 'get-from-value'];

    /**
     * Lists all TblBillHead models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblVspBillHeadCriteriaSearch();
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
    public function actionView($id)
    {
        $bsearchModel = new TblVspBillHeadCriteriaSlabsSearch();
        $bsearchModel->vsp_criteria_code = $id;
        $bdataProvider = $bsearchModel->createsearch(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
        ]);
    }

    /**
     * Creates a new TblBillHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblVspBillHeadCriteria();
        $searchModel = new TblVspBillHeadCriteriaSlabsSearch();
        $dataProvider = $searchModel->createsearch([]);
        $dataProvider->sort = false;
        $txModel = new TblVspBillHeadCriteriaSlabs();
        $this->viewFile = 'create';
        $txModel->scenario = 'create';
        $modelSave = [];
        $message = 'Bill Head Criteria';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());

            $txModel->load(Yii::$app->request->post());
            if ($txModel->validate()) {
                if (empty($this->model->vsp_criteria_code)) {
                    $this->model->scenario = 'create';
                    $this->model->vsp_criteria_code = Yii::$app->general->getPrimaryCode($this->model);
                    $modelSave[] = $this->model;
                }
                $txModel->vsp_criteria_code = $this->model->vsp_criteria_code;
                $txModel->vsp_slab_code = Yii::$app->general->getTransactionCode($txModel, $txModel->vsp_criteria_code);
                $txModel->union_code = $this->model->union_code;
                $txModel->general_formula_code = $this->model->general_formula_code;
                $txModel->bill_head_code = $this->model->bill_head_code;
                $modelSave[] = $txModel;
            }
            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->vsp_criteria_code];
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
                //return Json::encode(ActiveForm::validate($this->model, $txModel));
            }
        } else {
            return $this->render('create', [
                'model' => $this->model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'txModel' => $txModel,
            ]);
        }
        return $this->render('create', [
            'model' => $this->model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'txModel' => $txModel,
        ]);
    }

    /**
     * Updates an existing TblBillHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $searchModel = new TblVspBillHeadCriteriaSlabsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->sort = false;
        $txModel = new TblVspBillHeadCriteriaSlabs();
        $this->viewFile = 'create';
        $txModel->scenario = 'create';
        $modelSave = [];
        $message = 'Bill Head Criteria';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $txModel->load(Yii::$app->request->post());
            if ($txModel->validate()) {
                $historyModel = new TblVspBillHeadCriteriaHistory();
                Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
                $modelSave[] = $historyModel;
                $txModel->vsp_criteria_code = $this->model->vsp_criteria_code;
                $txModel->vsp_slab_code = Yii::$app->general->getTransactionCode($txModel, $txModel->vsp_criteria_code);
                $txModel->union_code = $this->model->union_code;
                $txModel->general_formula_code = $this->model->general_formula_code;
                $txModel->bill_head_code = $this->model->bill_head_code;
                $modelSave[] = $txModel;
            }
            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->vsp_criteria_code];
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
                //                return Json::encode(ActiveForm::validate($this->model, $txModel));
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
                'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel,
            ]);
        }
        return $this->render('update', [
            'model' => $this->model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'txModel' => $txModel,
        ]);
    }

    /**
     * Deletes an existing TblBillHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
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
    protected function findModel($id)
    {
        if (($model = TblVspBillHeadCriteria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionVspBillHeadApplicability($id)
    {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblVspBillHeadCriteriaApplicability();
        $appModel->model->wef_date = $appModel->model->from_date = date('Y-m-d');
        $appModel->model->to_date = date('Y-m-d', strtotime('+ 1 year'));
        $appModel->periodic_applicability = TRUE;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'vsp_criteria_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'bill head criteria applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $billHead = $model->billHead;
        $appModel->assignStaticData = [
            'bill_head_for' => $billHead->bill_head_for,
            'bill_head_code' => $model->bill_head_code,
        ];
        $appModel->header_title = ' [Criteria: ' . $model->criteria_name . '] ';
        $appModel->fields = [
            'from_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->from_date);
            }],
            'to_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->to_date);
            }],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
            }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function ($model) {
                return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
            }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
                return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
            }],
            'name' => ['view' => ['grid'], 'value' => function ($model) {
                return $model->getName($model->applicable_for);
            }],
        ];
        if ($billHead->bill_head_for == 'MEMBER') {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType = new TblCustomerType();
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        }
        $appModel->dcs_filters = $value;
        $appModel->actions = ['delete' => ['option' => 'bill_head_criteria_applicability_code,bill_head_criteria_applicability_code,tbl-vsp-bill-head-criteria/delete-applicability']];

        return $appModel->createApp();
    }

    public function actionDeleteApplicability()
    {
        $model = new TblVspBillHeadCriteriaApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblVspBillHeadCriteriaApplicabilityHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionListGrid()
    {
        $searchModel = new TblVspBillHeadCriteriaSlabsSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblVspBillHeadCriteria'));

        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
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
        $deleteModel = new TblVspBillHeadCriteriaSlabs();
        $id = Yii::$app->request->get('id');
        $existData = $deleteModel::find()->where(['vsp_slab_code' => $id])->one();
        $saveModel = [];
        $deletedata = [];
        $deletedetail = $deleteModel::find()->where(['vsp_criteria_code' => $existData->vsp_criteria_code])
            ->andWhere(['>=', 'from_val', $existData->from_val])
            ->all();
        foreach ($deletedetail as $value) {
            $historyModel = new TblVspBillHeadCriteriaSlabsHistory();
            Yii::$app->operation->history($value, $historyModel, DELETE);
            $deletedata[] = $value;
            $saveModel[] = $historyModel;
        }
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
        $Model = new TblVspBillHeadCriteriaSlabs();
        $existdata = $Model::find()
            ->select('max(to_val) as to_val')
            ->where(['vsp_criteria_code' => Yii::$app->request->post('id')])
            ->one();

        $data = !empty($existdata->to_val) ? $existdata->to_val + 0.01 : 0;
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($data);
    }

    public function actionUpdateToDate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $historyModel = new TblVspBillHeadCriteriaHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $model->load(Yii::$app->request->post());
            if (!empty($model->to_date)) {
                $model->to_date = Yii::$app->formatter->asDate($model->to_date, DATE_FORMAT);
                $historyModel->criteria_name = $historyModel->criteria_name . '-' . $model->to_date;
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($historyModel->save()) {
                        Yii::$app->db->createCommand("update tbl_vsp_bill_head_criteria_applicability set to_date = :to_date where vsp_criteria_code = :vsp_criteria_code  and from_date <= :from_date")
                            ->bindValue(':to_date', $model->to_date)
                            ->bindValue(':vsp_criteria_code', $model->vsp_criteria_code)
                            ->bindValue(':from_date', $model->to_date)
                            ->execute();
                        $transaction->commit();
                        Yii::$app->display->message(true, 'Bill Head Criteria Applicability To Date', 'edit');
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
        $appModel->model = new TblVspBillHeadCriteriaApplicability();
        $appModel->model->scenario = 'updateToDate';
        $appModel->update_applicability = TRUE;
        $appModel->check_wef_date = TRUE;
        $appModel->model->to_date = date('Y-m-d');
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'vsp_criteria_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'bill head criteria applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $billHead = $model->billHead;
        $appModel->assignStaticData = [
            'bill_head_for' => $billHead->bill_head_for,
            'bill_head_code' => $model->bill_head_code,
        ];
        $appModel->header_title = ' To Date Upadte [Criteria: ' . $model->criteria_name . '] ';
        $appModel->fields = [
            'from_date' => ['view' => ['grid'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->from_date);
            }],
            'to_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->to_date);
            }],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
            }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function ($model) {
                return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
            }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
                return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
            }],
            'name' => ['view' => ['grid'], 'value' => function ($model) {
                return $model->getName($model->applicable_for);
            }],
        ];

        if ($billHead->bill_head_for == 'MEMBER') {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType = new TblCustomerType();
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        }
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }
}
