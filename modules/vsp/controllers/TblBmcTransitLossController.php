<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblBmcTransitLoss;
use app\modules\vsp\models\TblBmcTransitLossSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * TblBmcTransitLossController implements the CRUD actions for TblBmcTransitLoss model.
 */
class TblBmcTransitLossController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcTransitLoss models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcTransitLossSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcTransitLoss model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcTransitLoss model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblBmcTransitLoss();
        $query = TblBmcTransitLoss::find()->where('0=1');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->from_date = Yii::$app->formatter->asDate($model->from_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($model->from_shift);
            $model->to_date = Yii::$app->formatter->asDate($model->to_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($model->to_shift);
            $dcs = (!empty($model->dcs_code) && is_array($model->dcs_code)) ? ',' . implode(',', $model->dcs_code) . ',' : $model->dcs_code;
            $data = [];
            $data['from_datetime'] = $model->from_date;
            $data['to_datetime'] = $model->to_date;
            $data['union_code'] = $model->union_code;
            $data['plant_code'] = $model->plant_code;
            $data['mcc_plant_code'] = $model->mcc_plant_code;
            $data['bmc_code'] = $model->bmc_code;
            $data['dcs_code'] = $dcs;
            Yii::$app->ClientPaymentConfig->processPayment('transit_loss', $data);
            /* $result = \Yii::$app->db->createCommand("{CALL sp_bmc_transit_loss (:union_code,:plant_code,:mcc_plant_code,:bmc_code,:dcs_code,:from_date,:to_date)}")
              ->bindValue(':from_date', $model->from_date)
              ->bindValue(':to_date', $model->to_date)
              ->bindValue(':union_code', $model->union_code)
              ->bindValue(':plant_code', $model->plant_code)
              ->bindValue(':mcc_plant_code', $model->mcc_plant_code)
              ->bindValue(':bmc_code', $model->bmc_code)
              ->bindValue(':dcs_code', $dcs);
              $result->execute(); */
            $query = TblBmcTransitLoss::find()
                    ->where(['union_code' => $model->union_code])
                    ->andWhere(['>=', 'date_time_of_collection', $model->from_date])
                    ->andWhere(['<=', 'date_time_of_collection', $model->to_date]);
            !empty($model->plant_code) ? $query->andWhere(['plant_code' => $model->plant_code]) : '';
            !empty($model->mcc_plant_code) ? $query->andWhere(['mcc_plant_code' => $model->mcc_plant_code]) : '';
            !empty($model->bmc_code) ? $query->andWhere(['bmc_code' => $model->bmc_code]) : '';
            !empty($model->dcs_code) ? $query->andWhere(['dcs_code' => $model->dcs_code]) : '';
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        return $this->render('create', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateTransitLoss() {
        if (Yii::$app->request->post()['TblBmcTransitLoss']) {
            $transit_loss_code = Yii::$app->request->post()['TblBmcTransitLoss']['transit_loss_code'];
            $loss_applied_to = Yii::$app->request->post()['TblBmcTransitLoss']['loss_applied_to'];
            $save_model = [];
            $cnt = 0;
            foreach ($transit_loss_code as $key => $value) {
                if ($loss_applied_to[$key] == 2) {
                    $data = TblBmcTransitLoss::findOne($transit_loss_code[$key]);
                    $data->scenario = 'updateLossType';
                    $data->loss_applied_to = 2;
                    $save_model[] = $data;
                    $cnt++;
                }
            }
            $transaction = $this->generalModel->saveTransaction($save_model, ['transit loss', 'create']);
            if ($transaction !== FALSE && $transaction != 'customRender') {
                return $this->redirect(['index']);
            }
        }
    }

    /**
     * Finds the TblBmcTransitLoss model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBmcTransitLoss the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcTransitLoss::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
