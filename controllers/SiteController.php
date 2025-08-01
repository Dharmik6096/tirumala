<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
//use app\models\LoginForm;
use webvimark\modules\UserManagement\models\forms\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use webvimark\components\BaseController;
use webvimark\modules\UserManagement\components\UserAuthEvent;
use webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm;
use webvimark\modules\UserManagement\models\forms\ConfirmEmailForm;
use webvimark\modules\UserManagement\models\forms\PasswordRecoveryForm;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\db\Query;
use app\models\Dashboard;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;
use app\modules\collection\models\TblMilkCollection;
use app\modules\payment\models\TblPaymentTransaction;
use app\modules\payment\models\TblBankPaymentLog;
use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use app\modules\payment\models\TblMemberPayment;
use app\modules\payment\models\TblDcsPayment;
use app\components\FTPConnection;
use app\modules\payment\models\TblMemberPaymentHistory;
use app\modules\payment\models\TblReversePaymentFileLog;
use app\modules\payment\models\TblTransporterPayment;
use app\modules\payment\models\TblTransporterPaymentHistory;
use app\modules\webservice\models\TblAppNotification;
use app\models\TblSms;
use app\models\CollectionFarmerCreamy;
use yii\data\ArrayDataProvider;
use app\modules\creamy\models\TblDcsPortalCreamy;
use app\models\TblDbConfig;
use app\modules\syncutility\models\TblInbox;
use app\models\GeneralModel;
use app\models\TblDashboardUserWidgets;
use app\models\TblDashboardWidgets;
use app\modules\webservice\eipl\models\TblDpuCollectionHoData;
use app\modules\syncutility\models\TblSyncLog;
use app\modules\syncutility\models\TblSentbox;
use app\modules\syncutility\models\TblGenerateSentbox;
use app\modules\organisation\models\TblMasterTransfer;
use app\modules\syncutility\models\TblInboxConstraint;
use app\modules\collection\models\TblBmcCollectionNotExist;
use app\modules\collection\models\TblMilkCollectionNotExists;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblBmcMilkType;

class SiteController extends Controller {

    public $freeAccessActions = ['rail-login', 'rail-logout', 'set-organization', 'screen2', 'get-states', 'get-organization', 'get-data', 'milk-collection', 'load-dcs-data', 'send-collection-sms', 'load-daily-data', 'load-month-data', 'check-sftp', 'route-dcs-list', 'payment-file-status', 'update-payment-status', 'send-notification', 'tx-farmer', 'decrypt-data', 'collection-farmer-creamy', 'set-cross-tab', 'bmc-cross-tab-details', 'creamy-data-process', 'load-table', 'parse-inbox-data', 'get-collection-ftp', 'generate-sentbox', 'master-transfer', 'load-dashboard-farmer-rmrd-data', 'load-dashboard-block-data', 'set-hit-count-tab', 'load-year-data', 'set-collection-count-summary', 'load-dashboard-today-vs-yesterday-collection', 'help-manual', 'terms', 'privacy-policy', 'load-dashboard-milk-collection-summary', 'schema-refresh', 'load-dashboard-mobile-data', 'merge-weight-quality-data'];

    public function init() {
        parent::init();
//        $language = (!empty(Yii::$app->session->get('Unions')) && count(explode(',', Yii::$app->session->get('Unions'))) == 1) ? Yii::$app->session->get('organizations_code') . '/' . Yii::$app->session->get('LanguageCode') : Yii::$app->session->get('LanguageCode');
        $language = (!empty(Yii::$app->session->get('eiplCode'))) ? Yii::$app->session->get('eiplCode') . '/' . Yii::$app->session->get('LanguageCode') : Yii::$app->session->get('LanguageCode');
        \Yii::$app->language = $language;
        $path = Yii::$app->basePath . '/messages/' . $language;
        if (!file_exists($path)) {
            \Yii::$app->language = Yii::$app->session->get('LanguageCode');
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['rail-login,rail-logout'],
                'rules' => [
                        [
                        'actions' => ['rail-login,rail-logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->redirect(['dashboard']);
    }

    public function actionDashboard() {

        $this->layout = "@app/themes/pcdf/layouts/dashboardLayout.php";
        $model = new Dashboard();
        if (!empty(Yii::$app->request->post('Dashboard')['union_code'])) {
            $union_str = Yii::$app->request->post('Dashboard')['union_code'];
            $model->union_code = Yii::$app->request->post('Dashboard')['union_code'];
        } else {
            $unionModel = new TblUnions();
            $union = $unionModel->getActiveUnions();
            $union_ary = ArrayHelper::getColumn($union, 'union_code');
            $union_str = implode(',', $union_ary);
        }
        $dcs_str = NULL;
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $dcs_str = Yii::$app->session->get('Dcs');
            $dcs_str = ',' . $dcs_str . ',';
        }
//echo $union_str; exit;
        $today_date = date('Y-m-d');
        if (!empty(Yii::$app->request->post('Dashboard')['date'])) {
            $date = Yii::$app->request->post('Dashboard')['date'];
            $end_date = date('Y-m-d', strtotime($date));
            $start_date = date('Y-m-d', strtotime("-1 months", strtotime($end_date)));
        } else {
            $end_date = $today_date;
            $start_date = date('Y-m-d', strtotime("-1 months", strtotime($today_date)));
        }
        $defaultWidget = 'farmer';
        if (Yii::$app->session->get('eiplCode') == 'GYAN') {
            $defaultWidget = 'rmrd';
        }
        if (Yii::$app->session->get('UserType') == 4) {
            $defaultWidget = 'plant';
        }
        if (!empty(Yii::$app->request->post('Dashboard')['shift'])) {
            $model->shift = Yii::$app->request->post('Dashboard')['shift'];
        } else {
            $cur_time = date_create(date('H:i:s'));
            $morning_time = date_create('16:00:00');
            $diff = date_diff($morning_time, $cur_time);
            $model->shift = 1;
            if (($diff->h > 0 || $diff->i > 0) && $diff->invert == 0) {
                $model->shift = 2;
            }
        }
        $model->performance_type = !empty(Yii::$app->request->post('performance_type')) ? Yii::$app->request->post('performance_type') : '0';
        $model->widget_type = isset(Yii::$app->request->post('Dashboard')['widget_type']) ? Yii::$app->request->post('Dashboard')['widget_type'] : $defaultWidget;
        $model->mcc_code = isset(Yii::$app->request->post('Dashboard')['mcc_code']) ? Yii::$app->request->post('Dashboard')['mcc_code'] : '';
        $dashboardUserWidgets = new TblDashboardUserWidgets();
        if (Yii::$app->request->post()) {
            $dashboardUserWidgets = !empty($dashboardUserWidgets->getDashboardUserWidgets()) ? $dashboardUserWidgets->getDashboardUserWidgets() : $dashboardUserWidgets;
            $rmrd_widget_position = json_encode(Yii::$app->request->post('Dashboard')['rmrd_widgets']);
            $farmer_widget_position = json_encode(Yii::$app->request->post('Dashboard')['farmer_widgets']);
            $farmer_widget_popup = json_encode(!empty(Yii::$app->request->post('Dashboard')['farmer_widgets_after']) ? Yii::$app->request->post('Dashboard')['farmer_widgets_after'] : '');
            $rmrd_widget_popup = json_encode(!empty(Yii::$app->request->post('Dashboard')['rmrd_widgets_after']) ? Yii::$app->request->post('Dashboard')['rmrd_widgets_after'] : '');
            $plant_widget_position = json_encode(!empty(Yii::$app->request->post('Dashboard')['plant_widgets']) ? Yii::$app->request->post('Dashboard')['plant_widgets'] : '');
            $plant_widget_popup = json_encode(!empty(Yii::$app->request->post('Dashboard')['plant_widgets_after']) ? Yii::$app->request->post('Dashboard')['plant_widgets_after'] : '');
            if (!empty($dashboardUserWidgets)) {
                $dashboardUserWidgets->user_id = Yii::$app->session->get('UserCode');
            }
            $dashboardUserWidgets->position_farmer = $farmer_widget_position;
            $dashboardUserWidgets->position_rmrd = $rmrd_widget_position;
            $dashboardUserWidgets->is_farmer_popup = $farmer_widget_popup;
            $dashboardUserWidgets->is_rmrd_popup = $rmrd_widget_popup;
            $dashboardUserWidgets->position_plant = $plant_widget_position;
            $dashboardUserWidgets->is_plant_popup = $plant_widget_popup;
            $dashboardUserWidgets->save();
        }
        $dashboardWidgets = new TblDashboardWidgets();
        $widgets = $dashboardWidgets->getDashboardWidgets();
        $farmerWidgets = [];
        $rmrdWidgets = [];
        $plantWidgets = [];
// $dashboardUserWidgets = new TblDashboardUserWidgets();
        foreach ($widgets as $key => $value) {
            if ($value->widget_type == 'farmer')
                $farmerWidgets[] = $value->widget_id;
            if ($value->widget_type == 'rmrd')
                $rmrdWidgets[] = $value->widget_id;
            if ($value->widget_type == 'plant')
                $plantWidgets[] = $value->widget_id;
        }

        $userWidgets = $dashboardUserWidgets->getDashboardUserWidgets();
        $userRmrdWidgets = [];
        $userFarmerWidgets = [];
        $userFarmerPopup = [];
        $userRmrdPopup = [];
        $userPlantWidgets = [];
        $userPlantPopup = [];
        if (!empty($userWidgets)) {
            $userFarmerWidgets = json_decode($userWidgets->position_farmer);
            $userRmrdWidgets = json_decode($userWidgets->position_rmrd);
            $userFarmerPopup = json_decode(!empty($userWidgets->is_farmer_popup) ? $userWidgets->is_farmer_popup : '');
            $userRmrdPopup = json_decode(!empty($userWidgets->is_rmrd_popup) ? $userWidgets->is_rmrd_popup : '');
            $userPlantWidgets = json_decode(!empty($userWidgets->position_plant) ? $userWidgets->position_plant : '');
            $userPlantPopup = json_decode(!empty($userWidgets->is_plant_popup) ? $userWidgets->is_plant_popup : '');
        }

        $model->date = $end_date;
        $plant_str = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
        $mcc_str = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
        $bmc_str = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
        $dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
        $month = date('Y-m', strtotime($end_date));
        $results = []; // $results = $this->callDashboardSp($union_str, $start_date, $end_date, $dcs_str);
        $results2 = []; // $results2 = $this->callDashboardSp($union_str, $today_date, $today_date, $dcs_str);
        $results3 = []; // $results3 = $this->callDashboardSp($union_str, $end_date, $end_date, $dcs_str);
        $results4 = []; //$this->callDashboardCalSp($union_str, $month);
        $results5 = []; //$results5 = $this->getSpResult('fed_union');
        $results6 = []; //$results6 = $this->getWidgetDetails('sp_Portal_dashboard_bmc_collection', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $end_date, $end_date);
        $results7 = []; //$results7 = $this->getBmcSpResult('sp_Portal_BMC_Dispatch', $union_str, $end_date, $end_date, $bmc_str);
        $results8 = []; //$results8 = $this->getReconciliationSpResult('sp_portal_dashboard_rptDPU_GPRSDataReconciliation_chart', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $end_date, $end_date);
        $milk_collection = []; //$this->getWidgetDetails('sp_Portal_dashboard_milk_collection', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $end_date, $end_date);
        $monthly_milk_collection = []; // $this->getWidgetDetails('sp_Portal_dashboard_monthly_milk_collection', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $start_date, $end_date);
        $dashboard_blocks = []; //$this->getWidgetDetails('sp_Portal_dashboard_blocks', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $end_date, $end_date);
        $member_mobile_detail = []; // $this->getMemberMobileDetail('sp_Portal_dashboard_piechart_member_app', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code);
        $dashboard_farmer_rmrd_blocks = []; //$this->getWidgetDetails('sp_Portal_dashboard_blocks', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $end_date, $end_date);
        $dashboard_farmer_rmrd_avg = []; //$this->getWidgetDetails('sp_Portal_dashboard_blocks', $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $end_date, $end_date);
        $dashboard_farmer_status = [];
        $dashboard_society_status_pie_chart = [];
        $milk_collection_summary = [];

// $dpu_data = $this->DPUDataCollection($model);

        return $this->render('dashboard', ['model' => $model, 'results' => $results, 'date' => $end_date, 'results2' => $results2, 'results3' => $results3, 'results4' => $results4, 'results5' => $results5, 'results6' => $results6, 'results7' => $results7, 'results8' => $results8, 'milk_collection' => $milk_collection, 'monthly_milk_collection' => $monthly_milk_collection, 'dashboard_blocks' => $dashboard_blocks, 'member_mobile_detail' => $member_mobile_detail, 'dashboard_farmer_rmrd_blocks' => $dashboard_farmer_rmrd_blocks, 'dashboard_farmer_rmrd_avg' => $dashboard_farmer_rmrd_avg, 'dashboard_farmer_status' => $dashboard_farmer_status, 'farmerWidgets' => $farmerWidgets, 'rmrdWidgets' => $rmrdWidgets, 'userRmrdWidgets' => $userRmrdWidgets, 'userFarmerWidgets' => $userFarmerWidgets, 'dashboard_society_status_pie_chart' => $dashboard_society_status_pie_chart, 'milk_collection_summary' => $milk_collection_summary,
        'userFarmerPopup' => $userFarmerPopup, 'userRmrdPopup' => $userRmrdPopup, 'plantWidgets' => $plantWidgets, 'userPlantWidgets' => $userPlantWidgets, 'userPlantPopup' => $userPlantPopup]);
    }

    private function getReconciliationSpResult($sp_name, $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_code, $sdate, $edate) {
        $bmc = ($bmc_str == '0') ? $bmc_str : substr($bmc_str, 1, -1);
        $cur_time = date_create(date('H:i:s'));
        $morning_time = date_create('16:00:00');
        $diff = date_diff($morning_time, $cur_time);
        $time = '06:00:00';
        if (($diff->h > 0 || $diff->i > 0) && $diff->invert == 0) {
            $time = '18:00:00';
        }
        $query = \Yii::$app->db->createCommand("{CALL $sp_name(:union_code,:plant_code,:mcc_code,:bmc_code,:dcs_code,:date1,:date2)}")
                ->bindValue(':union_code', ',' . $union_str . ',')
                ->bindValue(':plant_code', $plant_str)
                ->bindValue(':mcc_code', $mcc_str)
                ->bindValue(':bmc_code', $bmc_str)
                ->bindValue(':dcs_code', $dcs_code)
                ->bindValue(':date1', $sdate . ' ' . $time)
                ->bindValue(':date2', $edate . ' ' . $time);
        $results = $query->queryAll();
        return $results;
    }

    private function getBmcSpResult($sp_name, $union_str, $sdate, $edate, $bmc_str) {
        $query = \Yii::$app->db->createCommand("{CALL " . $sp_name . "(:union_code,:bmc_code,:startdate,:enddate)}")
                ->bindValue(':union_code', ',' . $union_str . ',')
                ->bindValue(':startdate', $sdate)
                ->bindValue(':enddate', $edate)
                ->bindValue(':bmc_code', $bmc_str);
        $results = $query->queryAll();
        return $results;
    }

    private function callDashboardSp($union_str, $sdate, $edate, $dcs_str) {
        $query = \Yii::$app->db->createCommand("{CALL sp_Portal_Dashboard(:union_code,:startdate,:enddate,:dcs_code)}")
                ->bindValue(':union_code', ',' . $union_str . ',')
                ->bindValue(':startdate', $sdate)
                ->bindValue(':enddate', $edate)
                ->bindValue(':dcs_code', $dcs_str);
        $results = $query->queryAll();
        return $results;
    }

    private function callDashboardCalSp($rlsData, $month) {
        $month_start = date("$month-01");
        $month_end = date("Y-m-t", strtotime($month_start));
        $sp_param = [];
        $sp_name = 'sp_Portal_Dashboard_Cal_Day_new';
        $sp_param[] = !empty($rlsData['union']) ? ',' . $rlsData['union'] . ',' : 0;
        $sp_param[] = !empty($rlsData['plant']) ? ',' . $rlsData['plant'] . ',' : 0;
        $sp_param[] = !empty($rlsData['mcc']) ? ',' . $rlsData['mcc'] . ',' : 0;
        $sp_param[] = !empty($rlsData['bmc']) ? ',' . $rlsData['bmc'] . ',' : 0;
        $sp_param[] = !empty($rlsData['dcs']) ? ',' . $rlsData['dcs'] . ',' : 0;
        $sp_param[] = $month_start; //date('Y-m-d', strtotime($data['from_date'])) . ' 06:00:00';
        $sp_param[] = $month_end; //date('Y-m-d', strtotime($data['to_date'])) . ' 18:00:00';
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);

//        $query = \Yii::$app->db->createCommand("{CALL sp_Portal_Dashboard_Cal_Day(:union_code,:startdate,:enddate)}")
//                ->bindValue(':union_code', ',' . $union_str . ',')
//                ->bindValue(':startdate', $month_start)
//                ->bindValue(':enddate', $month_end);
//
//        $results = $query->queryAll();
        return $results;
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionRailLogin() {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        $identityModel = new \app\models\IdentityMaster();
        $identity = $identityModel->getIdentity();

        if (empty($identity)) {

            return $this->render('error');
        }

        if (Yii::$app->request->isAjax) {
            $model->username = $identity->organization_code . '#' . $model->username;
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->username = $identity->organization_code . '#' . $model->username;
            $user = $model->getUser();
            $userRoels = $user->findByRole('vendor');

            $permission = '';
            foreach ($userRoels as $key => $row) {
                if ($user['id'] == $row['id']) {
                    $permission = 'true';
                }
            }

            if ($model->login()) {

                if ($permission == 'true') {
                    return $this->redirect(['/site/dashboard']);
                } else {
                    Yii::$app->user->logout();
                    $model->addError('password', UserManagementModule::t('front', 'You are not authorize to login'));
                    $model->username = $_POST['LoginForm']['username'];
                }
            } else {
                $model->username = $_POST['LoginForm']['username'];
            }
        }
        Yii::$app->session->set('Login-sess', 'Rail');
        $this->layout = "@app/themes/pcdf/layouts/installationLayout.php";
        return $this->render('login', compact('model'));
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionRailLogout() {
        Yii::$app->user->logout();
        $this->redirect(Url::toRoute('site/rail-login', true));
    }

    /* function for get languages at login time */

    public function actionGetOrganization() {
        $out = NULL;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
//$list['en|0'] = ['English'];
            $list = [];
            if ($value[0] == 'UNION') {

                $unions = \app\modules\organisation\models\TblUnions::find()->select(['union_code', 'union_name'])->where(['is_active' => true])->all();

                $list = \yii\helpers\ArrayHelper::map($unions, 'union_code', 'union_name');
            }
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    /* function for retriving data for common depend dropdown */

    public function actionGetData() {

        if (isset($_POST['depdrop_parents']) && $_POST['depdrop_parents'][0] != '') {
            $cnt = 0;
            foreach ($_POST as $key => $val) {
                if ($cnt == 0) {
                    $cnt++;
                    continue;
                }
                $data = explode(',', $key);
            }
            $where = [];
            $i = 8;
            $j = 1;
            for ($i = 8; $i < count($data); $i++) {
//                if (!empty($data[$i + 1]) && !empty($_POST['depdrop_parents'][$j])) {
                if (!empty($data[$i + 1]) && (!empty($_POST['depdrop_parents'][$j]) || (isset($_POST['depdrop_parents'][$j]) && $_POST['depdrop_parents'][$j] == 0) )) {
                    $depend_value = explode('###', $_POST['depdrop_parents'][$j]);
                    $where[$data[$i + 1]] = $depend_value;
                    $j++;
                }
            }
            $fields[] = 'id';
            $fields[] = 'value';
            $select_fields['id'] = $data[3];
            $select_fields['value'] = $data[4];
            if (!empty($data[5])) {
                array_push($select_fields, $data[5]);
            }
            $refVal = explode('~', $select_fields['value']);
            $select_fields['value'] = !empty($refVal[0]) ? $refVal[0] : '';
            if (!empty($refVal[1])) {
                array_push($select_fields, $refVal[1]);
            }

            $check_list = [];
            if (!empty($data[7]) && $data[6] == 1) {
                $check_list = explode('-', $data[7]);
            }
            $local_name = (!empty($data[5])) ? $data[5] : '';
            $model_name = Yii::$app->path->define($data[0]);
            $model = new $model_name();
            $table_name = $model->tableName();
            $out = NULL;
            if ($model->hasAttribute('is_active')) {
                $where['is_active'] = 1;
            }
            if ($data[2] != '') {
                $unionQuery = $model->find()->select($select_fields)
                                ->where([$data[3] => $data[2], $data[1] => $_POST['depdrop_parents'][0]])
// ->andWhere($where)
                                ->createCommand()->rawSql;
                $tmp_query = $model->find()->select($select_fields)
                                ->where([$data[1] => $_POST['depdrop_parents'][0]])->andWhere($where)->union($unionQuery);
                if ($data[8] != 'false') {
                    $tmp_query->andWhere(['<=', 'valid_from', date('Y-m-d')]);
                }
                $query = new Query();
                $records = $query->select('*')->from(['u' => $tmp_query])->orderBy($fields[1])->all();
            } else {
                $records = $model->find()->select($select_fields)
                                ->where([$data[1] => $_POST['depdrop_parents'][0]])->andWhere($where)->orderBy($fields[1])->all();
            }
            foreach ($records as $key => $r) {
                if (!empty($data[5]) && !empty($r[$data[5]]))
                    $value = $r['value'] . '(' . $r[$data[5]] . ')';
                else
                    $value = $r['value'];
                if (!empty($refVal[1]) && !empty($r[$refVal[1]]))
                    $value .= ' - ' . $r[$refVal[1]];
                if ($data[6] == 0 || empty($check_list) || in_array($r['id'], $check_list))
                    $out[] = array('id' => $r['id'],
                        'name' => $value);
            }
            return Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        return Json::encode(['output' => '', 'selected' => '']);
        return;
    }

    public function actionGetAutoData() {
        
    }

    public function actionExceltoCsv() {

        $path = Yii::$app->basePath . '/web/data.xlsx';
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        switch ($ext) {
            case 'xls':
                $format = 'Excel5';
                break;
            case 'xlsx':
                $format = 'Excel2007';
                break;
            case 'xml':
                $format = 'Excel2003XML';
                break;
        }

        $reader = \PHPExcel_IOFactory::createReader($format);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($path);

        $savePath = Yii::$app->basePath . '/web/data.csv';
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'CSV');
        $writer->save($savePath);

        chmod($savePath, 0777);
    }

    public function actionLoadDcsData() {

        $rlsData = $this->setRlsData();
        if (isset($_POST['dt'])) {
            $sp_param = [];
            $sp_name = 'sp_Portal_Dashboard_Cal_new';
            $sp_param[] = !empty($rlsData['union']) ? ',' . $rlsData['union'] . ',' : 0;
            $sp_param[] = !empty($rlsData['plant']) ? ',' . $rlsData['plant'] . ',' : 0;
            $sp_param[] = !empty($rlsData['mcc']) ? ',' . $rlsData['mcc'] . ',' : 0;
            $sp_param[] = !empty($rlsData['bmc']) ? ',' . $rlsData['bmc'] . ',' : 0;
            $sp_param[] = !empty($rlsData['dcs']) ? ',' . $rlsData['dcs'] . ',' : 0;
            $sp_param[] = $_POST['dt']; //date('Y-m-d', strtotime($data['from_date'])) . ' 06:00:00';
            $sp_param[] = $_POST['dt']; //date('Y-m-d', strtotime($data['to_date'])) . ' 18:00:00';
            $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
//            $query = \Yii::$app->db->createCommand("{CALL sp_Portal_Dashboard_Cal(:union_code,:startdate,:enddate,:dcs_code)}")
//                    ->bindValue(':union_code', ',' . $union_str . ',')
//                    ->bindValue(':startdate', $_POST['dt'])
//                    ->bindValue(':enddate', $_POST['dt'])
//                    ->bindValue(':dcs_code', $dcs_str);
//            $results = $query->queryAll();
            if (!empty($results)) {
                $results = array_values($results);
//$results=(object)$results;
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success', 'res' => $results];
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Json::encode(['status' => 'failure', 'res' => '']);
        return;
    }

    public function actionLoadMonthData() {
        if (!empty(Yii::$app->request->post('m'))) {
//            $union_str = Yii::$app->request->post('union');
//            $mcc_str = Yii::$app->request->post('mcc');
            $month = Yii::$app->request->post('m');
            $rlsData = $this->setRlsData();

            $results = $this->callDashboardCalSp($rlsData, $month);
            if (!empty($results)) {
                if (!empty($results)) {
                    foreach ($results as $res) {
                        $cal_data[$res['dt']] = [$res['AvgFAT'], $res['AvgSNF'], $res['Qty']];
                    }
                } else {
                    $cal_data = [];
                }
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success', 'res' => $cal_data];
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Json::encode(['status' => 'failure', 'res' => '']);
        return;
    }

    public function actionLoadDailyData() {

        if (!empty(Yii::$app->request->post('union'))) {
            $union_str = Yii::$app->request->post('union');
        } else {
            $unionModel = new TblUnions();
            $union = $unionModel->getActiveUnions();
            $union_ary = ArrayHelper::getColumn($union, 'union_code');
            $union_str = implode(',', $union_ary);
        }
        $dcs_str = NULL;
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $dcs_str = Yii::$app->session->get('Dcs');
            $dcs_str = ',' . $dcs_str . ',';
        }
        $today_date = date('Y-m-d');
        $results = $this->callDashboardSp($union_str, $today_date, $today_date, $dcs_str);
        if (!empty($results)) {
            $results = array_values($results);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['status' => 'success', 'res' => $results];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Json::encode(['status' => 'failure', 'res' => '']);
        return;
    }

    public function actionSendCollectionSms() {
        try {
            $smsModel = new TblSms();
            $smsData = $smsModel->getData();
            $sms_ids = array_column($smsData, 'sms_id');
            $update = $smsModel->updateSmsStatus($sms_ids);
            foreach ($smsData as $sms) {
                $mobile = '91' . $sms->mobile_no;
                $msg = $sms->sms_txt;
                $sent = Yii::$app->bsmartsms->sendSmsPOST($mobile, $msg);
                $sms->sms_status = 'Y';
                $sms->sms_msgid = $sent;
                $sms->updated_at = date('Y-m-d H:i:s');
                $sms->status = 2;
                $sms->save(false);
            }
        } catch (yii\base\Exception $e) {
            var_dump($e);
        }
    }

    public function actionSendCollectionSmsNew() {
        try {
            $smsModel = new TblSms();
            $smsData = $smsModel->getData();
            $sms_ids = array_column($smsData, 'sms_id');
            $update = $smsModel->updateSmsStatus($sms_ids);
            foreach ($smsData as $sms) {
                $mobile = '91' . $sms->mobile_no;
                $msg = $sms->sms_txt;
                $sent = Yii::$app->bsmartsms->sendSmsPOSTNew($mobile, $msg);
                $sms->sms_status = 'Y';
                $sms->sms_msgid = $sent;
                $sms->sms_result = '0';
                $sms->updated_at = date('Y-m-d H:i:s');
                $sms->status = 2;
                $sms->save(false);
            }
            $paymentModel = new TblPaymentTransaction();
            $paymentModel = $paymentModel->getSmsRecords();
            foreach ($paymentModel as $payment) {
                $mobile = '91' . $payment->mobile_no; //'919712147065';
// $message = 'We have initiated your payment of RS.' . $payment->final_amount . '. actual effect is subject to bank realization.';
                if ($payment->type == 'member') {
                    $m_code = substr($payment->code, -4);
                    $message = $m_code . ':,
 दूध की मात्रा: ' . $payment->qty . ' लि. की धनराशि Rs.' . $payment->final_amount . ' बैंक को भेज दिया';
                    $sent = Yii::$app->bsmartsms->sendSmsPOSTNew($mobile, $message, TRUE);
                } else {
                    $message = 'We have disbursed payment of Rs. ' . $payment->final_amount . ' on ' . date('d-m-Y') . ' to the bank.Subject to realisation.';
                    $sent = Yii::$app->bsmartsms->sendSmsPOSTNew($mobile, $message);
                }
                $payment->sms_status = 'Y';
                $payment->sms_msgid = $sent;
                $payment->sms_timestamp = date('Y-m-d H:i:s');
                $payment->save(false);
            }
        } catch (yii\base\Exception $e) {
            var_dump($e);
        }
    }

    public function actionLoadChart() {
        $sp = Yii::$app->request->post('sp');
        $results = $this->getSpResult($sp);
        $series = [];
        $labels = [];

//echo '<pre>';
// print_r($results);die;
        if (!empty($results)) {
            $keys = array_keys($results[0]);
            foreach ($keys as $key) {
                if (in_array($key, ['qty', 'fat', 'snf', 'kgfat', 'kgsnf', 'm_qty', 'e_qty', 'm_fat', 'm_snf', 'e_fat', 'e_snf', 'm_kgfat', 'e_kgfat', 'm_kgsnf', 'e_kgsnf', 'm_quantity', 'e_quantity', 'DPUCount', 'CFCount', 'quantity'])) {
                    $series[$key] = array_column($results, $key);
                }
                if (in_array($key, ['union_short_name', 'collection_date', 'period', 'dcs_name', 'union_name', 'VillageName', 'bmc_name'])) {
                    $labels[] = array_column($results, $key);
                }
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $series, 'lbl' => $labels];
    }

    public function actionPaymentFileStatus() {
        $model = new TblBankPaymentLog();
        $data = $model->getFileRecord();
        $misfile = ['status' => '', 'misfile' => []];
        foreach ($data as $file) {
            if ($file->unionBankPaymentCode->server_type == 'eipl') {
                $misfile = $this->CheckMisFile($file, $misfile['misfile']);
                if ($file->status != $misfile['status']) {
                    $file->status = $misfile['status'];
                    $file->save(FALSE);
                }
            } else {
                $new_status = $file->status;
                if (!file_exists($file->file_path)) {
                    $new_status = 2;
                }
                if (!empty($file->unionBankPaymentCode->ftp_type)) {
                    $array = explode('/', $file->file_path);
                    $array = array_reverse($array);
                    $file_name = $array[0];
                    $pickFiles = $this->getFileList($file);
                    $ftp = new FTPConnection();
                    $ftp->ftp_type = $file->unionBankPaymentCode->ftp_type;
                    $ftp->ftp_host = $file->unionBankPaymentCode->ftp_server;
                    $ftp->ftp_username = $file->unionBankPaymentCode->ftp_username;
                    $ftp->ftp_password = $file->unionBankPaymentCode->ftp_password;
                    $ftp->ftp_port = $file->unionBankPaymentCode->ftp_port;
                    $ftp->ftp_path = $file->unionBankPaymentCode->reverse_ftp_path;
                    $ftp->local_path = $file->unionBankPaymentCode->reverse_server_path;
                    Yii::$app->general->checkDirectory($ftp->local_path . 'archive/');
                    foreach ($pickFiles as $fname) {
                        $ftp->file_name = $fname;
                        if ($ftp->DownloadFile()) {
                            $this->SaveReverseFileLog($file->unionBankPaymentCode->union_code, $fname);
                            echo 'Downloaded : ' . $fname . '<br/>';
                        } else {
                            echo 'Not Downloaded : ' . $fname . '<br/>';
                        }
                    }
//                if (!empty($file->unionBankPaymentCode->compare_file_name)) {
//                    $ftp->file_name = str_replace('[BANK_ACCOUNT_NO]', $file->unionBankPaymentCode->bank_account_no, $file->unionBankPaymentCode->compare_file_name);
//                    $date = date('Ymd', strtotime($file->payment_date));
//                    $ftp->file_name = str_replace('[DATE]', $date, $ftp->file_name);
//                    if (Yii::$app->general->checkDirectory($ftp->local_path) && Yii::$app->general->checkDirectory($ftp->local_path . 'archive/') && $ftp->DownloadFile()) {
//                        $new_status = 3;
//                    }
//                }
                }
                if ($file->status != $new_status) {
                    $file->status = $new_status;
                    $file->save(FALSE);
                }
            }
        }
        $destination = Yii::$app->basePath . '/web/payment/';
        if (Yii::$app->general->checkDirectory($destination) && Yii::$app->general->checkDirectory($destination . 'archive/')) {
            $delete = [];
            foreach (array_unique($misfile['misfile']) as $source) {
                $files = scandir($source);
                foreach ($files as $file) {
                    if (in_array($file, array(".", "..")))
                        continue;
                    if (copy($source . $file, $destination . $file)) {
                        $delete[] = $source . $file;
                    }
                }
            }
            foreach ($delete as $file) {
                unlink($file);
            }
        }
    }

    public function actionUpdatePaymentStatus() {
        $folder = Yii::$app->basePath . '/web/payment/';
        $this->ReadPaymentFile($folder, 'UNION', 'csv');
        $folder = Yii::$app->basePath . '/web/payment/AXIS/';
        $this->ReadPaymentFile($folder, 'AXIS', 'xlsx');
    }

    public function CheckMisFile($file, $misfile = []) {
        $array = explode('/', $file->file_path);
        $array = array_reverse($array);
        unset($array[0]);
        unset($array[1]);
        $array = array_reverse($array);
        $mispath = implode('/', $array) . '/mis/';
        if (is_dir($mispath) && count(scandir($mispath)) > 2) {
            $misfile[] = $mispath;
            return ['status' => 3, 'misfile' => $misfile];
        } else if (!file_exists($file->file_path)) {
            return ['status' => 2, 'misfile' => $misfile];
        }
        return ['status' => $file->status, 'misfile' => $misfile];
    }

    private function getSpResult($sp_name) {

        $post = Yii::$app->request->post('Dashboard');
        if (!empty($post['mag_from_shift'])) {
            $post['mav_from_shift'] = $post['mag_from_shift'];
        }
        if (!empty($post['mag_to_shift'])) {
            $post['mav_to_shift'] = $post['mag_to_shift'];
        }
        if (!empty($post['shift'])) {
            $post['from_shift'] = $post['shift'];
            $post['to_shift'] = $post['shift'];
            if ($post['shift'] == 3) {
                $post['from_shift'] = 1;
                $post['to_shift'] = 2;
            }
        }
        $input = $this->SpInput($sp_name, $post);
        $spname = $input['name'];
        $in_array = explode(',', str_replace(' ', '', $input['input']));
        $param_str = '';
        $is_from_date = true;
        foreach ($in_array as $in) {
            $data = explode('=', $in);
            $variable = explode('~', $data[0]);
            $val_type = explode('|', $data[1]);
            $param = $variable[0];

            $param1 = isset($variable[1]) ? $variable[1] : '';
            $value = !empty($post[$param]) ? $post[$param] : $val_type[0];
            $value = (isset($val_type[1]) && $val_type[1] == 'date') ? date('Y-m-d', strtotime($value)) : $value;
            $value = (isset($val_type[1]) && $val_type[1] == 'list') ? str_replace('-', ',', $value) : $value;

            if (!empty($val_type[1])) {
                $checkshift = explode(':', $val_type[1]);
                if (isset($checkshift[0]) && $checkshift[0] == 'dateshift') {
                    if (!empty($post[$checkshift[1]])) {
                        $value = date('Y-m-d', strtotime($value)) . ' ' . Yii::$app->general->getshift($post[$checkshift[1]]);
                    } else {
                        $value = date('Y-m-d', strtotime($value)) . ' ' . Yii::$app->general->getshift(1);
                    }
                }
            }
            if (in_array($param, ['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code'])) {
                if ($value != 0) {
                    $value = ',' . $value . ',';
                }
            }
            if (isset($val_type[1]) && $val_type[1] == 'date') {
                if (isset($post['from_shift'])) {
                    if ($is_from_date) {
                        $value .= ' ' . \Yii::$app->general->getshift($post['from_shift']);
                        $is_from_date = FALSE;
                    } else {
                        $value .= ' ' . \Yii::$app->general->getshift($post['to_shift']);
                    }
                }
            }
            $param_str .= "'" . $value . "',";
        }
        $param_str = rtrim($param_str, ",");
        $query = \Yii::$app->db->createCommand("{CALL $spname($param_str)}");
// echo $query->rawSql;exit;
        $results = $query->queryAll();
        return $results;
    }

    private function SpInput($sp, $post = []) {
        if (!empty(Yii::$app->request->post('union'))) {
            $union_str = Yii::$app->request->post('union');
        } else {
            $unionModel = new TblUnions();
            $union = $unionModel->getActiveUnions();
            $union_ary = ArrayHelper::getColumn($union, 'union_code');
            $union_str = implode('-', $union_ary);
        }

        $cur_time = date_create(date('H:i:s'));
        $morning_time = date_create('16:00:00');
        $diff = date_diff($morning_time, $cur_time);
        $time = '06:00:00';
        if (($diff->h > 0 || $diff->i > 0) && $diff->invert == 0) {
            $time = '18:00:00';
        }

        $plant_code = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
//        $mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : (!empty(Yii::$app->request->post['mcc_code']) ? ',' . Yii::$app->request->post['mcc_code'] . ',' : 0);
//        $bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : (!empty(Yii::$app->request->post['bmc_code']) ? ',' . Yii::$app->request->post['bmc_code'] . ',' : 0);
//        $dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : (!empty(Yii::$app->request->post['dcs_code']) ? ',' . Yii::$app->request->post['dcs_code'] . ',' : 0);
        $mcc_code = (!empty(Yii::$app->request->post('mcc')) && Yii::$app->request->post('mcc') != 0) ? Yii::$app->request->post('mcc') : 0;
        $mcc_code = (empty($mcc_code) && !empty($post['mcc_code']) && $post['mcc_code'] != 0) ? $post['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? Yii::$app->session->get('MCC') : 0);
        $bmc_code = (!empty($post['bmc_code']) && $post['bmc_code'] != 0) ? $post['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? Yii::$app->session->get('BMC') : 0);
        $dcs_code = (!empty($post['dcs_code']) && $post['dcs_code'] != 0) ? $post['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? Yii::$app->session->get('Dcs') : 0);
        $union_str = '-' . $union_str . '-';
        $dcs_str = NULL;
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $dcs_str = Yii::$app->session->get('Dcs');
            $dcs_str = ',' . $dcs_str . ',';
        }
        $plant_code = str_replace(',', '-', $plant_code);
        $mcc_code = str_replace(',', '-', $mcc_code);
        $bmc_code = str_replace(',', '-', $bmc_code);
        $dcs_str = str_replace(',', '-', $dcs_str);
        $dcs_code = str_replace(',', '-', $dcs_code);
        $widget_type = '';
        $customer_type = '';
        $performance_type = '0';
        $member_code = '0';
        if (!empty(Yii::$app->request->post('widget_type'))) {
            $widget_type = Yii::$app->request->post('widget_type');
        }
        if (!empty(Yii::$app->request->post('customer_type'))) {
            $customer_type = Yii::$app->request->post('customer_type');
        }
        if (!empty(Yii::$app->request->post('performance_type'))) {
            $performance_type = Yii::$app->request->post('performance_type');
        }
        if (!empty(Yii::$app->request->post('member_code'))) {
            $member_code = Yii::$app->request->post('member_code');
        }
        $array = [
            'fed_union' => [
                'name' => 'sp_dashboard_fed_union',
                'input' => 'qlt_param=1,date~shift=' . date('Y-m-d') . '|date,shift=0',
            ],
            'fed_comparison' => [
                'name' => 'sp_dashboard_fed_comparison',
                'input' => 'qlt_param=1,from_date=' . date('Y-m-d') . '|date,to_date=' . date('Y-m-d') . '|date, from_date2=' . date('Y-m-d') . '|date,to_date2=' . date('Y-m-d') . '|date',
            ],
            'fed_datewise' => [
                'name' => 'sp_dashboard_fed_datewise',
                'input' => 'qlt_param=1,from_date=' . date('Y-m-d') . '|date,to_date=' . date('Y-m-d') . '|date',
            ],
            'union_comparison' => [
                'name' => 'sp_portal_dashboard_union_comparison',
                'input' => 'qlt_param=1,from_date=' . date('Y-m-d') . '|date,to_date=' . date('Y-m-d') . '|date,from_date2=' . date('Y-m-d') . '|date,to_date2=' . date('Y-m-d') . '|date,union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list',
            ],
            'union_datewise' => [
                'name' => 'sp_portal_dashboard_union_datewise',
                'input' => 'qlt_param=1,from_date=' . date('Y-m-d') . '|date,to_date=' . date('Y-m-d') . '|date,union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list',
            ],
            'member_datewise' => [
                'name' => 'sp_portal_dashboard_member_datewise',
                'input' => 'qlt_param=1,from_date=' . date('Y-m-d') . '|date,to_date=' . date('Y-m-d') . '|date,union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,member_code=' . $member_code . '|list',
            ],
            'bmc_union_comparison' => [
                'name' => 'sp_portal_dashboard_bmc_union_comparison',
                'input' => 'qlt_param=1,from_date=' . date('Y-m-d') . '|date,to_date=' . date('Y-m-d') . '|date,from_date2=' . date('Y-m-d') . '|date,to_date2=' . date('Y-m-d') . '|date,union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list',
            ],
            'bmc_union_datewise' => [
                'name' => 'sp_portal_dashboard_bmc_union_datewise',
                'input' => 'qlt_param=1,from_date=' . date('Y-m-d') . '|date,to_date=' . date('Y-m-d') . '|date,union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list',
            ],
            'milk_coll_widget' => [
                'name' => 'sp_Portal_dashboard_milk_collection',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'bmc_coll_widget' => [
                'name' => 'sp_Portal_dashboard_bmc_collection',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'bmc_dispatch_widget' => [
                'name' => 'sp_Portal_BMC_Dispatch',
                'input' => 'union_code=' . $union_str . '|list,bmc_code=' . $bmc_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'reconciliation_chart_widget' => [
                'name' => 'sp_portal_dashboard_rptDPU_GPRSDataReconciliation_chart',
                'appendTime' => true,
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . ' ' . $time . '|date,date=' . date('Y-m-d') . ' ' . $time . '|date',
            ],
            'table_milk_collection' => [
                'name' => 'sp_portal_dashboard_milk_collection_table',
                'appendTime' => true,
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,from_date=' . date('Y-m-d') . '|dateshift:from_shift,to_date=' . date('Y-m-d') . '|dateshift:to_shift',
            ],
            'manual_vs_auto_collection' => [
                'name' => 'sp_portal_dashboard_society_raw_data',
                'appendTime' => true,
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,from_date=' . date('Y-m-d') . '|dateshift:from_shift,to_date=' . date('Y-m-d') . '|dateshift:to_shift',
            ],
            'dipatch_vs_receipt' => [
                'name' => 'sp_portal_dashboard_dispatch_vs_receipt',
                'appendTime' => true,
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,hidden_from_date=' . date('Y-m-d') . '|date,hidden_to_date=' . date('Y-m-d') . '|date',
            ],
            'bmc_collection_summary' => [
                'name' => 'sp_Portal_dashboard_qlty_qty_sap_summary',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,from_date=' . date('Y-m-d') . '|dateshift:from_shift',
            ],
            'monthly_milk_collection' => [
                'name' => 'sp_Portal_dashboard_monthly_milk_collection',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,hidden_from_date=' . date('Y-m-d') . '|date,hidden_to_date=' . date('Y-m-d') . '|date',
            ],
            'dashboard_blocks' => [
                'name' => 'sp_Portal_dashboard_blocks',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'piechart_member_app' => [
                'name' => 'sp_Portal_dashboard_piechart_member_app',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list',
            ],
            'dashboard_farmer_rmrd_blocks' => [
                'name' => 'sp_portal_dashboard_farmer_rmrd_blocks',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date,widget_type=' . $widget_type,
            ],
            'dashboard_farmer_rmrd_avg' => [
                'name' => 'sp_portal_dashboard_farmer_rmrd_avg',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date,widget_type=' . $widget_type,
            ],
            'dashboard_farmer_status' => [
                'name' => 'sp_portal_dashboard_farmer_status',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'tbl_collc_count_summary' => [
                'name' => 'sp_portal_dashboard_collection_count_summary',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'dashboard_milk_analysis' => [
                'name' => 'sp_dashboard_milk_analysis',
//                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,from_date_milk_analysis=' . date('Y-m-d') . '|dateshift:from_shift,to_date_milk_analysis=' . date('Y-m-d') . '|dateshift:to_shift',
//                'appendTime' => true,
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,from_date=' . date('Y-m-d') . '|dateshift:mav_from_shift,to_date=' . date('Y-m-d') . '|dateshift:mav_to_shift',
            ],
            'today_vs_yesterday_collection' => [
                'name' => 'sp_portal_dashboard_today_vs_yesterday_collection',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'top_dcs_collection' => [
                'name' => 'sp_portal_dashboard_top_dcs_collection',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date,widget_type=' . $widget_type . ',customer_type=' . $customer_type . ',performance_type=' . $performance_type,
            ],
            'top_rmrd_collection' => [
                'name' => 'sp_portal_dashboard_top_dcs_collection',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date,widget_type=' . $widget_type . ',customer_type=DCS' . ',performance_type=' . $performance_type,
            ],
            'dashboard_society_status_pie_chart' => [
                'name' => 'sp_portal_dashboard_farmer_status',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
            'milk_collection_summary' => [
                'name' => 'sp_milk_collection_summary_status',
                'input' => 'union_code=' . $union_str . '|list,plant_code=' . $plant_code . '|list,mcc_code=' . $mcc_code . '|list,bmc_code=' . $bmc_code . '|list,dcs_code=' . $dcs_code . '|list,date=' . date('Y-m-d') . '|date,date=' . date('Y-m-d') . '|date',
            ],
        ];
        return $array[$sp];
    }

    private function ReadPaymentFile($folder, $bank_type, $file_type) {
        if (is_dir($folder)) {
            if ($dh = opendir($folder)) {
                while (($file = readdir($dh)) !== false) {
                    if (pathinfo($file, PATHINFO_EXTENSION) == $file_type) {
                        $objPHPExcel = PHPExcel_IOFactory::load($folder . $file);
                        $objPHPExcel->getDefaultStyle()
                                ->getNumberFormat()
                                ->setFormatCode(
                                        \PHPExcel_Style_NumberFormat::FORMAT_TEXT
                        );
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            if ($bank_type == 'UNION') {
                                $this->UnionPaymentData($worksheet);
                            } else if ($bank_type == 'AXIS') {
                                $this->AxisPaymentData($worksheet);
                            }
                        }
                        if (copy($folder . $file, $folder . 'archive/' . $file)) {
                            unlink($folder . $file);
                        }
                    }
                }
            }
        }
    }

    private function PaymentUpdate($payment_code, $status, $utr_no, $ref_no, $date, $reason) {
        $model = TblPaymentTransaction::findOne($payment_code);
        if (!empty($model) && $model->bank_status == NULL) {
            $model->utr_no = $utr_no;
            $model->reference_no = $ref_no;
            $model->process_date = $date;
            $model->reject_reason = $reason;
            $model->bank_status = $status;
            if (strtoupper($model->bank_status) != 'SUCCESS') {
                $model->status = 'rejected';
                $disburse_amount = '0.00';
            } else {
                $model->status = 'disbursed';
                $disburse_amount = $model->final_amount;
            }
            $model->disburse_date = $model->process_date;
            $model->disburse_amount = (double) $disburse_amount;
            $model->save(FALSE);
            $payment_model = [];
            if ($model->type == 'dcs') {
                $payment_model = TblMemberPayment::find()->where(['vsp_payment_reference_no' => $payment_code])->all();
            } else if ($model->type == 'member') {
                $payment_model = TblMemberPayment::find()->where(['payment_transaction_code' => $payment_code])->all();
            } else if ($model->type == 'tp') {
                $payment_model = TblTransporterPayment::find()->where(['payment_transaction_code' => $payment_code])->all();
            }
            foreach ($payment_model as $data) {
                if ($model->type == 'member') {
                    $historyModel = new TblMemberPaymentHistory();
                } else {
                    $historyModel = new TblTransporterPaymentHistory();
                }
                Yii::$app->operation->history($data, $historyModel, 'UPDATE');
                $historyModel->save(FALSE);
                if ($model->type == 'member' && $model->status == 'rejected') {
                    $dcs_data = TblDcsPayment::find()
                                    ->where(['dcs_code' => $data->dcs_code, 'dcs_payment_cycle_applicabilty_code' => $data->dcs_payment_cycle_applicabilty_code, 'dcs_payment_cycle_code' => $data->dcs_payment_cycle_code])->one();
                    if (!empty($dcs_data) && $dcs_data->status != 'processed') {
                        $dcs_data->status = 'processed';
                        $dcs_data->save(FALSE);
                    }
                }
                $data->status = $model->status;
                if ($data->status == 'rejected') {
                    $disburse_amount = '0.00';
                } else {
                    $disburse_amount = $data->final_amount;
                }
                $data->disburse_date = $model->disburse_date;
                $data->disburse_amount = (double) $disburse_amount;
                $data->utr_no = $model->utr_no;
                $data->reference_no = $model->reference_no;
                $data->process_date = $model->process_date;
                $data->reject_reason = $model->reject_reason;
                $data->bank_status = $model->bank_status;
                $data->save(FALSE);
            }
        }
    }

    private function UnionPaymentData($worksheet) {
        for ($row = 1; $row <= $worksheet->getHighestRow(); $row ++) {
            $payment_code = $worksheet->getCell('D' . $row)->getValue();
            $status = $worksheet->getCell('O' . $row)->getValue();
            $utr_no = $worksheet->getCell('P' . $row)->getValue();
            $ref_no = $worksheet->getCell('Q' . $row)->getValue();
            $date = date('Y-m-d', strtotime($worksheet->getCell('R' . $row)->getValue()));
            $reason = $worksheet->getCell('S' . $row)->getValue();
            $this->PaymentUpdate($payment_code, $status, $utr_no, $ref_no, $date, $reason);
        }
    }

    private function AxisPaymentData($worksheet) {
        for ($row = 2; $row <= $worksheet->getHighestRow(); $row ++) {
            $status = $worksheet->getCell('M' . $row)->getValue();
            if (strtoupper($status) == 'EXECUTED' || strtoupper($status) == 'SETTLED') {
                $status = 'SUCCESS';
            } else if (strtoupper($status) == 'CANCELLED' || strtoupper($status) == 'RETURNED SETTLED') {
                $status = 'FAILURE';
            } else {
                $status = '';
            }
            if ($status != '') {
                $payment_code = $worksheet->getCell('C' . $row)->getValue();
                $utr_no = $worksheet->getCell('L' . $row)->getValue();
                $ref_no = '';
                $date = date('Y-m-d', strtotime($worksheet->getCell('J' . $row)->getFormattedValue()));
                $reason = $worksheet->getCell('N' . $row)->getValue();

                $this->PaymentUpdate($payment_code, $status, $utr_no, $ref_no, $date, $reason);
            }
        }
    }

    private function getFileList($file) {
        $data = new TblReversePaymentFileLog();
        $data->union_code = $file->unionBankPaymentCode->union_code;
        $oldFiles = $data->getRecord();
        $oldFiles = ArrayHelper::getColumn($oldFiles, 'file_name');
        $ftp = new FTPConnection();
        $ftp->ftp_type = $file->unionBankPaymentCode->ftp_type;
        $ftp->ftp_host = $file->unionBankPaymentCode->ftp_server;
        $ftp->ftp_username = $file->unionBankPaymentCode->ftp_username;
        $ftp->ftp_password = $file->unionBankPaymentCode->ftp_password;
        $ftp->ftp_port = $file->unionBankPaymentCode->ftp_port;
        $ftp->ftp_path = $file->unionBankPaymentCode->reverse_ftp_path;
        $ftpFiles = $ftp->ListFile();
        $pickFiles = array_diff($ftpFiles, $oldFiles);
        return $pickFiles;
    }

    private function SaveReverseFileLog($union_code, $file_name) {
        $model = new TblReversePaymentFileLog();
        $model->union_code = $union_code;
        $model->file_name = $file_name;
        $model->save(FALSE);
    }

    public function actionSpGetData() {

        if (isset($_POST['depdrop_parents']) && $_POST['depdrop_parents'][0] != '') {
            $cnt = 0;
            foreach ($_POST as $key => $val) {
                if ($cnt == 0) {
                    $cnt++;
                    continue;
                }
                $data = explode(',', $key);
            }

            $fields[] = $data[3];
            $fields[] = $data[4];
            if (!empty($data[5])) {
                array_push($fields, $data[5]);
            }
            $check_list = [];
            if (!empty($data[7]) && $data[6] == 1) {
                $check_list = explode('-', $data[7]);
            }
            $local_name = (!empty($data[5])) ? $data[5] : '';
            $model_name = Yii::$app->path->define($data[0]);
            $model = new $model_name();
            $table_name = $model->tableName();
            $out = NULL;


            $result = \Yii::$app->db->createCommand("{CALL [sp_dropdown](:dd1,:dd2,:dd3,:dd4)}")
                    ->bindValue(':dd1', $_POST['depdrop_parents'][0])
                    ->bindValue(':dd2', '')
                    ->bindValue(':dd3', '')
                    ->bindValue(':dd4', '');
            $records = $result->queryAll();

            foreach ($records as $key => $r) {
                if (!empty($data[5]) && !empty($r[$data[5]]))
                    $value = $r[$data[4]] . '(' . $r[$data[5]] . ')';
                else
                    $value = $r[$data[4]];
                if ($data[6] == 0 || empty($check_list) || in_array($r[$data[3]], $check_list))
                    $out[] = array('id' => $r[$data[3]],
                        'name' => $value);
            }
            return Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        return Json::encode(['output' => '', 'selected' => '']);
        return;
    }

    public function actionSendNotification() {
        $url = \Yii::$app->params['notification_url'];
        $types = ['1', '2', '3', '4'];
        foreach ($types as $type) {
            $serverKey = '';
            if ($type == '1') {
                $serverKey = \Yii::$app->params['everest_notification_key'];
            } else if ($type == '2') {
                $serverKey = \Yii::$app->params['member_notification_key'];
            } else if ($type == '3') {
                $serverKey = \Yii::$app->params['bmc_notification_key'];
            } else if ($type == '4') {
                $serverKey = \Yii::$app->params['ho_notification_key'];
            }
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=' . $serverKey;
            $model = new TblAppNotification();
            $model->app_type = $type;
            foreach ($model->getRecord() as $data) {
                $reg_id = [];
                $activation_id = [];
                $notification_data = [];
                if (in_array($type, ['1', '2', '3'])) {
                    $notification_data = $data->activeMobile;
                }
                if (in_array($type, ['4'])) {
                    $notification_data = $data->activeUser;
                }
                foreach ($notification_data as $notif) {
                    if ($model->app_type == $notif->type) {
                        $reg_id[] = $notif->device_id;
                        $activation_id[] = $notif->activation_id;
                    }
                }
                if (!empty($reg_id)) {
                    $notification = array('title' => $data->notification_title, 'body' => $data->notification_text, 'sound' => 'default', 'badge' => '1');
                    $arrayToSend = array('registration_ids' => $reg_id, 'data' => $notification, 'priority' => 'high');
                    $json = json_encode($arrayToSend);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//Send the request
                    $response = curl_exec($ch);
//Close request
                    if ($response === FALSE) {
                        die('FCM Send Error: ' . curl_error($ch));
                    }
                    curl_close($ch);
                    $response = json_decode($response);
                    $data->mobile_no = $data->mobile_no;
                    $data->is_send = 1;
                    $data->device_count = count($activation_id);
                    $data->activation_id = implode(',', $activation_id);
                    $data->success_count = $response->success;
                    $data->save();
                }
            }
        }
    }

    public function actionTxFarmer() {
        $model = \Yii::$app->db_rmrd->createCommand("SELECT * FROM txfarmer where farmerid != '' ");
        $users = $model->queryAll();
        foreach ($users as $data) {
            $result = Yii::$app->db_rmrd->createCommand("UPDATE txfarmer SET txflag=txflag WHERE "
                            . "farmerid='" . $data['farmerid'] . "' "
                            . "and vlccid='" . $data['vlccid'] . "' "
                            . "and mccid='" . $data['mccid'] . "' "
                            . "and sampleno='" . $data['sampleno'] . "' "
                            . "and dtdate='" . $data['dtdate'] . "' "
                            . "and shift='" . $data['shift'] . "' "
                            . "and sampletime='" . $data['sampletime'] . "'")
                    ->execute();
            Yii::$app->db_rmrd->createCommand()
                    ->insert('txfarmer_temp', $data)->execute();
        }
    }

    public function actionDecryptData() {
        $model = \app\modules\details\models\TblBankDetails::find()->select(['ifsc', 'bank_account_no', 'detail_code'])->where("bank_account_no != '' and bank_account_no is not null")->all();
        foreach ($model as $data) {
            $result = Yii::$app->db->createCommand("UPDATE tbl_bank_details SET d_ifsc='$data->ifsc',d_bank_account_no='$data->bank_account_no' WHERE "
                            . "detail_code='" . $data->detail_code . "' ")
                    ->execute();
        }
    }

    private function getWidgetDetails($sp_name, $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_str, $sdate, $edate) {
        $query = \Yii::$app->db->createCommand("{CALL $sp_name(:union_code,:plant_code,:mcc_code,:bmc_code,:dcs_code,:startdate,:enddate)}")
                ->bindValue(':union_code', ',' . $union_str . ',')
                ->bindValue(':plant_code', $plant_str)
                ->bindValue(':mcc_code', $mcc_str)
                ->bindValue(':bmc_code', $bmc_str)
                ->bindValue(':dcs_code', $dcs_str)
                ->bindValue(':startdate', $sdate)
                ->bindValue(':enddate', $edate);
        $results = $query->queryAll();
        return $results;
    }

    public function actionGetWidgetLazyDetails() {
        $data = Yii::$app->request->post();
        $sp_name = $data['sp_name'];
        $query = \Yii::$app->db->createCommand("{CALL $sp_name(:union_code,:plant_code,:mcc_code,:bmc_code,:dcs_code,:startdate,:enddate)}")
                ->bindValue(':union_code', ',' . $data['union_str'] . ',')
                ->bindValue(':plant_code', $data['plant_str'])
                ->bindValue(':mcc_code', $data['mcc_str'])
                ->bindValue(':bmc_code', $data['bmc_str'])
                ->bindValue(':dcs_code', $data['dcs_str'])
                ->bindValue(':startdate', $data['sdate'])
                ->bindValue(':enddate', $data['edate']);
        $output = $query->queryAll();
        $output = json_encode($output);
// var_dump($output);die;
        return $output;
    }

    public function actionCollectionFarmerCreamy() {
        $db_config = new TblDbConfig();
        $database = $db_config->activeConnection();
        foreach ($database as $db) {
            \Yii::$app->general->SetDBConnection('db_creamy', $db);
            $model = new CollectionFarmerCreamy();
            $vlcc_model = new TblDcsPortalCreamy();
            $vlccid = $vlcc_model->getVlcc();
            $modelData = $model->getNewDcs($vlccid);
            foreach ($modelData as $data) {
                $farmer_id = $data['farmerid'];
                $vlcc_id = $data['vlccid'];
                $sample_id = $data['sampleno'];
                $dtdate_id = $data['dtdate'];
                $shift_id = $data['shift'];
                $update = $model->updateDcs($farmer_id, $vlcc_id, $sample_id, $dtdate_id, $shift_id);
            }
            foreach ($modelData as $data) {
                $milkCollection = new TblMilkCollection();
                $dcs_code = ($db->union_code == '006') ? substr(trim($data->vlccid), -7) : $data->vlccid;
                $milkCollection->dcs_code = in_array($dcs_code, ['1011618', '1011647', '1011648', '1015576', '19990001']) ? '00' . $dcs_code : $dcs_code;
                $milkCollection->member_code = (strlen($data->farmerid) > 4) ? $data->farmerid : $milkCollection->dcs_code . str_pad($data->farmerid, 4, 0, STR_PAD_LEFT);
                $milkCollection->shift = (($data->shift == 'M') ? 1 : 2);
                $milkCollection->date_time_of_collection = date('Y-m-d', strtotime($data->dtdate)) . ' ' . (($data->shift == 'M') ? '06:00:00' : '18:00:00');
                $milkCollection->sample_no = $data->sampleno;
                $olddata = $milkCollection->find()->where(['member_code' => $milkCollection->member_code, 'dcs_code' => $milkCollection->dcs_code, 'shift' => $milkCollection->shift, 'date_time_of_collection' => $milkCollection->date_time_of_collection, 'sample_no' => $milkCollection->sample_no])->one();
                if (!empty($olddata)) {
                    $milkCollection = $olddata;
                }
                $milkCollection->attributes = $data->attributes;
                $milkCollection->shift = (string) (($data->shift == 'M') ? 1 : 2);
                $milkCollection->milk_type_code = array_values(Yii::$app->db->createCommand("SELECT dbo.getMilktype('$data->milktype')")->queryOne())[0];
                $milkCollection->milk_quality_type_code = array_values(Yii::$app->db->createCommand("SELECT dbo.getMilkQltytype('$data->milkqtype')")->queryOne())[0];
                $milkCollection->date_time_of_recieve = $data->createddate;
                $milkCollection->dt_date = $data->dtdate;
                $milkCollection->sms_status = 'n';
                $milkCollection->status = 'Accept';
                $milkCollection->qlty_time = $data->qltytime;
                $milkCollection->qty_time = $data->qtytime;
                $milkCollection->qty_mode = 0;
                $milkCollection->qty_auto = $data->qtyauto;
                $milkCollection->qlty_auto = $data->qltyauto;
                $milkCollection->type_of_data_receive = 'online';
//  $milkCollection->rate_code = (string) $data->rateid;
                $milkCollection->bmc_code = Yii::$app->general->getforeignkey($milkCollection->dcsCode, 'bmc_code');
                $milkCollection->name = Yii::$app->general->getforeignkey($milkCollection->memberCode, 'member_name');
                $milkCollection->village_code = Yii::$app->general->getforeignkey($milkCollection->memberCode, 'village_code');
                try {
                    $data->modifieddate = date('Y-m-d H:i:s');
                    if ($milkCollection->validate() && $milkCollection->save(FALSE)) {
                        $data->data_post_status = 2;
                        $data->save(FALSE);
                    } else {
                        $data->data_post_status = 3;
                        $data->save(FALSE);
//                    var_dump($milkCollection);
                        var_dump($milkCollection->getErrors());
                    }
                } catch (UserException $e) {
                    $data->data_post_status = 3;
                    $data->modifieddate = date('Y-m-d H:i:s');
                    $data->save(FALSE);
                    var_dump($milkCollection->getErrors());
                } catch (\yii\db\Exception $e) {
                    $data->data_post_status = 3;
                    $data->modifieddate = date('Y-m-d H:i:s');
                    $data->save(FALSE);
                    var_dump($milkCollection->getErrors());
                }
            }
        }
    }

    public function actionSetCrossTab() {
        $output = [];
        $union = '';
//        if (!empty($_POST)) {
        $data = $_POST;
        $union = $data['union'];

        $union_str = 0;
        if (!empty(Yii::$app->request->post('union'))) {
            $union_str = Yii::$app->request->post('union');
        } else if (!empty(Yii::$app->session->get('Unions'))) {
            $union_str = Yii::$app->session->get('Unions');
            $union_str = ',' . $union_str . ',';
        } else {
            $unionModel = new TblUnions();
            $union = $unionModel->getActiveUnions();
            $union_ary = ArrayHelper::getColumn($union, 'union_code');
            $union_str = implode(',', $union_ary);
        }
        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'rpt_MIS_Shiftwise_CrossTab_BMC_Wise_new';
        $sp_param[] = date('Y-m-d', strtotime($data['from_date'])) . ' 06:00:00';
        $sp_param[] = date('Y-m-d', strtotime($data['to_date'])) . ' 18:00:00';
        $sp_param[] = $union_str; //$data['union'];
        $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
        $sp_param[] = empty($rlsData['mcc']) ? '0' : $rlsData['mcc'];
        $sp_param[] = empty($rlsData['bmc']) ? '0' : $rlsData['bmc'];
        $sp_param[] = empty($rlsData['dcs']) ? '0' : $rlsData['dcs'];
        $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
//        }
        return $this->renderAjax('bmc_cross_tab', ['output' => $output, 'union_code' => $union]);
    }

    public function actionTopDcsCollection() {
        $output = [];
        $union = '';
        $data = $_POST;
        $union = $data['union'];
        $union_str = 0;
        $mccc_str = 0;

        if (!empty(Yii::$app->request->post('union'))) {
            $union_str = Yii::$app->request->post('union');
        } else if (!empty(Yii::$app->session->get('Unions'))) {
            $union_str = Yii::$app->session->get('Unions');
            $union_str = ',' . $union_str . ',';
        } else {
            $unionModel = new TblUnions();
            $union = $unionModel->getActiveUnions();
            $union_ary = ArrayHelper::getColumn($union, 'union_code');
            $union_str = implode(',', $union_ary);
        }
        if (!empty(Yii::$app->request->post('mcc'))) {
            $mccc_str = Yii::$app->request->post('mcc');
        }
        $type = Yii::$app->request->post('type');
        $customertype = !empty(Yii::$app->request->post('customer_type')) ? Yii::$app->request->post('customer_type') : ($type == 'rmrd' ? 'DCS' : '');
        $performancetype = !empty(Yii::$app->request->post('performance_type')) ? Yii::$app->request->post('performance_type') : '0';

        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'sp_portal_dashboard_top_dcs_collection';
        $sp_param[] = $union_str; //$data['union'];
        $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
        $sp_param[] = !empty($mccc_str) ? $mccc_str : (empty($rlsData['bmc']) ? $rlsData['bmc'] : 0);
        $sp_param[] = empty($rlsData['bmc']) ? '0' : $rlsData['bmc'];
        $sp_param[] = empty($rlsData['dcs']) ? '0' : $rlsData['dcs'];
        $sp_param[] = date('Y-m-d', strtotime($data['from_date'])) . ' 06:00:00';
        $sp_param[] = date('Y-m-d', strtotime($data['to_date'])) . ' 18:00:00';
        $sp_param[] = $type;
        $sp_param[] = $customertype;
        $sp_param[] = $performancetype;
//        $sp_param[] = '';
        $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

        return $this->renderAjax('top_dcs_collection', ['output' => $output, 'union_code' => $union, 'customer_tpye' => $customertype, 'performance_type' => $performancetype]);
    }

    public function actionBmcCrossTabDetails() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_POST)) {
            $data = $_POST;
            $sp_param = [];
            $sp_name = 'rpt_MIS_Shiftwise_CrossTab_BMC_Wise_Sub';
            $sp_param[] = date('Y-m-d', strtotime($data['p_date']));
            $sp_param[] = $data['shift'];
            $sp_param[] = $data['union_Code'];
            $sp_param[] = $data['p_bmc_code'];
            $sp_param[] = $data['p_type'];
            $union = $data['union_Code'];
            $bmc = $data['bmc_name'];
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
        }
        return $this->renderAjax('bmc_cross_tab_details', ['output' => $output, 'bmc' => $bmc]);
    }

    public function actionCreamyDataProcess() {
        $db_config = new TblDbConfig();
        $database = $db_config->activeConnection();
        foreach ($database as $db) {
            \Yii::$app->general->SetDBConnection('db_creamy', $db);
            $processlist = $this->creamymodel();
            $vlcc_model = new TblDcsPortalCreamy();
            $vlccid = $vlcc_model->getVlcc();
            foreach ($processlist as $process) {
                $model_name = Yii::$app->path->define($process['master_model']);
                $model = new $model_name();

                $date = date('Y-m-d H:i:s', strtotime('-3 hours'));
                $data1 = $model->find()
                        ->where(['or', ['data_post_status' => 0], ['data_post_status' => NULL]])
                        ->andWhere([$process['master_model_dcs_key'] => $vlccid])
                        ->limit(80)
                        ->all();
                $data2 = $model->find()
                        ->where(['data_post_status' => 3])
                        ->andWhere([$process['master_model_dcs_key'] => $vlccid])
//                ->andWhere(['<=', 'modifieddate', $date])
                        ->limit(20)
                        ->all();
                $modelData = array_merge($data1, $data2);
                if (count($process['primary_key']) == 1) {
                    $update_key = $process['primary_key'][0];
                    $update_ids = array_column($modelData, $update_key);
                    $model->updateAll(['data_post_status' => 1], [$update_key => $update_ids]);
                } else {
                    foreach ($modelData as $data) {
                        $where = [];
                        foreach ($process['primary_key'] as $pk) {
                            $where[$pk] = $data[$pk];
                        }
                        $model->updateAll(['data_post_status' => 1], $where);
                    }
                }

                foreach ($modelData as $data) {
                    $model_name = Yii::$app->path->define($process['slave_model']);
                    $saveModel = new $model_name();
                    $saveModel->setAttributes($data->attributes);
                    if (!empty($process['replace_key_array'])) {
                        foreach ($process['replace_key_array'] as $creamy_key => $model_key) {
                            $saveModel->$model_key = $data->$creamy_key;
                        }
                    }
                    $changed_data = [];
                    if (!empty($process['validateFields'])) {
                        foreach ($process['validateFields'] as $fields) {
                            $field = explode(':', $fields);
                            $chage_field = !empty($field[1]) ? $field[1] : '';
                            if (!empty($chage_field)) {
                                if ($field[0] == 'dcs_code') {
                                    $dcs_code = ($db->union_code == '006') ? substr(trim($saveModel->$chage_field), -7) : $saveModel->$chage_field;
                                    $saveModel->$chage_field = in_array($dcs_code, ['1011618', '1011647', '1011648', '1015576', '19990001']) ? '00' . $dcs_code : $dcs_code;
                                }
                                if ($field[0] == 'shift') {
                                    $saveModel->$chage_field = ((trim($saveModel->$chage_field) == 'M') ? '1' : '2');
                                }
                                if ($field[0] == 'milk_type') {
                                    $saveModel->$chage_field = array_values(Yii::$app->db->createCommand("SELECT dbo.getMilktype('" . trim($saveModel->$chage_field) . "')")->queryOne())[0];
                                }
                                if ($field[0] == 'dateshift') {
                                    $saveModel->$chage_field = date('Y-m-d', strtotime($saveModel->$chage_field));
                                    if (!empty($field[2])) {
                                        $shift_field = $field[2];
                                        $saveModel->$chage_field = $saveModel->$chage_field . ' ' . Yii::$app->general->getshift($saveModel->$shift_field);
                                    }
                                }
                                if ($field[0] == 'member_code') {
                                    $saveModel->$chage_field = str_pad($saveModel->$chage_field, 4, '0', STR_PAD_LEFT);
                                    if (!empty($field[2])) {
                                        $dcs_field = $field[2];
                                        $saveModel->$chage_field = $saveModel->$dcs_field . $saveModel->$chage_field;
                                    }
                                }
                                if ($field[0] == 'milk_qlty_type') {
                                    $saveModel->$chage_field = array_values(Yii::$app->db->createCommand("SELECT dbo.getMilkQltytype('" . trim($saveModel->$chage_field) . "')")->queryOne())[0];
                                }
                                if ($field[0] == 'qty_mode') {
                                    $saveModel->$chage_field = array_values(Yii::$app->db->createCommand("SELECT dbo.getQtyMode('" . trim($saveModel->$chage_field) . "')")->queryOne())[0];
                                }
                                if ($field[0] == 'bmc_code') {
                                    $saveModel->$chage_field = Yii::$app->general->getforeignkey($saveModel->dcsCode, 'bmc_code');
                                }
                                $changed_data[$chage_field] = $saveModel->$chage_field;
                            }
                        }
                    }

                    if (isset($process['getCode']) && $process['getCode']) {
                        $key = $process['getCodeKey'];
                        $saveModel->$key = $saveModel->getCode();
                    }
                    $where = [];
                    $primary_key = $process['primary_key'];
                    $primary_key = !empty($process['slave_primary_key']) ? $process['slave_primary_key'] : $primary_key;
                    foreach ($primary_key as $pk) {
                        $primary_key = explode(':', $pk);
                        $model_key = $primary_key[0];
                        $creamy_key = !empty($primary_key[1]) ? $primary_key[1] : $primary_key[0];
                        $where[$model_key] = $saveModel->$model_key;
                    }
                    $olddata = $saveModel->find()->where($where)->one();
                    if (!empty($olddata)) {
                        $saveModel = $olddata;
                        $saveModel->setAttributes($data->attributes);
                        if (!empty($changed_data)) {
                            $saveModel->setAttributes($changed_data);
                        }
                    }
                    if (isset($process['scenario'])) {
                        $saveModel->scenario = $process['scenario'];
                    }
                    if ($saveModel->hasAttribute('union_code')) {
                        $saveModel->union_code = $db->union_code;
                    }
                    try {
                        if ($saveModel->validate() && $saveModel->save(FALSE)) {
                            if (!empty($process['childModel'])) {
                                foreach ($process['childModel'] as $childmodels) {
                                    $child_model_name = Yii::$app->path->define($childmodels);
                                    $childModel = new $child_model_name();
                                    if (!empty($process['childModelKey'][$childmodels])) {
                                        $childModelArray = $process['childModelKey'][$childmodels];
                                        foreach ($childModelArray['key'] as $model_key => $creamy_key) {
                                            $childModel->$model_key = $data->$creamy_key;
                                            if (isset($childModelArray['scenario']) && $childModelArray['scenario']) {
                                                $childModel->scenario = $process['scenario'];
                                            }
                                        }
                                        $savechildModel = true;
                                        $primary_key = !empty($childModelArray['primaryKeyCheck']) ? $childModelArray['primaryKeyCheck'] : [];
                                        if (!empty($primary_key)) {
                                            $where = [];
                                            foreach ($primary_key as $pk) {
                                                $where[$model_key] = $childModel->$model_key;
                                            }
                                            $oldChildModelData = $childModel->find()->where($where)->one();
                                            if (!empty($oldChildModelData)) {
                                                $savechildModel = false;
                                            }
                                        }
                                        if ($savechildModel) {
                                            if ($childModel->validate() && $childModel->save(FALSE)) {
                                                
                                            }
                                        }
                                    }
                                }
                            }
                            $data->data_post_status = 2;
                            $data->save(FALSE);
                        } else {
                            $data->data_post_status = 3;
                            $data->save(FALSE);
                            var_dump($saveModel->getErrors());
                        }
                    } catch (UserException $e) {
                        $data->data_post_status = 3;
                        $data->save(FALSE);
                        var_dump($saveModel->getErrors());
                    } catch (\yii\db\Exception $e) {
                        $data->data_post_status = 3;
                        $data->save(FALSE);
                        var_dump($saveModel->getErrors());
                    }
                }
            }
        }
    }

    public function creamymodel($l = '') {
        $label = [
            'DCS' => [
                'master_model' => 'MastervillageCreamy',
                'slave_model' => 'TblDcs',
                'primary_key' => ['villageid'],
                'master_model_dcs_key' => 'villageid',
                'scenario' => 'saveCreamyData',
                'slave_primary_key' => ['dcs_code:villageid'],
                'replace_key_array' => ['villageid' => 'dcs_code', 'villagename' => 'dcs_name', 'routeid' => 'route_code', 'mccid' => 'mcc_plant_code', 'villagestatus' => 'is_active'],
                'childModel' => ['TblSocietyCodes', 'TblSocietyVendor'],
                'childModelKey' => [
                    'TblSocietyCodes' => [
                        'key' => ['dcs_code' => 'dcs_code', 'bipl_code' => 'dcs_code'],
                        'primaryKeyCheck' => ['dcs_code'],
                        'scenario' => true
                    ],
                    'TblSocietyVendor' => [
                        'key' => ['dcs_code' => 'dcs_code'],
                        'primaryKeyCheck' => ['dcs_code'],
                        'scenario' => true
                    ]
                ]
            ],
            'Member' => [
                'master_model' => 'MasterfarmerCreamy',
                'slave_model' => 'TblMember',
                'primary_key' => ['farmerid', 'villageid'],
                'master_model_dcs_key' => 'villageid',
                'getCode' => true,
                'getCodeKey' => 'member_code',
                'scenario' => 'saveCreamyData',
                'slave_primary_key' => ['member_code:farmerid'],
                'replace_key_array' => ['farmername' => 'member_name', 'farmerid' => 'ex_member_code', 'farmeraddress' => 'address', 'farmergender' => 'gender_code', 'farmercontact' => 'mobile_no', 'farmercow' => 'no_of_cow_cross', 'farmerbuff' => 'no_of_buffalo', 'farmerbankac' => 'bank_account_no', 'farmerstatus' => 'is_active', 'villageid' => 'dcs_code', 'farmeraadharcode' => 'adhar_no', 'farmerpanno' => 'pan_no', 'farmerbankifsccode' => 'ifsc'],
                'validateFields' => ['dcs_code:dcs_code', 'member_code:ex_member_code'],
            ],
            'BmcCollection' => [
                'master_model' => 'CollectionvillageCreamy',
                'slave_model' => 'TblBmcCollection',
                'scenario' => 'saveCreamyData',
                'primary_key' => ['vlccid', 'bmcid', 'sampleno', 'dtdate', 'shift'],
                'master_model_dcs_key' => 'vlccid',
                'replace_key_array' => ['vlccid' => 'dcs_code', 'bmcid' => 'bmc_code', 'sampleno' => 'sample_no', 'shift' => 'shift_code', 'dtdate' => 'date_time_of_collection', 'routeid' => 'route_code', 'can' => 'no_of_can', 'rateid' => 'rate_code', 'qtyauto' => 'qty_auto', 'qltyauto' => 'qlty_auto', 'qtytime' => 'qty_time', 'qltytime' => 'qlty_time', 'qtymode' => 'qty_mode', 'milktype' => 'milk_type_code', 'milkqtype' => 'milk_quality_type_code'],
                'validateFields' => ['dcs_code:dcs_code', 'shift:shift_code', 'dateshift:date_time_of_collection:shift_code', 'qty_mode:qty_mode', 'milk_type:milk_type_code', 'milk_qlty_type:milk_quality_type_code', 'qty_mode:qty_mode', 'bmc_code:bmc_code'],
                'slave_primary_key' => ['date_time_of_collection', 'bmc_code', 'shift_code', 'dcs_code', 'sample_no'],
            ],
            'LocalSale' => [
                'master_model' => 'CollectionFarmerLocalSaleCreamy',
                'slave_model' => 'CollectionFarmerLocalSale',
                'primary_key' => ['farmerid', 'vlccid', 'sampleno', 'dtdate', 'shift'],
                'master_model_dcs_key' => 'vlccid',
                'validateFields' => ['dcs_code:vlccid', 'shift:shift', 'dateshift:dtdate:shift', 'member_code:farmerid:vlccid', 'milk_type:milktype', 'milk_qlty_type:milkqtype', 'qty_mode:qtymode'],
            ],
            'Calibration' => [
                'master_model' => 'TblMACAlibrationCreamy',
                'slave_model' => 'TblMACAlibration',
                'primary_key' => ['id'],
                'master_model_dcs_key' => 'PPCode',
                'replace_key_array' => ['id' => 'ref_id'],
                'validateFields' => ['dcs_code:PPCode', 'shift:shift', 'milk_type:MilkType', 'dateshift:dtdate:shift', 'bmc_code:BMCCode'],
                'slave_primary_key' => ['ref_id:ref_id'],
            ],
            'CalibrationChange' => [
                'master_model' => 'TblMACAlibrationChangeCreamy',
                'slave_model' => 'TblMACAlibrationChange',
                'primary_key' => ['id'],
                'master_model_dcs_key' => 'PPCode',
                'replace_key_array' => ['id' => 'ref_id'],
                'validateFields' => ['dcs_code:PPCode', 'shift:shift', 'milk_type:MilkType', 'dateshift:dtdate:shift', 'bmc_code:BMCCode'],
                'slave_primary_key' => ['ref_id:id'],
            ],
            'Cleaning' => [
                'master_model' => 'TblMACleaningCreamy',
                'slave_model' => 'TblMACleaning',
                'primary_key' => ['id'],
                'master_model_dcs_key' => 'PPCode',
                'replace_key_array' => ['id' => 'ref_id'],
                'validateFields' => ['dcs_code:PPCode', 'shift:shift', 'dateshift:dtdate:shift', 'bmc_code:BMCCode'],
                'slave_primary_key' => ['ref_id:id'],
            ],
            'DpuShiftEndSummary' => [
                'master_model' => 'TblDpuShiftEndSummaryCreamy',
                'slave_model' => 'TblDpuShiftEndSummary',
                'primary_key' => ['BMCCode', 'VillageCode', 'dtdate'],
                'master_model_dcs_key' => 'VillageCode',
                'replace_key_array' => ['Id' => 'ref_id'],
                'validateFields' => ['dcs_code:VillageCode', 'shift:shift', 'dateshift:dtdate:shift', 'bmc_code:BMCCode'],
                'slave_primary_key' => ['ref_id:id'],
            ],
            'MaSerialNo' => [
                'master_model' => 'TblMASerialNoCreamy',
                'slave_model' => 'TblMASerialNo',
                'primary_key' => ['BMCCode', 'PPCode', 'DtDate', 'serialno'],
                'master_model_dcs_key' => 'PPCode',
                'replace_key_array' => ['id' => 'ref_id'],
                'validateFields' => ['dcs_code:PPCode', 'shift:shift', 'dateshift:DtDate:shift', 'bmc_code:BMCCode'],
                'slave_primary_key' => ['ref_id:id'],
            ],
//            'ProductSale' => [
//                'master_model' => 'TblDpuProductDemandCreamy',
//                'slave_model' => 'TblDpuProductDemand',
//                'primary_key' => ['Id'],
//                'master_model_dcs_key' => 'VillageCode',
//                'replace_key_array' => ['BMCCode' => 'bmc_code', 'VillageCode' => 'dcs_code', 'MemberCode' => 'member_code', 'ProductId' => 'product_code'],
//                'validateFields' => ['dcs_code:dcs_code', 'shift:shift', 'dateshift:trDate:shift', 'member_code:member_code:dcs_code'],
//            ],
        ];
        return isset($label[$l]) ? $label[$l] : $label;
    }

    public function actionLoadTable() {
        $model = new Dashboard();
        $sp = Yii::$app->request->post('sp');
        $title = !empty(Yii::$app->request->post('title')) ? Yii::$app->request->post('title') : '';
        $popup = !empty(Yii::$app->request->post('popup')) ? Yii::$app->request->post('popup') : '';
        $results = $this->getSpResult($sp);
        $series = [];
        $labels = [];
        if (!empty($results)) {
            $keys = array_keys($results[0]);
            foreach ($keys as $key) {
                if (in_array($key, ['qty', 'fat', 'snf', 'kgfat', 'kgsnf', 'm_qty', 'e_qty', 'm_fat', 'm_snf', 'e_fat', 'e_snf', 'm_kgfat', 'e_kgfat', 'm_kgsnf', 'e_kgsnf'])) {
                    $series[$key] = array_column($results, $key);
                }
                if (in_array($key, ['union_short_name', 'collection_date', 'period'])) {
                    $labels[] = array_column($results, $key);
                }
            }
        }
        $dataProvider = '';
        if (!empty($results)) {
            $attr = '';
            foreach ($results[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $results,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        return $this->renderAjax('_chart_to_table', ['result' => $results, 'dataProvider' => $dataProvider, 'model' => $model, 'title' => $title, 'popup' => $popup]);
    }

    public function actionParseInboxData() {
        try {
            $unique_key = 'x_col1';
            $model = new TblInbox();
            $modelData = $model->getData();
            $i = 1;
            if (!empty($modelData)) {
                $version_ignore_tables = ['tbl_product_sale', 'tbl_product_sale_transaction'];
                $ignore_tables = ['tbl_product_stock', 'tbl_product_stock_transaction', 'tbl_product_receipt', 'tbl_product_receipt_transaction'];
                $tableWiseUniqueKeys = [
                    'tbl_member' => 'member_code',
                ];
                $version_no = 0;
                $update_ids = array_column($modelData, 'uuid');
//$model->updateAll(['data_post_status' => 1, 'error_timestamp' => date('Y-m-d H:i:s')], ['uuid' => $update_ids]);
                foreach ($modelData as $transaction_data) {
                    try {
                        $process_record = TRUE;
                        $is_insert = TRUE;
                        $delete = [];
                        $childModel = [];
                        $delete [] = $transaction_data;
                        $syncLogModel = new TblSyncLog();
                        $syncLogModel->setAttributes($transaction_data->attributes);
                        $childModel[] = $syncLogModel;
                        if (in_array($transaction_data->table_name, $ignore_tables)) {
                            $process_record = FALSE;
                        } else if (in_array($transaction_data->table_name, $version_ignore_tables)) {
//  if (!in_array($transaction_data->dest_org_id, ['001'])) {
                            $version_no = (int) str_replace('d_', '', $transaction_data->version_no);
                            if ($version_no <= 100) {
                                $process_record = FALSE;
                            }
// }
                        }
                        if ($process_record) {
                            $model_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $transaction_data->table_name)));
                            $model_name = Yii::$app->path->define($model_name);
                            $model = new $model_name();
                            $json = $transaction_data->json_text;
                            $json = (array) json_decode($json);
                            $json = Yii::$app->general->camelCaseToUnderscore($json);
                            $model->setAttributes($json);
                            $unique_key = isset($tableWiseUniqueKeys[$transaction_data->table_name]) ? $tableWiseUniqueKeys[$transaction_data->table_name] : $unique_key;

                            /* update record if already available */
                            if ($model->hasAttribute($unique_key) && !empty($model->$unique_key)) {
                                $unique_value = $model->$unique_key;
                                $model_data = $model->find()->where([$unique_key => $unique_value])->one();
                                if (!empty($model_data)) {
                                    $is_insert = FALSE;
                                    $model = $model_data;
                                    $history = $model_name . 'History';
                                    $historyModel = new $history();
                                    Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                                    $childModel[] = $historyModel;
                                    $model->setAttributes($json);
                                }
                            }
                            /* update record if already available */
                            $model->scenario = 'androidsync';
                            $model = Yii::$app->general->SetDataType($model);
                            if ($model->validate()) {

                                if (isset($model->is_sentbox)) {
                                    $model->is_sentbox = false;
                                }
                                if ($model->hasAttribute('originating_type')) {
                                    $model->originating_type = 23;
                                }

                                if (isset($model->saveChildRecords) && $model->saveChildRecords == true) {
                                    $model->setTransactionData($model, $json, $childModel);
                                }
                                if (isset($model->saveDeleteChildRecords) && $model->saveDeleteChildRecords == true) {
                                    $model->setTransactionSaveDeleteData($model, $json, $childModel, $delete);
                                }

                                if ($transaction_data->table_name == 'tbl_bmc_collection' || $transaction_data->table_name == 'tbl_milk_collection') {
                                    $model->scenario = 'androidsync_coll';
                                    if (!$model->validate()) {
                                        $setData = $model;
                                        if ($transaction_data->table_name == 'tbl_bmc_collection') {
                                            $model = new TblBmcCollectionNotExist();
                                            $model->attributes = $setData->attributes;
                                            $model->data_inserted_from = 'androidsync';
                                        } elseif ($transaction_data->table_name == 'tbl_milk_collection') {
                                            $model = new TblMilkCollectionNotExists();
                                            $model->attributes = $setData->attributes;
                                            $model->send_status = 0;
                                            $model->data_inserted_from = 'androidsync';
                                        }
                                    } else {
                                        if ($transaction_data->table_name == 'tbl_bmc_collection') {
                                            if ($model->hasAttribute('vehicle_no') && !empty($model->vehicle_no)) {
                                                $model->vehicle_no = str_replace('\n', '', $model->vehicle_no);
                                                $model->vehicle_no = trim(preg_replace('/\n/', '', $model->vehicle_no));
                                                $model->vehicle_no = trim(preg_replace('/\s/', '', $model->vehicle_no));
                                                $model->vehicle_no = trim(preg_replace('/\s+/', '', $model->vehicle_no));
                                            }
                                            $range = Yii::$app->general->getUnionConfiguration($model->union_code, 'buf_min_fat_range_bmc', 'PORTAL');
                                            $mapping = new TblBmcMilkType();
                                            $mapped = $mapping->find()->where(['bmc_code' => $model->bmc_code, 'is_active' => 1])->all();
                                            if (!empty($range) && !empty($mapped) && count($mapped) == 2) {
                                                $type = [];
                                                foreach ($mapped as $map) {
                                                    $type[] = $map->milk_type_code;
                                                }
                                                if (in_array(1, $type) && in_array(2, $type)) {
                                                    if ($range < $model->fat) {
                                                        $model->milk_type_code = 2;
                                                    } elseif ($range >= $model->fat) {
                                                        $model->milk_type_code = 1;
                                                    }
                                                }
                                                if (in_array(1, $type) && in_array(3, $type)) {
                                                    if ($range < $model->fat) {
                                                        $model->milk_type_code = 3;
                                                    } elseif ($range >= $model->fat) {
                                                        $model->milk_type_code = 1;
                                                    }
                                                }
                                                if (in_array(2, $type) && in_array(3, $type)) {
                                                    if ($range < $model->fat) {
                                                        $model->milk_type_code = 3;
                                                    } elseif ($range >= $model->fat) {
                                                        $model->milk_type_code = 2;
                                                    }
                                                }
                                            }
                                        } else if ($transaction_data->table_name == 'tbl_milk_collection') {
                                            if (!empty($model->other_reading)) {
                                                $model->other_reading = str_replace('\r\n', '#####', $model->other_reading);
                                                $model->other_reading = str_replace('\r', '#####', $model->other_reading);
                                                $model->other_reading = str_replace('\n', '#####', $model->other_reading);
                                                if (in_array(substr($model->member_code, -4), ['2097', '2098'])) {
                                                    $process_record = FALSE;
                                                    $model->setCleaningCalibration($model, $childModel);
                                                }
                                            }
                                        }
                                        $model->scenario = 'androidsync';
                                    }
                                }

                                if ($transaction_data->table_name == 'tbl_dcs_closing') {

                                    $model_data = $model->find()->where(['dcs_code' => $model->dcs_code, 'to_date' => $model->to_date, 'to_shift_code' => $model->to_shift_code, 'milk_type_code' => $model->milk_type_code])->one();
                                    if (!empty($model_data)) {
                                        $model = $model_data;
                                        $history = $model_name . 'History';
                                        $historyModel = new $history();
                                        Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                                        $childModel[] = $historyModel;
                                        unset($json['dcs_closing_code']);
                                        $model->setAttributes($json);
                                        $model->dcs_closing_code = $model_data->dcs_closing_code;
                                    } else {
                                        $model->dcs_closing_code = (string) Yii::$app->general->getCodeAutoIncrement($model, $i);
                                    }
                                }
                                $generalModel = new GeneralModel();
                                $masterSave = [];
                                if ($process_record) {
                                    $masterSave[] = $model;
                                }
                                $transaction = $generalModel->saveDeleteTransaction($masterSave, $childModel, $delete, ['transactional data', 'create'], true);
                                if ($transaction != 'customRedirect') {
                                    $transaction_data->error_log = !empty($transaction) ? (string) $transaction : 'error_occured';
                                    $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                    $transaction_data->data_post_status = 3;
                                    if (strstr($transaction_data->error_log, 'Cannot insert duplicate key')) {
                                        $inbox_constraint = new TblInboxConstraint();
                                        $inbox_constraint->attributes = $transaction_data->attributes;
                                        $inbox_constraint->processed_timestamp = date('Y-m-d H:i:s');
                                        $inbox_constraint->data_post_status = 3;
                                        $transaction = $generalModel->saveDeleteTransaction([$inbox_constraint], [], [$transaction_data], ['inbox constraint data', 'create']);
                                    } else {
                                        $transaction_data->save();
                                    }
                                }
                            } else {
                                $transaction_data->error_log = Json::encode($model->getErrors());
                                $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                $transaction_data->data_post_status = 3;
                                $transaction_data->save();
                            }
                        } else {
                            $generalModel = new GeneralModel();
                            $transaction = $generalModel->saveDeleteTransaction($childModel, [], $delete, ['transactional data', 'create'], true);
                            if ($transaction != 'customRedirect') {
                                $transaction_data->error_log = !empty($transaction) ? (string) $transaction : 'error_occured';
                                $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                $transaction_data->data_post_status = 3;
                                $transaction_data->save();
                            }
                        }
                    } catch (\Throwable $ex) {
                        $transaction_data->error_log = 'Throwable Exception';
                        $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                        $transaction_data->data_post_status = 3;
                        $transaction_data->save();
                    }
                    $i++;
                }
            }
        } catch (yii\base\Exception $e) {
            var_dump($e);
        }
    }

    public function actionGetCollectionFtp() {
        $ftp = new FTPConnection();
        $ftp->ftp_type = 'FTP';
        $ftp->ftp_host = '182.74.63.142';
        $ftp->ftp_username = 'hims';
        $ftp->ftp_password = '12345';
        $ftp->ftp_port = '9194';
        $ftp->ftp_path = '/';
//        $ftp->local_path = $file->unionBankPaymentCode->reverse_server_path;
        $ftpFiles = $ftp->ListFile();
        foreach ($ftpFiles as $ftpFile) {
            $fileName = explode('.', $ftpFile);
            $count = count($fileName);

//            if ($ftpFile == 'nifpl_b8479908ad2472ad_280819-300819.txt') {

            if (!empty($fileName[$count - 1]) && strtolower($fileName[$count - 1] == 'txt')) {
//                if(strtolower(substr($ftpFile, 0, 10)) != 'nifpl_demo') { // uncomment this line for read testing data
                if (strtolower(substr($ftpFile, 0, 10)) != 'nifpl_demo' && strtolower(substr($ftpFile, 0, 5) == 'nifpl')) { //use condition for get live data
                    $ftp->file_name = $ftpFile;
                    $contents = $ftp->GetFileContents();
                    $setData = [];
                    $currentDateTime = date('Y-m-d H:i:s');
                    $identityRecord = !empty(Yii::$app->eiplapp->identity) ? Yii::$app->eiplapp->identity : [];
                    $setData['access_token'] = 'PORTAL';
                    $setData['identity_type'] = 'FTP';
                    $setData['mobile_no'] = NULL;
                    $setData['device_id'] = $ftpFile;
                    $setData['entry_type'] = 'HTTP';
                    $setData['status'] = 0;
                    $setData['entry_datetime'] = $currentDateTime;
                    $master = [];
                    $connection = Yii::$app->getDb();
                    foreach ($contents as $content) {
                        if (!in_array(trim($content), ['START', 'ENDOK'])) {
                            $setData['encrypted_string'] = $content;
                            $model = new TblDpuCollectionHoData();
                            $model->setAttributes($setData);
                            $command = $connection->createCommand('SELECT NEWID() as id')->queryOne();
                            $model->uuid = $command['id'];
                            $master[] = $model;
                        }
                    }
                    $generalModel = new GeneralModel();
                    $transaction = $generalModel->saveTransaction($master, [], ['FTP Collection HO Data', 'create']);
                    if ($transaction == 'customRedirect') {
                        $ftp->DeleteFile();
                    }
                }
            }
//            }
        }
    }

    public function actionGenerateSentbox() {
        $model = new TblGenerateSentbox();
        $modelData = $model->getData();
        if (!empty($modelData)) {
            $update_ids = array_column($modelData, 'id');
            $model->updateAll(['status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], ['id' => $update_ids]);
            foreach ($modelData as $record) {
                try {
                    if (!empty($record->model_name)) {
                        $modelName = $record->model_name;
                    } else {
                        $table = $record->table_name;
                        $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
                    }
                    $modelName = Yii::$app->path->define($modelName);
                    $model = new $modelName();
                    $modelDataAll = $model->find()
                            ->where($record->where_clause)
                            ->all();
                    if (!empty($modelDataAll)) {
                        foreach ($modelDataAll as $modelData) {
                            $union_code = '';
                            $plant_code = '';
                            $mcc_plant_code = '';
                            $bmc_code = '';
                            $dcs_code = '';
                            ${$record->sentbox_key} = $record->{$record->sentbox_key};
                            $sentboxArray = [];
                            $sentboxArray = Yii::$app->general->getSentBoxCodes($plant_code, $mcc_plant_code, $bmc_code, $union_code, $dcs_code);
                            foreach ($sentboxArray as $sent) {
                                if (empty($record->dest_org_type) || $record->dest_org_type == $sent['type']) {
                                    $flag = !empty($record->operation_type) ? $record->operation_type : 'INSERT';
                                    $sentbox = new TblSentbox();
                                    $sentbox->dest_org_id = $sent['code'];
                                    $sentbox->source_org_id = !empty($modelData->union_code) ? $modelData->union_code : $record->union_code;
                                    $sentbox->dest_org_type = $sent['type'];
                                    if (!($sentbox->setSentbox($modelData, $flag))) {
                                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                                    }
                                }
                            }
                        }
                    }
                    $record->status = 2;
                    $record->response_datetime = date('Y-m-d H:i:s');
                    $record->save(FALSE);
                } catch (\Throwable $e) {
//var_dump($e);
                    $record->status = 3;
                    $record->response_datetime = date('Y-m-d H:i:s');
                    $record->save(FALSE);
                } catch (\yii\base\UserException $e) {
//var_dump($e);
                    $record->status = 3;
                    $record->response_datetime = date('Y-m-d H:i:s');
                    $record->save(FALSE);
                } catch (\yii\db\Exception $e) {
//var_dump($e);
                    $record->status = 3;
                    $record->response_datetime = date('Y-m-d H:i:s');
                    $record->save(FALSE);
                }
            }
        }
    }

    public function actionMasterTransfer() {
        $model = new TblMasterTransfer();
        $model->status = 0;
        $modelData = $model->getPickRecords();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->master_transfer_code;
            }, $modelData);
            $update = $model->updateFileStatus($ids);
            foreach ($modelData as $row) {
                try {
                    $result = \Yii::$app->db->createCommand("{CALL sp_process_transfer_request (:master_type,:transfer_type,:union_code,:plant_code,:old_mcc_plant_code,:new_mcc_plant_code,:old_bmc_code,:new_bmc_code,:old_dcs_code,:new_dcs_code,:old_route_code,:new_route_code,:old_member_code,:new_member_code,:wef_date,:operation_by,:update_transaction,:customer_code,:from_datetime,:to_datetime)}")
                            ->bindValue(':master_type', $row->master_type)
                            ->bindValue(':transfer_type', $row->transfer_type)
                            ->bindValue(':union_code', $row->union_code)
                            ->bindValue(':plant_code', $row->plant_code)
                            ->bindValue(':old_mcc_plant_code', $row->old_mcc_plant_code)
                            ->bindValue(':new_mcc_plant_code', $row->new_mcc_plant_code)
                            ->bindValue(':old_bmc_code', $row->old_bmc_code)
                            ->bindValue(':new_bmc_code', $row->new_bmc_code)
                            ->bindValue(':old_dcs_code', $row->old_dcs_code)
                            ->bindValue(':new_dcs_code', $row->new_dcs_code)
                            ->bindValue(':old_route_code', $row->old_route_code)
                            ->bindValue(':new_route_code', $row->new_route_code)
                            ->bindValue(':old_member_code', $row->old_member_code)
                            ->bindValue(':new_member_code', $row->new_member_code)
                            ->bindValue(':wef_date', $row->wef_date)
                            ->bindValue(':operation_by', $row->created_by)
                            ->bindValue(':update_transaction', $row->update_transaction)
                            ->bindValue(':customer_code', $row->customer_code)
                            ->bindValue(':from_datetime', $row->from_datetime)
                            ->bindValue(':to_datetime', $row->to_datetime);
                    $query = $result->execute();
                    $row->response_datetime = date('Y-m-d H:i:s');
                    $row->status = 2;
                    $row->save();
                } catch (\Throwable $e) {
//var_dump($e);
                    $row->response_datetime = date('Y-m-d H:i:s');
                    $row->status = 3;
                    $row->save();
                } catch (\yii\db\Exception $e) {
// var_dump($e);
                    $row->response_datetime = date('Y-m-d H:i:s');
                    $row->status = 3;
                    $row->save();
                }
            }
        }
    }

    private function getMemberMobileDetail($sp_name, $union_str, $plant_str, $mcc_str, $bmc_str, $dcs_str) {
        $query = \Yii::$app->db->createCommand("{CALL $sp_name(:union_code,:plant_code,:mcc_code,:bmc_code,:dcs_code)}")
                ->bindValue(':union_code', ',' . $union_str . ',')
                ->bindValue(':plant_code', $plant_str)
                ->bindValue(':mcc_code', $mcc_str)
                ->bindValue(':bmc_code', $bmc_str)
                ->bindValue(':dcs_code', $dcs_str);
        $results = $query->queryAll();
        return $results;
    }

    public function actionLoadDashboardBlockData() {
        $sp = Yii::$app->request->post('sp');
        $results = $this->getSpResult($sp);
        $res = [];
        foreach ($results[0] as $key => $value) {
            $res[$key] = $value;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $res];
    }

    public function actionLoadDashboardFarmerRmrdData() {
        $sp = Yii::$app->request->post('sp');
        $results = $this->getSpResult($sp);
        $res = [];
        $array_result = $results[0];
        if (count($results) > 1) {
            $array_result = $results;
        }
        foreach ($array_result as $key => $value) {
            $res[$key] = $value;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $res];
    }

    public function actionLoadDashboardTodayVsYesterdayCollection() {
        $sp = Yii::$app->request->post('sp');
        $results = $this->getSpResult($sp);
        $res = [];
        $array_result = $results[0];
        if (count($results) > 1) {
            $array_result = $results;
        }
        foreach ($array_result as $key => $value) {
            $res[$key] = $value;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $res];
    }

    public function setRlsData() {
        $union_str = 0;
        if (!empty(Yii::$app->request->post('union'))) {
            $union_str = Yii::$app->request->post('union');
        } else if (!empty(Yii::$app->session->get('Unions'))) {
            $union_str = Yii::$app->session->get('Unions');
            $union_str = ',' . $union_str . ',';
        } else {
            $unionModel = new TblUnions();
            $union = $unionModel->getActiveUnions();
            $union_ary = ArrayHelper::getColumn($union, 'union_code');
            $union_str = implode(',', $union_ary);
        }

        $plant_str = 0;
        if (!empty(Yii::$app->session->get('Plant'))) {
            $plant_str = Yii::$app->session->get('Plant');
            $plant_str = $plant_str;
        }
        $mcc_str = Yii::$app->request->post('mcc');
        if (empty($mcc_str)) {
            if (!empty(Yii::$app->session->get('MCC'))) {
                $mcc_str = Yii::$app->session->get('MCC');
                $mcc_str = $mcc_str;
            } else {
                $mcc_str = 0;
            }
        }
        $bmc_str = 0;
        if (!empty(Yii::$app->session->get('BMC'))) {
            $bmc_str = Yii::$app->session->get('BMC');
            $bmc_str = $bmc_str;
        }

        $dcs_str = 0;
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $dcs_str = Yii::$app->session->get('Dcs');
            $dcs_str = $dcs_str;
        }
        $rls = [];
        $rls['union'] = $union_str;
        $rls['plant'] = $plant_str;
        $rls['mcc'] = $mcc_str;
        $rls['bmc'] = $bmc_str;
        $rls['dcs'] = $dcs_str;
        return $rls;
    }

    public function actionSetHitCountTab() {
        $output = [];
        $union = '';
        $sp_name = 'sp_portal_dashboard_no_of_hits';
        if (!empty($_POST)) {
            $data = $_POST;
            $rlsData = $this->setRlsData();
            $sp_param = [];
            $sp_param[] = empty($rlsData['union']) ? '0' : $rlsData['union'];
            $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
            $sp_param[] = empty($rlsData['mcc']) ? '0' : $rlsData['mcc'];
            $sp_param[] = empty($rlsData['bmc']) ? '0' : $rlsData['bmc'];
            $sp_param[] = empty($rlsData['dcs']) ? '0' : $rlsData['dcs'];
            $sp_param[] = date('Y-m-d', strtotime($data['from_date_current']));
            $sp_param[] = date('Y-m-d', strtotime($data['to_date_current']));
            $sp_param[] = empty($data['hit_current']) ? '0' : $data['hit_current'];
            $sp_param[] = date('Y-m-d', strtotime($data['from_date_previous']));
            $sp_param[] = date('Y-m-d', strtotime($data['to_date_previous']));
            $sp_param[] = empty($data['hit_previous']) ? '0' : $data['hit_previous'];
            $union = $data['union'];
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
        }
        return $this->renderAjax('hit_count_tab', ['output' => $output, 'union_code' => $union]);
    }

    public function actionSetCollectionCountSummary() {
        $output = [];
        $union = '';
        $widget_for = '';
        $sp = 'sp_portal_dashboard_collection_count_summary';
        if (!empty($_POST)) {
            $data = $_POST;
            $rlsData = $this->setRlsData();
            $sp_param = [];
            $sp_param[] = empty($rlsData['union']) ? '0' : $rlsData['union'];
            $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
            $sp_param[] = empty($rlsData['mcc']) ? '0' : $rlsData['mcc'];
            $sp_param[] = empty($rlsData['bmc']) ? '0' : $rlsData['bmc'];
            $sp_param[] = empty($rlsData['dcs']) ? '0' : $rlsData['dcs'];
            $sp_param[] = date('Y-m-d', strtotime($data['from_date'])) . ' ' . Yii::$app->general->getshift($data['from_shift']);
            $sp_param[] = date('Y-m-d', strtotime($data['to_date'])) . ' ' . Yii::$app->general->getshift($data['to_shift']);
            $sp_param[] = empty($data['widget_for']) ? '' : $data['widget_for'];
            $union = $data['union'];
            $widget_for = $data['widget_for'];
            $output = \Yii::$app->general->getSpData($sp, $sp_param);
        }
        return $this->renderAjax('collc_count_summary_tab', ['output' => $output, 'union_code' => $union, 'widget_for' => $widget_for]);
    }

    public function actionLoadYearData() {
        if (!empty(Yii::$app->request->post('y'))) {
//            $union_str = Yii::$app->request->post('union');
//            $mcc_str = Yii::$app->request->post('mcc');
            $year = Yii::$app->request->post('y');
            $rlsData = $this->setRlsData();

            $results = $this->callDashboardYearCalSp($rlsData, $year);
            if (!empty($results)) {
                if (!empty($results)) {
                    foreach ($results as $res) {
                        $cal_data[$res['dt']] = [$res['AvgFAT'], $res['AvgSNF'], $res['KgFAT'], $res['KgSNF'], $res['Qty']];
                    }
                } else {
                    $cal_data = [];
                }
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success', 'res' => $cal_data];
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Json::encode(['status' => 'failure', 'res' => '']);
        return;
    }

    private function callDashboardYearCalSp($rlsData, $year) {
        $year_start = date("$year-01-01");
        $year_end = date("$year-12-31");
        $sp_param = [];
        $sp_name = 'sp_Portal_Dashboard_Cal_month';
        $sp_param[] = !empty($rlsData['union']) ? ',' . $rlsData['union'] . ',' : 0;
        $sp_param[] = !empty($rlsData['plant']) ? ',' . $rlsData['plant'] . ',' : 0;
        $sp_param[] = !empty($rlsData['mcc']) ? ',' . $rlsData['mcc'] . ',' : 0;
        $sp_param[] = !empty($rlsData['bmc']) ? ',' . $rlsData['bmc'] . ',' : 0;
        $sp_param[] = !empty($rlsData['dcs']) ? ',' . $rlsData['dcs'] . ',' : 0;
        $sp_param[] = $year_start; //date('Y-m-d', strtotime($data['from_date'])) . ' 06:00:00';
        $sp_param[] = $year_end; //date('Y-m-d', strtotime($data['to_date'])) . ' 18:00:00';
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        return $results;
    }

    public function actionDpuDataCollection() {
        $db_config = new TblDbConfig();
        $database = $db_config->activeConnection();
// if(empty($model->date)){
//     $model->date=date("Y-m-d");
// }
        $sp_param = [];
        $result1 = [];
        $result2 = [];
        if (!empty($_POST)) {
            $data = $_POST['Dashboard'];
            $sp_param[] = date('Y-m-d', strtotime($data['dup_search_date']));
            $sp_param[] = $data['dpu_shift'];
            $sp_param[] = $data['dpu_status'];
// var_dump($sp_param);die;
            foreach ($database as $db) {
                if ($db->db_type == 'sql') {
                    \Yii::$app->general->SetDBConnection('db_sql', $db);
                    $result1 = \Yii::$app->general->getSpData('data_milk_collection_widget', $sp_param, false, 'db_sql');
                } elseif ($db->db_type == 'mysql') {
                    \Yii::$app->general->SetDBConnection('db_mysql', $db);
                    $result2 = \Yii::$app->general->getSpData('data_milk_collection_widget', $sp_param, false, 'db_mysql', 'mysql');
                }
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => json_encode(array_merge($result1, $result2))];
    }

    public function setBreadcrums($data, $widget = '') {
        $title = '';
        $url = [];
        $url['dashboard'] = Url::to(['site/dashboard']);
        $title .= "<a class='href_link' href=" . $url['dashboard'] . ">Dashboard</a> > ";
        if (!empty($data)) {
            if (isset($data['union_code']) && $data['union_code'] != 0) {
                if ($widget == 'rmrd') {
                    $url['union'] = Url::to(['site/get-rmrd-unions', 'date' => $data['date']]);
                } else {
                    $url['union'] = Url::to(['site/get-unions', 'date' => $data['date']]);
                }
                $tbl_union_model = new TblUnions();
                $tbl_union_model->union_code = $data['union_code'];
                $union_name = !empty(Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name')) ? Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name') : 'N/A';
                $title .= "<a class='href_link' href=" . $url['union'] . ">" . Yii::t('app', 'Union') . ': <span class="link_font_color">' . $union_name . "</span></a> > ";
            }
            if (isset($data['mcc_code']) && $data['mcc_code'] != 0) {
                $tbl_plant_model = new TblMccPlant();
                $tbl_plant_model->mcc_plant_code = $data['mcc_code'];
                $union = isset($data['union_code']) ? $data['union_code'] : '';
                if ($widget == 'rmrd') {
                    $url['mcc'] = Url::to(['site/get-rmrd-mccs', 'date' => $data['date'], 'union_code' => $union, 'mcc_code' => '0']);
                } else {
                    $url['mcc'] = Url::to(['site/get-mccs', 'date' => $data['date'], 'union_code' => $union, 'mcc_code' => '0']);
                }
                $mcc_name = !empty(Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name')) ? Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name') : 'N/A';
                $title .= "<a class='href_link' href=" . $url['mcc'] . ">" . Yii::t('app', 'MCC') . ": <span class='link_font_color'>" . $mcc_name . "</span></a> > ";
            }
            if (isset($data['bmc_code']) && $data['bmc_code'] != 0) {
                $tbl_bmc_model = new TblDcsBmc();
                $tbl_bmc_model->bmc_code = $data['bmc_code'];
                $union = isset($data['union_code']) ? $data['union_code'] : '';
                $mcc = isset($data['mcc_code']) ? $data['mcc_code'] : '';
                if ($widget == 'rmrd') {
                    $url['bmc'] = Url::to(['site/get-rmrd-bmcs', 'date' => $data['date'], 'union_code' => $union, 'mcc_code' => '0']);
                } else {
                    $url['bmc'] = Url::to(['site/get-rmrd-bmcs', 'date' => $data['date'], 'union_code' => $union, 'mcc_code' => '0']);
                }
                $bmc_name = !empty(Yii::$app->general->getforeignkey($tbl_bmc_model->tblDcsBmc, 'bmc_name')) ? Yii::$app->general->getforeignkey($tbl_bmc_model->tblDcsBmc, 'bmc_name') : 'N/A';
                $title .= "<a class='href_link' href=" . $url['bmc'] . ">" . Yii::t('app', 'BMC') . ": <span class='link_font_color'>" . $bmc_name . "</span></a> > ";
            }
            if (isset($data['dcs_code']) && $data['dcs_code'] != 0) {
                $tbl_dcs_model = new TblDcs();
                $tbl_dcs_model->dcs_code = $data['dcs_code'];
                $union = isset($data['union_code']) ? $data['union_code'] : '';
                $mcc = isset($data['mcc_code']) ? $data['mcc_code'] : '';
                if ($widget == 'rmrd') {
                    $url['dcs'] = Url::to(['site/get-rmrd-dcs', 'date' => $data['date'], 'union_code' => $union, 'mcc_code' => $mcc, 'dcs_code' => '0']);
                } else {
                    $url['dcs'] = Url::to(['site/get-dcs', 'date' => $data['date'], 'union_code' => $union, 'mcc_code' => $mcc, 'dcs_code' => '0']);
                }
                $dcs_name = !empty(Yii::$app->general->getforeignkey($tbl_dcs_model->tblDcs, 'dcs_name')) ? Yii::$app->general->getforeignkey($tbl_dcs_model->tblDcs, 'dcs_name') : 'N/A';
                $title .= "<a class='href_link' href=" . $url['dcs'] . ">" . Yii::t('app', 'DCS') . ": <span class='link_font_color'>" . $dcs_name . "</span></a> > ";
            }
        }
        return $title;
    }

    public function actionGetUnions() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;

            $breadcrum_title = $this->setBreadcrums($data);

            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_union';
            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = isset($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = isset($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $widget_for = 'farmer';
            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = $widget_for;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date);
        }
// var_dump($output);die;
        return $this->render('_dashboard_grid_union', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetRmrdUnions() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data, 'rmrd');
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_union';
            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = isset($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = isset($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $widget_for = 'rmrd';
            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = $widget_for;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date, 'rmrd');
        }
// var_dump($output);die;
        return $this->render('_dashboard_grid_rmrd_union', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetMccs() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_mcc';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = isset($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = isset($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = '';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date);
        }
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        $tbl_union_model = new TblUnions();
        $tbl_union_model->union_code = $union;
        return $this->render('_dashboard_grid_mccs', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'union_name' => Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetRmrdMccs() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data, 'rmrd');
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_mcc';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = isset($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = isset($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $status = !empty($data['widget_for']) ? $data['widget_for'] : 'rmrd';
            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = $status;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date, 'rmrd');
        }
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        $tbl_union_model = new TblUnions();
        $tbl_union_model->union_code = $union;
        return $this->render('_dashboard_grid_rmrd_mccs', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'union_name' => Yii::$app->general->getforeignkey($tbl_union_model->tblUnion, 'union_name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetBmcs() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_bmc';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = isset($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';

            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = '';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date);
        }
        $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : '0';
        $tbl_plant_model = new TblMccPlant();
        $tbl_plant_model->mcc_plant_code = $mcc;
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        return $this->render('_dashboard_grid_bmc', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'mcc' => $mcc, 'mcc_name' => Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetRmrdBmcs() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data, 'rmrd');
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_bmc';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = isset($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $status = !empty($data['widget_for']) ? $data['widget_for'] : 'rmrd';
            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = $status;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date, 'rmrd');
        }
        $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : '0';
        $tbl_plant_model = new TblMccPlant();
        $tbl_plant_model->mcc_plant_code = $mcc;
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        return $this->render('_dashboard_grid_rmrd_bmc', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'mcc' => $mcc, 'mcc_name' => Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetDcs() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_dcs';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = !empty($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';

            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = '';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date);
        }
        $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : '0';
        $bmc = !empty($data['bmc_code']) ? $data['bmc_code'] : '0';
        $tbl_plant_model = new TblMccPlant();
        $tbl_plant_model->mcc_plant_code = $mcc;
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        return $this->render('_dashboard_grid_dcs', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'mcc' => $mcc, 'bmc' => $bmc, 'mcc_name' => Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetRmrdDcs() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data, 'rmrd');
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_dcs';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = !empty($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $status = !empty($data['widget_for']) ? $data['widget_for'] : 'rmrd';

            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = $status;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date, 'rmrd');
        }
        $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : '0';
        $bmc = !empty($data['bmc_code']) ? $data['bmc_code'] : '0';
        $tbl_plant_model = new TblMccPlant();
        $tbl_plant_model->mcc_plant_code = $mcc;
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        return $this->render('_dashboard_grid_rmrd_dcs', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'mcc' => $mcc, 'bmc' => $bmc, 'mcc_name' => Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'shift' => $data['shift']]);
    }

    public function actionGetRmrdVendor() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data, 'rmrd');
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_vendor';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = !empty($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $status = !empty($data['type']) ? $data['type'] : '';

            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = $status;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date, 'rmrd');
        }
        $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : '0';
        $bmc = !empty($data['bmc_code']) ? $data['bmc_code'] : '0';
        $tbl_plant_model = new TblMccPlant();
        $tbl_plant_model->mcc_plant_code = $mcc;
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        $title = ($status == 'BULKVEN' ? 'BULK Vendor' : 'VLCC Vendor');
        return $this->render('_dashboard_grid_vendor', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'mcc' => $mcc, 'bmc' => $bmc, 'mcc_name' => Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'title' => $title, 'shift' => $data['shift']]);
    }

    public function actionGetSocietyStatus() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'sp_Portal_dashboard_dcs_status';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = !empty($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $status = !empty($data['status']) ? $data['status'] : '';

            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $sp_param[] = $status;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date);
        }
        $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : '0';
        $bmc = !empty($data['bmc_code']) ? $data['bmc_code'] : '0';
        $tbl_plant_model = new TblMccPlant();
        $tbl_plant_model->mcc_plant_code = $mcc;
        $union_code = !empty($data['union_code']) ? $data['union_code'] : '0';
        $title = ($status == 'Installed' ? 'Installed Society' : ($status == 'Online' ? 'Online Society' : 'Offline Society'));
        return $this->render('_dashboard_grid_installed_dcs', ['output' => $output, 'date' => $data['date'], 'union' => $union_code, 'mcc' => $mcc, 'bmc' => $bmc, 'mcc_name' => Yii::$app->general->getforeignkey($tbl_plant_model->tblMccPlant, 'name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'title' => $title, 'shift' => $data['shift']]);
    }

    public function actionGetFarmers() {
        $output = [];
        $union = '';
        $bmc = '';
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'sp_dashboard_milk_col_farmers';

            $union = !empty($data['union_code']) ? $data['union_code'] : (!empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : '0');
            $mcc = !empty($data['mcc_code']) ? $data['mcc_code'] : (!empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : '0');
            $bmc_code = !empty($data['bmc_code']) ? $data['bmc_code'] : (!empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : '0');
            $dcs = isset($data['dcs_code']) ? $data['dcs_code'] : (!empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : '0');
            $plant = isset($data['plant']) ? $data['plant'] : (!empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : '0');
//            $date = date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';
            $date = !empty($data['shift']) ? Yii::$app->general->getShiftWithDate($data['shift'], $data['date']) : date('Y-m-d', strtotime($data['date'])) . ' 00:00:00';

            $sp_param[] = $union;
            $sp_param[] = $plant;
            $sp_param[] = $mcc;
            $sp_param[] = $bmc_code;
            $sp_param[] = $dcs;
            $sp_param[] = is_array($date) ? $date['from_date'] : $date;
            $sp_param[] = is_array($date) ? $date['to_date'] : $date;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

            $blocks_data = $this->getFarmerBlockStatus($union, $plant, $mcc, $bmc_code, $dcs, $date);
        }
        $dcs = !empty($data['dcs_code']) ? $data['dcs_code'] : '0';
        $tbl_dcs_model = new TblDcs();
        $tbl_dcs_model->dcs_code = $dcs;
        return $this->render('_dashboard_grid_farmers', ['output' => $output, 'date' => $data['date'], 'dcs_name' => Yii::$app->general->getforeignkey($tbl_dcs_model->tblDcs, 'dcs_name'), 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title, 'union' => $union, 'shift' => $data['shift']]);
    }

    public function getFarmerBlockStatus($union = '0', $plant = '0', $mcc = '0', $bmc_code = '0', $dcs = '0', $date = '0', $widget_for = 'farmer') {

        $sp_param = [];
        $sp_param[] = $union;
        $sp_param[] = $plant;
        $sp_param[] = $mcc;
        $sp_param[] = $bmc_code;
        $sp_param[] = $dcs;
        $sp_param[] = is_array($date) ? $date['from_date'] : $date;
        $sp_param[] = is_array($date) ? $date['to_date'] : $date;
        $sp_name = 'sp_portal_dashboard_farmer_status';
        $farmer_status = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $sp_name = 'sp_portal_dashboard_farmer_rmrd_blocks';
        $sp_param[] = $widget_for;
        $farmer_blocks = \Yii::$app->general->getSpData($sp_name, $sp_param);
        return [$farmer_status, $farmer_blocks];
// sp_portal_dashboard_farmer_status
    }

    public function actionLoadDashboardMilkAnalysis() {
        $fromShift = '';
        $toShift = '';
        $fromDate = '';
        $toDate = '';
        $postData = Yii::$app->request->post();
        if (!empty($postData['Dashboard']['date'])) {
            $fromDate = $postData['Dashboard']['date'];
            $toDate = $postData['Dashboard']['date'];
        } elseif (!empty($postData['Dashboard']['from_date'])) {
            $fromDate = $postData['Dashboard']['from_date'];
            $toDate = $postData['Dashboard']['to_date'];
        }
        if (!empty($postData['Dashboard']['shift'])) {
            $fromShift = $postData['Dashboard']['shift'];
            $toShift = $postData['Dashboard']['shift'];
        } elseif (!empty($postData['Dashboard']['mag_from_shift'])) {
            $fromShift = $postData['Dashboard']['mag_from_shift'];
            $toShift = $postData['Dashboard']['mag_to_shift'];
        }
        $sp = $postData['sp'];
        $results = $this->getSpResult($sp);
        $res = [];
        $array_result = $results;
        if (count($results) > 1) {
            $array_result = $results;
        }
        foreach ($array_result as $key => $value) {
            $res[$key] = $value;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $res, 'fromDate' => $fromDate, 'toDate' => $toDate, 'fromShift' => $fromShift, 'toShift' => $toShift];
    }

    public function actionHelpManual() {
        $path = Yii::getAlias('@webroot') . '/web/docs/user_manual.pdf';
        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path, 'user_manual.pdf');
        }
    }

    public function actionPrivacyPolicy() {
        $this->layout = false;
        return $this->render('privacy_policy');
    }

    public function actionTerms() {
        $this->layout = false;
        return $this->render('terms');
    }

    public function actionLoadDashboardMilkCollectionSummary() {
        $sp = Yii::$app->request->post('sp');
        $results = $this->getSpResult($sp);
        $res = [];
        $array_result = $results[0];
        if (count($results) > 1) {
            $array_result = $results;
        }
        foreach ($array_result as $key => $value) {
            $res[$key] = $value;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $res];
    }

    public function actionLoadPieChart() {
        $sp = Yii::$app->request->post('sp');
        $results = $this->getSpResult($sp);
        $series = [];
        $series['onlineDcs'] = 0;
        $series['offlineDcs'] = 0;
        $labels = [];

        if (!empty($results)) {
            $keys = array_keys($results[0]);
            foreach ($keys as $key) {
                if (in_array($key, ['onlineDcs', 'offlineDcs'])) {
                    $series[$key] = $results[0][$key]; //array_column($results, $key);
                }
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $series, 'lbl' => $labels];
    }

    public function actionSchemaRefresh() {
        Yii::$app->db->schema->refresh();
        echo 'Schema Refreshed';
    }

    public function actionGetInstalledMpps() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_installed_mpp';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_mpp', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetInstalledMembers() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_installed_member';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_member', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetInstalledEmployees() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_installed_employee';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_employee', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetInstalledSupervisors() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_installed_supervisor';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_supervisor', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetInstalledManagers() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_installed_az_manager';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_manager', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetInstalledOtherStaff() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_installed_other_Staff';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_other_Staff', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetNotInstalledMpps() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_not_installed_mpp';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_not_installed_mpp', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetNotInstalledMembers() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_not_installed_member';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_not_installed_member', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetNotInstalledEmployees() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_not_installed_employee';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_not_installed_employee', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetNotInstalledSupervisors() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_not_installed_supervisor';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_not_installed_supervisor', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetNotInstalledManagers() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_not_installed_az_manager';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_not_installed_manager', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionGetNotInstalledOtherStaff() {
        $output = [];
        if (!empty($_GET)) {
            $data = $_GET;
            $breadcrum_title = $this->setBreadcrums($data);
            $sp_param = [];
            $sp_name = 'proc_mobile_user_count_list';
            $sp_param[] = 0;
            $sp_param[] = 'total_app_not_installed_other_Staff';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $blocks_data = $this->getMobileBlockStatus([1, '']);
        }
        return $this->render('_dashboard_grid_not_installed_other_Staff', ['output' => $output, 'date' => $data['date'], 'blocks_data' => $blocks_data, 'breadcrum_title' => $breadcrum_title]);
    }

    public function actionLoadDashboardMobileData() {
        $sp_name = 'proc_mobile_user_count_list';
        $sp_param[] = 1;
        $sp_param[] = '';
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $res = [];
        foreach ($results[0] as $key => $value) {
            $res[$key] = $value;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $res];
    }

    public function getMobileBlockStatus($sp_param) {
        $sp_name = 'proc_mobile_user_count_list';
        $mobile_status = \Yii::$app->general->getSpData($sp_name, $sp_param);
        return [$mobile_status];
    }

    public function actionLoadMobilePieChart() {
        $sp_name = 'proc_mobile_user_count_and_list';
        $sp_param = [];
        $sp_param[] = 1;
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $series = [];
        if (!empty($results)) {
            $series = [];
            foreach ($results as $result) {
                $widgetKey = $result['key'];

                if (!isset($series[$widgetKey])) {
                    $series[$widgetKey] = [];
                }
                $series[$widgetKey][] = $result;
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'series' => $series];
    }

    public function actionLoadTemperatureData() {
        $date = date('Y-m-d');
        if (!empty(Yii::$app->request->post('Dashboard')['date'])) {
            $date = Yii::$app->request->post('Dashboard')['date'];
            $date = date('Y-m-d', strtotime($date));
        }
        $results = \Yii::$app->general->getSpData('sp_get_iot_temperature_data', [$date]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'results' => $results];
    }

    public function actionMccWiseIndentSummary() {
        $output = [];
        $union = 0;
        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'mis_mcc_wise_indent_summary';
        if (!empty(Yii::$app->request->post('union'))) {
            $union = Yii::$app->request->post('union');
        }
        $mcc = !empty($rlsData['mcc']) ? ',' . $rlsData['mcc'] . ',' : 0;
        if (!empty(Yii::$app->request->post('mcc'))) {
            $mcc = Yii::$app->request->post('mcc');
        }
        $date = Yii::$app->request->post('Dashboard')['date'];
        $date = date('Y-m-d', strtotime($date));
        $sp_param[] = $union;
        $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
        $sp_param[] = $mcc;
        $sp_param[] = is_array($date) ? $date['from_date'] : $date;
        $sp_param[] = is_array($date) ? $date['to_date'] : $date;
        $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $table = $this->renderAjax('_mcc_wise_indent_summary', ['output' => $output]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'output' => $output, 'mcc_wise_indent_summary' => $table];
    }

    public function actionComplainSummaryDashboard() {

        $sp_name = 'proc_complain_dashboard_list';
        $sp = 'proc_complain_dashboard';
        $results = \Yii::$app->general->getSpData($sp_name, []);
        $res = \Yii::$app->general->getSpData($sp, []);
        $series = [];
        if (!empty($results)) {
            $series = [];
            foreach ($results as $result) {
                $widgetKey = $result['cdate'];

                if (!isset($series[$widgetKey])) {
                    $series[$widgetKey] = [];
                }
                $series[$widgetKey] = $result;
            }
        }
        $tableHtml = $this->renderAjax('_complain_summary_table.php', ['results' => $results]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'series' => $series, 'res' => $res[0], 'tableHtml' => $tableHtml];
    }

    public function actionMergeWeightQualityData() {
        $sp_name = 'process_weight_quality_merge_data';
        \Yii::$app->general->getSpData($sp_name, [], TRUE);
    }
    
    public function actionPlantIntransitTankerMilkDetail() {
        $output = [];
        $union = 0;
        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'sp_portal_dashboard_plant_intransit_tanker_milk_detail';
        $data_type = !empty(Yii::$app->request->post('data_type')) ? Yii::$app->request->post('data_type') : '0';
        if (!empty(Yii::$app->request->post('union'))) {
            $union = Yii::$app->request->post('union');
        }
        $date = Yii::$app->request->post('Dashboard')['date'];
        $date = date('Y-m-d', strtotime($date));
        $sp_param[] = $data_type;
        $sp_param[] = $union;
        $sp_param[] = empty($rlsData['plant']) ? '' : $rlsData['plant'];
        $sp_param[] = is_array($date) ? $date['from_date'] : $date . ' 00:00:00';
        $sp_param[] = is_array($date) ? $date['to_date'] : $date . ' 23:59:00';
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $table = $this->renderAjax('_intransit_tanker_milk_detail', ['results' => $results]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $results[0], 'intransit_tanker_milk_detail' => $table];
    }
    
    public function actionIntransitTankerStatusDetail() {
        $output = [];
        $union = 0;
        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'sp_portal_dashboard_plant_intransit_tanker_status_detail';
        $data_type = !empty(Yii::$app->request->post('data_type')) ? Yii::$app->request->post('data_type') : '0';
        if (!empty(Yii::$app->request->post('union'))) {
            $union = Yii::$app->request->post('union');
        }
        $date = Yii::$app->request->post('Dashboard')['date'];
        $date = date('Y-m-d', strtotime($date));
        $sp_param[] = $data_type;
        $sp_param[] = $union;
        $sp_param[] = empty($rlsData['plant']) ? '' : $rlsData['plant'];
        $sp_param[] = is_array($date) ? $date['from_date'] : $date . ' 00:00:00';
        $sp_param[] = is_array($date) ? $date['to_date'] : $date . ' 23:59:00';
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $table = $this->renderAjax('_intransit_tanker_status_detail', ['results' => $results]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'res' => $results[0], 'intransit_tanker_status_detail' => $table];
    }
    
    public function actionPlantWiseTankerStatus() {
        $output = [];
        $union = 0;
        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'sp_portal_dashboard_plant_wise_tanker_status';
        if (!empty(Yii::$app->request->post('union'))) {
            $union = Yii::$app->request->post('union');
        }
        $data_type = !empty(Yii::$app->request->post('data_type')) ? Yii::$app->request->post('data_type') : '0';
        $date = Yii::$app->request->post('Dashboard')['date'];
        $date = date('Y-m-d', strtotime($date));
        $sp_param[] = $union;
        $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
        $sp_param[] = is_array($date) ? $date['from_date'] : $date . ' 00:00:00';
        $sp_param[] = is_array($date) ? $date['to_date'] : $date . ' 23:59:00';
        $sp_param[] = $data_type;
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        
        $table = $this->renderAjax('_plant_wise_tanker_status', ['results' => $results]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'output' => $output, 'plant_wise_tanker_status' => $table];
    }
    
    public function actionPlantWiseTankerMilkDetail() {
        $output = [];
        $union = 0;
        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'sp_portal_dashboard_plant_wise_tanker_milk_detail';
        if (!empty(Yii::$app->request->post('union'))) {
            $union = Yii::$app->request->post('union');
        }
        $data_type = !empty(Yii::$app->request->post('data_type')) ? Yii::$app->request->post('data_type') : '0';
        $date = Yii::$app->request->post('Dashboard')['date'];
        $date = date('Y-m-d', strtotime($date));
        $sp_param[] = $union;
        $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
        $sp_param[] = is_array($date) ? $date['from_date'] : $date . ' 00:00:00';
        $sp_param[] = is_array($date) ? $date['to_date'] : $date . ' 23:59:00';
        $sp_param[] = $data_type;
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        
        $table = $this->renderAjax('_plant_wise_tanker_milk_detail', ['results' => $results]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'output' => $output, 'plant_wise_tanker_milk_detail' => $table];
    }
    
    public function actionPlantTankerCapacityWiseTankerStatus() {
        $output = [];
        $union = 0;
        $sp_param = [];
        $rlsData = $this->setRlsData();
        $sp_name = 'sp_portal_dashboard_plant_tanker_capacity_wise_tanker_status';
        if (!empty(Yii::$app->request->post('union'))) {
            $union = Yii::$app->request->post('union');
        }
        $data_type = !empty(Yii::$app->request->post('data_type')) ? Yii::$app->request->post('data_type') : '0';
        $date = Yii::$app->request->post('Dashboard')['date'];
        $date = date('Y-m-d', strtotime($date));
        $sp_param[] = $union;
        $sp_param[] = empty($rlsData['plant']) ? '0' : $rlsData['plant'];
        $sp_param[] = is_array($date) ? $date['from_date'] : $date . ' 00:00:00';
        $sp_param[] = is_array($date) ? $date['to_date'] : $date . ' 23:59:00';
        $sp_param[] = $data_type;
        $results = \Yii::$app->general->getSpData($sp_name, $sp_param);
        
        $table = $this->renderAjax('_plant_tanker_capacity_wise_tanker_status', ['results' => $results]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'output' => $output, 'plant_tanker_capacity_wise_tanker_status' => $table];
    }

}
