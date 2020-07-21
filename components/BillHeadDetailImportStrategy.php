<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use ruskid\csvimporter\ARImportStrategy;
use Yii;
use app\modules\vsp\models\TblBillHeadInstallment;

class BillHeadDetailImportStrategy extends ARImportStrategy {

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
                    $model->bill_head_detail_code = Yii::$app->general->getCodeAutoIncrement($model);
                    if (empty($model->getErrors()) && $model->validate()) {
                        $modelList[] = $model;
                        $model->is_active = 1;
                        $model->originating_type = 1;
                        $no = !empty($model->no_installment) ? ($model->no_installment) : 1;
                        $cycleModel = new \app\modules\payment\models\TblPaymentCycle();
                        $cycle = $model->validatePaymentCycle();
                        for ($i = 0; $i < $no; $i++) {
                            $instModel = new TblBillHeadInstallment();
                            $instModel->bill_head_detail_code = $model->bill_head_detail_code;
                            $instModel->bill_head_code = $model->bill_head_code;
                            $instModel->union_code = $model->union_code;
                            $instModel->customer_code = $model->customer_code;
                            $instModel->dcs_code = $model->dcs_code;
                            if (!empty($instModel->dcs_code)) {
                                $customer_type = 'DCS';
                            } else {
                                $customer_type = $model->customer_type;
                            }
                            $instModel->customer_type = $model->customer_type;
                            $instModel->bill_head_for = $model->bill_head_for;
                            $instModel->installement_cycle = ($i + 1);
                            $instModel->installment_amount = floatval($model->amount / $no);
//                            $instModel->payment_cycle_code = $cycle;
//                            $instModel->installment_date = Yii::$app->general->getforeignkey($instModel->paymentCycleCode, 'from_date');
                            $modelList[] = $instModel;
//                            if ($model->no_installment > $i + 1) {
//                                $cycle = $cycleModel->getNextCycleCode($instModel->payment_cycle_code, $model->bmc_code, $customer_type, 'BMC');
//                                if (empty($cycle)) {
//                                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . Yii::t('app/validation', 'Payment Cycle Applicability is not available For Future Installment.')];
//                                }
//                            }
                        }

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
