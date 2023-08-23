<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblAnimalInspector;
use app\modules\organisation\models\TblAnimalInspectorSearch;
use app\modules\organisation\models\TblAnimalInspectorHistory;
use app\modules\organisation\models\TblAnimalInspectorApplicability;
use app\modules\organisation\models\TblAnimalInspectorApplicabilitySearch;
use app\modules\organisation\models\TblAnimalInspectorApplicabilityHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblAnimalInspectorController implements the CRUD actions for TblAnimalInspector model.
 */
class TblAnimalInspectorController extends \app\controllers\ChildController {

    /**
     * Lists all TblAnimalInspector models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAnimalInspectorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAnimalInspector model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAnimalInspector model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAnimalInspector();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {

            $transaction = $this->generalModel->saveTransaction([$this->model], ['Animal Inspector', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblAnimalInspector model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblAnimalInspectorHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Animal Inspector', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblAnimalInspector model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAnimalInspector model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAnimalInspector the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAnimalInspector::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeactivateDoctor($id) {

        $this->model = $this->findModel($id);
        $saveModel = [];
        $deleteModel = [];

        $historyModel = new TblAnimalInspectorHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $saveModel[] = $historyModel;
        $this->model->is_active = 0;
        $saveModel[] = $this->model;

        $applicabilityModel = TblAnimalInspectorApplicability::find()->where(['animal_inspector_code' => $this->model->animal_inspector_code])->all();
        if (!empty($applicabilityModel)) {
            foreach ($applicabilityModel as $value) {
                $historyModels = new TblAnimalInspectorApplicabilityHistory();
                Yii::$app->operation->history($value, $historyModels, DELETE);
                $saveModel[] = $historyModels;
                $deleteModel[] = $value;
            }
        }
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Animal Inspector', 'edit']);

        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Doctor Deactivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Doctor Not Deactivated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionActivateDoctor($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblAnimalInspectorHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active = 1;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Animal Inspector', 'edit']);

        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Doctor Activated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Doctor Not Activated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionAnimalInspectorApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblAnimalInspectorApplicability();
        $appModel->with_wef_date = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'animal_inspector_code';
        $appModel->field_value = $id;
        $appModel->options = ['tanker_rate'];
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->trans_label = Yii::t('app', 'dispatch center applicability');
        $appModel->header_title = !empty($model->description) ? ' - ' . $id . ' (' . $model->description . ') ' : ' - ' . $id;
        $appModel->fields = [
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC Code'), 'value' => function($model) {
                    return \Yii::$app->general->getforeignkey($model->dcsCode, 'bmc_code');
                }],
            'dcs_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'DCS Code'), 'value' => function($model) {
                    return \Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code');
                }],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return \Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return \Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                }],
            'dcs_name' => ['view' => ['grid'], 'value' => function($model) {
                    return \Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code', 'filter' => false],
        ];
        $value = ['DCS' => Yii::t('app', 'DCS')];
        $appModel->actions = ['delete' => ['option' => 'animal_inspector_applicability_code,animal_inspector_applicability_code,tbl-animal-inspector/delete-applicability']];
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblAnimalInspectorApplicabilityHistory();
            $record = TblAnimalInspectorApplicability::findOne(Yii::$app->request->post('id'));
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
