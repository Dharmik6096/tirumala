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

class ChildController extends Controller {

    //put your code here
    protected $viewFile;
    protected $model;
    protected $generalModel;

    public function init() {
        parent::init();
        $language = (!empty(Yii::$app->session->get('organizations_code')) && Yii::$app->session->get('organizations_type') == 'UNION' && count(explode(',', Yii::$app->session->get('organizations_code')) == 1)) ? Yii::$app->session->get('organizations_code') . '/' . Yii::$app->session->get('LanguageCode') : Yii::$app->session->get('LanguageCode');
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

}

?>
