<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblRateRecalculation;
use app\modules\dcsoperation\models\TblRateRecalculationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblCustomerMaster;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\payment\models\TblPaymentCycleApplicability;

/**
 * TblRateRecalculationController implements the CRUD actions for TblRateRecalculation model.
 */
class TblRateRecalculationController extends \app\controllers\ChildController {

    public $freeAccessActions = ['check-lock-payment'];

    /**
     * Lists all TblRateRecalculation models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblRateRecalculationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblRateRecalculation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView() {
        $searchModel = new TblRateRecalculationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderGrid = $searchModel->gridsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $dataProvider->getModels()[0],
                    'dataProviderGrid' => $dataProviderGrid,
                    'searchModelGrid' => $searchModel,
        ]);
    }

    /**
     * Creates a new TblRateRecalculation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $searchModel = new TblRateRecalculationSearch();
        $searchModel->scenario = 'recalculation_search';
        $this->model = new TblRateRecalculation();
        $this->model->union_code = $searchModel->union_code;
        $this->model->bmc_code = $searchModel->bmc_code;
        $dataProvider = null;
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $searchModel->load(Yii::$app->request->queryParams);
            if ($this->model->validate()) {
                $dcs_codes = [];
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                foreach ($codes as $code) {
                    $c = explode('###', $code);
                    $dcs_codes[] = $c[0];
                }
                return $this->saveAndRedirect($dcs_codes, $searchModel, $this->model->rate_code, 'all', $codes);
            }
        } else {
            $dataProvider = $searchModel->searchDataRecalculation(Yii::$app->request->queryParams);
        }

        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'rtype' => 'forced'
        ]);
    }

    public function actionCreateRecalc() {
        $searchModel = new TblRateRecalculationSearch();
        $this->model = new TblRateRecalculation();
        $searchModel->scenario = 'recalculation_search';
        $this->model->scenario = 'recalculation_search_custom';
        $this->model->union_code = $searchModel->union_code;
        $dataProvider = null;

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $searchModel->load(Yii::$app->request->queryParams);
            $dcs_codes = $rateCodes = [];
            $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
            foreach ($codes as $code) {
                $c = explode('###', $code);
                $dcs_codes[] = $c[0];
                $rateCodes[] = $c[1];
            }
            $this->model->rate_code = $rateCodes;
            $this->model->recalc_for = $searchModel->recalc_for;
            if ($this->model->validate()) {

                return $this->saveAndRedirect($dcs_codes, $searchModel, $rateCodes, 'custom', $codes);
            }
        }
        $dataProvider = $searchModel->searchDataRecalculation(Yii::$app->request->queryParams, 'sp_Portal_Data_Recalculation_Custom');

        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'rtype' => 'custom'
        ]);
    }

    public function saveAndRedirect($dcs_codes, $searchModel, $rateCode, $rtype, $data = [], $customeCode = []) {
        $master = [];
        //$coll_data = $dataProvider->allModels;//->getModels();
        if (empty($dcs_codes)) {
            $dcs_codes[] = NULL;
        } else if (!is_array($dcs_codes) && $rtype == 'custom') {
            $dcs_codes = [$searchModel->dcs_code];
        }
        $customerType = $calcFor = [];
        if ($rtype == 'custom') {
            foreach ($data as $code) {
                $c = explode('###', $code);
                $customerType[] = !empty($c[4]) ? $c[4] : '';
                $calcFor[] = !empty($c[5]) ? $c[5] : '';
            }
        } else {
            foreach ($data as $code) {
                $c = explode('###', $code);
                $customerType[] = !empty($c[1]) ? $c[1] : '';
                $calcFor[] = !empty($c[2]) ? $c[2] : '';
            }
        }
        if (!empty($dcs_codes)) {
            foreach ($dcs_codes as $key => $dcs_code) {
                $saveModel = new TblRateRecalculation();
                $saveModel->union_code = $searchModel->union_code;
                $saveModel->plant_code = $searchModel->plant_code;
                $saveModel->mcc_plant_code = $searchModel->mcc_plant_code;
                $saveModel->bmc_code = $searchModel->bmc_code;
                $saveModel->rate_code = is_array($rateCode) ? $rateCode[$key] : $rateCode;
                $saveModel->rate_type = is_array($calcFor) ? $calcFor[$key] : $calcFor;
                $saveModel->from_date = date('Y-m-d', strtotime($searchModel->from_date));
                $saveModel->to_date = date('Y-m-d', strtotime($searchModel->to_date));
                $saveModel->from_shift = $searchModel->from_shift;
                $saveModel->to_shift = $searchModel->to_shift;
                $saveModel->recalc_for = $searchModel->recalc_for;
                $saveModel->customer_code = !empty($dcs_code['customer_code']) ? $dcs_code['customer_code'] : $dcs_code;
                $saveModel->recalc_type = $rtype;
                $saveModel->customer_type = !empty($dcs_code['customer_type']) ? $dcs_code['customer_type'] : (is_array($customerType) ? $customerType[$key] : $customerType);
                $saveModel->dcs_code = strtolower($saveModel->customer_type) == 'dcs' ? (!empty($dcs_code['customer_code']) ? $dcs_code['customer_code'] : $dcs_code) : NULL;
                $master[] = $saveModel;
            }
        }
        $transaction = $this->generalModel->saveTransaction($master, ['Rate Recalculation', 'create']);
        if ($transaction == 'customRedirect') {
            $trans = \Yii::$app->db->beginTransaction();
            try {
                if ($rtype == 'custom') {
                    foreach ($data as $code) {
                        $c = explode('###', $code);
                        $sp_params = [$searchModel->bmc_code, $c[1], (string) $c[0], date('Y-m-d H:i:s', strtotime($c[2])), date('Y-m-d H:i:s', strtotime($c[3])), $c[5], (string) $c[0], $c[4]];
                        $sp = 'sp_Portal_Process_Recalculation';
                        \Yii::$app->general->getSpData($sp, $sp_params);
                    }
                } else {
                    foreach ($dcs_codes as $code) {
                        $from_shift = Yii::$app->general->getshift($searchModel->from_shift);
                        $to_shift = Yii::$app->general->getshift($searchModel->to_shift);
                        $fdate = date('Y-m-d H:i:s', strtotime($searchModel->from_date . ' ' . $from_shift));
                        $tdate = date('Y-m-d H:i:s', strtotime($searchModel->to_date . ' ' . $to_shift));
                        $codes = (string) (!empty($code['customer_code']) ? $code['customer_code'] : $code);
                        $sp_params = [$searchModel->bmc_code, $rateCode, $codes, $fdate, $tdate, $searchModel->recalc_for, $codes, $c[1]];
                        $sp = 'sp_Portal_Process_Recalculation';
                        \Yii::$app->general->getSpData($sp, $sp_params);
                    }
                }
                $trans->commit();
            } catch (UserException $e) {
                $trans->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $e->getMessage()]);
            } catch (\yii\db\Exception $e) {
                $trans->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing TblRateRecalculation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rate_recalculation_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblRateRecalculation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblRateRecalculation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblRateRecalculation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblRateRecalculation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDcsDispatch() {
        $searchModel = new TblRateRecalculationSearch();
        $searchModel->recalc_for = 'member';
        $searchModel->scenario = 'recalculation_dispatch';
        $this->model = new TblRateRecalculation();
        $dataProvider = null;
        if (Yii::$app->request->post()) {
            $searchModel->load(Yii::$app->request->queryParams);
            $this->model->attributes = $searchModel->attributes;
            $this->model->load(Yii::$app->request->post());
            if ($searchModel->recalc_type == 'custom') {
                $this->model->rate_code = '0';
            }
            if ($this->model->validate()) {
                $this->model->rate_type = 'Member';
                $this->model->recalc_for = 'member';
                $this->model->customer_type = 'DCS';
                $this->model->module_type = 'dispatch';
                $this->model->from_date = date('Y-m-d', strtotime($searchModel->from_date));
                $this->model->to_date = date('Y-m-d', strtotime($searchModel->to_date));

                return $this->saveDispatchData($this->model);
            }
        } else {
            $dataProvider = $searchModel->searchDataDispatch(Yii::$app->request->queryParams);
        }

        return $this->render('create_dispatch', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function saveDispatchData($model) {
        $saveModel = [];
        if ($model->recalc_type == 'all') {
            if (empty($model->dcs_code)) {
                $dcs = new TblDcs();
                $dcs_codes = array_keys($dcs->getBMCDCSList($model->bmc_code));
            } else {
                $dcs_codes [] = $model->dcs_code;
            }
            foreach ($dcs_codes as $key => $dcs_code) {
                $model->dcs_code = $model->customer_code = $dcs_code;
                $rate_model = new TblRateRecalculation();
                $rate_model->attributes = $model->attributes;
                $fdate = $rate_model->from_date . ' ' . Yii::$app->general->getshift($rate_model->from_shift);
                $tdate = $rate_model->to_date . ' ' . Yii::$app->general->getshift($rate_model->to_shift);
                $rate_model->sp_param = [(string) $rate_model->bmc_code, (string) $rate_model->dcs_code, $fdate, $tdate, $rate_model->rate_code];
                $saveModel[] = $rate_model;
            }
        } else {
            $data = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
            foreach ($data as $code) {
                $c = explode('###', $code);
                $model->dcs_code = $model->customer_code = $c[0];
                $model->rate_code = $c[1];
                $rate_model = new TblRateRecalculation();
                $rate_model->attributes = $model->attributes;
                $rate_model->sp_param = [(string) $rate_model->bmc_code, (string) $c[0], date('Y-m-d H:i:s', strtotime($c[2])), date('Y-m-d H:i:s', strtotime($c[3])), $c[1]];
                $saveModel[] = $rate_model;
            }
        }

        if (!empty($saveModel)) {
            $trans = \Yii::$app->db->beginTransaction();
            try {
                $master = [];
                foreach ($saveModel as $m) {
                    $res = $m->save();
                    $master[] = $res;
                    if ($res) {
                        Yii::$app->general->getSpData('sp_Portal_Process_Recalculation_Dispatch', $m->sp_param);
                    } else {
                        break;
                    }
                }
                if (!in_array(FALSE, $master)) {
                    $trans->commit();
                    Yii::$app->display->message(true, 'Rate Recalculation for Dispatch', 'create');
                } else {
                    $trans->rollback();
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Your transaction is not saved successfully']);
                }
            } catch (UserException $e) {
                $trans->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $e->getMessage()]);
            } catch (\yii\db\Exception $e) {
                $trans->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionCheckLockPayment() {
        $response = [];
        $response['status'] = 'success';
        $response['msg'] = '';

        $postData = Yii::$app->request->post()['selection'];
        $searchData = Yii::$app->request->post()['TblRateRecalculationSearch'];

        foreach ($postData as $detail) {
            $expload = explode('###', $detail);

            if (!empty(Yii::$app->request->post()['TblRateRecalculation'])) {
                $fromDate = Yii::$app->formatter->asDate($searchData['from_date'], 'php:Y-m-d');
                $toDate = Yii::$app->formatter->asDate($searchData['to_date'], 'php:Y-m-d');
                $type = $expload[1];
                $for = $expload[2];
                $name = $expload[3];
            } else {
                $fromDate = Yii::$app->formatter->asDate($expload[2], 'php:Y-m-d');
                $toDate = Yii::$app->formatter->asDate($expload[3], 'php:Y-m-d');
                $type = $expload[4];
                $for = $expload[5];
                $name = $expload[6];
            }
            $flagArray = $for == 'Member' ? ['data_lock_member', 'billing_lock_member'] : ['data_lock_bmc', 'billing_lock_bmc'];
            $payment_model = new TblPaymentCycleApplicability;
            $data = $payment_model->find()
                    ->where(['union_code' => $searchData['union_code'], 'applicable_code' => $searchData['bmc_code'], 'applicable_for' => 'BMC', 'applicable_type' => $type])
                    ->andWhere(['or', ['AND', ['<=', 'CAST(from_date as date)', $fromDate], ['>=', 'CAST(to_date as date)', $fromDate]], ['AND', ['<=', 'CAST(from_date as date)', $toDate], ['>=', 'CAST(to_date as date)', $toDate]]])
                    ->one();

            if (!empty($data)) {
                foreach ($flagArray as $flag) {
                    if ($data->$flag == 1) {
                        $response['msg'] = 'Payment Cycle is Locked For <b>' . $name . '</b>, Recal For <b>' . $for . '</b>';
                        $response['status'] = 'error';
                    }
                }
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

}
