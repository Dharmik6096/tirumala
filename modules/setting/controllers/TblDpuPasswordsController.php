<?php

namespace app\modules\setting\controllers;

use Yii;
use app\modules\setting\models\TblDpuPasswords;
use app\modules\setting\models\TblDpuPasswordsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsSearch;
use app\components\Model;

/**
 * TblDpuPasswordsController implements the CRUD actions for TblDpuPasswords model.
 */
class TblDpuPasswordsController extends \app\controllers\ChildController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all TblDpuPasswords models.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDpuPasswords();
        $searchModel = new TblDcsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $dataProvider->getModels();
        $model = [];
        if (!empty($data)) {
            foreach ($data as $dpu) {
                $this->model = new TblDpuPasswords();
                $this->model->dcs_code = $dpu->dcs_code;
                $data = $this->model->getDpuDetails();
                if (!empty($data)) {
                    $model[] = $data;
                } else {
                    $model[] = $this->model;
                }
            }
        } else {
            $model[] = $this->model;
        }

        if (Model::loadMultiple($model, Yii::$app->request->post())) {
            $dpu_passwords = [];
            foreach ($model as $dpu_password) {
                if(!empty($dpu_password->AdminPwd) && !empty($dpu_password->SuperPwd) && !empty($dpu_password->UserPwd)){
                    $dpu_password->mcc_code = Yii::$app->general->getforeignkey($dpu_password->dcsCode, 'mcc_plant_code');
                    $dpu_password->lastmodified = date('Y-m-d H:i:s');
                    $dpu_password->modifiedby = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                    $dpu_passwords[] = $dpu_password;
                }
            }
            $transaction = $this->generalModel->saveTransaction($dpu_passwords, [], ['DPU Passwords', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['create']);
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblDpuPasswords model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDpuPasswords the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDpuPasswords::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
