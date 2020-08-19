<?php

namespace app\modules\soap\controllers;

use Yii;
use SoapClient;
use stdClass;
use SimpleXMLElement;
use app\modules\soap\Soap;
use app\modules\soap\models\TblMasterDataIntermediate;

class MasterDataController extends \app\modules\soap\controllers\DefaultController {

    public function actionIndex() {
        foreach ($this->union_array as $union_code) {
            $MasterList = $this->MasterList();
            $key = '';
            foreach ($MasterList as $key => $config) {
                try {
                    if (!empty($config['request_param'])) {
                        if (!empty($config['static_param'])) {
                            foreach ($config['static_param'] as $k => $arr) {
                                foreach ($arr as $v) {
                                    $params = new stdClass();
                                    $params->{$k} = $v;
                                    $this->soapCall($config, $key, $params, $union_code);
                                }
                            }
                        } else {
                            $request_param = $config['request_param'];
                            $model_name = Yii::$app->path->define($request_param['model']);
                            $model = new $model_name();
                            $model_data = $model->find()->where(['union_code' => $union_code]);
                            if (isset($config['where_clause'])) {
                                $model_data->andWhere($config['where_clause']);
                            }
                            $model_data = $model_data->all();
                            foreach ($model_data as $data) {
                                $params = new stdClass();
                                foreach ($request_param['param_key'] as $k => $v) {
                                    $params->{$k} = $data->{$v};
                                }
                                $this->soapCall($config, $key, $params, $union_code);
                            }
                        }
                    } else {
                        $this->soapCall($config, $key, [], $union_code);
                    }
                } catch (\Throwable $ex) {
                    $this->createCpLogFile('', $ex->getMessage(), $key);
                }
            }
        }
    }

    protected function soapCall($config, $key, $params = [], $union_code) {
        try {
            $client = new SoapClient(Yii::$app->params['soap_api_url'], ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
            $result = $client->{$key}($params);
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result->{$config['result_key']}->any);
            $xml = new SimpleXMLElement($response);
            $dataset = $xml->xpath('//Table');
            if (!empty($dataset)) {
                if (!empty($config['save_fun'])) {
                    $this->{$config['save_fun']}($key, $config, $params, $dataset, $union_code);
                } else {
                    $this->savedata($key, $config, $params, $dataset, $union_code);
                }
            }
        } catch (\Throwable $ex) {
            $this->createCpLogFile('', $ex->getMessage(), $key);
        }
    }

    protected function savedata($api_name, $config, $params, $dataset, $union_code) {
        try {
            if (!empty($config['ack_config'])) {
                $send_ack = TRUE;
                $ack_api = $config['ack_config'][0];
                $ack_key = $config['ack_config'][1];
                $ack_input = $config['ack_config'][2];
                $ack_data = [];
            } else {
                $send_ack = FALSE;
                $ack_api = $ack_input = $ack_key = '';
                $ack_data = [];
            }
            $success_codes = [];
            $error_codes = [];
            $master_model = [];
            $valid = [];
            foreach ($dataset as $model_data) {
                $json_encode = json_encode($model_data);
                $model_data = json_decode(str_replace('{}', '""', $json_encode));
                $model = new TblMasterDataIntermediate();
                $model->setAttributes((array) $params);
                $model->setAttributes((array) $model_data);
                $model->service_type = $api_name;
                $model->union_code = $union_code;
                $model->created_at = date('Y-m-d H:i:s');
                $model->created_by = 'BKG';
                if ($model->validate() && $model->save()) {
                    if ($send_ack) {
                        $ack_data[] = $model->{$ack_key};
                    }
                } else {
                    $valid[] = $model->validate();
                    $master_model[] = $model;
                }
            }
            if ($send_ack) {
                $client = new SoapClient(Yii::$app->params['soap_api_url'], ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
                $input = new stdClass();
                $input->{$ack_input} = implode(',', $ack_data);
                $result = $client->{$ack_api}($input);
            }
            if (in_array(FALSE, $valid)) {
                $logs = [];
                foreach ($master_model as $smodel) {
                    $logs['data'][] = $smodel->getAttributes();
                    $logs['errors'][] = $smodel->getErrors();
                }
                $text = json_encode($logs);
                $this->createCpLogFile('', $text, $api_name);
            }
        } catch (\Throwable $ex) {
            $this->createCpLogFile('', $ex->getMessage(), $api_name);
        }
    }

    public function saveRate($api_name, $config, $params, $dataset, $union_code) {
        if (!empty($dataset)) {
            // $first_raw = (array) $dataset[0];
            //$first_raw = $this->strReplaceAssoc($first_raw);
            //  $snf_keys = array_keys($first_raw);
            $min_snf = 8; //min($snf_keys);
            $max_snf = 9.9;
            // $min_fat = $first_raw['Fat'];
            $max_fat = 0;
            $fat_array = [];
            // $shift_time = (strpos(strtoupper($api_name), 'EVENING') !== FALSE) ? ' 18:00:00.000000' : ' 06:00:00.000000';
            $shift_applicability = (strpos(strtoupper($api_name), 'EVENING') !== FALSE) ? 2 : 1;
            //$wef_date = date('Y-m-d', strtotime($first_raw['EffectiveDate'])) . $shift_time;
            $rate_id = $params->RateID;
            $request_param = $config['request_param'];
            $model_name = Yii::$app->path->define($request_param['model']);
            $model = new $model_name();
            $model_data = $model->find()->where(['reference_code' => $rate_id, 'union_code' => $union_code, 'shift_applicability' => $shift_applicability, 'is_process' => 0])->one();
            if (!empty($model_data)) {
                $milk_type_code = (strpos(strtoupper($api_name), 'COW') !== FALSE) ? 1 : 2;
                $milk_qlty_code = 1;
                $rate_type_code = 2;
                $model_name = Yii::$app->path->define($request_param['model'] . 'Details');
                $purchaseDetailModel = new $model_name();
                $detailmaxID = ($request_param['model'] == 'TblDcsPurchaseRate') ? $purchaseDetailModel->getCode() : 0;
                $master = [];
                $based = [];
                $data = [];
                $baseCode = 0;
                $i = 0;
                $cnt = 0;
                $d_cnt = 1;
                foreach ($dataset AS $detail) {
                    $fat = $detail->Fat;
                    $fat_array[] = (float) $fat;
//                    if ($d_cnt == (count($dataset))) {
//                        $max_fat = $fat;
//                    }
                    $rate_array = $this->strReplaceAssoc((array) $detail);
                    unset($rate_array['Fat']);
                    unset($rate_array['CreatedDate']);
                    unset($rate_array['EffectiveDate']);
                    unset($rate_array['EffectiveDateTo']);
                    foreach ($rate_array as $snf => $rtpl) {
                        if ($request_param['model'] == 'TblDcsPurchaseRate') {
                            $data [$i] [] = [
                                $detailmaxID + $cnt,
                                $model_data->purchase_rate_code,
                                $rate_type_code,
                                $milk_qlty_code,
                                $milk_type_code,
                                number_format((float) $fat, 2),
                                number_format((float) $snf, 2),
                                number_format((float) $rtpl, 2)
                            ];
                        } else {
                            $data [$i] [] = [
                                $model_data->purchase_rate_code,
                                $rate_type_code,
                                $milk_qlty_code,
                                $milk_type_code,
                                number_format((float) $fat, 2),
                                number_format((float) $snf, 2),
                                number_format((float) $rtpl, 2)
                            ];
                        }
                        $cnt ++;
                        if (count($data [$i]) == 1000) {
                            $i ++;
                        }
                    }
                    $d_cnt++;
                }
                $min_fat = !empty($fat_array) ? min($fat_array) : 0;
                $max_fat = !empty($fat_array) ? max($fat_array) : 0;
                $model_name = Yii::$app->path->define($request_param['model'] . 'Based');
                $purchaseBasedModel = new $model_name();
                $basemaxID = $purchaseBasedModel->getCode();
                $purchaseBasedModel->purchase_rate_code = $model_data->purchase_rate_code;
                $purchaseBasedModel->rate_based_code = $basemaxID + $baseCode;
                $purchaseBasedModel->milk_type_code = $milk_type_code;
                ($request_param['model'] == 'TblPurchaseRate') ? ($purchaseBasedModel->rate_type_code = $rate_type_code) : ($purchaseBasedModel->rate_type = $rate_type_code);
                $purchaseBasedModel->quality_param_code = 1;
                $purchaseBasedModel->start_range = $min_fat;
                $purchaseBasedModel->end_range = $max_fat;
                $purchaseBasedModel->milk_quality_type_code = $milk_qlty_code;
                $based[] = $purchaseBasedModel;
                $baseCode++;

                $purchaseBasedModel = new $model_name();
                $purchaseBasedModel->purchase_rate_code = $model_data->purchase_rate_code;
                $purchaseBasedModel->rate_based_code = $basemaxID + $baseCode;
                $purchaseBasedModel->milk_type_code = $milk_type_code;
                ($request_param['model'] == 'TblPurchaseRate') ? ($purchaseBasedModel->rate_type_code = $rate_type_code) : ($purchaseBasedModel->rate_type = $rate_type_code);
                $purchaseBasedModel->quality_param_code = 2;
                $purchaseBasedModel->start_range = $min_snf;
                $purchaseBasedModel->end_range = $max_snf;
                $purchaseBasedModel->milk_quality_type_code = $milk_qlty_code;
                $based[] = $purchaseBasedModel;
                $baseCode++;
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $save_applicability = ($request_param['model'] == 'TblPurchaseRate' && $milk_type_code == 1) ? TRUE : FALSE;
                    $purchaseDetailModel->deleteAll(['milk_type_code' => $milk_type_code, 'purchase_rate_code' => $model_data->purchase_rate_code]);
                    $purchaseBasedModel->deleteAll(['milk_type_code' => $milk_type_code, 'purchase_rate_code' => $model_data->purchase_rate_code]);
                    if ($milk_type_code == 1) {
                        $model_data->is_process = 1;
                        $error = $model_data->save();
                        $master[] = $error;
                    }
                    foreach ($based as $b) {
                        $b->scenario = 'excel';
                        $error = $b->save();
                        $master[] = $error;
                    }
                    if ($request_param['model'] == 'TblDcsPurchaseRate') {
                        $clm_seq = ['code', 'purchase_rate_code', 'rate_type_code', 'milk_quality_type_code', 'milk_type_code', 'fat', 'snf', 'rtpl'];
                    } else {
                        $clm_seq = ['purchase_rate_code', 'rate_type_code', 'milk_quality_type_code', 'milk_type_code', 'fat', 'snf', 'rtpl'];
                    }
                    foreach ($data as $d) {
                        \Yii::$app->db->createCommand()->batchInsert(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $request_param['model'])) . '_details', $clm_seq, $d)->execute();
                    }

                    if ($save_applicability) {
                        $current_datetime = date('Y-m-d H:i:s');
                        $model_name = Yii::$app->path->define($request_param['model'] . 'Applicability');
                        $rateAppModel = new $model_name();
                        $rateAppModel->deleteAll(['purchase_rate_code' => $model_data->purchase_rate_code]);
                        $model_name = Yii::$app->path->define($request_param['model'] . 'ApplicabilityPending');
                        $rateAppTempModel = new $model_name();
//                        \Yii::$app->db->createCommand()->insert($rateAppModel, $rateAppTempModel
//                                        ->select(['created_at', 'created_by', 'is_active', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'purchase_rate_code', 'shift_code', 'union_code', 'rate_type', 'rate_gen_method_code', 'download_date_time', 'is_download'])->where([ 'purchase_rate_code' => $model_data->purchase_rate_code]))->execute();
//                    
                        Yii::$app->db->createCommand("insert into tbl_purchase_rate_applicability (created_at,created_by,is_active,updated_at,updated_by,wef_date,dcs_code,purchase_rate_code,shift_code,union_code,rate_type,rate_gen_method_code,download_date_time,is_download,originating_org_code,originating_org_type,originating_type) select created_at,created_by,is_active,updated_at,updated_by,wef_date,dcs_code,purchase_rate_code,shift_code,union_code,rate_type,rate_gen_method_code,download_date_time,0,originating_org_code,originating_org_type,originating_type from tbl_purchase_rate_applicability_pending where purchase_rate_code=:purchase_rate_code")
                                ->bindValue(':purchase_rate_code', $model_data->purchase_rate_code)
                                ->execute();
                        Yii::$app->db->createCommand("update d set d.rate_flag=1 from tbl_dcs d inner join tbl_purchase_rate p on p.reference_code=d.rate_chart_code where p.purchase_rate_code=:purchase_rate_code")
                                ->bindValue(':purchase_rate_code', $model_data->purchase_rate_code)
                                ->execute();
                        Yii::$app->db->createCommand("insert into tbl_generate_sentbox ([table_name],[where_clause],[operation_type],[sentbox_key],[union_code],[dcs_code],[status],[entry_datetime]) select 'tbl_purchase_rate_applicability',CONCAT('dcs_code=''',dcs_code,''' and wef_date=''',wef_date,''),'INSERT','dcs_code',union_code,dcs_code,0,'$current_datetime' from tbl_purchase_rate_applicability_pending where purchase_rate_code=:purchase_rate_code")
                                ->bindValue(':purchase_rate_code', $model_data->purchase_rate_code)
                                ->execute();

                        $rateAppTempModel->deleteAll(['purchase_rate_code' => $model_data->purchase_rate_code]);
                    }
                    if ($transaction->isActive && !in_array(FALSE, $master)) {
                        $transaction->commit();
                    } else {
                        $logs = [];
                        $logs['data'][] = $model_data->getAttributes();
                        $logs['errors'][] = $model_data->getErrors();
                        foreach ($based as $smodel) {
                            $logs['data'][] = $smodel->getAttributes();
                            $logs['errors'][] = $smodel->getErrors();
                        }
                        $text = json_encode($logs);
                        $this->createCpLogFile('', $text, $api_name);
                    }
                } catch (\Throwable $ex) {
                    $transaction->rollback();
                    $this->createCpLogFile('', $ex->getMessage(), $api_name);
                }
            }
        }
    }

    public function MasterList() {
        $data = [
            'ProductGroupMaster' => [
                'result_key' => 'ProductGroupMasterResult',
                'ack_config' => ['ProductGroupMasterAck', 'ProductGroup_Code', 'strProductGroup'],
            ],
            'ProductMaster' => [
                'result_key' => 'ProductMasterResult',
                'ack_config' => ['ProductMasterAck', 'Product_Code', 'strProduct'],
            ],
            'PlantMaster' => [
                'result_key' => 'PlantMasterResult',
                'ack_config' => ['PlantMasterAck', 'PlantCode', 'strPlant'],
            ],
            'CenterMaster' => [
                'result_key' => 'CenterMasterResult',
                'ack_config' => ['CenterMasterAck', 'Center_Code', 'strCenter'],
                // 'static_param' => ['PlantCode' => ['7301', '7302']],
                'request_param' => ['model' => 'TblMccPlant', 'param_key' => ['PlantCode' => 'mcc_plant_code']],
            ],
            'FarmerMaster' => [
                'result_key' => 'FarmerMasterResult',
                'ack_config' => ['FarmerMasterAck', 'Farmer_Code', 'strFarmer'],
                // 'static_param' => ['MCCCode' => ['1031']],
                'request_param' => ['model' => 'TblDcs', 'param_key' => ['MCCCode' => 'sap_center_code']],
            ],
            'VendorMaster' => [
                'result_key' => 'VendorMasterResult',
                'ack_config' => ['VendorMasterAck', 'Vendor_Code', 'strVendor'],
                //  'static_param' => ['PlantCode' => ['7301']],
                'request_param' => ['model' => 'TblMccPlant', 'param_key' => ['PlantCode' => 'mcc_plant_code']],
            ],
            'RateIDForMCC' => [
                'result_key' => 'RateIDForMCCResult',
            ],
            'RateIDForVendor' => [
                'result_key' => 'RateIDForVendorResult',
            ],
            'BuffaloMorningRate' => [
                'result_key' => 'BuffaloMorningRateResult',
                //'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
            'CowMorningRate' => [
                'result_key' => 'CowMorningRateResult',
                //'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
            'BuffaloEveningRate' => [
                'result_key' => 'BuffaloEveningRateResult',
                // 'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
            'CowEveningRate' => [
                'result_key' => 'CowEveningRateResult',
                //'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
            'BuffaloMorningRateforV' => [
                'result_key' => 'BuffaloMorningRateforVResult',
                //  'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblDcsPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
            'CowMorningRateforV' => [
                'result_key' => 'CowMorningRateforVResult',
                //   'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblDcsPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
            'BuffaloEveningRateforV' => [
                'result_key' => 'BuffaloEveningRateforVResult',
                //  'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblDcsPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
            'CowEveningRateforV' => [
                'result_key' => 'CowEveningRateforVResult',
                // 'static_param' => ['RateID' => ['1001']],
                'request_param' => ['model' => 'TblDcsPurchaseRate', 'param_key' => ['RateID' => 'reference_code']],
                'save_fun' => 'saveRate',
                'where_clause' => ['is_process' => 0]
            ],
        ];
        return $data;
    }

}
