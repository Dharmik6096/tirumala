<?php

namespace app\components;

use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblRouteMapping;
use ruskid\csvimporter\ARImportStrategy;
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
                    foreach ($this->configs as $config) {
                        $value = call_user_func($config['value'], $row);
                        if (isset($config['attribute']) && ($model->hasAttribute($config['attribute']))) {
                            ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                        } else if (property_exists($model, $config['attribute'])) {
                            $model->{$config['attribute']} = $value;
                        }
                    }
                    $this->setData($model, $row);
                    if ($model->validate()) {
                        $model->operation = 'INSERT';
                        $existData = $model->find()->where([$model->master_key => $model->{$model->master_key}, 'master_type' => $model->master_type, 'is_active' => 1])->one();
                        $pattern = \Yii::$app->general->getKeyPattern($model->master_type);
                        if(!empty($pattern) && $pattern['master_hierarchy_auto_entry'] == 1){
                            if(!empty($existData)){
                                foreach ($model->attributes as $attribute => $value) {
                                    if (empty($existData->{$attribute})) {
                                        $existData->{$attribute} = $value;
                                    }
                                }
                                $existData->operation = 'UPDATE';
                                \Yii::$app->general->setKeyPatternChild($pattern, $existData, $existData->master_type, $existData->{$existData->master_key}, $existData->master_key, $existData, true);
                                $modelList = $existData;
                            } else {
                                $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $model->master_type)));
                                if($modelName == 'TblBmc'){
                                    $modelName = 'TblDcsBmc';
                                }
                                $modelName = Yii::$app->path->define($modelName);
                                $modelMaster = $modelName::find()->where(['or', [$model->master_key => $row[2]], ['ref_code' => $row[2]]])->one();
                                \Yii::$app->general->setKeyPatternChild($pattern, $modelMaster, $model->master_type, $model->{$model->master_key}, $model->master_key, $model);                                
                                $modelList = $model;
                            }
                            if (empty($modelList->getErrors())) {
                                $modelList->set_master_hierarchy = [];
                                $master[] = $modelList->save();
                                if (!in_array(FALSE, $master)) {
                                    $trans->commit();
                                    $count++;
                                    $importedPks[] = $modelList->primaryKey;
                                } else {
                                    $trans->rollback();
                                    $message = '';
                                    foreach ($modelList->getErrors() as $errorkey => $value) {
                                        $message .= $value[0] . '<br/>';
                                    }
                                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                                }
                            } else {
                                $message = '';
                                foreach ($modelList->getErrors() as $errorkey => $value) {
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

    public function setData(&$model, $row)
    {
        $mapping = [
            'MEMBER'   => ['tbl_member', 'member_code', TblMember::class],
            'CUSTOMER' => ['tbl_customer_master', 'customer_code', TblCustomerMaster::class],
            'ROUTE'    => ['tbl_route_mapping', 'route_code', TblRouteMapping::class],
            'DCS'      => ['tbl_dcs', 'dcs_code', TblDcs::class],
            'BMC'      => ['tbl_bmc', 'bmc_code', TblDcsBmc::class],
            'MCC'      => ['tbl_mcc_plant', 'mcc_plant_code', TblMccPlant::class],
            'PLANT'    => ['tbl_plant', 'plant_code', TblPlant::class]
        ];

        $type = strtoupper($row[1]);
        if (isset($mapping[$type])) {
            [$master_type, $master_key, $class] = $mapping[$type];
            $modelData = $class::find()->where(['or', [$master_key => $row[2]], ['ref_code' => $row[2]]])->one();
            if ($modelData) {
                if ($type === 'ROUTE') {
                    $route_code = $modelData->route_code ?? null;
                    $model->route_code = $route_code;
                    $to_type = strtoupper($modelData->to_type);
                    
                    switch ($to_type) {
                        case 'BMC':
                            $modelData = $modelData->dcsBmcCode;
                            break;
                        case 'MCC':
                            $modelData = $modelData->mccCode;
                            break;
                        case 'PLANT':
                            $modelData = $modelData->activePlantCode;
                            break;
                    }
                } else if ($type === 'MEMBER') {
                    $model->member_code = $modelData->member_code ?? null;
                    $modelData = $modelData->dcsCode;
                }

                $model->master_type = $master_type;
                $model->master_key = $master_key;
                $model->customer_code = $modelData->customer_code ?? null;
                $model->dcs_code = $modelData->dcs_code ?? null;
                $model->bmc_code = $modelData->bmc_code ?? null;
                $model->mcc_plant_code = $modelData->mcc_plant_code ?? null;
                $model->plant_code = $modelData->plant_code ?? null;
            }
        }
    }
}
