<?php

namespace app\modules\restservices\controllers;

use app\modules\restservices\controllers\RestController;
use app\modules\restservices\models\RestModel;
use Yii;

/**
 * Default controller for the `restservices` module
 */
class SocietyController extends RestController {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionSocietyData() {
        $model = new RestModel();
        return $this->response($model->societyData(Yii::$app->request->post('username')));
    }

    public function actionMemberData() {
        $model = new RestModel();
        return $this->response($model->memberList(Yii::$app->request->post('society_code')));
    }

    public function actionCollectionData() {
        $model = new RestModel();
        return $this->response($model->collectionData(Yii::$app->request->post('society_code'), Yii::$app->request->post('shift'), Yii::$app->request->post('date')));
    }

    public function actionPurchaseRateData() {
        $model = new RestModel();
        return $this->response($model->purchaseRateData(Yii::$app->request->post('society_code'), Yii::$app->request->post('date'), Yii::$app->request->post('milk_type_code')));
    }

    public function actionSaveMember() {
        $model = new RestModel();
        return $this->response($model->saveMember(Yii::$app->request->post('member_name'), Yii::$app->request->post('dcs_code'), Yii::$app->request->post('mobile_no'), Yii::$app->request->post('bank_name'), Yii::$app->request->post('branch_name'), Yii::$app->request->post('bank_account_no'), Yii::$app->request->post('ifsc'), Yii::$app->request->post('adhar_no')));
    }

    public function actionPaymentCycleList() {
        $model = new RestModel();
        return $this->response($model->paymentCycleList(Yii::$app->request->post('society_code'), Yii::$app->request->post('limit')));
    }

    public function actionPaymentCycleData() {
        $model = new RestModel();
        return $this->response($model->paymentCycleData(Yii::$app->request->post('society_code'), Yii::$app->request->post('member_code'), Yii::$app->request->post('payment_cycle_code')));
    }

}
