<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblUnionsSearch;
use app\modules\organisation\models\TblUnionsHistory;
use app\modules\organisation\models\TblUnionsDistrictMapping;
use app\modules\organisation\models\TblUnionsDistrictMappingHistory;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\controllers\ChildController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblUnionsController implements the CRUD actions for TblUnions model.
 */
class TblUnionsController extends ChildController {

    public $bankDetails;
    public $contactDetails;

    /**
     * @inheritdoc
     */

    /**
     * Lists all TblUnions models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TblUnions();
        $searchModel = new TblUnionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblUnions model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $bsearchModel = new TblBankDetailsSearch();
        $bsearchModel->module_name = 'union';
        $bsearchModel->module_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'union';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblUnions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblUnions();
        $this->viewFile = 'create';
        $this->bankDetails = new TblBankDetails();
        $this->contactDetails = new TblContactDetails();
        $this->model->valid_from = date('Y-m-d');
        $this->contactDetails->scenario = 'additional';
        $validate = 1;

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->federation_code = Yii::$app->session->get('Federations');
            $this->model->union_code = $this->model->getCode();
            $this->setModel($this->model);
            $mapping = [];
            $this->bankDetails->load(Yii::$app->request->post());
            if (!empty($this->bankDetails->bank_code)) {
                $this->bankDetails->setModel('union', $this->model->union_code);
                $this->bankDetails->scenario = 'bank_selected';
                array_push($mapping, $this->bankDetails);
            }
            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('union', $this->model->union_code);
            array_push($mapping, $this->contactDetails);
            // set mapping table
            $modelMapping = new TblUnionsDistrictMapping();
            $this->setMapping($modelMapping);
            array_push($mapping, $modelMapping);
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'union_name', $this->model->union_name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model], $mapping, ['union', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblUnions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $oldDistrict = $this->model->district_code;
        $validate = 1;
        if (Yii::$app->request->post()) {
            $historyModel = new TblUnionsHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);

            //set mapping model
            $mappingList = [];

            if (!$this->model->getCheckDcsExist()) {
                if ($oldDistrict != $this->model->district_code) {
                    $oldModel = TblUnionsDistrictMapping::find()->where(['union_code' => $this->model->union_code, 'district_code' => $oldDistrict])->one();
                    if ($oldModel) {
                        $mappingHistory = new TblUnionsDistrictMappingHistory();
                        Yii::$app->operation->history($oldModel, $mappingHistory, DELETE);
                        array_push($mappingList, $mappingHistory);
                        array_push($mappingList, $oldModel);
                    }
                    $modelMapping = new TblUnionsDistrictMapping();
                    $this->setMapping($modelMapping);
                    array_push($mappingList, $modelMapping);
                }
            }

            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'union_name', $_POST['TblUnions']['union_name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $mappingList, ['union', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblUnions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_unions', 'tbl_union_district', 'tbl_union_district_history', Yii::$app->request->post('id'), 'union_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblUnionsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
//            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], ['TblUnionsDistrictMapping', 'TblUnionsDistrictMappingHistory'], 'union_code');
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], ['TblUnionsDistrictMapping', 'TblUnionsDistrictMappingHistory', 'TblContactDetails', 'TblContactDetailsHistory', 'TblBankDetails', 'TblBankDetailsHistory'], ['union_code', 'union']);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblUnions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblUnions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblUnions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'bankDetails' => $this->bankDetails,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    /**
     * Description: return branch array
     * By: Dhara
     * DAte: 8-11-2016
     * @return type
     */
    public function actionUnionList() {
        $out = NULL;
        if (isset($_POST['depdrop_parents'])) {

            $cnt = 0;
            foreach ($_POST as $key => $val) {
                if ($cnt == 0) {
                    $cnt++;
                    continue;
                }
                $data = explode(',', $key);
            }
            $code = str_replace("'", '', $key);
            $value = $_POST['depdrop_parents'];
            $subcenter = new TblUnions();
            $list = $subcenter->getUnions($value[0], $code);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        echo \yii\helpers\Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMapDistricts($id) {
        $model = new TblUnionsDistrictMapping();
        $modelUnion = $this->findModel($id);
        $values = $model->getDistrict($modelUnion);
        if (Yii::$app->request->post()) {
            $district_code = Yii::$app->request->post('TblUnionsDistrictMapping')['district_code'];
            $postData = array_filter($district_code);
            if (empty($postData)) {
                $model->addError('union_code', 'Please select at lease one district.');
                $district_code = [];
                return $this->render('_map_districts', [
                            'model' => $model, 'district_list' => $values['district_list'], 'selected' => $district_code, 'modelUnion' => $modelUnion, 'defaultValue' => $modelUnion->district_code
                ]);
            }

            $oldAssignments = array_keys(ArrayHelper::map($model->find()->where(['union_code' => $id, 'is_active' => 1])->all(), 'district_code', 'district_code'));
            $newAssignments = Yii::$app->general->array_flatten($postData);
            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_diff($oldAssignments, $newAssignments);
            $record = $this->generalModel->mappingTransaction($toRevoke, $toAssign, ['TblUnionsDistrictMapping', 'TblUnionsDistrictMappingHistory'], ['union_code', 'district_code'], $id);

            if ($record) {
                Yii::$app->display->message(true, 'union district mapping', 'edit');
                return $this->redirect(['index']);
            }
        }
        return $this->render('_map_districts', [
                    'model' => $model, 'district_list' => $values['district_list'], 'selected' => $values['selected'], 'modelUnion' => $modelUnion, 'defaultValue' => $modelUnion->district_code
        ]);
    }

    public function actionGetFederationUnions() {

        $finalUnions = [];
        if (!empty($_POST['fed'])) {
            $fed = explode(',', $_POST['fed']);
            $model = new TblUnions();
            foreach ($fed as $row) {
                $unions = $model->getUnions($row);
                $finalUnions = array_merge($finalUnions, $unions);
            }
        }
        echo \yii\helpers\Json::encode(['status' => 'success', 'data' => $finalUnions]);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->union_code]);
    }

    private function setModel() {
        $this->model->union_name = ucwords($this->model->union_name);
        $this->model->registration_date = ($this->model->registration_date == '') ? null : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
        $this->model->contact_person_pan_no = strtoupper($this->model->contact_person_pan_no);
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);

        if (!empty(Yii::$app->request->post('file_name'))) {
            $file_name = Yii::$app->request->post('file_name');
            $base_path = Yii::getAlias('@webroot');//	 Yii::$app->basePath;
            $base_url = Yii::$app->urlManager->createAbsoluteUrl('');
            $logo_path = Yii::$app->params['logo_path'];
            $file = $base_path . Yii::$app->params['temp_logo_path'] . $file_name;
            $path = $base_path . $logo_path;

            Yii::$app->general->checkDirectory($path);

            if (!empty($this->model->logo)) {
                $exist_logo = $base_path . str_replace($base_url, '', $this->model->logo);
                if (file_exists($exist_logo)) {
                    unlink($exist_logo);
                }
            }
            $logo_name = 'logo_UNION_' . $this->model->union_code . '.' . explode('.', $file_name)[1];
            $upload = copy($file, $path . $logo_name);
            if ($upload) {
                if (file_exists($file)) {
                    unlink($file);
                }
                $this->model->logo = $base_url . $logo_path . $logo_name;
            }
        }
    }

    private function setMapping(&$modelMapping) {
        $modelMapping->union_code = $this->model->union_code;
        $modelMapping->district_code = $this->model->district_code;
        $modelMapping->is_active = 1;
    }

    public function actionDistrictList() {
        $out = null;
        $selected = '';
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $stateCode = $value[1];
            $district = new TblUnionsDistrictMapping();
            $list = $district->getDistrictArray($unionCode, $stateCode);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
            return;
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionBankDetails($id) {
        $bankDetails = new TblBankDetails();
        $bankDetails->scenario = 'additional';
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name = 'union';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelUnion = $this->findModel($id);
        return $this->render('../../../details/views/tbl-bank-details/create', [
                    'model' => $bankDetails,
                    'id' => $id,
                    'module' => 'union',
                    'dist' => $modelUnion->district_code,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dist_field' => 'tblunions-district_code'
        ]);
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'union';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'union',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionUploadImg() {

        // $path = Yii::$app->basePath . Yii::$app->params['temp_logo_path'];
$path = Yii::getAlias('@webroot') . Yii::$app->params['temp_logo_path'];        
if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        $file = \yii\web\UploadedFile::getInstanceByName('file');
        $name = 'logo_' . Yii::$app->session->get('organizations_type') . Yii::$app->session->get('organizations_code') . '.' . $file->extension;
//        $name = 'logo_' . Yii::$app->session->get('organizations_type') . '_' . '002' . '.' . $file->extension;
        if ($file->saveAs($path . $name)) {
            chmod($path . $name, 0777);
            return $name; //echo $name;
        }
    }

}
