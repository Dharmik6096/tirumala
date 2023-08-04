<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii;
use app\modules\import\ARImportStrategy;
use yii\widgets\ActiveForm;

class MemberImportStrategy extends ARImportStrategy {

    /**
     * Will multiple import data into table
     * @param array $data CSV data passed by reference to save memory.
     * @return array Primary keys of imported data
     */
    public function import(&$data) {

        $importedPks = [];
        $count = 0;
        $dcs_code='';
        $data = array_filter($data, function($var) {
            return !empty($var[0]) && !is_null($var);
        });
        $data = array_filter($data);

        foreach ($data as $key => $row) {

            $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

            if ($key == 0)
                continue;

            if (!$skipImport) {
                /* @var $model \yii\db\ActiveRecord */
                $model = new $this->className;
                if (!empty($this->scenario))
                    $model->scenario = $this->scenario;

                $uniqueAttributes = [];
                $addedAttributes = [];
                foreach ($this->configs as $config) {
                    if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                        $value = call_user_func($config['value'], $row);

                        //Create array of unique attributes
                        if (isset($config['unique']) && $config['unique']) {
                            $uniqueAttributes[$config['attribute']] = trim($value);
                        }
                        $addedAttributes [] = $config['attribute'];

                        //Set value to the model
                        $model->setAttribute($config['attribute'], trim($value));
                    }
                }
                if(isset($this->defaultFields)){
                    foreach ($this->defaultFields as $config) {

                        if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                            // $value = call_user_func($config['value'], $row);
                            //Create array of unique attributes
                            if (isset($config['unique']) && $config['unique']) {
                                $uniqueAttributes[$config['attribute']] = trim($config['value']);
                            }

                            //Set value to the model
                            $model->setAttribute($config['attribute'], trim($config['value']));
                        }
                    }
                }
                if(isset($model->dcsCode)) {
                    $model->state_code=$model->dcsCode->state_code;
                    $model->district_code=$model->dcsCode->district_code;
                    $model->sub_district_code=$model->dcsCode->sub_district_code;                
                    $model->village_code=$model->dcsCode->village_code;               
                }
                $model->member_code = $model->getCode();
                if(isset($model->no_of_buffalo) || isset($model->no_of_cow_cross) || isset($model->no_of_cow_ind)) {
                    isset($model->no_of_buffalo)? $no_of_buffalo = $model->no_of_buffalo : $no_of_buffalo = 0;
                    isset($model->no_of_cow_cross)? $no_of_cow_cross = $model->no_of_cow_cross : $no_of_cow_cross = 0;
                    isset($model->no_of_cow_ind)? $no_of_cow_ind = $model->no_of_cow_ind : $no_of_cow_ind = 0;
                    $model->total_animals = (int)$no_of_buffalo + (int)$no_of_cow_cross + (int)$no_of_cow_ind;
                }
                $error = ActiveForm::validate($model);
                if ($model->save()) {
                    $count++;
                } else {
                    $message = '';                    
                    foreach ($model->getErrors() as $errorkey => $value) {
                        $message.=$value[0] . '<br>';
                    }
                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message/* ,'error'=>$errors */];
                }
                if ($this->isActiveRecordUnique($uniqueAttributes)) {
                    $importedPks[] = $model->primaryKey;
                    $dcs_code=$model->dcs_code;
                }
                
            }
        }
        if ($count == count($data) || $count == count($data) - 1) {
//            if(!empty($dcs_code))
//            {
//                if(Yii::$app->general->isVendor($dcs_code,'BIPL')){
//                    $model=new \app\modules\dcsoperation\models\TblMember();
//                    $model->dcs_code=$dcs_code;
//                    $model->generateBiplMemberFiles();
//                }
//            }
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)/* ,'error'=>$errors */];
        }
//        } catch (UserException $e) {
//            $trans->rollback();
//            return ['total'=>0,'status'=>'error','msg'=>$e->getMessage(),'pk'=>0/*,'error'=>$errors*/];
////            echo $e->getMessage();
//        }
//        return ['total'=>count($data),'pk'=>$importedPks/*,'error'=>$errors*/];
    }

}
