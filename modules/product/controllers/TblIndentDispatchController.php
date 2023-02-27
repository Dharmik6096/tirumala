<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblIndentDispatch;
use app\modules\product\models\TblIndentDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblIndentMasterSearch;
use app\modules\product\models\TblIndentMaster;
use app\modules\product\models\TblIndentMasterHistory;

/**
 * TblIndentDispatchController implements the CRUD actions for TblIndentDispatch model.
 */
class TblIndentDispatchController extends \app\controllers\ChildController {

    public $searchModel;
    public $dataProvider;

    /**
     * Lists all TblIndentDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblIndentDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblIndentDispatch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblIndentDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $dispatchModel = new TblIndentDispatch();
        $searchModel = new TblIndentMasterSearch();
        $searchModel->scenario = 'indentApprove';
        if (Yii::$app->request->post()) {
            $searchModel->load(Yii::$app->request->post());
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = 5;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $msg = 'Indent Dispatch';
                $where = [];
                $i = 1;
                foreach ($codes as $code) {
                    $data = explode('###', $code);
                    $dcs = $data[0];
                    $product = $data[1];
                    $qty = $data[2];
                    $dispatch = new TblIndentDispatch();
                    $dispatch->setAttributes($searchModel->attributes);
                    $dispatch->indent_dispatch_code = Yii::$app->general->getCodeAutoIncrement($dispatch, $i); //$this->model->getChallanNo($modelAttributes[$key]->sub_center_code);
                    $dispatch->challan_date = date('Y-m-d'); //date('Y-m-d', strtotime($dispatchModel->challan_date));
                    $dispatch->vehicle_no = 1; //$dispatchModel->vehicle_no;
                    $dispatch->dispatch_date = $dispatch->challan_date;
                    $dispatch->reference_no = '123'; //$dispatch->reference_no;
                    $dispatch->dcs_code = $dcs;
                    $dispatch->product_code = $product;
                    $dispatch->customer_code = $dcs;
                    $dispatch->customer_type = 'dcs';
                    $dispatch->status = 5;
                    $dispatch->route_code = $searchModel->route_code;
                    $dispatch->dispatch_qty = $qty;
                    $saveModel[] = $dispatch;
                    $existIndentData = TblIndentMaster::find()->where(['dcs_code' => $dcs, 'product_code' => $product, 'status' => 2])->all();
                    if (!empty($existIndentData)) {
                        foreach ($existIndentData as $indent) {
                            $historyModel = new TblIndentMasterHistory();
                            Yii::$app->operation->history($indent, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $indent->status = 5;
                            $indent->status_by = \Yii::$app->user->identity->user_code;
                            $indent->status_date = date('Y-m-d H:i:s');
                            $saveModel[] = $indent;
                        }
                    }
                    $i ++;
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, [$msg, 'create']);
                if ($transaction == 'customRedirect') {
//                    return $this->redirect(['index']);
                }
            }
        }
        $dataProvider = $searchModel->indentdispatchsearch(Yii::$app->request->queryParams);

        return $this->render('create', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dispatchModel' => $dispatchModel,
        ]);
    }

    /**
     * Updates an existing TblIndentDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->indent_dispatch_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblIndentDispatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblIndentDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblIndentDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblIndentDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
