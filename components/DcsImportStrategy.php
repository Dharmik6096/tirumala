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
use app\modules\organisation\models\TblSocietyCollection;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\general\models\TblDpuIncentiveMaster;

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
//                        $model->dcs_code=$model->getCode();


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

                    $modelList = [];
                    $model->mcc_plant_code = Yii::$app->general->getforeignkey($model->bmcCode, 'mcc_plant_code');
                    $model->plant_code = Yii::$app->general->getforeignkey($model->mccPlantCode, 'plant_code');
                    $model->setModel();
                    $error = ActiveForm::validate($model);

                    if ($model->hasAttribute('is_active')) {
                        $nm = ucwords(str_replace('_', ' ', 'is_active'));
                        if (!(preg_match('/^[0-9]*$/', $model->is_active))) {
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                        if ($model->is_active != 0 && $model->is_active != 1) {
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                    }
//                    array_push($modelList, $model);

                    if (empty($model->getErrors()) && $model->validate()) {
                        array_push($modelList, $model);
                        if (!empty($model->village_code)) {
                            $modelMapping = new TblDcsVillageMapping();
                            $modelMapping->dcs_code = $model->dcs_code;
                            $modelMapping->village_code = $model->village_code;
                            $modelMapping->is_active = isset($model->is_active) && $model->is_active != NULL ? $model->is_active : 1;
                            array_push($modelList, $modelMapping);
                        }
                        $modelCodes = new TblSocietyCodes();
                        $modelCodes->dcs_code = $model->dcs_code;
                        $modelCodes->bipl_code = substr($model->dcs_code, 2, 8);
                        $modelCodes->union_code = $model->union_code;
                        $modelCodes->bmc_code = NULL;
                        array_push($modelList, $modelCodes);
//                        var_dump($model->dcs_code);exit;
//                        $list = $model->setSubCenter('I',$this->scenario);
//
//                        foreach ($list as $row){
//                            array_push($modelList, $row);
//                        }

                        $society_model = new TblSocietyCollection();
                        $society_model->dcs_code = $model->dcs_code;
                        $society_model->from_date = date('Y-m-d H:i:s');
                        $society_model->status = 1;
                        $society_model->remarks = NULL;
                        array_push($modelList, $society_model);

                        $member_rate_model = new TblPurchaseRate();
                        $member_rate_data = $member_rate_model->getRecord($model->rate_chart_member);
                        if (!empty($member_rate_data)) {
                            $member_applicability = new TblPurchaseRateApplicability();
                            $member_applicability->purchase_rate_code = $member_rate_data->purchase_rate_code;
                            $member_applicability->wef_date = $member_rate_data->wef_date;
                            $member_applicability->shift_code = $member_rate_data->shift_id;
                            $member_applicability->union_code = $model->union_code;
                            $member_applicability->dcs_code = $model->dcs_code;
                            array_push($modelList, $member_applicability);
                        }

                        $incentive_model = new TblDpuIncentiveMaster();
                        $incentive_model->dcs_code = $model->dcs_code;
                        $incentive_model->m_cutoff_time = '12:30';
                        $incentive_model->e_cutoff_time = '22:30';
                        $incentive_model->m_start_time = '01:00';
                        $incentive_model->e_start_time = '14:00';
                        $incentive_model->m_lock_time = '13:55';
                        $incentive_model->e_lock_time = '23:55';
                        $incentive_model->inc_rate = 0.0;
                        $incentive_model->inc_deduction = 0.0;
                        $incentive_model->union_code = $model->union_code;
                        array_push($modelList, $incentive_model);

                        foreach ($modelList as $modelRow) {
                            $master[] = $modelRow->save();
                        }

                        if ($this->isActiveRecordUnique($uniqueAttributes)) {
                            $importedPks[] = $model->primaryKey;
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

}
