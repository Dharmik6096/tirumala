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

/**
 * TblRateRecalculationController implements the CRUD actions for TblRateRecalculation model.
 */
class TblRateRecalculationController extends \app\controllers\ChildController {

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
                if (strtolower($searchModel->recalc_for) == 'member') {
                    $dcs_codes = $searchModel->dcs_code;
//                    if (empty($dcs_codes)) {
//                        $dcs = new TblDcs();
//                        $dcs_codes = array_keys($dcs->getBMCDCSList($searchModel->bmc_code));
//                    }
                } else if (strtolower($searchModel->recalc_for) == 'bmc') {
                    $dcs_codes = $searchModel->customer_code;
//                    if (empty($dcs_codes)) {
//                        $custome = new TblCustomerMaster();
//                        $data = $custome->getBMCCustomerList($searchModel->bmc_code);
//                        $dcs_codes = [];
//                        foreach ($data as $detail) {
//                            $searchModel->customer_code = $detail->customer_code;
//                            $searchModel->customer_type = $detail->customer_type;
//                            $dcs_codes[] = $searchModel;
//                        }
//                    }
                }

                $this->saveAndRedirect($dcs_codes, $searchModel, $this->model->rate_code, 'all');
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

                $this->saveAndRedirect($dcs_codes, $searchModel, $rateCodes, 'custom', $codes);
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

    public function saveAndRedirect($dcs_codes, $searchModel, $rateCode, $rtype, $data = []) {
        $master = [];
        //$coll_data = $dataProvider->allModels;//->getModels();
        if (empty($dcs_codes)) {
            $dcs_codes[] = NULL;
        } else if (!is_array($dcs_codes) && $rtype == 'custom') {
            $dcs_codes = [$searchModel->dcs_code];
        }
        if ($rtype == 'custom') {
            $customerType = $calcFor = [];
            foreach ($data as $code) {
                $c = explode('###', $code);
                $customerType[] = !empty($c[4]) ? $c[4] : '';
                $calcFor[] = !empty($c[5]) ? $c[5] : '';
            }
        } else {
            if (strtolower($searchModel->recalc_for) == 'member') {
                if (!is_array($dcs_codes)) {
                    $dcs_codes = [$searchModel->dcs_code];
                }
                $customerType = 'DCS';
                $calcFor = 'Member';
            } else {
                if (!is_array($dcs_codes)) {
                    $dcs_codes = [$searchModel->customer_code];
                }
                $customerType = $searchModel->customer_type;
                $calcFor = 'BMC';
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
                $saveModel->customer_code = $dcs_code;
                $saveModel->recalc_type = $rtype;
                $saveModel->customer_type = is_array($customerType) ? $customerType[$key] : $customerType;
                $saveModel->dcs_code = strtolower($saveModel->customer_type) == 'dcs' ? $dcs_code : NULL;
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
                        $sp_params = [$searchModel->bmc_code, $c[1], $c[0], date('Y-m-d H:i:s', strtotime($c[2])), date('Y-m-d H:i:s', strtotime($c[3])), $searchModel->recalc_for, $c[0], $c[4]];
                        $sp = 'sp_Portal_Process_Recalculation';
                        \Yii::$app->general->getSpData($sp, $sp_params);
                    }
                } else {
                    foreach ($dcs_codes as $code) {
                        $from_shift = Yii::$app->general->getshift($searchModel->from_shift);
                        $to_shift = Yii::$app->general->getshift($searchModel->to_shift);
                        $fdate = date('Y-m-d H:i:s', strtotime($searchModel->from_date . ' ' . $from_shift));
                        $tdate = date('Y-m-d H:i:s', strtotime($searchModel->to_date . ' ' . $to_shift));
                        $codes = $code;
                        $type = (strtolower($searchModel->recalc_for) == 'member' && empty($searchModel->customer_type)) ? 'DCS' : $searchModel->customer_type;
                        $sp_params = [$searchModel->bmc_code, $rateCode, $codes, $fdate, $tdate, $searchModel->recalc_for, $codes, $type];
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

        $this->redirect(['index']);
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

}
