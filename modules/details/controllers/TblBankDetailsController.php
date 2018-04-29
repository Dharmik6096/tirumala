<?php

namespace app\modules\details\controllers;

use Yii;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblBankDetailsHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\helpers\Json;
/**
 * TblBankDetailsController implements the CRUD actions for TblBankDetails model.
 */
class TblBankDetailsController extends \app\controllers\ChildController
{
    
    /**
     * Lists all TblBankDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblBankDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBankDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBankDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($module,$id)
    {
        $this->model = new TblBankDetails();
        $this->viewFile = 'create';
        if(Yii::$app->request->isAjax){
            $this->model->load(Yii::$app->request->post());
            $this->model->setModel($module,$id,0);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($this->model);
        }
        else if ($this->model->load(Yii::$app->request->post())) {
            $this->model->setModel($module,$id,0);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Bank Details', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    protected function customRender() {
        $request = Yii::$app->request->queryParams;
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name=$request['module'];
        $searchModel->module_code=$request['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($this->viewFile, ['model' => $this->model,'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,'module'=>$request['module'],'id'=>$request['id'],'dist'=>'','dist_field'=>'',]);
    }
    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }
    /**
     * Updates an existing TblBankDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$module)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblBankDetailsHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date=Yii::$app->formatter->asDate($this->model->wef_date,DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Bank Details', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblBankDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_bank_details',Yii::$app->request->post('id'), 'detail_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblBankDetailsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBankDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBankDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblBankDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     public function actionDeactivate($id)
    {
        $this->model = $this->findModel($id);
        $historyModel = new TblBankDetailsHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active=0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Bank Details', 'edit']);
        if ($transaction !== FALSE) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => 'Account deactivated successfully.']);
            }
            else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Could not deactivate. Please try again.']);
            }
        $this->redirect(Url::previous());
    }
    
    public function actionSetDefault($id)
    {
        $this->model = $this->findModel($id);
        $historyModel = new TblBankDetailsHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $moduleName = $this->model->module_name;
        $modulecode = $this->model->module_code;
        $bankDetail = TblBankDetails::find()
        ->where([
            'module_name' => $moduleName,
            'module_code' => $modulecode,
            'is_default' => 1,
            ])
        ->all();
        if(!empty($bankDetail))
        {
            foreach ($bankDetail as $detail)
            {
                $detail->is_default=0;
                $detail->save();
            }
        }
        $this->model->is_default=1;
        
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Bank Details', 'edit']);
        if ($transaction !== FALSE) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => 'Account Set as default.']);
            }
            else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Could not set as default Account. Please try again.']);
            }
        $this->redirect(Url::previous());
    }
}
