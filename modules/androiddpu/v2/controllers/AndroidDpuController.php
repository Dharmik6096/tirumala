<?php

namespace app\modules\androiddpu\v2\controllers;

use yii\web\Controller;
//use app\modules\vendorapi\controllers\RestController;
use Yii;
use ReflectionClass;
use DateTime;
use app\modules\vendorapi\models\TblVendorApiData;
use webvimark\modules\UserManagement\models\User;
use app\modules\vendorapi\Vendorapi;
use app\models\TblUserOrganizationMapping;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationDetails;
use app\modules\androiddpu\controllers\RestController;
use app\modules\organisation\models\TblMccPlant;

/**
 * Default controller for the `vendorapi` module
 */
class AndroidDpuController extends \app\modules\androiddpu\v1\controllers\AndroidDpuController {

    public function actionVerification() {
        $res_data = [];
        $data = $this->post_data;
        $content = !empty($data['content']) ? $data['content'] : [];
        $model = new TblAndroidInstallationDetails();
        $model->hash_key = $data['token'];
        $model->otp_code = $content['otp_code'];
        $model->imei_no = $data['imei'];
        $model = $model->getData();
        if (!empty($model)) {
            $model->is_active = 1;
            $model->sync_key = rand(1000, 9999);
            $model->sync_active = 1;
            $transaction = $this->generalModel->saveTransaction([$model], ['app verification', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            }
            $androidDpuModel = new TblAndroidInstallationDetails();
            $androidDpuModel->android_installation_details_id = $model->android_installation_details_id;
            $androidDpuModel->android_installation_id = $model->android_installation_id;
            $detailsId = $androidDpuModel->getRecords();
            $androidDpuModel->updateAll(['sync_active' => 0], ['android_installation_id' => $model->android_installation_id, 'android_installation_details_id' => $detailsId]);
            $res_data['message'] = 'OTP Verified.';
            $res_data['sync_key'] = (string) $model->sync_key;
            $this->getParentDetails($res_data, $data);
        } else {
            $res_data['message'] = 'OTP Not Verified.';
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function getParentDetails(&$res_data, $data) {
        $code = $data['organization_code'];
        $type = $data['organization_type'];
        $parent_code = '';
        $parent_type = '';
        $parent_name = '';
        if ($type == 'VLC') {
            $model = new TblDcs();
            $model->dcs_code = $code;
            $detail_type = 'society';
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $parent_code = $model_data->bmc_code;
                $parent_type = 'BMC';
                $parent_name = Yii::$app->general->getforeignkey($model_data->bmcCode, 'bmc_name');
            }
        } else if ($type == 'BMC') {
            $model = new TblDcsBmc();
            $model->bmc_code = $code;
            $detail_type = 'bmc';
            $bmc_code[] = $code;
            $model_data = $model->singleBmcData();
            if (!empty($model_data)) {
                $parent_code = $model_data->mcc_code;
                $parent_type = 'MCC';
                $parent_name = Yii::$app->general->getforeignkey($model_data->tblMccPlant, 'name');
            }
        } else if ($type == 'MCC') {
            $model = new TblMccPlant();
            $model->mcc_plant_code = $code;
            $detail_type = 'mccPlant';
            $mcc_code[] = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $parent_code = $model_data->plant_code;
                $parent_type = 'PLANT';
                $parent_name = Yii::$app->general->getforeignkey($model_data->plantCode, 'name');
            }
        }
        $res_data['parent_type'] = $parent_type;
        $res_data['parent_code'] = $parent_code;
        $res_data['parent_name'] = $parent_name;
    }
    
    public function actionInitialization() {
        $res_data = [];
        $data = $this->post_data;
        $model = new TblAndroidInstallationDetails();
        $id_model = $model->getActiveData($data);
        if (!empty($id_model)) {
            $org_code = $data['organization_code'];
            $org_type = $data['organization_type'];
            if (!empty($org_code)) {
                $type = $org_type;
                $detail_type = '';
                $code = $org_code;
                $dcs_code = [];
                $bmc_code = [];
                $mcc_code = [];
                $plant_code = [];
                $union_code = '';
                if ($type == 'VLC') {
                    $model = new TblDcs();
                    $model->dcs_code = $code;
                    $detail_type = 'society';
                    $dcs_code[] = $code;
                    $model_data = $model->getData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $bmc_code[] = $model_data->bmc_code;
                        $mcc_code[] = Yii::$app->general->getforeignkey($model_data->bmcCode, 'mcc_code');
                        $plant_code[] = Yii::$app->general->getmultiforeignkey($model_data->bmcCode, ['tblMccPlant'], 'plant_code');
                    }
                } else if ($type == 'BMC') {
                    $model = new TblDcsBmc();
                    $model->bmc_code = $code;
                    $detail_type = 'bmc';
                    $bmc_code[] = $code;
                    $model_data = $model->singleBmcData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $mcc_code[] = $model_data->mcc_code;
                        $plant_code[] = Yii::$app->general->getforeignkey($model_data->tblMccPlant, 'plant_code');
                    }
                    $dcsCodes = $model->dcsCodes;
                    foreach ($dcsCodes as $dcsCode) {
                        $dcs_code[] = $dcsCode->dcs_code;
                    }
                } else if ($type == 'MCC') {
                    $model = new TblMccPlant();
                    $model->mcc_plant_code = $code;
                    $detail_type = 'mccPlant';
                    $mcc_code[] = $code;
                    $model_data = $model->getData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $plant_code[] = $model_data->plant_code;
                    }
                    $bmcCodes = $model->bmcCodes;
                    foreach ($bmcCodes as $bmcCode) {
                        $bmc_code[] = $bmcCode->bmc_code;
                        $dcsCodes = $bmcCode->dcsCodes;
                        foreach ($dcsCodes as $dcsCode) {
                            $dcs_code[] = $dcsCode->dcs_code;
                        }
                    }
                }
                if (!empty($model_data)) {

                    $file = $type . '_' . $code . '_' . date('Y.m.d_H.i.s');
                    $fileName = $file . '.db';
                    $id_model->db_path = '/installation-identity/' . $file . '.db';
                    $FolderPath = Yii::$app->basePath . '/installation-identity/';
                    //                $zipfolder = Yii::$app->basePath . '/installation-identity/' . $file;
                    if (!is_dir($FolderPath)) {
                        $oldmask = umask(0);
                        mkdir($FolderPath, 0777, TRUE);
                        umask($oldmask);
                    }
                    copy($FolderPath . 'everest_amcs.db', $FolderPath . $fileName);
                    \Yii::$app->sqlite->_path = $FolderPath;
                    \Yii::$app->sqlite->_organisation_code = $code;
                    \Yii::$app->sqlite->_organisation_type = $type;

                    $transaction = $this->generalModel->saveTransaction([$id_model], ['app initialization', 'create']);
                    if ($transaction == 'customRedirect') {
                        $dcs_code = implode('\',\'', $dcs_code);
                        $dcs_code = !empty($dcs_code) ? '\'' . $dcs_code . '\'' : $dcs_code;
                        $bmc_code = implode('\',\'', $bmc_code);
                        $bmc_code = !empty($bmc_code) ? '\'' . $bmc_code . '\'' : $bmc_code;
                        $mcc_code = implode('\',\'', $mcc_code);
                        $mcc_code = !empty($mcc_code) ? '\'' . $mcc_code . '\'' : $mcc_code;
                        $plant_code = implode('\',\'', $plant_code);
                        $plant_code = !empty($plant_code) ? '\'' . $plant_code . '\'' : $plant_code;
                        \Yii::$app->sqlite->createSqlFileDcs($fileName, $dcs_code, $bmc_code, $mcc_code, $plant_code, $code, $type, $union_code);
                        $res_data['db_path'] = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . $id_model->db_path;
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
