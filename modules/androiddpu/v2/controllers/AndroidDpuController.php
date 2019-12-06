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
use app\modules\configuration\models\TblUnionConfigResult;

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
                $parent_code = $model_data->mcc_plant_code;
                $parent_type = 'MCC';
                $parent_name = Yii::$app->general->getforeignkey($model_data->tblMccPlant, 'name');
            }
        } else if ($type == 'MCC') {
            $model = new TblMccPlant();
            $model->mcc_plant_code = $code;
            $detail_type = 'mccPlant';
            $mcc_plant_code[] = $code;
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
        $db_file = 'bmc_app.db';
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
                $mcc_plant_code = [];
                $plant_code = [];
                $union_code = '';
                if ($type == 'VLC') {
                    $db_file = 'everest_amcs.db';
                    $model = new TblDcs();
                    $model->dcs_code = $code;
                    $detail_type = 'society';
                    $dcs_code[] = $code;
                    $model_data = $model->getData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $bmc_code[] = $model_data->bmc_code;
                        $mcc_plant_code[] = $model_data->mcc_plant_code;
                        $plant_code[] = $model_data->plant_code;
                    }
                } else if ($type == 'BMC') {
                    $model = new TblDcsBmc();
                    $model->bmc_code = $code;
                    $detail_type = 'bmc';
                    $bmc_code[] = $code;
                    $model_data = $model->singleBmcData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $mcc_plant_code[] = $model_data->mcc_plant_code;
                        $plant_code[] = $model_data->plant_code;
                    }
                    $dcsCodes = $model->dcsCodes;
                    foreach ($dcsCodes as $dcsCode) {
                        $dcs_code[] = $dcsCode->dcs_code;
                    }
                } else if ($type == 'MCC') {
                    $model = new TblMccPlant();
                    $model->mcc_plant_code = $code;
                    $detail_type = 'mccPlant';
                    $mcc_plant_code[] = $code;
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
                    copy($FolderPath . $db_file, $FolderPath . $fileName);
                    \Yii::$app->sqlite->_path = $FolderPath;
                    \Yii::$app->sqlite->_organisation_code = $code;
                    \Yii::$app->sqlite->_organisation_type = $type;

                    $transaction = $this->generalModel->saveTransaction([$id_model], ['app initialization', 'create']);
                    if ($transaction == 'customRedirect') {
                        $dcs_code = implode('\',\'', $dcs_code);
                        $dcs_code = !empty($dcs_code) ? '\'' . $dcs_code . '\'' : $dcs_code;
                        $bmc_code = implode('\',\'', $bmc_code);
                        $bmc_code = !empty($bmc_code) ? '\'' . $bmc_code . '\'' : $bmc_code;
                        $mcc_plant_code = implode('\',\'', $mcc_plant_code);
                        $mcc_plant_code = !empty($mcc_plant_code) ? '\'' . $mcc_plant_code . '\'' : $mcc_plant_code;
                        $plant_code = implode('\',\'', $plant_code);
                        $plant_code = !empty($plant_code) ? '\'' . $plant_code . '\'' : $plant_code;
                        $response = \Yii::$app->sqlite->createSqlFileDcs($fileName, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code, $code, $type, $union_code);
                        if ($response) {
                            $res_data['db_path'] = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . $id_model->db_path;
                        } else {
                            $res_data['db_path'] = NULL;
                        }
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionStartUp() {
        $res_data = [];
        $res_data['config'] = [];
        $res_data['collectionConfig'] = [];
        $res_data['rate'] = [];
        $res_data['rate']['mPurchaseRateCode'] = "";
        $res_data['rate']['mPurchaseRateCodeBlock'] = "";
        $res_data['rate']['ePurchaseRateCode'] = "";
        $res_data['rate']['ePurchaseRateCodeBlock'] = "";
        $res_data['memberDownload'] = FALSE;
        $res_data['welcomeMessage'] = 'Welcome to Everest Instruments Pvt. Ltd.';
        $data = $this->post_data;
        if (!empty($data['organization_code']) && !empty($data['organization_type'])) {
            $org_code = $data['organization_code'];
            $org_type = $data['organization_type'];
            if ($org_type == 'VLC') {
                $model = new TblDcs();
                $model->dcs_code = $org_code;
                $model_data = $model->getData();
                if (!empty($model_data)) {
                    $current_rate_detail = Yii::$app->general->getSpData('sp_app_amcs_v2_current_rate_detail', [$org_code]);
                    if (!empty($current_rate_detail)) {
                        $res_data['rate']['mPurchaseRateCode'] = $current_rate_detail[0]['m_rate_code'];
                        $res_data['rate']['mPurchaseRateCodeBlock'] = $current_rate_detail[0]['m_rate_block'];
                        $res_data['rate']['ePurchaseRateCode'] = $current_rate_detail[0]['e_rate_code'];
                        $res_data['rate']['ePurchaseRateCodeBlock'] = $current_rate_detail[0]['e_rate_block'];
                    }
                    $collection_status = Yii::$app->general->getSpData('sp_society_collection_status', [$org_code]);
                    $collection_status = empty($collection_status) ? $model_data->is_active : $collection_status[0]['collection_status'];
                    $res_data['memberDownload'] = (bool) $model_data->is_name_request;
                    $res_data['config']['collectionBlock'] = !(bool) $collection_status;
                    $res_data['config']['dcsBlock'] = !(bool) $model_data->is_active;
                    $res_data['config']['dispatchMandate'] = (bool) $model_data->is_dispatch_mandate;
                    $res_data['config']['weightManual'] = (bool) $model_data->is_weight_manual;
                    $res_data['config']['qualityManual'] = (bool) $model_data->is_quality_manual;
                    $config = $model->dpuIncentiveMaster;
                    if (!empty($config)) {
                        $att = $config->attributes;
                        $res_data['collectionConfig']['m_start_time'] = $att['m_start_time'];
                        $res_data['collectionConfig']['m_cutoff_time'] = $att['m_cutoff_time'];
                        $res_data['collectionConfig']['m_lock_time'] = $att['m_lock_time'];
                        $res_data['collectionConfig']['e_start_time'] = $att['e_start_time'];
                        $res_data['collectionConfig']['e_cutoff_time'] = $att['e_cutoff_time'];
                        $res_data['collectionConfig']['e_lock_time'] = $att['e_lock_time'];
                        $res_data['collectionConfig']['inc_rate'] = $att['inc_rate'];
                        $res_data['collectionConfig']['inc_deduction'] = $att['inc_deduction'];
                    }
                    $animalType = [];
                    foreach ($model_data->tblDcsMilkType as $milktype) {
                        $min_fat = $min_snf = $min_clr = $max_fat = $max_snf = $max_clr = 0.0;
                        $rate_range = $milktype->rateChartRange;
                        if (!empty($rate_range)) {
                            $min_fat = $rate_range->min_fat;
                            $max_fat = $rate_range->max_fat;
                            $min_snf = $rate_range->min_snf;
                            $max_snf = $rate_range->max_snf;
                            $min_clr = $rate_range->min_clr;
                            $max_clr = $rate_range->max_clr;
                        }
                        $animalType[] = [
                            'milk_type_code' => $milktype->milk_type_code,
                            'milk_type_name' => $milktype->milkTypeCode->animal_type_name,
                            'min_fat' => $min_fat,
                            'max_fat' => $max_fat,
                            'min_snf' => $min_snf,
                            'max_snf' => $max_snf,
                            'min_clr' => $min_clr,
                            'max_clr' => $max_clr
                        ];
                    }
                    $IncentiveDeduction = [];
                    foreach ($model_data->collectionIncentive as $incentive) {
                        $IncentiveDeduction[] = [
                            'from_time' => $incentive->from_time,
                            'to_time' => $incentive->to_time,
                            'scheme_type' => $incentive->scheme_type,
                            'shift_code' => $incentive->shift_code,
                            'amount' => $incentive->amount,
                            'from_date' => $incentive->from_time,
                            'to_date' => $incentive->to_time,
                        ];
                    }
                    $res_data['collectionConfig']['allowedMilkType'] = $animalType;
                    $res_data['collectionConfig']['collectionIncentiveDeduction'] = $IncentiveDeduction;
                }
            } else if ($org_type == 'BMC') {
                $model = new TblDcsBmc();
                $model->bmc_code = $org_code;
                $model_data = $model->singleBmcData();
            } else if ($org_type == 'MCC') {
                $model = new TblMccPlant();
                $model->mcc_plant_code = $org_code;
                $model_data = $model->getData();
            }
            if (!empty($model_data)) {
                $model = new TblUnionConfigResult();
                $model->union_code = $model_data->union_code;
                $model->config_for = $org_type;
                foreach ($model->getConfigList() as $d) {
                    $res_data['config'][$d['config_key']] = $d['config_result_key'];
                }
                $res_data['welcomeMessage'] = 'Welcome to ' . $model_data->unionCode->union_name . '.';
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
