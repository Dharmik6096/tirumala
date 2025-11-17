<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use ruskid\csvimporter\ARImportStrategy;
use app\modules\organisation\models\TblDcsVillageMapping;
use app\modules\organisation\models\TblSocietyCodes;
use yii\widgets\ActiveForm;
use Yii;
use app\modules\organisation\models\TblDcsMilkType;
use app\modules\general\models\TblSocietyVendor;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblDcsVillageMappingHistory;
use app\modules\organisation\models\TblDcsMilkTypeHistory;
use app\modules\organisation\models\TblRouteMappingSources;
use app\modules\organisation\models\TblRouteMappingSourcesHistory;
use app\modules\dcsoperation\models\TblMember;
use app\modules\product\models\TblProductSaleRate;
use app\modules\product\models\TblProductSaleRateApplicability;
use app\models\TblUserOrganizationMapping;
use app\modules\usermanagement\models\User;
use yii\base\UserException;

class DcsImportStrategy extends ARImportStrategy {

    public function import(&$data) {
        $importedPks = [];
        $errors = [];
        $count = 0;


        //$abc = array_map('array_filter', $data);
        $data = array_filter($data, function($var) {
            return !empty($var[0]) && !is_null($var);
        });
        $data = array_filter($data);

        foreach ($data as $key => $row) {

            $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

            if ($key == 0)
                continue;

            if (!$skipImport) {
                $trans = \Yii::$app->db->beginTransaction();
                try {
                    /* @var $model \yii\db\ActiveRecord */
                    $model = new $this->className;
                    if (!empty($this->scenario))
                        $model->scenario = $this->scenario;


                    $uniqueAttributes = [];
                    $addedAttributes = [];
                    foreach ($this->configs as $config) {
                        $value = call_user_func($config['value'], $row);
                        if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                            //Create array of unique attributes
                            if (isset($config['unique']) && $config['unique']) {
                                $uniqueAttributes[$config['attribute']] = $value;
                            }
                            //Set value to the model
                            ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                            $addedAttributes[$config['attribute']] = $config['attribute'];
                        } else if (property_exists($model, $config['attribute'])) {
                            //Set value to the model of public attribute
                            $model->{$config['attribute']} = $value;
                            $addedAttributes[$config['attribute']] = $config['attribute'];
                        }
                    }
                    if (isset($this->defaultFields)) {
                        foreach ($this->defaultFields as $default) {
                            //                        var_dump($default['value']);exit;
                            if (isset($default['attribute']) && $model->hasAttribute($default['attribute'])) {
                                $value = $default['value'];
                                //Set value to the model
                                $model->setAttribute($default['attribute'], $value);
                            }
                        }
                    }
                    $modelList = [];
                    $deleteModel = [];
                    $model->setModel();
                    $is_update = FALSE;
                    $existData = NULL;
                    if (!empty($model->dcs_code)) {
                        $dcs_data = $model->getValidDcsModel($model->dcs_code);
                        if (!empty($dcs_data)) {
                            $existData = $dcs_data;
                            $is_update = TRUE;
                        }
                    }
                    if (!$is_update) {
                        Yii::$app->general->validateBMC($model, 'bmc_code', 'bmc_code');
                        $bmc_data = $model->bmcCode;
                        if (!empty($bmc_data)) {
                            $model->mcc_plant_code = $bmc_data->mcc_plant_code;
                            $model->plant_code = $bmc_data->plant_code;
                        }
                        $error = ActiveForm::validate($model);
                    }
//                    $model->dcs_code = $model->getCode();
                    $findField = isset($this->details['update_key']) ? $this->details['update_key'] : '';
                    $excludeField = isset($this->details['exclude_update']) ? $this->details['exclude_update'] : '';
                    if (!empty($findField)) {
                        /* $findFields = explode(',', $findField);
                          foreach ($findFields as $val) {
                          $where[$val] = $model->$val;
                          }
                          $existData = $model::find()->where($where)->one(); */
                        if (!empty($existData) && !empty($excludeField)) {
                            $excludes = [];
                            $exclude = explode(',', $excludeField);
                            foreach ($exclude as $val) {
                                $excludes[] = $val;
                            }
                            $oldVillage = $existData->village_code;
                            $oldMilkType = $existData->milk_type_code;
                            $model = $existData;
                            $model->scenario = 'importCsv';
                            $historyModel = new TblDcsHistory();
                            \Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            array_push($modelList, $historyModel);
                            $this->setAttributes($this->configs, $model, $row, $excludes);
                        } else {
                            $model->dcs_code = $model->getCode();
                            $model->valid_from = date('Y-m-d');
                        }
                    }
                    if ($model->hasAttribute('is_active')) {
                        $nm = ucwords(str_replace('_', ' ', 'is_active'));
                        if (!(preg_match('/^[0-9]*$/', $model->is_active))) {
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                        if ($model->is_active != 0 && $model->is_active != 1) {
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                    }

                    if (empty($model->getErrors()) && $model->validate()) {
                        if (empty($model->vendor)) {
                            $societyVendor = $model->societyVendors;
                            if (!empty($societyVendor)) {
                                $model->vendor = $societyVendor->vendor_code;
                            }
                        } else {
                            if (!$is_update || $model->isAttributeChanged('dpu_type', FALSE)) {
                                $vendorModel = new TblSocietyVendor();
                                $vendorModel->dcs_code = $model->dcs_code;
                                $vendorModelData = $vendorModel->getRecord();
                                if (!empty($vendorModelData)) {
                                    $vendorModelData->vendor_code = $model->vendor;
                                    array_push($modelList, $vendorModelData);
                                } else {
                                    $vendorModel->vendor_code = $model->vendor;
                                    array_push($modelList, $vendorModel);
                                }
                            }
                        }

//                        array_push($modelList, $model);

                        if (!empty($existData) && ($oldVillage != $model->village_code)) {
                            $oldModel = TblDcsVillageMapping::find()->where(['dcs_code' => $model->dcs_code, 'village_code' => $oldVillage])->one();
                            if (!empty($oldModel)) {
                                $mappingHistory = new TblDcsVillageMappingHistory();
                                Yii::$app->operation->history($oldModel, $mappingHistory, DELETE);
                                array_push($modelList, $mappingHistory);
                                array_push($deleteModel, $oldModel);
                            }
                            $modelMapping = new TblDcsVillageMapping();
                            $modelMapping->dcs_code = $model->dcs_code;
                            $modelMapping->village_code = $model->village_code;
                            $modelMapping->is_active = isset($model->is_active) && $model->is_active != NULL ? $model->is_active : 1;
                            array_push($modelList, $modelMapping);
                        } elseif (empty($existData) && !empty($model->village_code)) {
                            $modelMapping = new TblDcsVillageMapping();
                            $modelMapping->dcs_code = $model->dcs_code;
                            $modelMapping->village_code = $model->village_code;
                            $modelMapping->is_active = isset($model->is_active) && $model->is_active != NULL ? $model->is_active : 1;
                            array_push($modelList, $modelMapping);
                        }
                        $errors = [];
                        if (empty($existData)) {
                            $modelCodes = new TblSocietyCodes();
                            $modelCodes->dcs_code = $model->dcs_code;
                            $modelCodes->bipl_code = substr($model->dcs_code, 2, 8);
                            $modelCodes->union_code = $model->union_code;
//                        $modelCodes->bmc_code = NULL;
                            $modelCodes->bmc_code = $model->bmc_code;
                            array_push($modelList, $modelCodes);

                            $model->setbankDetails($model, $modelList, $errors);
                            $model->setContactDetails($model, $modelList, $errors);
                            $model->setModelData($model, $modelList);
                        } else {
                            $defaultBankDetail = $existData->defaultBankDetail;
                            $defaultContactDetail = $existData->defaultContactDetail;
                            if (empty($defaultBankDetail) || $defaultBankDetail->bank_account_no != $model->bank_account_no) {
                                if (!empty($defaultBankDetail)) {
                                    $defaultBankDetail->is_default = 0;
                                    $defaultBankDetail->is_active = 0;
                                    array_push($modelList, $defaultBankDetail);
                                }
                                $model->setbankDetails($model, $modelList, $errors);
                            } elseif (!empty($defaultBankDetail)) {
                                $defaultBankDetail->ifsc = $model->ifsc;
                                $defaultBankDetail->bank_code = $model->bank_code;
                                $defaultBankDetail->branch_code = $model->branch_code;
                                $defaultBankDetail->beneficiary_name = $model->beneficiary_name;
                                if ($defaultBankDetail->isAttributeChanged('ifsc', FALSE) || $defaultBankDetail->isAttributeChanged('bank_code', FALSE) || $defaultBankDetail->isAttributeChanged('branch_code', FALSE) || $defaultBankDetail->isAttributeChanged('beneficiary_name', FALSE)) {
                                    array_push($modelList, $defaultBankDetail);
                                }
                            }
                            if (empty($defaultContactDetail) || $defaultContactDetail->mobile_no != $model->mobile_no) {
                                if (!empty($defaultContactDetail)) {
                                    $defaultContactDetail->is_default = 0;
                                    $defaultContactDetail->is_active = 0;
                                    array_push($modelList, $defaultContactDetail);
                                }
                                $model->setContactDetails($model, $modelList, $errors);
                            } elseif (!empty($defaultContactDetail)) {
                                $defaultContactDetail->department = $model->department;
                                $defaultContactDetail->contact_person = $model->contact_person;
                                $defaultContactDetail->firstname = $model->contact_person;
                                $defaultContactDetail->local_contact_person = $model->local_contact_person;
                                $defaultContactDetail->lastname = $model->middle_name;
                                $defaultContactDetail->surname = $model->surname;
                                $defaultContactDetail->local_lastname = $model->local_middlename;
                                $defaultContactDetail->local_surname = $model->local_surname;
                                if ($defaultContactDetail->isAttributeChanged('department', FALSE) || $defaultContactDetail->isAttributeChanged('contact_person', FALSE) || $defaultContactDetail->isAttributeChanged('firstname', FALSE)) {
                                    array_push($modelList, $defaultContactDetail);
                                }
                            }
                        }
                        if (empty($existData) || ($model->default_milk_type != $model->milk_type_code)) {
                            $existmilkType = TblDcsMilkType::find()->where(['dcs_code' => $model->dcs_code, 'is_active' => 1])->all();
                            if (in_array($model->milk_type_code, [7, 8])) {
                                $milkTypeCode = [1, 2, 3];
                                $existArray = \yii\helpers\ArrayHelper::map($existmilkType, 'milk_type_code', 'milk_type_code');
                                $toRevoke = array_diff($existArray, $milkTypeCode);
                                $toAssign = array_diff($milkTypeCode, $existArray);
                            } else {
                                $milkTypeCode = $model->setMilkType($model->milk_type_code);
                                $existArray = \yii\helpers\ArrayHelper::map($existmilkType, 'milk_type_code', 'milk_type_code');
                                $toRevoke = array_diff($existArray, $milkTypeCode);
                                $toAssign = array_diff($milkTypeCode, $existArray);
                            }
                            foreach ($toRevoke as $value) {
                                $milkoldModel = TblDcsMilkType::find()->where(['dcs_code' => $model->dcs_code, 'milk_type_code' => $value])->one();
                                $milkHistory = new TblDcsMilkTypeHistory();
                                Yii::$app->operation->history($milkoldModel, $milkHistory, DELETE);
                                array_push($modelList, $milkHistory);
                                array_push($deleteModel, $milkoldModel);
                            }
                            foreach ($toAssign as $value) {
                                $modelMilk = new TblDcsMilkType();
                                $modelMilk->dcs_code = $model->dcs_code;
                                $modelMilk->milk_type_code = $value;
                                $modelMilk->is_active = 1;
                                $modelMilk->scenario = 'dcsImport';
                                array_push($modelList, $modelMilk);
                            }
                        }
                        if ($model->isAttributeChanged('route_code', FALSE)) {
                            $oldRoutes = TblRouteMappingSources::find()->where(['from_dest' => $model->dcs_code, 'from_type' => 'society'])->all();
                            if (!empty($oldRoutes)) {
                                foreach ($oldRoutes as $oldRoute) {
                                    $sourcesHistory = new TblRouteMappingSourcesHistory();
                                    Yii::$app->operation->history($oldRoute, $sourcesHistory, DELETE);
                                    array_push($modelList, $sourcesHistory);
                                    array_push($deleteModel, $oldRoute);
                                }
                            }
                            $sourceMapping = new TblRouteMappingSources();
                            $sourceMapping->from_dest = $model->dcs_code;
                            $sourceMapping->route_code = $model->route_code;
                            $sourceMapping->from_type = 'society';
                            $sourceMapping->to_dest = $model->bmc_code;
                            $sourceMapping->to_type = 'bmc';
                            $sourceMapping->is_active = 1;
                            array_push($modelList, $sourceMapping);
                        }
                        $model->default_milk_type = $model->milk_type_code;

                        $productSaleRateApplicabilityAuto = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'product_sale_rate_applicability_auto');
                        if (!empty($productSaleRateApplicabilityAuto)) {
                            $productSaleRateApplicability = new TblProductSaleRateApplicability;
                            $productSaleRate = new TblProductSaleRate;
                            $productSaleRate = $model->getProductSaleRates($model->union_code);
                            if (!empty($productSaleRate)) {
                                foreach ($productSaleRate as $rate) {
                                    $productSaleRateApplicability = new TblProductSaleRateApplicability();
                                    $productSaleRateApplicability->attributes = $rate->attributes;
                                    $productSaleRateApplicability->applicable_for = 'DCS';
                                    $productSaleRateApplicability->applicable_code = $model->dcs_code;
                                    $productSaleRateApplicability->created_at = date('Y-m-d H:i:s');
                                    $productSaleRateApplicability->wef_date = date('Y-m-d H:i:s');
                                    $productSaleRateApplicability->created_by = Yii::$app->user->identity->id;
                                    array_push($modelList, $productSaleRateApplicability);
                                }
                            }
                        }

                        if (!empty($model->employee_id)) {
                            $userModel = new User();
                            $userDetail = $userModel->getUserId($model->employee_id, 'DCS');
                            if (!empty($userDetail)) {
                                $orgMapping = new TblUserOrganizationMapping();
                                $orgMapping->organization_code = $model->dcs_code;
                                $orgMapping->organization_type = 'DCS';
                                $orgMapping->user_id = $userDetail->id;
                                $existingMappings = $orgMapping->getAllUserOrgMapping();
                                if (empty($existingMappings)) {
                                    $orgMapping->is_active = $userDetail->is_active;
                                    Yii::$app->operation->defaults($orgMapping, INSERT);
                                    array_push($modelList, $orgMapping);
                                }
                            }
                        }

                        $master[] = $model->save(TRUE, FALSE);
                        foreach ($modelList as $modelRow) {
                            $is_saved = $modelRow->save();
                            $master[] = $is_saved;
                            if (!$is_saved && empty($errors)) {
                                $errors[] = $modelRow->getErrors();
                            }
                        }
                        foreach ($deleteModel as $delete) {
                            $master[] = $delete->delete();
                        }

                        /*  if ($this->isActiveRecordUnique($uniqueAttributes)) {
                          $importedPks[] = $model->primaryKey;
                          } */
                        $importedPks[] = $model->dcs_code;

                        if (empty($existData) && ($model->auto_member_create == 1)) {
                            $model->autoGenerateMember($master);
                        }

                        if (!in_array(FALSE, $master)) {
                            $trans->commit();
                            $count++;
                        } else {
                            $trans->rollback();
                            $message = '';
                            foreach ($model->getErrors() as $errorkey => $value) {
                                $message .= $value[0] . '<br>';
                            }

                            foreach ($errors as $array) {
                                foreach ($array as $errorkey => $value) {
                                    $message .= $value[0] . '<br>';
                                }
                            }
                            return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                        }
                    } else {
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $message .= $value[0] . '<br>';
                        }
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }

//                    $modelMapping = new TblDcsVillageMapping();
//                    $modelMapping->dcs_code = $model->dcs_code;
//                    $modelMapping->village_code = $model->village_code;
//                    $modelMapping->is_active =$model->is_active;
//                    array_push($modelList, $modelMapping);
//                                      
//                    foreach ($modelList as $modelRow){
//                        $master[] = $modelRow->save();
//                    }
//                 
//                    if ($this->isActiveRecordUnique($uniqueAttributes)) {
//                              $importedPks[] = $model->primaryKey;
//                    }    
//                    if (!in_array(FALSE, $master)) {
//                        $trans->commit();
//                        $count++;
//                    } else {
//                        $trans->rollback();
//                        $message='';
//                        foreach($model->getErrors() as $errorkey => $value){
//                            $message.=$value[0].'<br>';
//                        }
//                        return ['total'=>0,'status'=>'error','pk'=>0,'msg'=>'There is error in Record No : '.$key.'<br>'.$message];
//                    }
                } catch (UserException $e) {
                    $trans->rollback();
                    return ['total' => 0, 'status' => 'error', 'msg' => $e->getMessage(), 'pk' => 0];
                }
            }
        }
        if ($count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)/* ,'error'=>$errors */];
        }
    }

    public function setAttributes($configs, &$model, $row, $excludes = []) {
        foreach ($configs as $config) {
            if (isset($config['attribute']) && !in_array($config['attribute'], $excludes)) {
                $value = call_user_func($config['value'], $row);
                if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                    //Create array of unique attributes
                    if (isset($config['unique']) && $config['unique']) {
                        $uniqueAttributes[$config['attribute']] = $value;
                    }
                    //Set value to the model
                    ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                    $addedAttributes[$config['attribute']] = $config['attribute'];
                } else if (property_exists($model, $config['attribute'])) {
                    //Set value to the model of public attribute
                    $model->{$config['attribute']} = $value;
                    $addedAttributes[$config['attribute']] = $config['attribute'];
                }
            }
        }
    }

}
