<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblMemberCreditLimit;
use app\modules\payment\models\TblMemberCreditLimitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblMemberCreditLimitTransaction;
/**
 * TblMemberCreditLimitController implements the CRUD actions for TblMemberCreditLimit model.
 */
class TblMemberCreditLimitController extends \app\controllers\ChildController
{
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
     * Lists all TblMemberCreditLimit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMemberCreditLimitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMemberCreditLimit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($member_credit_limit_code)
    {
        $model = new TblMemberCreditLimitTransaction();
        $dataProvider = $model->search($member_credit_limit_code);
        
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMemberCreditLimit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMemberCreditLimit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMemberCreditLimit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
