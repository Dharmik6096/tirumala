<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;
use app\components\GeneralFunctions;
use yii\helpers\Json;

class ServiceController extends Controller
{
    public $enableCsrfValidation = false;
    //public $freeAccessActions = ['test'];

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionStateCreate()
    {
        if(!empty($_POST))
        {
            $model = new \app\modules\geo\models\TblStates;
            $model->attributes = $_POST;
            if($model->save())
            {
                return Json::encode(['status'=>'success']);
            }else{
                $er = Json::encode($model->errors);
                return Json::encode(['status'=>'error','errors'=>$er]);
            }
               
        }
    }

    
    /**
     * Description: Handle Webservice call from tradiecom
     * By: Dhara
     * Date: 29-4-2016
     * @param type $key
     */
    public function actionWebServices($key){

        $ch = curl_init();
        $serverIP=$_SERVER['SERVER_ADDR'];
        $json = file_get_contents('php://input', 'rb');

        $data = $this->jsonParse($json);
        $baseUrl=  Url::base(true).'/service/'.$key;
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $content = curl_exec ($ch);
        print_r($content);
        curl_close($ch);
    }

    /*
         * Description: Parse jason data posted form web service.
         */
    public function jsonParse($json)
    {
        return \yii\helpers\Json::decode($json,true);
        //return  CJSON::decode($json,true);
    }
}