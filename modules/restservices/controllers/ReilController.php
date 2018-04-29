<?php

namespace app\modules\restservices\controllers;

use Yii;
use app\modules\restservices\controllers\RestController;
use app\modules\restservices\models\ReilModel;
use yii\web\Response;
use yii\helpers\Json;
/**
 * Default controller for the `restservices` module
 */
class ReilController extends RestController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    
    public function actionCheckCollectionData() {
       $model=new ReilModel();
       //var_dump(Yii::$app->request->getRawBody());exit;
       $data= json_decode(Yii::$app->request->getRawBody(), true);
                
       return $this->response($model->getCollectionData($data));
    }
    
    public function response($data) {
        // Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['msg'=>$data];
    }
       
}
