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
use yii\helpers\ArrayHelper;

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
        $db_file = 'everest_amcs.db';
        $res_data = [];
        $data = $this->post_data;
        $model = new TblAndroidInstallationDetails();
        $id_model = $model->getActiveData($data);
        if (!empty($id_model)) {
            $org_code = $data['organization_code'];
            $org_type = $data['organization_type'];
            if (!empty($org_code)) {
                $orgDetail = $this->getOrgDetail($org_type, $org_code);
                $dcs_code = $orgDetail['dcs_code'];
                $bmc_code = $orgDetail['bmc_code'];
                $mcc_plant_code = $orgDetail['mcc_plant_code'];
                $plant_code = $orgDetail['plant_code'];
                $union_code = $orgDetail['union_code'];
                $model_data = $orgDetail['model_data'];
                if (!empty($model_data)) {
                    $file = $org_type . '_' . $org_code . '_' . date('Y.m.d_H.i.s');
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
                    \Yii::$app->sqlite->_organisation_code = $org_code;
                    \Yii::$app->sqlite->_organisation_type = $org_type;

                    $transaction = $this->generalModel->saveTransaction([$id_model], ['app initialization', 'create']);
                    if ($transaction == 'customRedirect') {
                        $response = \Yii::$app->sqlite->createSqlFileDcs($fileName, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code, $org_code, $org_type, $union_code);
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
        $data = $this->post_data;
        if (!empty($data['organization_code']) && !empty($data['organization_type'])) {
            $model = new TblAndroidInstallationDetails();
            $id_model = $model->getActiveData($data);
            if (!empty($id_model)) {
                $res_data['config'] = [];
                $res_data['collectionConfig'] = [];
                $res_data['rate'] = [];
                $res_data['rate']['mPurchaseRateCode'] = "";
                $res_data['rate']['mPurchaseRateCodeBlock'] = "";
                $res_data['rate']['ePurchaseRateCode'] = "";
                $res_data['rate']['ePurchaseRateCodeBlock'] = "";
                $res_data['rate']['memberApplicableRate'] = "";
                $res_data['rate']['bmcApplicableRate'] = "";
                $res_data['memberDownload'] = FALSE;
                $res_data['welcomeMessage'] = 'Welcome to Everest Instruments Pvt. Ltd.';
                $res_data['shift_timing'] = [];
                $mcc_bmc_config = TRUE;
                $MappedMilkType = [];
                $org_code = $data['organization_code'];
                $org_type = $data['organization_type'];
                $orgDetail = $this->getOrgDetail($org_type, $org_code, FALSE);
                $dcs_code = $orgDetail['dcs_code'];
                $bmc_code = $orgDetail['bmc_code'];
                $mcc_plant_code = $orgDetail['mcc_plant_code'];
                $plant_code = $orgDetail['plant_code'];
                $union_code = $orgDetail['union_code'];
                $model_data = $orgDetail['model_data'];
                if (!empty($model_data)) {
                    if ($org_type == 'VLC') {
                        $mcc_bmc_config = FALSE;
                        $current_rate_detail = Yii::$app->general->getSpData('sp_app_amcs_v2_current_rate_detail', [$org_code]);
                        if (!empty($current_rate_detail)) {
                            $purchase_rate_code = (string) $model_data->member_rate_code;
                            $res_data['rate']['mPurchaseRateCode'] = $current_rate_detail[0]['m_rate_code'];
                            $res_data['rate']['mPurchaseRateCodeBlock'] = !empty($purchase_rate_code) ? $purchase_rate_code : $current_rate_detail[0]['m_rate_block'];
                            $res_data['rate']['ePurchaseRateCode'] = $current_rate_detail[0]['e_rate_code'];
                            $res_data['rate']['ePurchaseRateCodeBlock'] = !empty($purchase_rate_code) ? $purchase_rate_code : $current_rate_detail[0]['e_rate_block'];
                        }
                        $collection_status = Yii::$app->general->getSpData('sp_society_collection_status', [$org_code]);
                        $collection_status = empty($collection_status) ? $model_data->is_active : $collection_status[0]['collection_status'];
                        $res_data['memberDownload'] = (bool) $model_data->is_name_request;
                        $res_data['config']['collectionBlock'] = !(bool) $collection_status;
                        $res_data['config']['dcsBlock'] = !(bool) $model_data->is_active;
                        $res_data['config']['dispatchMandate'] = $model_data->is_dispatch_mandate > 0 ? true : false; //(bool) $model_data->is_dispatch_mandate;
                        $res_data['config']['weightManual'] = (bool) $model_data->is_weight_manual;
                        $res_data['config']['qualityManual'] = (bool) $model_data->is_quality_manual;
                        $config = $model_data->dpuIncentiveMaster;
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
                        $MappedMilkType = $model_data->tblDcsMilkType;
                        $collectionIncentive = $model_data->collectionIncentive;
                    } else if ($org_type == 'BMC') {
                        $MappedMilkType = $model_data->tblBmcMilkType;
                        $collectionIncentive = [];
                    } else if ($org_type == 'MCC') {
                        $MappedMilkType = $model_data->tblMccMilkType;
                        $collectionIncentive = [];
                    }
                    if ($mcc_bmc_config) {
                        $res_data['memberDownload'] = FALSE;
                        $res_data['config']['collectionBlock'] = FALSE;
                        $res_data['config']['dcsBlock'] = FALSE;
                        $res_data['config']['dispatchMandate'] = FALSE;
                        $res_data['config']['weightManual'] = (bool) $model_data->is_weight_manual;
                        $res_data['config']['qualityManual'] = (bool) $model_data->is_quality_manual;
                        $res_data['rate']['mPurchaseRateCode'] = "";
                        $res_data['rate']['mPurchaseRateCodeBlock'] = "";
                        $res_data['rate']['ePurchaseRateCode'] = "";
                        $res_data['rate']['ePurchaseRateCodeBlock'] = "";
                        $res_data['rate']['mPurchaseRateCodeBmc'] = "";
                        $res_data['rate']['mPurchaseRateCodeBlockBmc'] = "";
                        $res_data['rate']['ePurchaseRateCodeBmc'] = "";
                        $res_data['rate']['ePurchaseRateCodeBlockBmc'] = "";
                        $res_data['collectionConfig']['m_start_time'] = "";
                        $res_data['collectionConfig']['m_cutoff_time'] = "";
                        $res_data['collectionConfig']['m_lock_time'] = "";
                        $res_data['collectionConfig']['e_start_time'] = "";
                        $res_data['collectionConfig']['e_cutoff_time'] = "";
                        $res_data['collectionConfig']['e_lock_time'] = "";
                        $res_data['collectionConfig']['inc_rate'] = "";
                        $res_data['collectionConfig']['inc_deduction'] = "";
                    }
                    $animalType = [];
                    foreach ($MappedMilkType as $milktype) {
                        $min_fat = $min_snf = $min_clr = $max_fat = $max_snf = $max_clr = 0.0;
                        $milktype->app_type = $org_type;
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
                    foreach ($collectionIncentive as $incentive) {
                        $IncentiveDeduction[] = [
                            'from_time' => $incentive->from_time,
                            'to_time' => $incentive->to_time,
                            'scheme_type' => $incentive->scheme_type,
                            'shift_code' => $incentive->shift_code,
                            'amount' => $incentive->amount,
                            'from_date' => $incentive->from_date,
                            'to_date' => $incentive->to_date,
                        ];
                    }
                    $res_data['collectionConfig']['allowedMilkType'] = $animalType;
                    $res_data['collectionConfig']['collectionIncentiveDeduction'] = $IncentiveDeduction;

                    $model = new TblUnionConfigResult();
                    $model->union_code = $model_data->union_code;
                    $model->config_for = $org_type;
                    foreach ($model->getConfigList() as $d) {
                        $res_data['config'][$d['config_key']] = $d['config_result_key'];
                    }

                    $res_data['welcomeMessage'] = 'Welcome to ' . $model_data->unionCode->union_name . '.';
                    $dcs_code = ',' . implode(',', $dcs_code) . ',';
                    $bmc_code = ',' . implode(',', $bmc_code) . ',';
                    $mcc_plant_code = ',' . implode(',', $mcc_plant_code) . ',';
                    $plant_code = ',' . implode(',', $plant_code) . ',';
                    $is_member_rate = isset($res_data['config']['required_member_rate']) ? $res_data['config']['required_member_rate'] : '1';
                    if ($is_member_rate == '1') {
                        $member_rate = Yii::$app->general->getSpData('sp_app_amcs_v2_pending_rate_detail_member', [$dcs_code, $id_model->device_id, $id_model->hash_key]);
                        if (!empty($member_rate)) {
                            $res_data['rate']['memberApplicableRate'] = implode(',', array_column($member_rate, 'purchase_rate_code'));
                        }
                    }
                    if ($mcc_bmc_config) {
                        $bmc_rate = Yii::$app->general->getSpData('sp_app_amcs_v2_pending_rate_detail_bmc', [$plant_code, $mcc_plant_code, $bmc_code, $dcs_code, $id_model->device_id, $id_model->hash_key]);
                        if (!empty($bmc_rate)) {
                            $res_data['rate']['bmcApplicableRate'] = implode(',', array_column($bmc_rate, 'purchase_rate_code'));
                        }
                    }

                    $shiftTimigData = Yii::$app->general->getSpData('sp_app_amcs_v2_collection_shift_time', [$org_type, $org_code]);
                    if (!empty($shiftTimigData)) {
                        $res_data['shift_timing'] = $shiftTimigData;
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
