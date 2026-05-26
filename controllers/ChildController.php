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
use app\modules\configuration\models\TblReportTxnLog;

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
        /* if (!defined('QUALITY_PARAM')) {
          $model = new TblQualityParam();
          define('QUALITY_PARAM', $model->getParams());
          } */
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

    public static function printDocument($controls, $path, $filename, $type, $out = 'web', $append_control = TRUE) {
        if ($append_control) {
            $controls['locale'] = 'en';
            $controls['REPORT_LOCALE'] = 'en_IN';
            $controls['digit_config'] = 0;
        }
        $clientJasper = new Client(\Yii::$app->params['jasper_server'], \Yii::$app->params['jasper_username'], \Yii::$app->params['jasper_password']);
        $clientJasper->setRequestTimeout(300);
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

    public function FTPSubFolder() {
        return [
            'DATFILES' => [['ext' => '.BDF', 'module_name' => 'TblMilkCollection']],
        ];
    }

    public function RegisterReportRequest($report_type, $config, $controls, $search_param = NULL) {
        $model = new TblReportTxnLog();
        $txnLogExistRecord = $model->find()->where(['status' => 0, 'user_code' => \Yii::$app->user->identity->user_code])->one();
        if (empty($txnLogExistRecord)) {
            $model->report_type = $report_type;
            $model->report_title = $config['title'];
            $model->sp_name = $report_type == 'mis' ? $config['sp_name'] : $config['path'];
            $model->input_param = json_encode($controls);
            $model->search_param = $search_param;
            $model->export_file_name = isset($config['export_file_name']) ? $config['export_file_name'] : NULL;
            $model->decrypt_data = isset($config['to_decrypt']) ? json_encode($config['to_decrypt']) : NULL;
            $model->file_type = $report_type == 'mis' ? 'xls' : 'pdf';
            $model->user_code = $model->created_by = \Yii::$app->user->identity->user_code;
            $model->created_at = date('Y-m-d H:i:s');
            $model->union_code = isset($controls['union_code']) ? $controls['union_code'] : NULL;
            $model->union_code = isset($controls['p_union_code']) ? $controls['p_union_code'] : $model->union_code;
            $model->status = 0;
            if ($model->save()) {
                return 'Your Request has been submitted For Report Data. <br/>You can download file from My Report Request screen after sometime.';
            } else {
                return 'Error While Request Submit.';
            }
        }
        return 'Your request is pending for this report ' . $txnLogExistRecord->report_title . '<br/>You can register new request once this report is processed.';
    }

}

?>
