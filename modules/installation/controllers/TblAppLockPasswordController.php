<?php

namespace app\modules\installation\controllers;

use Yii;
use app\modules\installation\models\TblAppLockPassword;
use app\modules\installation\models\TblAppLockPasswordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblAppLockPasswordController implements the CRUD actions for TblAppLockPassword model.
 */
class TblAppLockPasswordController extends \app\controllers\ChildController {

    /**
     * Lists all TblAppLockPassword models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAppLockPasswordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblAppLockPassword model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAppLockPassword();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->app_lock_password_code = Yii::$app->general->getCodeAutoIncrement($this->model);

            $var1 = substr($this->model->android_key, 0, 1);
            $var2 = substr($this->model->android_key, 1, 1);
            $var3 = substr($this->model->android_key, 2, 1);
            $var4 = substr($this->model->android_key, 3, 1);
            $hr = str_pad(date('H'), 2, '0', STR_PAD_LEFT);
            $this->model->hour = $hr;
            $result1 = str_pad(abs($var1 + $var2 - $hr + $var4), 2, '0', STR_PAD_LEFT);
            $result2 = str_pad(abs($var2 - $var3 + $hr - $var3), 2, '0', STR_PAD_LEFT);
            $result3 = str_pad(abs($var3 - $var4 - $hr + $var2), 2, '0', STR_PAD_LEFT);
            $result4 = str_pad(abs($var4 + $var1 + $hr - $var1), 2, '0', STR_PAD_LEFT);
            $result = $result1 . $result2 . $result3 . $result4;
            $this->model->app_password = $result;

            $transaction = $this->generalModel->saveTransaction([$this->model], ['App Lock Password', 'create']);
            if ($transaction == 'customRedirect') {
                $msg = 'App Lock Password Successfully Generated </br>'
                        . 'Password is : ' . $result;

                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                return $this->redirect(['index']);
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblAppLockPassword model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblAppLockPassword the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAppLockPassword::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
