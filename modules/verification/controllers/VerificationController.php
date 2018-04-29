<?php

namespace app\modules\verification\controllers;

use Yii;
use app\modules\verification\models\TblVerification;
use yii\helpers\Url;
use app\modules\verification\models\TblKycRecord;
use yii\helpers\Json;

/**
 * TblBankDetailsController implements the CRUD actions for TblBankDetails model.
 */
class VerificationController extends \app\controllers\ChildController {

    public function actionVerifyBankDetail($id, $type) {
        $data = explode(',', $id);
        $this->model = new TblVerification();
        $details = $this->model->getBankDetails($data[0]);
        $this->setattributes();
        $this->model->module_name = $details['model'];
        $this->model->module_field = $details['verify_field'];
        $this->model->module_id = $data[1];
        $this->model->status = $type;
        $transaction = $this->generalModel->saveTransaction([$this->model], ['Verification Details', 'create']);
        if ($transaction !== FALSE) {
            if ($type == '1') {
                $message = 'Bank Account verified successfully.';
            } elseif ($type == '2') {
                $message = 'Bank Account rejected successfully.';
            }
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => $message]);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not verify. Please try again.']);
        }
        $this->redirect(Url::previous());
    }

    public function actionVerifyContactDetail($id, $type) {
        $data = explode(',', $id);
        $this->model = new TblVerification();
        $details = $this->model->getContactDetails($data[0]);
        $this->setattributes();
        $this->model->module_name = $details['model'];
        $this->model->module_field = $details['verify_field'];
        $this->model->module_id = $data[1];
        $this->model->status = $type;
        $transaction = $this->generalModel->saveTransaction([$this->model], ['Verification Details', 'create']);
        if ($transaction !== FALSE) {
            if ($type == '1') {
                $message = 'Contact detail verified successfully.';
            } elseif ($type == '2') {
                $message = 'Contact detail rejected successfully.';
            }
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => $message]);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not verify. Please try again.']);
        }
        $this->redirect(Url::previous());
    }

    private function setattributes() {
        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        $this->model->is_verified = 1;
        $this->model->verified_by = $user;
        $this->model->verified_on = date('Y-m-d H:i:s');
    }

    public function actionKycDetail() {
        $this->model = new TblKycRecord();
        $this->model->load(Yii::$app->request->post());
        $this->model->is_kyc = 1;
        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        $this->model->kyc_by = $user;
        $this->model->kyc_on = date('Y-m-d H:i:s');
        $transaction = $this->generalModel->saveTransaction([$this->model], ['KYC Details', 'create']);
        $this->redirect(Url::previous());
    }

    public function actionKycInformation() {
        if (Yii::$app->request->post()) {
            $model_name = Yii::$app->path->define(Yii::$app->request->post('flag'));
            $model = new $model_name();
            $key = $model->tableSchema->primaryKey[0];
            $model->{$key} = Yii::$app->request->post('id');
            $data = $model->getKycInfo();
            return Json::encode(['status' => 'success', 'info' => $data]);
        }
        return Json::encode(['status' => 'error', 'info' => []]);
    }

}
