<?php

namespace app\modules\payment\controllers;

use yii\web\Controller;
use app\modules\payment\models\TblUnionCreditLimit;
/**
 * Default controller for the `payment` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    public function actionUnionCredit(){
        $model = new TblUnionCreditLimit();
        $data = $model->find()
             ->where(['credit_type' => 1])
             ->all();
        $res = [];
        if(!empty($data)){
            foreach ($data as $creditLimit){
                $result = \Yii::$app->db->createCommand("{CALL [sp_member_credit_limit_allocate](:union_code,:credit_value,:collection_date)}")
                            ->bindValue(':union_code', $creditLimit->union_code)
                            ->bindValue(':credit_value', $creditLimit->credit_value)
                            ->bindValue(':collection_date', date('Y-m-d'));
                $query = $result->queryAll();
                $res[] = $query;
            }
        }
        if(!empty($res)){
            echo "success";
        }
        else{
            echo "Error";
        }
     
    }
}
