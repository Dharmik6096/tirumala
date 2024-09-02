<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblLocalMilkRate;
use app\modules\dcsoperation\models\TblLocalMilkRateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblLocalMilkSaleRate;
use app\modules\dcsoperation\models\TblLocalMilkSaleRateHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblLocalMilkRateController implements the CRUD actions for TblLocalMilkRate model.
 */
class TblLocalMilkRateController extends \app\controllers\ChildController {

    /**
     * Lists all TblLocalMilkRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLocalMilkRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductSaleRate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblLocalMilkRateSearch();
        $query = TblLocalMilkRate::find()->select(['milk_quality_type_code', 'union_code'])->where(['local_milk_rate_code' => $id])->one();
        $milk_quality_type_code = $query['milk_quality_type_code'];
        $searchModel->milk_quality_type_code = $milk_quality_type_code;
        $searchModel->union_code = $query['union_code'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductSaleRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblLocalMilkRate();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Local Milk Rate', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblLocalMilkRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->local_sale_rate_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblLocalMilkRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblLocalMilkRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLocalMilkRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLocalMilkRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLocalMilkRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblLocalMilkSaleRate();
        $appModel->model->wef_date = $model->wef_date;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'local_milk_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'local milk rate applicability';
        $appModel->header_title = ' [Local Milk Rate: ' . $model->rate . '] ';
        $appModel->assignStaticData = [
            'rate' => $model->rate,
            'milk_quality_type_code' => $model->milk_quality_type_code,
            'milk_class' => $model->milk_class,
            'milk_type_code' => $model->milk_type_code,
        ];

        $appModel->fields = [
            'dcs_code' => ['view' => ['grid'], 'value' => 'dcs_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return \Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                }],
            'name' => ['view' => ['grid'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }],
            'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
        ];

        $appModel->actions = ['delete' => ['option' => 'dcs_code,local_milk_rate_code,tbl-local-milk-rate/delete-local-milk-rate']];
        return $appModel->createApp();
    }

    public function actionDeleteLocalMilkRate() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblLocalMilkSaleRateHistory();
            $record = TblLocalMilkSaleRate::find()->where(['local_milk_rate_code' => Yii::$app->request->post('id')])->one();
            Yii::$app->operation->history($record, $detailHistory, DELETE);
            $master[] = $detailHistory->save(FALSE);
            $master[] = $record->delete();
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            } else {
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
            }
        } catch (UserException $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
