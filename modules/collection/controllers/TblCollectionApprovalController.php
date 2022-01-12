<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblCollectionApproval;
use app\modules\collection\models\TblCollectionApprovalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblCollectionApprovalHistory;

class TblCollectionApprovalController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblCollectionApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
                    'type' => 'view',
                    'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id) {
        if (($model = TblCollectionApproval::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApproveCollection($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'approve';
        if ($this->model->load(Yii::$app->request->post())) {
            $historyModel = new TblCollectionApprovalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->approve_date = date('Y-m-d H:i:s');
            $this->model->allow_till_date = date('Y-m-d H:i:s', strtotime('+' . $this->model->valid_hours . ' hours'));
            $this->model->approved_by = !empty(Yii::$app->session->get('UserCode')) ? Yii::$app->session->get('UserCode') : '';
            $this->model->is_approve = 1;
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Manual Collection Approval', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('view', [
                    'type' => 'approve',
                    'model' => $this->model,
        ]);
    }

}
