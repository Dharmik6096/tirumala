<?php

namespace app\modules\general\controllers;

use Yii;
use app\modules\general\models\TblBanner;
use app\modules\general\models\TblBannerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\general\models\TblBannerApplicability;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\document\models\TblAttachment;
use yii\widgets\ActiveForm;
use app\modules\general\models\TblBannerApplicabilitySearch;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblBannerHistory;
use app\modules\document\models\TblAttachmentHistory;
use app\modules\general\models\TblBannerApplicabilityHistory;
use app\modules\general\models\TblDepartment;
use app\modules\usermanagement\models\TblEiplAppMenuActionsMapping;

/**
 * TblBannerController implements the CRUD actions for TblBanner model.
 */
class TblBannerController extends \app\controllers\ChildController {

    public $freeAccessActions = ['tap-event-list'];

    /**
     * Lists all TblBanner models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBanner model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblBannerApplicabilitySearch();
        $searchModel->banner_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $banner_attachment = new TblAttachment();
        $attachmentDataProvider = new ActiveDataProvider([
            'query' => $banner_attachment->find()->where(['module_code' => (string) $id, 'module_name' => 'tbl_banner']),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'banner_attachment' => $banner_attachment,
                    'attachmentDataProvider' => $attachmentDataProvider,
        ]);
    }

    /**
     * Creates a new TblBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBanner();
        $attachment = new TblAttachment();
        $this->viewFile = 'create';
        $saveModel = [];
        if (Yii::$app->request->post()) {

            $this->model->load(Yii::$app->request->post());

            $this->model->from_date = !empty($this->model->from_date) ? date('Y-m-d', strtotime($this->model->from_date)) : '';
            $this->model->to_date = !empty($this->model->to_date) ? date('Y-m-d', strtotime($this->model->to_date)) : '';
            $this->model->banner_for = 'mobile_app';

            $saveModel[] = $this->model;

            if ($this->model->validate() && empty($this->model->getErrors())) {
                $attachment_file = Yii::$app->request->post()['attachment'];
                if (!empty($attachment_file)) {
                    $attachment = new TblAttachment();
                    $attachment->load(Yii::$app->request->post());
                    $attachment->module_name = 'tbl_banner';
                    $ext = (explode(".", $attachment_file));
                    $file = Yii::$app->urlManager->createAbsoluteUrl('') . Yii::$app->params['banner_upload'] . $attachment_file;
                    $attachment->attachment = $file;
                    $attachment->file_name = $attachment_file;
                    $attachment->attachment_type = $ext[1];
                    $attachment->remarks = $this->model->title;
                    $saveModel[] = $attachment;
                    $auto_key_config['TblAttachment'][] = ['self_key' => 'module_code', 'parent_key' => 'banner_code', 'parent_index' => 0];
                }

                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['Banner', 'create'], $auto_key_config);

                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $err = [];
                foreach ($this->model->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                return Json::encode($err);
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'attachment' => $attachment,
        ]);
    }

    /**
     * Updates an existing TblBanner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->banner_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblBanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $saveModel = [];
        $deleteModel = [];

        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblBannerHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $saveModel[] = $historyModel;
        $deleteModel[] = $this->model;

        $banner_applicability_data = TblBannerApplicability::find()->where(['banner_code' => $this->model->banner_code])->all();
        foreach ($banner_applicability_data as $key => $id) {
            $bannerApplicabilityHistory = new TblBannerApplicabilityHistory();
            Yii::$app->operation->history($id, $bannerApplicabilityHistory, DELETE);
            $deleteModel[] = $banner_applicability_data[$key];
            $saveModel[] = $bannerApplicabilityHistory;
        }

        $attachment_data = TblAttachment::find()->where(['module_code' => (string) $this->model->banner_code, 'module_name' => 'tbl_banner'])->one();
        if (!empty($attachment_data)) {
            $attachmentHistoryModel = new TblAttachmentHistory();
            Yii::$app->operation->history($attachment_data, $attachmentHistoryModel, DELETE);
            $saveModel[] = $attachmentHistoryModel;
            $deleteModel[] = $attachment_data;
        }

        $transaction = $this->generalModel->saveDeleteTransaction([], $saveModel, $deleteModel, ['Banner', 'delete']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Record Deleted Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBannerUpload() {
        $path = Yii::$app->params['banner_upload'];
        if (!is_dir($path)) {
            Yii::$app->general->CreateDirectory($path);
        }

        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');

            if ($file) {

                $pathinfo = pathinfo($file->name);
                $extension = $pathinfo['extension'];
                if ($extension == 'mp4') {
                    $maxVideoSize = 15 * 1024 * 1024;
                    if ($file->size <= $maxVideoSize) {
                        $fn = $this->uploadFile($extension);
                        if ($file->saveAs($path . $fn)) {
                            $record = ['status' => 'success', 'msg' => $fn];
                        } else {
                            $record = ['status' => 'error', 'msg' => 'Video Not Uploaded Due to Error'];
                        }
                    } else {
                        $record = ['status' => 'error', 'msg' => 'Video size max allowed 20 MB.'];
                    }
                } else {
                    $imageSize = getimagesize($file->tempName);
                    $imageWidth = $imageSize[0];
                    $imageHeight = $imageSize[1];
                    $minHeight = 500;
                    $maxHeight = 2000;
                    $actualRatio = $imageWidth / $imageHeight;

                    if ($imageHeight >= $minHeight && $imageHeight <= $maxHeight && $actualRatio >= 1.76 && $actualRatio <= 1.80) {
                        $fn = $this->uploadFile($extension);

                        if ($file->saveAs($path . $fn)) {
                            $record = ['status' => 'success', 'msg' => $fn];
                        } else {
                            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
                        }
                    } else {
                        $record = ['status' => 'error', 'msg' => 'Image dimensions are not within the allowed range.'];
                    }
                }
            } else {
                $record = ['status' => 'error', 'msg' => 'No file or video was uploaded.'];
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File or Video Not Uploaded Due to Error'];
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
    }

    public function uploadFile($extension) {
        if (isset($extension)) {
            $user_code = Yii::$app->session->get('UserCode');
            return 'banner' . '_' . $user_code . '_' . time() . '.' . $extension;
        }
    }

    public function actionRemove() {
        $old_attachment = Yii::$app->request->post('value');

        $path = Yii::$app->params['banner_upload'];
        if (!empty($old_attachment)) {
            if (file_exists($path . $old_attachment)) {
                if ($old_attachment != Yii::$app->request->post('old_value')) {
                    unlink($path . $old_attachment);
                }
                return 1;
            }
        } else {
            return 0;
        }
    }

    public function actionTapEventList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            $login_type = $parents[0];
            if (!empty($login_type)) {
                $menuModel = new TblEiplAppMenuActionsMapping();
                $data = $menuModel->tapevent($login_type);

                foreach ($data as $key => $val) {
                    $out[] = array('id' => $val['service_url'], 'name' => $val['action_name']);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionBannerApplicability($id) {
        $cmodel = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblBannerApplicability();
        $appModel->model->banner_code = $id;
        $appModel->searchModel = new TblBannerApplicabilitySearch();
        $appModel->field_name = 'banner_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'banner applicability';
        $appModel->top_section = FALSE;
        $appModel->is_union = FALSE;
        $appModel->union_code = $cmodel->union_code;
        $appModel->bmc_field_name = 'applicable_code';
        $appModel->options = ['bmc'];
        $appModel->payment = false;
        $appModel->customer_type_wise_entry = true;
        $appModel->assignMultiData = true;
        $appModel->setModelFields = true;
        $appModel->assignMultiDataKey = 'department';
        $appModel->assignDataKey = 'applicable_code';
        $departmentModel = new TblDepartment();
        $userLoginType = $departmentModel->getActiveDepartments();
        $appModel->customer_type_list = $userLoginType;
        $appModel->customer_type_field_name = 'department';

        $appModel->assignStaticData = [
            'applicable_for' => 'BMC',
        ];

        $appModel->fields = [
            'mcc_name' => ['view' => ['grid'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                },],
            'mcc_plant_code' => ['view' => ['grid'], 'value' => 'mcc_plant_code'],
            'bmc_code' => ['view' => ['grid'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }],
            'department' => ['view' => ['grid'],
                'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->departmentId, 'department');
                }],
            'applicable_for' => ['view' => ['grid'], 'value' => 'applicable_for'],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
        ];
        $appModel->actions = [];

        return $appModel->customerTypeWiseApplicability();
    }
}
