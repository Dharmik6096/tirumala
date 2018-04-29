<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

/*
 * Description: This widget used to display header dynamically
 * By: Dhara
 * Date: 3-5-2016
 */

class Display extends Widget{

    public $msg;
    public $alert_type;
    public $timeout;

    public function show($message,$alert_type,$type='alert',$field='',$hiddenfield=''){

            if($type=='confirm')
                return $this->render('Confirm',array('msg'=>$message,'field'=>$field,'hiddenfield'=>$hiddenfield));
            else
                return $this->render('Snackbar',array('msg'=>$message,'type'=>$alert_type));
    }


    public function message($flag,$module,$operation){
         $session=$flag?"success":"error";
         Yii::$app->getSession()->setFlash($session, [
                            'type' => $session,
                            'message' => Yii::$app->label->message($operation,$module),
                        ]);
    }

}
?>
