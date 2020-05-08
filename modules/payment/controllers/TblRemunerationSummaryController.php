<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblRemunerationSummary;
use app\modules\payment\models\TblRemunerationSummaryHistory;
use app\modules\payment\models\TblVspPayment;
use yii\data\ActiveDataProvider;

class TblRemunerationSummaryController extends \app\controllers\ChildController {

    public function actionCreate() {
        $model = new TblRemunerationSummary();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->from_datetime = date('Y-m-d', strtotime($model->from_datetime)) . ' ' . \Yii::$app->general->getshift(1);
            $model->to_datetime = date('Y-m-d', strtotime($model->to_datetime)) . ' ' . \Yii::$app->general->getshift(2);
            $this->getRemunerationSpData($model);
            return $this->redirect(['tbl-vsp-payment/payment-adjust', 'TblVspPayment' => ['from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime, 'bmc_code' => $model->bmc_code, 'union_code' => $model->union_code, 'billing_type' => 'remuneration', 'customer_type' => 'DCS']]);
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    private function getRemunerationSpData($model) {
        $result = \Yii::$app->db->createCommand("{CALL sp_remuneration_payment (:union_code,:plant_code,:mcc_plant_code,:bmc_code,:from_date,:to_date,:calculate_milk_recovey,:calculate_other_head)}")
                ->bindValue(':from_date', $model->from_datetime)
                ->bindValue(':to_date', $model->to_datetime)
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':bmc_code', $model->bmc_code)
                ->bindValue(':plant_code', $model->plant_code)
                ->bindValue(':mcc_plant_code', $model->mcc_plant_code)
                ->bindValue(':calculate_milk_recovey', $model->calculate_milk_recovey)
                ->bindValue(':calculate_other_head', $model->calculate_other_head);
        $query = $result->execute();
        return $query;
    }

}
