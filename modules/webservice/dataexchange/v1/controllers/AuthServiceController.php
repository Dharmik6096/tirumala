<?php

namespace app\modules\webservice\dataexchange\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\helpers\Json;
use app\modules\vendorapi\models\TblDataExchangeActivation;

class AuthServiceController extends ActiveController {

    public $modelClass = 'app\modules\webservice\models';

    public function actions() {
        return [];
    }

    protected function verbs() {
        return [
            '*' => ['POST'],
        ];
    }

    public function actionIndex() {
        $username = \Yii::$app->params['data_exchange_un'];
        $password = \Yii::$app->params['data_exchange_pw'];
        $post_data = Json::decode(Yii::$app->request->getRawBody());
        if (!empty($post_data['username']) && !empty($post_data['password']) && $post_data['username'] == $username && $post_data['password'] == $password) {
            $token = Yii::$app->security->generateRandomString(256);
            $message = 'Operation performed successfully';
            $expired_at = date('Y-m-d H:i:s', strtotime(' + 6 hours'));
            try {
                $ActiveModel = new TblDataExchangeActivation();
                $ActiveModel->token = $token;
                $ActiveModel->valid_from = date('Y-m-d H:i:s');
                $ActiveModel->valid_to = $expired_at;
                $ActiveModel->save(false);

                $response = [
                    'code' => '200',
                    'status' => 'SUCCESS',
                    'message' => $message,
                    'data' => ['token' => $token, 'expired_at' => $expired_at],
                ];
            } catch (yii\base\Exception $e) {
                $message = 'Server Error';
                $message1 = 'Operation fails due to server error';
                $response = [
                    'code' => '400',
                    'status' => 'ERROR',
                    'message' => $message,
                    'data' => ['message' => $message1],
                ];
            }
        } else {
            $message = 'Validation Fails';
            $message1 = 'username or password Is Invalid';
            $response = [
                'code' => '400',
                'status' => 'ERROR',
                'message' => $message,
                'data' => ['message' => $message1],
            ];
        }
//        $data = json_encode($response);
//        echo $data;
        $data = $response;
        return $data;
    }

}
