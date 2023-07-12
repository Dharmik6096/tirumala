<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\models\GeneralModel;

trait ChildControllerTrait {

    //put your code here
    protected $viewFile;
    protected $model;
    protected $generalModel;

    public function init() {
        if (empty($this->layout)) {
            $this->layout = "@app/web/themes/emilk/layouts/main.php";
        }

        parent::init();
//        $language = (!empty(Yii::$app->session->get('organizations_code')) && Yii::$app->session->get('organizations_type') == 'UNION' && count(explode(',', Yii::$app->session->get('organizations_code')) == 1)) ? Yii::$app->session->get('organizations_code') . '/' . Yii::$app->session->get('LanguageCode') : Yii::$app->session->get('LanguageCode');
        $language = (!empty(Yii::$app->session->get('eiplCode'))) ? Yii::$app->session->get('eiplCode') . '/' . Yii::$app->session->get('LanguageCode') : Yii::$app->session->get('LanguageCode');
        $language = Yii::$app->session->get('LanguageCode');
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

        $this->generalModel = new GeneralModel();
    }

    public function behaviors() {
        return [
            'ghost-access' => [
                'class' => 'app\modules\usermanagement\components\GhostAccessControl',
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

}

?>
