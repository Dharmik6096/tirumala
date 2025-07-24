<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use app\modules\document\models\TblAttachment;
use app\modules\feedback\models\TblNonMemberHouseHoldCurrentPouringSearch;
use Yii;
use app\modules\feedback\models\TblNonMemberHouseHoldVisit;
use app\modules\feedback\models\TblNonMemberHouseHoldVisitSearch;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * TblNonMemberHouseHoldVisitController implements the CRUD actions for TblNonMemberHouseHoldVisit model.
 */
class TblNonMemberHouseHoldVisitController extends ChildController
{

    /**
     * Lists all TblNonMemberHouseHoldVisit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblNonMemberHouseHoldVisitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblNonMemberHouseHoldVisit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $searchModel = new TblNonMemberHouseHoldCurrentPouringSearch();
        $params = Yii::$app->request->queryParams;
        $searchModel->house_hold_visit_id = $id;
        $dataProvider = $searchModel->search($params);
        $attachment = new TblAttachment();
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $id, 'module_name' => 'tbl_non_member_house_hold_visit']),
        ]);
        return $this->render('view', [
            'model' => $this->model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'dataProviderOther' => $dataProviderOther,
            'attachment' => $attachment,
        ]);
    }

    /**
     * Finds the TblNonMemberHouseHoldVisit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblNonMemberHouseHoldVisit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblNonMemberHouseHoldVisit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
