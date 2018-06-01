<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblMilkCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblPurchaseRate;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMilkCollectionController implements the CRUD actions for TblMilkCollection model.
 */
class TblMilkCollectionController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-rtpl'];

    /**
     * Lists all TblMilkCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkCollection model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkCollection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkCollection();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->mobile_no = Yii::$app->general->getforeignkey($this->model->memberCode, 'mobile_no');
            $this->model->name = Yii::$app->general->getforeignkey($this->model->memberCode, 'member_name');
            $this->model->village_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'village_code');
            $datetime = date('Y-m-d H:i:s');
            $this->model->date_time_of_collection = date('Y-m-d') . ' ' . Yii::$app->general->getshift($this->model->shift);
            $this->model->date_time_of_recieve = $datetime;
            $this->model->dt_date = $datetime;
            $this->model->qlty_time = $datetime;
            $this->model->qty_time = $datetime;
            $this->model->type_of_data_receive = 'Manual';
            $this->model->status = 'Accept';
            $this->model->qty_mode = 1;
            $this->model->qlty_auto = 1;
            $this->model->qty_auto = 1;
            $this->model->sms_status = 'n';
            $this->model->sample_no = $this->model->getSampleNo();
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Collection', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMilkCollection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->milk_collection_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMilkCollection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMilkCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidateRtpl() {
        $data['dcs_code'] = Yii::$app->request->post('dcs_code');
        $data['milk_type'] = Yii::$app->request->post('milk_type');
        $data['milk_quality_type'] = Yii::$app->request->post('milk_quality_type');
        $data['dt_date'] = Yii::$app->request->post('dt_date');
        $data['dt_date'] = (Yii::$app->request->post('dt_date')) ? Yii::$app->formatter->asDate(Yii::$app->request->post('dt_date'), DATE_FORMAT) : '';
        $data['shift'] = Yii::$app->request->post('shift');
        $data['dt_date'] = $data['dt_date'] . ' ' . \Yii::$app->general->getshift($data['shift']);
        $data['fat'] = Yii::$app->request->post('fat');
        $data['snf'] = Yii::$app->request->post('snf');

        $model = new TblPurchaseRate();
        $rtpl_data['list'] = $model->purchaseRate($data);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        if (!empty($rtpl_data['list'])) {
            return Json::encode(['status' => 'success', 'data' => $rtpl_data]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

}
