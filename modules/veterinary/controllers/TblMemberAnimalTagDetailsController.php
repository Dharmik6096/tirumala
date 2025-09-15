<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\veterinary\models\TblMemberAnimalTagDetails;
use app\modules\veterinary\models\TblMemberAnimalTagDetailsSearch;
use yii\web\NotFoundHttpException;

/**
 * TblMemberAnimalTagDetailsController implements the CRUD actions for TblMemberAnimalTagDetails model.
 */
class TblMemberAnimalTagDetailsController extends ChildController
{

    /**
     * Lists all TblMemberAnimalTagDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMemberAnimalTagDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMemberAnimalTagDetails model.
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
     * Finds the TblMemberAnimalTagDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMemberAnimalTagDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMemberAnimalTagDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
