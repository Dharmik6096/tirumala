<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblTaxDetail;
use app\modules\dcsaccounting\models\TblTaxDetailHistory;
use app\modules\dcsaccounting\models\TblTaxDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsaccounting\models\TblTax;
use app\modules\dcsaccounting\models\TblTaxHistory;
use app\modules\dcsaccounting\models\TblTaxDepends;
use app\modules\dcsaccounting\models\TblTaxDependsHistory;
use app\modules\dcsaccounting\models\TblBasicTax;
use app\modules\dcsaccounting\models\TblBasicTaxHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblTaxDetailController implements the CRUD actions for TblTaxDetail model.
 */
class TblTaxDetailController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-tax-depends'];
    protected $data;
    protected $depends;
    protected $basic_tax;
    private $list = [];

    /**
     * Lists all TblTaxDetail models.
     * @return mixed
     */
    public function actionIndex($id = null) {
        $taxModel = new TblTax();
        $record = $taxModel->getRecord($id);
        $taxes = $taxModel->getActiveTax();

        $taxModel = new TblTaxDepends();
        $model = new TblTaxDetail();
        $data = $model->getDetailData($id);

        return $this->render('index', [
                    'record' => $record,
                    'taxModel' => $taxModel, 'data' => $data, 'taxes' => $taxes
        ]);
    }

    public function actionGetTaxDepends() {

        if (!empty($_POST['value'])) {
            $taxModel = new TblTaxDepends();
            $data = $taxModel->getRecords($_POST['value']);
            echo \yii\helpers\Json::encode(['status' => 'success', 'data' => $data]);
        }
    }

    public function actionGetCalculation() {
        $tax = $_POST['tax'];
        $value = $_POST['amount'];
        $model = new TblTaxDetail();
        $data = $model->getDetail($tax);
        $dependModel = new TblTaxDepends();
        $records = [];
        $calculation = null;
        $total = 0;
        foreach ($data as $key => $d) {
            $records[$key]['tax_code'] = $d->basic_tax_code;
            $records[$key]['tax_name'] = $d->basicTaxCode->basic_tax_name;
            $records[$key]['tax_val'] = $d->percentage;
            $records[$key]['operation'] = ($d->type == 0) ? 'Addition' : 'Substraction';

            if ($key == 0) {
                $calculation = $value * $d->percentage / 100;
            } else {

                $sum = 0;
                $depend_data = $dependModel->getDepends($d->tax_detail_code);
                foreach ($depend_data as $depend) {
                    $sum += $calculation;
                }
                $calculation = $sum * $d->percentage / 100;
            }
            $records[$key]['amount'] = $calculation;
            if ($d->type == 0)
                $total += $records[$key]['amount'];
            else
                $total -= $records[$key]['amount'];
        }

        echo \yii\helpers\Json::encode(['status' => 'success', 'data' => $records, 'total' => $total]);
    }

    /**
     * Displays a single TblTaxDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblTaxDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblTaxDetail();
        $this->model->tax_code = $id;
        $this->viewFile = 'create';

        $this->data = $this->model->getDetailData($id);
        $this->depends = new TblTaxDepends();
        $basicTax = new TblBasicTax();
        $this->basic_tax = $basicTax->basicTaxForDetail($id);

        $taxModel = new TblTax();
        $taxModelData = $taxModel->findOne($id);
        if (!empty($taxModelData)) {
            $taxModel = $taxModelData;
        }
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->tax_detail_code = Yii::$app->general->getCodeAutoIncrement($this->model); //$this->model->getCode();
            $this->model->is_active = 1;
            $this->model->tax_group_code = $taxModel->tax_group_code;
            if (empty($_POST['TblTaxDetail']['tax_detail_code'])) {
                $this->model->addError('tax_depends', 'Please select at least one record.');
                return $this->customRender();
            }
            $mapList = [];
            $mapList = $this->addTexDepends($_POST['TblTaxDetail']['tax_detail_code'], $this->model, $this->model->is_active);

            $transaction = $this->generalModel->saveTransaction([$this->model], $mapList, ['Tax Detail', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    public function addTexDepends($post, $tax_detail_id, $is_active) {
        $list = [];
        if (!empty($post)) {
            $i = 1;
            foreach ($post as $key => $value) {
                $depends = new TblTaxDepends();
                $depends->tax_depends_code = Yii::$app->general->getCodeAutoIncrement($this->model, $i);
                $depends->tax_detail_code = $tax_detail_id->tax_detail_code;
                $depends->union_code = $tax_detail_id->union_code;
                $depends->is_active = $is_active;
                $depends->steps = ($key + 1);
                array_push($list, $depends);
                $i++;
//                $code = str_pad((int) $code + 1, 9, '0', STR_PAD_LEFT);
            }
        }
        return $list;
    }

    public function customRender() {
        return $this->render('create', ['model' => $this->model, 'data' => $this->data, 'depends' => $this->depends, 'basic_tax' => $this->basic_tax]);
    }

    public function customRedirect() {
        return $this->redirect(['index', 'id' => $this->model->tax_code]);
    }

    /**
     * Updates an existing TblTaxDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tax_detail_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblTaxDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $record = ['status' => 'error', 'msg' => 'No Record Selected'];
        if (!empty($_POST['id'])) {

            $id = $_POST['id'];
            $mainModel = $this->findModel($id);
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted'];
            if ($mainModel) {
                $this->getTaxDetail($_POST['id']);
                $list[] = $_POST['id'];
                $list = array_unique(array_merge($list, array_values($this->list)));
                $save_list = [];
                foreach ($list as $a) {
                    $taxDependModel = new TblTaxDepends();
                    $taxDependsChild = $taxDependModel->getDetailRecord($a);
                    foreach ($taxDependsChild as $model) {
                        $historyModel = new TblTaxDependsHistory();
                        Yii::$app->operation->history($model, $historyModel, DELETE);
                        $save_list[] = $model;
                        $save_list[] = $historyModel;
                    }
                }
                foreach ($list as $a) {
                    $mainModel = $this->findModel($a);
                    $historyModel = new TblTaxDetailHistory();
                    Yii::$app->operation->history($mainModel, $historyModel, DELETE);
                    $save_list[] = $mainModel;
                    $save_list[] = $historyModel;
                }

                $record = $this->generalModel->deleteTransaction($save_list);
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    private function getTaxDetail($id) {
        $model = new TblTaxDepends();
        $data = $model->find()->select(['tax_detail_code'])->where(['tax_detail_code' => $id])->asArray()->all();
        $this->list = array_merge($this->list, $this->getchild($data));
    }

    private function getchild($array) {
        $list = [];
        foreach ($array as $a) {
            $list[] = $a['tax_detail_code'];
        }
        return $list;
    }

    /**
     * Finds the TblTaxDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTaxDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTaxDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
