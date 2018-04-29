<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblComplaintActivity;
use app\modules\complaint\models\TblComplaintActivitySearch;
use app\modules\complaint\models\TblComplaint;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\UserException;

/**
 * TblComplainActivityController implements the CRUD actions for TblComplainActivity model.
 */
class TblComplaintActivityController extends \app\controllers\ChildController
{
    public $complaint_model;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblComplainActivity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblComplaintActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblComplainActivity model.
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
     * Creates a new TblComplainActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $this->model = new TblComplaintActivity();
        $this->complaint_model = $this->findModel($id);
        $this->viewFile = 'create';
        $this->setComplainModel($this->complaint_model);

        if ($this->model->load(Yii::$app->request->post())) {
            $old_attachment = $this->complaint_model->oldattributes['attachment'];
            $this->setModel($this->model);
            $this->model->attachment = Yii::$app->request->post('attachment');
            $this->complaint_model->status =  $this->model->status;
            $this->complaint_model->affects_data =  $this->model->affects_data;
            $this->complaint_model->attachment = $this->model->attachment;
            $transaction = $this->generalModel->saveTransaction([$this->model,$this->complaint_model], ['Complain Activity', 'edit']);
            if ($transaction !== FALSE) {
                if ($this->model->attachment != $old_attachment && !empty($old_attachment)) {
                    unlink(Yii::$app->params['complaint_dir_path'] . $old_attachment);
                }
                return $this->redirect(['tbl-complaint/index']);
            }
        }
        return $this->customRender();
    }
    
    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'complaint_model' => $this->complaint_model,
        ]);
    }


    /**
     * Updates an existing TblComplainActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblComplainActivity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    private function setModel() {
        $this->model->date = ($this->model->date == '') ? null : Yii::$app->formatter->asDate($this->model->date, DATE_FORMAT);        
        $user = isset(\Yii::$app->user->identity->user_code)?\Yii::$app->user->identity->user_code:null; 
        $this->model->updated_by = $user;
        $this->model->updated_at = date('Y-m-d H:i:s');
    }
    private function setComplainModel($complain_model) {
        $this->model->complaint_code =  $complain_model->complaint_code;
        $this->model->status =  $complain_model->status;
        $this->model->date =  date('Y-m-d');
        $this->model->issue_type =  $this->model->getType();
        $this->model->affects_data =  $complain_model->affects_data;
    }

    /**
     * Finds the TblComplainActivity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblComplainActivity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblComplaint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
