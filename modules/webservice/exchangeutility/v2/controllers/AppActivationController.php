<?php

namespace app\modules\webservice\exchangeutility\v2\controllers;

use Yii;
use app\modules\installation\models\TblAndroidInstallationDetails;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\syncutility\models\TblInbox;
use app\models\TblEiplUserOrganizationMapping;
use app\modules\syncutility\models\TblInboxOther;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblMccPlant;

class AppActivationController extends \app\modules\webservice\exchangeutility\v1\controllers\AppActivationController {

    public $fileName = 'everest_utility_v2.db';

    public function actionInitialization() {
        $res_data = [];
        $data = $this->post_data;
        $model = new TblAndroidInstallationDetails();
        $id_model = $model->getActiveData($data);
        if (!empty($id_model)) {
            $userCode = $data['organization_code'];
            $mapModel = new TblEiplUserOrganizationMapping();
            $mapModel->user_id = $data['organization_code'];
            $mapModelData = $mapModel->getUserOrgs($mapModel->user_id);
            $org_code = [];
            if (!empty($mapModelData) && !empty($mapModelData[0]['organization_type'])) {
                $org_type = $mapModelData[0]['organization_type'];
                foreach ($mapModelData as $mapModelD) {
                    $org_code[] = $mapModelD['organization_code'];
                }
            }
            if (!empty($org_code)) {
                $type = $org_type;
                $detail_type = '';
                $code = $org_code;
                $dcs_code = [];
                $bmc_code = [];
                $mcc_code = [];
                $plant_code = [];
                $union_code = '';
                if ($type == 'VLC' || $type == 'DCS') {
                    $model = new TblDcs();
                    $model->dcs_code = $code;
                    $detail_type = 'society';
                    $dcs_code = $code;
                    $model_data = $model->getData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $bmc_code[] = $model_data->bmc_code;
                        $mcc_code[] = Yii::$app->general->getforeignkey($model_data->bmcCode, 'mcc_plant_code');
                        $plant_code[] = Yii::$app->general->getmultiforeignkey($model_data->bmcCode, ['tblMccPlant'], 'plant_code');
                    }
                } else if ($type == 'BMC') {
                    $model = new TblDcsBmc();
                    $model->bmc_code = $code;
                    $detail_type = 'bmc';
                    $bmc_code = $code;
                    $model_data = $model->getAllBmcData();
                    foreach ($model_data as $model_d) {
                        $union_code = $model_d->union_code;
                        $mccCode = $model_d->mcc_plant_code;
                        if (!in_array($mccCode, $mcc_code)) {
                            $mcc_code[] = $mccCode;
                        }
                        $plant = Yii::$app->general->getforeignkey($model_d->tblMccPlant, 'plant_code');
                        if (!in_array($plant, $plant_code)) {
                            $plant_code[] = $plant;
                        }
                        $dcsCodes = $model_d->dcsCodes;
                        foreach ($dcsCodes as $dcsCode) {
                            $dcs_code[] = $dcsCode->dcs_code;
                        }
                    }
                } else if ($type == 'MCC') {
                    $model = new TblMccPlant();
                    $model->mcc_plant_code = $code;
                    $detail_type = 'mccPlant';
                    $mcc_code = $code;
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

                    $db_file = $this->fileName;
                    $file = $type . '_' . $userCode . '_' . date('Y.m.d_H.i.s');
                    $fileName = $file . '.db';
                    $id_model->db_path = '/installation-identity/' . $file . '.db';
                    $FolderPath = Yii::$app->basePath . '/installation-identity/';
                    $DestFolderPath = Yii::$app->basePath . '/web/installation-identity/';
                    //                $zipfolder = Yii::$app->basePath . '/installation-identity/' . $file;
                    if (!is_dir($FolderPath)) {
                        $oldmask = umask(0);
                        mkdir($FolderPath, 0777, TRUE);
                        umask($oldmask);
                    }
                    copy($FolderPath . $db_file, $DestFolderPath . $fileName);
                    \Yii::$app->sqlite->_path = $FolderPath;
                    \Yii::$app->sqlite->_organisation_code = $org_code;
                    \Yii::$app->sqlite->_organisation_type = $org_type;
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

    public function actionInbox() {
        $tbl_inbox_other = ['tbl_milk_collection_audit', 'tbl_send_sms_count', 'tbl_bmc_tanker_dispatch_audit', 'tbl_app_startup', 'tbl_bmc_collection_summary', 'tbl_bmc_collection_summary_dcs_wise', 'tbl_consolidated_collection_summary', 'tbl_trip_entry_summary'];
        $tbl_inbox_both = ['tbl_milk_collection_summary' => 'tbl_milk_collection_summary_other'];
        $res_data = [];
        $message = 'Unable to save!';
        $success_id = [];
        $error_id = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            foreach ($data['content'] as $transaction_data) {
                if (!empty($transaction_data['uuid'])) {
                    $modelSave = [];
                    $request = new HttpRequest();
                    $transaction_data = $request->camelCaseToUnderscore($transaction_data);
                    if (!empty($transaction_data['table_name']) && in_array($transaction_data['table_name'], $tbl_inbox_other)) {
                        $model = new TblInboxOther();
                    } else {
                        $model = new TblInbox();
                    }
                    $model->setAttributes($transaction_data);
                    $model->sync_timestamp = date('Y-m-d H:i:s');
                    $modelSave[] = $model;
                    if (!empty($transaction_data['table_name']) && array_key_exists($transaction_data['table_name'], $tbl_inbox_both)) {
                        $model_other = new TblInboxOther();
                        $model_other->setAttributes($transaction_data);
                        $model_other->sync_timestamp = date('Y-m-d H:i:s');
                        $model_other->table_name = $tbl_inbox_both[$transaction_data['table_name']];
                        $modelSave[] = $model_other;
                    }
                    $transaction = $this->generalModel->saveDeleteTransaction($modelSave, [], [], ['transactional data', 'create'], true);
                    if ($transaction == 'customRedirect') {
                        $message = 'Successfully Saved!';
                        $success_id[] = $transaction_data['uuid'];
                    } else {
                        $errorData = !empty($transaction) ? (string) $transaction : 'error_occured';
                        if (strstr($errorData, 'Cannot insert duplicate key')) {
                            $success_id[] = $transaction_data['uuid'];
                        } else {
                            $error_id[] = $transaction_data['uuid'];
                        }
                    }
                }
            }
        }
        $res_data['success_id'] = implode(',', $success_id);
        $res_data['error_id'] = implode(',', $error_id);
        $this->response['message'] = [$message];
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
