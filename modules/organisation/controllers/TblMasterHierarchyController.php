<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\organisation\models\TblMasterHierarchy;
use app\modules\organisation\models\TblMasterHierarchySearch;
use yii\web\NotFoundHttpException;

class TblMasterHierarchyController extends ChildController {
    /**
     * Lists all TblMasterHierarchy models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMasterHierarchySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMasterHierarchy model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the TblDcs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMasterHierarchy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
?>