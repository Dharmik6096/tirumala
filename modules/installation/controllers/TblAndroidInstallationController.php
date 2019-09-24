<?php

namespace app\modules\installation\controllers;

use Yii;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\installation\models\TblAndroidInstallationDetails;
use app\modules\installation\models\TblAndroidInstallationDetailsSearch;
use yii\helpers\Json;
use app\components\WebApi;
use yii\helpers\Url;

/**
 * TblAndroidInstallationController implements the CRUD actions for TblAndroidInstallation model.
 */
class TblAndroidInstallationController extends \app\controllers\ChildController {

    public $freeAccessActions = ['device-list'];

    /**
     * Lists all TblAndroidInstallation models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAndroidInstallationDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAndroidInstallation model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $bsearchModel = new TblAndroidInstallationDetailsSearch();
        $bsearchModel->android_installation_id = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bsearchModel' => $bsearchModel,
                    'bdataProvider' => $bdataProvider,
        ]);
    }

    /**
     * Creates a new TblAndroidInstallation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAndroidInstallation();
        $this->model->scenario = 'create_portal';
        $this->viewFile = 'create';
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $code = $data['TblAndroidInstallation']['dcs_code'];
            $existData = $this->model->find()->where(['organization_code' => $code, 'organization_type' => 'VLC'])->one();
            $instDetail = new TblAndroidInstallationDetails();
            $master = [];
            if (empty($existData)) {
                $model = new TblAndroidInstallation();
                $model->scenario = 'create_portal';
                $model->load(Yii::$app->request->post());
                $model->android_installation_id = $model->getCode();
                $model->organization_code = $code;
                $model->organization_type = 'VLC';
                $master[] = $model;
            } else {
                $existDetailData = $instDetail->getExistData($existData->android_installation_id);
                if (!empty($existDetailData)) {
                    foreach ($existDetailData as $detail) {
                        $detail->is_active = 0;
                        $master[] = $detail;
                    }
                }
            }
            $this->model->organization_code = $code;
            $this->model->organization_type = 'VLC';
            $mobile = Yii::$app->general->getforeignkey($this->model->defaultContactDetail, 'mobile_no');
            $instDetail->android_installation_id = !empty($existData) ? $existData->android_installation_id : $model->android_installation_id;
            $instDetail->mobile_no = $mobile;
            $instDetail->otp_code = 1234;
            $instDetail->hash_key = Yii::$app->security->generateRandomString(20);
            $instDetail->is_active = 1;
            $instDetail->is_expired = 0;
            $instDetail->device_id = '';
            $instDetail->sync_key = rand(1000, 9999);
            $instDetail->sync_active = 1;
            $instDetail->imei_no = '';
            $instDetail->db_version = $data['TblAndroidInstallation']['db_version'];
            $file = $this->model->organization_type . '_' . $this->model->organization_code . '_' . date('Y.m.d_H.i.s');
            $instDetail->db_path = '/installation-identity/' . $file . '.zip';
            $instDetail->installation_type = 1;
            $master[] = $instDetail;
            $transaction = $this->generalModel->saveTransaction($master, ['AMCS Installation', 'create']);
            if ($transaction == 'customRedirect') {
                if (!$this->generateIdentity($file, $instDetail->hash_key)) {
                    $instDetail->delete(FALSE);
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Your transaction is not saved successfully']);
                    return $this->customRender();
                }
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblAndroidInstallation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->android_installation_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblAndroidInstallation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAndroidInstallation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblAndroidInstallation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAndroidInstallation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeviceList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if (!empty($parents[0]) && !empty($parents[1])) {
                    $device = new TblAndroidInstallationDetails();
                    $rows = $device->getActiveDeviceData($parents[0], $parents[1]);
                    foreach ($rows as $value) {
                        $out[] = array('id' => $value->device_id, 'name' => $value->device_id);
                    }
                    echo Json::encode(['output' => $out, 'selected' => '']);
                    return;
                }
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function generateIdentity($file, $token) {
        $fileName = $file . '.db';
        $dbFilePath = Yii::$app->basePath . '/installation-identity/';
        $FolderPath = Yii::$app->basePath . '/installation-identity/' . $file . '/';
        $zipfolder = Yii::$app->basePath . '/installation-identity/' . $file;
        if (!is_dir($FolderPath)) {
            $oldmask = umask(0);
            mkdir($FolderPath, 0777, TRUE);
            umask($oldmask);
        } else {
            $files = glob($FolderPath . '*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file))
                    unlink($file); // delete file
            }
        }
        copy($dbFilePath . $this->model->db_version, $FolderPath . $fileName);
        \Yii::$app->sqlite->_path = $FolderPath;
        \Yii::$app->sqlite->_organisation_code = $this->model->organization_code;
        \Yii::$app->sqlite->_organisation_type = $this->model->organization_type;
        \Yii::$app->sqlite->is_offline = TRUE;

        $dcs_code = $this->model->dcs_code;
        $dcs_code = !empty($dcs_code) ? '\'' . $dcs_code . '\'' : $dcs_code;
        $bmc_code = $this->model->bmc_code;
        $bmc_code = !empty($bmc_code) ? '\'' . $bmc_code . '\'' : $bmc_code;
        $mcc_plant_code = $this->model->mcc_plant_code;
        $mcc_plant_code = !empty($mcc_plant_code) ? '\'' . $mcc_plant_code . '\'' : $mcc_plant_code;
        $plant_code = $this->model->plant_code;
        $plant_code = !empty($plant_code) ? '\'' . $plant_code . '\'' : $plant_code;
        $dbFileName = $file . '/' . $fileName;
        $response = \Yii::$app->sqlite->createSqlFileDcs($dbFileName, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code, $this->model->organization_code, $this->model->organization_type, $this->model->union_code);
        if ($response) {
            $body = [];
            $body['deviceId'] = '';
            $body['eiplCode'] = Yii::$app->params['eipl_code'];
            $body['imeiNo'] = '';
            $body['latLong'] = '';
            $body['versionNo'] = '';
            $body = json_encode($body);
            $api = new WebApi();
            $api->serverUrl = Yii::$app->params['client_url'];
            $api->apiurl = 'webservice/eipl/v1/eipl-app/verify-identity';
            $api->authentication = FALSE;
            $api->vendor_code = Yii::$app->params['eipl_code'];
            $api->body = $body;
            $api->return_actual = TRUE;
            $result = $api->POSTDATA();
            $response = json_decode($result);
            if (!empty($response) && !empty($response->data) && !empty($response->statusCode) && $response->statusCode == 200) {
                $myfile = fopen($FolderPath . 'client.json', 'w');
                fwrite($myfile, $result);
                fclose($myfile);

                $body = [];
                $body['deviceId'] = '';
                $body['eiplCode'] = Yii::$app->params['eipl_code'];
                $body['imei'] = '';
                $body['latLong'] = '';
                $body['versionNo'] = '';
                $body['content'] = [];
                $body['identityCode'] = '';
                $body['organizationCode'] = $this->model->organization_code;
                $body['organizationType'] = $this->model->organization_type;
                $body['token'] = $token;
                $body = json_encode($body);

                $api = new WebApi();
                $api->serverUrl = Url::base(true) . '/';
                $api->apiurl = 'androiddpu/v2/android-dpu/start-up';
                $api->authentication = FALSE;
                $api->vendor_code = Yii::$app->params['eipl_code'];
                $api->body = $body;
                $api->return_actual = TRUE;
                $result = $api->POSTDATA();
                $response = json_decode($result);
                if (!empty($response) && !empty($response->data) && !empty($response->status) && $response->status == 'success') {
                    $myfile = fopen($FolderPath . 'pref.json', 'w');
                    fwrite($myfile, $result);
                    fclose($myfile);

                    $myfile = fopen($FolderPath . 'eipl.txt', 'w');
                    fwrite($myfile, $token);
                    fclose($myfile);
                    $pass = 'EI' . $this->model->organization_code . 'PL';
                    Yii::$app->general->ZipOperation($zipfolder, TRUE, '', $pass, '*', 'zip');
                    $files = glob($zipfolder . '/*'); // get all file names
                    foreach ($files as $file) { // iterate files
                        if (is_file($file))
                            unlink($file); // delete file
                    }
                    rmdir($zipfolder);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function actionDownload($id) {
        if (file_exists($id) && \Yii::$app->response->sendFile(($id))) {
            
        } else {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'error',
                'message' => Yii::t('app', 'File not available.'),
            ]);
            return $this->redirect(['index']);
        }
    }

}
