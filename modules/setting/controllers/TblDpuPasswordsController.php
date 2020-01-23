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
use app\modules\setting\models\TblDPUPasswordsHistory;

/**
 * TblDpuPasswordsController implements the CRUD actions for TblDpuPasswords model.
 */
class TblDpuPasswordsController extends \app\controllers\ChildController {

    /**
     * Lists all TblDpuPasswords models.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDpuPasswords();
        $searchModel = new TblDcsSearch();
        $model = [];
        $dataProvider = $this->setModel($searchModel, $model);

        if (Model::loadMultiple($model, Yii::$app->request->post())) {
            $saveModel = [];
            $historyModel = [];
            foreach ($model as $dpu_password) {
                if (!empty($dpu_password->AdminPwd) && !empty($dpu_password->SuperPwd) && !empty($dpu_password->UserPwd)) {
                    $dpu_password->mcc_code = Yii::$app->general->getforeignkey($dpu_password->dcsCode, 'mcc_plant_code');
                    $dpu_password->lastmodified = date('Y-m-d H:i:s');
                    $dpu_password->modifiedby = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                    if (!empty($dpu_password->oldAttributes) && ($dpu_password->AdminPwd != $dpu_password->oldAttributes['AdminPwd'] || $dpu_password->SuperPwd != $dpu_password->oldAttributes['SuperPwd'] || $dpu_password->UserPwd != $dpu_password->oldAttributes['UserPwd'])) {
                        $existData = TblDpuPasswords::find()->where(['dcs_code' => $dpu_password->dcs_code])
                                ->one();
                        if (!empty($existData)) {
                            $history = new TblDPUPasswordsHistory();
                            \Yii::$app->operation->history($existData, $history, 'UPDATE');
                            $historyModel[] = $history;
                            $existData->attributes = $dpu_password->attributes;
                            $saveModel[] = $existData;
                        }
                    } else {
                        $saveModel[] = $dpu_password;
                    }
                }
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, $historyModel, ['DPU Passwords', 'edit']);
            if ($transaction == 'customRedirect') {
                $dataProvider = $this->setModel($searchModel, $model);
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

    function setModel(&$searchModel, &$model) {
        $dataProvider = $searchModel->dpupasssearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'dpuPassword';
        $dataProvider->pagination = false;
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
        return $dataProvider;
    }

}
