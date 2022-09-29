<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblBmcCollectionTransfer;
use app\modules\collection\models\TblBmcCollectionTransferSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\modules\collection\models\TblBmcCollectionTransferHistory;
use app\modules\collection\models\TblBmcCollection;
use app\modules\organisation\models\TblDcs;
use app\modules\collection\models\TblBmcCollectionHistory;

/**
 * TblBmcCollectionTransferController implements the CRUD actions for TblBmcCollectionTransfer model.
 */
class TblBmcCollectionTransferController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcCollectionTransfer models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcCollectionTransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcCollectionTransfer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcCollectionTransfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $searchModel = new TblBmcCollectionTransferSearch();
        $dataProvider = $searchModel->createsearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'MilkCollectionTranfer';

        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $saveModel = [];
                $deleteModel = [];
                $type = 'create';
                $post = Yii::$app->request->post()['TblBmcCollectionTransferSearch'];
              
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($deletedata as $code) {
                    $where['milk_collection_code'] = $code;
                    $existData = TblBmcCollection::find()->where($where)->one();
                    $bmc = $existData->bmc_code;
                    $dcs = $existData->dcs_code;
                    $dcsModel = TblDcs::find()->where(['dcs_code' => $dcs, 'bmc_code' => $bmc])->one();
                    $sapVendorCode = !empty($dcsModel) ? $dcsModel->sap_vendor_code : '';
                    $dcsVendorModel = TblDcs::find()->where(['sap_vendor_code' => $sapVendorCode, 'plant_code' => $post['to_plant_code'], 'mcc_plant_code' => $post['to_mcc_plant_code']])->one();
                    if (!empty($dcsVendorModel)) {
                        $succCount++;
                        $historyModel = new TblBmcCollectionHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->plant_code = $post['to_plant_code'];
                        $existData->mcc_plant_code = $post['to_mcc_plant_code'];
                        $existData->own_mcc_plant_code = $post['to_mcc_plant_code'];
                        $existData->bmc_code = $post['to_mcc_plant_code'];
                        $existData->own_bmc_code = $post['to_mcc_plant_code'];
                        $existData->dcs_code = $dcsVendorModel->dcs_code;
                        $existData->bmc_code = $dcsVendorModel->bmc_code;
                        $existData->route_code = $dcsVendorModel->route_code;
                        $existData->scenario = 'DataTransfer';
                        $saveModel[] = $existData;
                    } else {
                        $errorCount++;
                    }
                }
                $Transfer = new TblBmcCollectionTransfer();
                $Transfer->attributes = $searchModel->attributes;
                $Transfer->from_date = Yii::$app->formatter->asDate($Transfer->from_date , DATE_FORMAT) . ' ' . Yii::$app->general->getshift($Transfer->from_shift_code);
                $Transfer->to_date = Yii::$app->formatter->asDate($Transfer->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($Transfer->to_shift_code);

                $Transfer->to_plant_code = $post['to_plant_code'];
                $Transfer->to_mcc_plant_code = $post['to_mcc_plant_code'];
                $saveModel[] = $Transfer;
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['BMC Collection data Tranfer', $type]);
//                if ($transaction == 'customRedirect') {
//                    return $this->redirect(['index']);
//                }
            }
            $msg = ('BMC  Collection Data Tranfer Tranfer successfully. <br />Tranfer count : ' . $succCount . '<br />Not Tranfer count : ' . $errorCount);
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => $msg]);
            $getData = Yii::$app->request->queryParams;
            if (!empty($getData['TblBmcCollectionTransferSearch'])) {
                return $this->redirect(['create', 'TblBmcCollectionTransferSearch' => $getData['TblBmcCollectionTransferSearch']]);
            } else {
                return $this->redirect(['create']);
            }
        }

        return $this->render('create', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing TblBmcCollectionTransfer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tranfer_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblBmcCollectionTransfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBmcCollectionTransfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBmcCollectionTransfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcCollectionTransfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
