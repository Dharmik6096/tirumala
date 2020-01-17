<?php

namespace app\modules\syncutility\controllers;

use Yii;

class DpuPendriveFileController extends \app\controllers\ChildController {

    public function actionIndex() {
        exec('java -jar C:\wamp64\www\tirumala\php-data.jar HelloPhp displayName "Chirag"', $out, $retval);
        var_dump($out);
        var_dump($retval);
        // $myclass=new java("C:/wamp64/www/tirumala/php-1.0.jar");
        // echo 'hiii';
    }

}
