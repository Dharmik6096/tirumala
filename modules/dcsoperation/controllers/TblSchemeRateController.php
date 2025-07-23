<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblSchemeRate;
use app\modules\dcsoperation\models\TblSchemeRateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblSchemeRateApplicability;
use app\modules\dcsoperation\models\TblSchemeRateMcc;
use app\modules\dcsoperation\models\TblSchemeRateApplicabilityHistory;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\dcsoperation\models\TblSchemeRateHistory;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use webvimark\modules\UserManagement\models\User;
use app\modules\dcsoperation\models\TblSchemeRateApplicabilitySearch;
use app\modules\dcsoperation\models\TblSchemeRateApplicabilityAlias;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * TblSchemeRateController implements the CRUD actions for TblSchemeRate model.
 */
class TblSchemeRateController extends \app\controllers\ChildController {

    /**
     * Lists all TblSchemeRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSchemeRateSearch();
        $searchModel->is_active = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeRate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $appsearchModel = new TblSchemeRateApplicabilitySearch();
        $appsearchModel->scheme_rate_code = $id;
        $appsearchModel->is_active = 0;
        $appdataProvider = $appsearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'appsearchModel' => $appsearchModel, 'appdataProvider' => $appdataProvider,
        ]);
    }

    /**
     * Creates a new TblSchemeRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSchemeRate();
        $this->viewFile = 'create';
        $saveModel = [];
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->model->from_shift);
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->model->to_shift);
            $this->model->scheme_rate_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            if ($this->model->validate()) {
                $saveModel [] = $this->model;
                if (!empty($this->model->is_mcc_wise_rate)) {
                    $i = 1;
                    $mccArray = Yii::$app->request->post()['mcc_plant_code'];
                    foreach ($mccArray as $mcc) {
                        $modelmcc = new TblSchemeRateMcc();
                        $modelmcc->scheme_rate_mcc_code = Yii::$app->general->getCodeAutoIncrement($modelmcc, $i);
                        $modelmcc->scheme_rate_code = $this->model->scheme_rate_code;
                        $modelmcc->mcc_plant_code = $mcc;
                        $modelmcc->union_code = $this->model->union_code;
                        $saveModel[] = $modelmcc;
                        $i++;
                    }
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Scheme Master', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Finds the TblSchemeRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSchemeRateApplicability($id) {
        $model = $this->findModel($id);        
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblSchemeRateApplicability();
        $customerType = new TblCustomerType();
        if ($model->is_member_rate == 1) {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        }
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->periodic_applicability = TRUE;
        $appModel->check_applicability_with_field_name = FALSE;
        $appModel->field_name = 'scheme_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'scheme rate applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
//        $appModel->options = ['dcs'];
        $appModel->model->from_date = $model->from_date;
        $appModel->model->to_date = $model->to_date;
        $appModel->model->from_shift = $model->from_shift;
        $appModel->model->to_shift = $model->to_shift;
        $appModel->model->rtpl = $model->rtpl;
        $appModel->model->rate_class = $model->rate_class;
        $appModel->model->is_member_rate = $model->is_member_rate;
        $appModel->model->description = $model->description;
        $fromDate = $model->from_date;
        $toDate = $model->to_date;
        $fromShift = $model->from_shift;
        $toShift = $model->to_shift;
        $rate = $model->rtpl;
        $appModel->header_title = ' - ' . $id . '(Rate : ' . $rate . ', ' . $fromDate . ':' . $fromShift . ' To ' . $toDate . ':' . $toShift . ') ';
        $rateMccModel = new TblSchemeRateMcc();
        $rateMcc = $rateMccModel->getRateMcc($id);

        $appModel->fields = [
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Parent Code'), 'value' => function ($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, TRUE, FALSE);
                }],
            'bmc_name' => ['view' => ['grid'], 'label' => Yii::t('app', 'Parent Name'), 'value' => function ($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, FALSE, FALSE);
                }],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function ($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                }],
            'mcc_name' => ['view' => ['grid'], 'value' => function ($model) {
                    if ($model->applicable_for == 'PLANT') {
                        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                    } else if ($model->applicable_for == 'MCC') {
                        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                    } else if ($model->applicable_for == 'BMC') {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    } else if ($model->applicable_for == 'DCS') {
                        return Yii::$app->general->getforeignkey($model->dcsName, 'dcs_name');
                    } else {
                        return ''; // Yii::$app->general->getforeignkey($model->customerMasterCode, 'customer_name');
                    }
                }],
                //'dcs_name' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],           
        ];

        $appModel->actions = ['delete' => ['option' => 'applicable_code,scheme_rate_app_code,tbl-scheme-rate/delete-applicability',
            ], 'generate_sentbox' => function ($url, $model) {
                $class = '';
                $options = ['title' => Yii::t('app', 'Export Sentbox'), 'class' => $class];
                return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/dcsoperation/tbl-scheme-rate/export-sentbox', 'id' => $model->scheme_rate_app_code], $options);
            },
            'deactive_applicability' => function ($url, $model) { {
                    $class = $model->is_active == 1 ? 'fa-close' : 'fa-check';
                    $title = 'Deactivate';
                    $url = '/dcsoperation/tbl-scheme-rate/deactivate-applicability'; //$model->is_active == 1 ? '/dcsoperation/tbl-scheme-rate/deactivate-applicability' : '/payment/tbl-payment-cycle/member-data-lock';
                    $popupClass = ' disabled ';

                    if (User::canRoute($url) && !empty($model->is_active)) {
                        $popupClass = ' generalGridConfirmationPopup ';
                    }
                    $popupWindowTitle = 'Are you sure you want to Deactivate';
                    $options = [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-original-title' => $title,
                        'data-popup-message' => $popupWindowTitle,
                        'class' => $popupClass,
                        'data-post-url' => Url::to([$url, 'id' => $model->scheme_rate_app_code])
                    ];
                    return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'id' => $model->scheme_rate_app_code], $options);
                }
            }];

        $appModel->ratechart = true;
//        $appModel->dcs_filters = ['MCC' => 'MCC', 'PLANT' => 'PLANT', 'VENDOR' => 'VENDOR'];
        $appModel->dcs_filters = $value;
        $appModel->check_wef_date = false;
        $appModel->generateMail = false;
        $appModel->rateMccCode = $rateMcc;
        $appModel->isApproval = true;
        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblSchemeRateApplicabilityHistory();
            $record = TblSchemeRateApplicability::findOne(Yii::$app->request->post('id'));
            Yii::$app->operation->history($record, $detailHistory, DELETE);
            $master[] = $detailHistory->save(FALSE);
            $master[] = $record->delete();
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            } else {
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
            }
        } catch (UserException $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionExportSentbox($id) {
        $appModel = new TblSchemeRateApplicability();
        $model = $appModel->find()->where(['scheme_rate_app_code' => $id])->one();
        ob_clean();
        $operation = !empty($model->updated_at) ? 'UPDATE' : 'INSERT';
        $sentbox = $model->sentboxModel($id, 'VLC');
        $sentboxData = $sentbox->setSentboxDownload($model, $operation);
        $jsonData = Json::encode($sentbox->jsonModel($sentboxData), JSON_UNESCAPED_UNICODE);
        $extention = 'txt';
        $header = [
            'mime' => 'text/plain',
            'extension' => $extention,
            'writer' => 'Excel2007',
        ];

        $labelT = $model->applicable_code . '-schemerate-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'];
        header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');

        $key = Yii::$app->general->SetSecurityEncryptionKey('UNION', $model->union_code);
        Yii::$app->encrypter->setGlobalPassword($key);
        echo Yii::$app->general->encryptData($jsonData) . PHP_EOL;
        exit();
    }

    public function actionDeactivate($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblSchemeRateHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $deleteModel = [];
        $saveModel[] = $historyModel;
        if ($this->model->is_active == 1) {
            $this->model->is_active = 0;
            $saveModel[] = $this->model;
            $existData = TblSchemeRateApplicability::find()->where(['scheme_rate_code' => $id])->all();
            if (!empty($existData)) {
                foreach ($existData as $applicability) {
                    $historyModelApp = new TblSchemeRateApplicabilityHistory();
                    Yii::$app->operation->history($applicability, $historyModelApp, 'UPDATE');
                    $saveModel[] = $historyModelApp;
                    $applicability->is_active = 0;
                    $saveModel[] = $applicability;
                }
            }
        }

        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Scheme Rate', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Scheme Rate Dectivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Scheme Rate Not Dectivated.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDeactivateApplicability($id) {
        $saveModel = [];
        $deleteModel = [];
        $applicability = TblSchemeRateApplicability::find()->where(['scheme_rate_app_code' => $id])->one();
        if (!empty($applicability)) {
            $historyModelApp = new TblSchemeRateApplicabilityHistory();
            Yii::$app->operation->history($applicability, $historyModelApp, 'UPDATE');
            $saveModel[] = $historyModelApp;
            $applicability->is_active = 0;
            $saveModel[] = $applicability;
        }

        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Scheme Rate App', 'edit']);
//        return $this->redirect(['/dcsoperation/tbl-scheme-rate/scheme-rate-applicability', 'id' => 1]);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Scheme Rate Applicability Dectivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Scheme Rate Applicability Not Dectivated.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDeactiveIndex() {
        $searchModel = new TblSchemeRateSearch();
        $searchModel->is_active = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
}
