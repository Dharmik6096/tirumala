<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of language
 *
 * @author aura001
 */

namespace app\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii;
use app\models\GeneralModel;
use app\modules\dcsoperation\models\TblQualityParam;
use app\models\ChildModel;
use Jaspersoft\Client\Client;
use app\modules\organisation\models\TblUnions;

class ChildController extends Controller {

    //put your code here
    protected $viewFile;
    protected $model;
    protected $generalModel;

    public function init() {
        parent::init();
//        $language = (!empty(Yii::$app->session->get('organizations_code')) && Yii::$app->session->get('organizations_type') == 'UNION' && count(explode(',', Yii::$app->session->get('organizations_code')) == 1)) ? Yii::$app->session->get('organizations_code') . '/' . Yii::$app->session->get('LanguageCode') : Yii::$app->session->get('LanguageCode');
        $language = (!empty(Yii::$app->session->get('eiplCode'))) ? Yii::$app->session->get('eiplCode') . '/' . Yii::$app->session->get('LanguageCode') : Yii::$app->session->get('LanguageCode');
        \Yii::$app->language = $language;
        $path = Yii::$app->basePath . '/messages/' . $language;
        if (!file_exists($path)) {
            \Yii::$app->language = Yii::$app->session->get('LanguageCode');
        }

        if (!defined('DATE_FORMAT'))
            define('DATE_FORMAT', 'php:Y-m-d');
        if (!defined('INSERT'))
            define('INSERT', 'INSERT');
        if (!defined('UPDATE'))
            define('UPDATE', 'UPDATE');
        if (!defined('DELETE'))
            define('DELETE', 'DELETE');
        if (!defined('IMPORT_PATH'))
            define('IMPORT_PATH', Yii::$app->basePath . '/web/import/');
        if (!defined('SENTBOX_FLAG'))
            define('SENTBOX_FLAG', 'Y');
        if (!defined('QUALITY_PARAM')) {
            $model = new TblQualityParam();
            define('QUALITY_PARAM', $model->getParams());
        }
        $this->generalModel = new GeneralModel();
    }

    public function behaviors() {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    protected function customRedirect() {
        return $this->redirect(['index']);
    }

    protected function customRender() {
        $childModel = new ChildModel();
        $attrs = $this->model;
        $attrs = $childModel->decryptModel($attrs);
        $this->model->load($attrs);
        return $this->render($this->viewFile, ['model' => $this->model]);
    }

    public static function printDocument($controls, $path, $filename, $type, $out = 'web') {
        $controls['locale'] = 'en';
        $controls['REPORT_LOCALE'] = 'en';
        $controls['digit_config'] = 0;
        $clientJasper = new Client(\Yii::$app->params['jasper_server'], \Yii::$app->params['jasper_username'], \Yii::$app->params['jasper_password']);
        $output = $clientJasper->reportService()->runReport(preg_replace('#/+#', '/', Yii::$app->params['report_path'] . $path), $type, null, null, $controls);
        if ($out == 'mail') {
            return $output;
        }
        if (strlen($output) > 954) {
            $date = date('dmY');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Description: File Transfer');
            header('Content-Disposition: inline; filename=' . $filename . $date . '.pdf');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . strlen($output));
            header('Content-Type: application/' . $type);
            echo $output;
            exit();
        }
    }

}

?>
