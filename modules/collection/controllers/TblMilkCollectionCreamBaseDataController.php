<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollectionCreamBaseData;
use app\modules\collection\models\TblMilkCollectionHistory;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblMilkCollectionCreamBaseDataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMilkCollectionCreamBaseDataController implements the CRUD actions for TblMilkCollectionCreamBaseData model.
 */
class TblMilkCollectionCreamBaseDataController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblMilkCollectionCreamBaseDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMilkCollectionCreamBaseData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollectionCreamBaseData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollectionCreamBaseData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteCollection() {
        $searchModel = new TblMilkCollectionCreamBaseDataSearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMilkCollection';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($deletedata as $code) {
                    $where['milk_collection_code'] = $code;
                    $existData = TblMilkCollection::find()->where($where)->one();
                    $model = new TblMilkCollectionCreamBaseData();
                    $historyModel = new TblMilkCollectionHistory();
                    $model->attributes = $existData->attributes;
                    Yii::$app->operation->history($existData, $historyModel, DELETE);
                    $saveModel[] = $model;
                    $saveModel[] = $historyModel;
                    $deleteModel[] = $existData;
                    $message = 'Milk Collection';
                    $type = 'delete';
                }
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
            }
        }

        return $this->render('delete', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
