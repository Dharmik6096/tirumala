<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblComplain;
use app\modules\complaint\models\TblComplainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblComplainController implements the CRUD actions for TblComplain model.
 */
class TblComplainController extends \app\controllers\ChildController {

    public $freeAccessActions = ['problem-list','get-asset'];

    /**
     * Lists all TblComplain models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblComplainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblComplain model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblComplain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblComplain();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->complain_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblComplain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->complain_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblComplain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblComplain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblComplain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblComplain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
//    public function actionGetAsset() {
//        $assetList = \app\modules\assetmanagement\models\TblAssetTransaction::find()->where(['to_type' => $_POST['to_type'], 'to_dest' => $_POST['to_dest'], 'status' => 2])->all();
//        echo Json::encode(['output' => '', 'selected' => '']);
//    }
    
//
//    public function actionProblemList() {
//        $out = [];
//        if (isset($_POST['depdrop_parents'])) {
//            $parents = $_POST['depdrop_parents'];
//            if (!empty($parents[0])) {
//                $problems = new TblComplainProblem();
//                $data = $problems->geComplainProblemList($parents[0]);
//                foreach ($data as $key => $val) {
//                    $out[] = array('id' => $key, 'name' => $val);
//                }
//                echo Json::encode(['output' => $out, 'selected' => '']);
//                return;
//            }
//        }
//        echo Json::encode(['output' => '', 'selected' => '']);
//    }

}
