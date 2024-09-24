<?php

namespace app\components;

use app\models\TblKeyPatternChild;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMasterHierarchy;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use ruskid\csvimporter\ARImportStrategy;
use yii\widgets\ActiveForm;
use Yii;
use yii\base\UserException;

class MasterHerarchyImportStrategy extends ARImportStrategy {

    public function import(&$data) {
        $importedPks = [];
        $count = 0;

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
                    $modelList = [];
                    $model = new $this->className;
                    if (!empty($this->scenario)) {
                        $model->scenario = $this->scenario;
                    }
                    // $whereCondition = ['is_active' => 1];
                    foreach ($this->configs as $config) {
                        $value = call_user_func($config['value'], $row);
                        if (isset($config['attribute']) && ($model->hasAttribute($config['attribute']))) {
                            //Set value to the model
                            ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                        } else if (property_exists($model, $config['attribute'])) {
                            //Set value to the model of public attribute
                            $model->{$config['attribute']} = $value;
                        }
                    }
                    $this->setData($model, $row);
                    if ($model->validate()) {
                        $model->operation = 'INSERT';
                        $existData = $model->find()->where([$model->master_key => $model->{$model->master_key}, 'is_active' => 1])->one();
                        $pattern = \Yii::$app->general->getKeyPattern($model->master_type);
                        if(!empty($pattern) && $pattern['master_hierarchy_auto_entry'] == 1){
                            if(!empty($existData)){
                                for ($i = 1; $i <= 5; $i++) {
                                    $ref_code_key = 'ref_code'.$i;
                                    if(empty($existData->{$ref_code_key})){
                                        $existData->{$ref_code_key} = $model->{$ref_code_key};
                                    }   
                                }
                                $existData->operation = 'UPDATE';
                                \Yii::$app->general->setKeyPatternChild($pattern, $existData, $existData->master_type, $existData->{$existData->master_key}, $existData->master_key, $existData);
                                $modelList[] = $existData;
                            } else {
                                \Yii::$app->general->setKeyPatternChild($pattern, $model, $model->master_type, $model->{$model->master_key});                                
                                $modelList[] = $model;
                            }
                            foreach ($modelList as $modelRow) {
                                $modelRow->set_master_hierarchy = [];
                                $master[] = $modelRow->save();
                            }
                            if (!in_array(FALSE, $master)) {
                                $trans->commit();
                                $count++;
                                $importedPks[] = $model->primaryKey;
                            } else {
                                $trans->rollback();
                                $message = '';
                                foreach ($model->getErrors() as $errorkey => $value) {
                                    $message .= $value[0] . '<br/>';
                                }
                                return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                            }
                        }
                    } else {
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $message .= $value[0] . '<br/>';
                        }
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }
                } catch (UserException $e) {
                    $trans->rollback();
                    return ['total' => 0, 'status' => 'error', 'msg' => $e->getMessage(), 'pk' => 0];
                }
            }
        }
        if ($count == (count($data) - 1)) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)];
        }
    }

    public function setData(&$model, $row) {
        $member_code = '';
        switch (strtoupper($row[1])) {
            case 'MEMBER':
                $modelData = TblMember::find()->where(['or', ['member_code' => $row[2]], ['ref_code' => $row[2]]])->one();
                if(!empty($modelData)){
                    $member_code = $modelData['member_code'];
                    $modelData = $modelData->dcsCode;
                }
                break;

            case 'CUSTOMER':
                $modelData = TblCustomerMaster::find()->where(['or', ['customer_code' => $row[2]], ['ref_code' => $row[2]]])->one();
                break;

            case 'DCS':
                $modelData = TblDcs::find()->where(['or', ['dcs_code' => $row[2]], ['ref_code' => $row[2]]])->one();
                break;
        
            case 'BMC':
                $modelData = TblDcsBmc::find()->where(['or', ['bmc_code' => $row[2]], ['ref_code' => $row[2]]])->one();
                break;
        
            case 'MCC':
                $modelData = TblMccPlant::find()->where(['or', ['mcc_plant_code' => $row[2]], ['ref_code' => $row[2]]])->one();
                break;
        
            case 'PLANT':
                $modelData = TblPlant::find()->where(['or', ['plant_code' => $row[2]], ['ref_code' => $row[2]]])->one();
                break;
        
            default:
                $modelData = null;
                break;
        }
        if(!empty($modelData)){
            $model->master_type = $modelData::tableName();
            $model->master_key = $modelData->primaryKey()[0];
            $model->member_code = !empty($member_code) ? $member_code : NULL;
            $model->customer_code = !empty($modelData['customer_code']) ? $modelData['customer_code'] : NULL;
            $model->route_code = !empty($modelData['route_code']) ? $modelData['route_code'] : NULL;
            $model->dcs_code = !empty($modelData['dcs_code']) ? $modelData['dcs_code'] : NULL;
            $model->bmc_code = !empty($modelData['bmc_code']) ? $modelData['bmc_code'] : NULL;
            $model->mcc_plant_code = !empty($modelData['mcc_plant_code']) ? $modelData['mcc_plant_code'] : NULL;
            $model->plant_code = !empty($modelData['plant_code']) ? $modelData['plant_code'] : NULL;
        }
    }
}
