<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblTankerRateApplicability;
use app\modules\tankermovement\models\TblTankerRateApplicabilitySearch;
use app\modules\tankermovement\models\TblTankerRate;
use app\modules\tankermovement\models\TblPartyMaster;
use app\modules\organisation\models\TblUnions;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\tankermovement\models\TblTankerRateApplicabilityHistory;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * TblTankerRateApplicabilityController implements the CRUD actions for TblTankerRateApplicability model.
 */
class TblTankerRateApplicabilityController extends \app\controllers\ChildController {

    public $purchaseRate;
    public $routes;

    /**
     * Lists all TblTankerRateApplicability models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTankerRateApplicabilitySearch();
        $dataProvider = $searchModel->downloadSearch(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTankerRateApplicability model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblTankerRateApplicability model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $pr = new TblTankerRate();
        $purchaseRate = $pr->getRecord($id);
        $this->purchaseRate = $purchaseRate;
        $this->model = new TblTankerRateApplicability();
        $this->viewFile = 'create';
        $this->model->union_code = $purchaseRate->union_code;
        $this->model->tanker_rate_code = $id;
        $this->model->wef_date = $purchaseRate->wef_date;
        $this->model->shift_code = $this->routes['shiftCode'];
        if ($this->model->load(Yii::$app->request->post())) {

            if ($this->model->validate()) {
                $oldModel = new TblTankerRateApplicability();
                $mappingList = [];
                $dataold = $oldModel->find()->where(['tanker_rate_code' => $id])->all();
                $returnedArray = \yii\helpers\ArrayHelper::map($dataold, 'applicable_code', 'applicable_code');

                $toRevoke = array_diff($returnedArray, $this->model->applicable_code);
                $toAssign = array_diff($this->model->applicable_code, $returnedArray);
               
                
//                    foreach ($toRevoke as $value) {
//                        $revokeModel = TblTankerRateApplicability::find()->where(['applicable_code' => $value, 'tanker_rate_code' => $id])->one();
//
//                        $milkHistory = new TblTankerRateApplicabilityHistory();
//                        echo '<pre>';
//                        print_r($revokeModel);
//                        print_r($milkHistory);
//                        die;
//                        Yii::$app->operation->history($revokeModel, $milkHistory, DELETE);
//                        $mappingList[] = $milkHistory;
//                        $mappingList[] = $milkModel;
//                        //                    echo 'revike = '.$value.' code = '.$milkModel->rate_app_code.' = '.$milkModel->is_delete.'<br>';
//                    }
                
                //$code = $this->model->getCode();
                foreach ($toAssign as $value) {
                    $milkModel = new TblTankerRateApplicability();
//                     $milkModel->rate_app_code = $code;
                    $milkModel->applicable_code = $value;
                    $milkModel->applicable_for = 'Party';
                    $milkModel->tanker_rate_code = $this->model->tanker_rate_code;
                    $milkModel->union_code = $this->model->union_code;
                    $milkModel->is_active = 1;
                    $milkModel->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
                    $milkModel->shift_code = $this->model->shift_code;
                    $check = $milkModel->checkDuplicate();

                    if ($check == 1) {
                        $this->model->addError('wef_date', $milkModel->wef_date . ' date already taken by party.');
                        return $this->customRender();
                    }
//                    $code = str_pad($code + 1, 2, '0', STR_PAD_LEFT);
                    $this->model = $milkModel;
                    $mappingList[] = $milkModel;
                }
                $transaction = $this->generalModel->saveTransaction($mappingList, ['Tanker rate applicability', 'create']);
                if($transaction=='customRedirect')
                {
                    return $this->redirect(['tbl-tanker-rate-applicability/create','id'=>$this->model->tanker_rate_code]);
                }
            }
        }
        return $this->customRender();
    }

    protected function customRender() {
        $searchModel = new TblTankerRateApplicabilitySearch();
        $searchModel->tanker_rate_code = $this->model->tanker_rate_code;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $selected = $this->model->getParty();
        if (!empty($this->model->union_code)) {
            $party_list = $this->loadParty($this->model->union_code);
        } else {
            $party_list = [];
        }
        return $this->render('create', [
                    'model' => $this->model, 'purchaseRate' => $this->purchaseRate,
                    'party_list' => $party_list, 'selected' => $selected,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function customRedirect() {

        return $this->redirect(['tbl-tanker-rate/index']);
    }

    /**
     * Updates an existing TblTankerRateApplicability model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        echo '<pre>';
       print_r($id);
        die;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rate_app_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }
    
       public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $model = new TblTankerRateApplicability();
        $data = $model->findOne(['rate_app_code'=>$id]);
        $this->model = $data;
        $historyModel = new TblTankerRateApplicabilityHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;
        
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Tanker Rate Applicability', 'delete']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(['create', 'id' => $this->model->tanker_rate_code]);
        }
    }


//    public function actionDelete() {
//        $saveModel = [];
//        $deleteModel = [];
//        $this->model = $this->findModel(Yii::$app->request->post('id'));
//        echo 'wewew<pre>';
//        print_r(Yii::$app->request->post('id'));
//        die;
//        $historyModel = new TblTankerRateApplicabilityHistory();
//        Yii::$app->operation->history($this->model, $historyModel, DELETE);
//        $deleteModel[] = $this->model;
//        $saveModel[] = $historyModel;
//        echo 'ss<pre>';
//        die;
//
//        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Tanker Rate Applicability', 'delete']);
//        if ($transaction == 'customRedirect') {
//            //return $this->redirect(['create', 'id' => $this->model->tanker_rate_code]);
//        }
//    }

    /**
     * Finds the TblTankerRateApplicability model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblTankerRateApplicability the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTankerRateApplicability::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function loadParty($union_code) {
        $partyModel = new TblPartyMaster();
        $partyList = $partyModel->find()->where(['union_code' => $union_code])->all();
        $partyList = ArrayHelper::map($partyList, 'party_master_code', 'party_name');
        return $partyList;
    }
}
