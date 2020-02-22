<?php

namespace app\modules\soap\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `soap` module
 */
class DefaultController extends Controller {

    public $union_array = ['003'];

    public function strReplaceAssoc(array $replace) {
        $replace_array = $replace;
        foreach ($replace_array as $k => $v) {
            $n_k = str_replace('x003', '', $k);
            $n_k = str_replace('_', '', $n_k);
            $replace_array[$n_k] = $v;
            if ($n_k !== $k) {
                unset($replace_array[$k]);
            }
        }
        return $replace_array;
    }

    public function createCpLogFile($path = '', $text = '', $cp_code = '') {
        $path = empty($path) ? Yii::$app->params['vendorApiErrorLogPath'] : $path;
        if (!empty($cp_code)) {
            $path = $path . '\\' . $cp_code;
        }
        $path = str_replace('\\', '/', realpath(\Yii::$app->basePath . '/../')) . $path;
        if (\Yii::$app->general->checkDirectory($path)) {
            $timestamp = date('Y-m-d-His');
            $fileName = $path . "/" . $timestamp . '.txt';
            $logfile = fopen($fileName, "w") or die("Unable to open file!");
            fwrite($logfile, $text);
            fclose($logfile);
        }
        return;
    }

}
