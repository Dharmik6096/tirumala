<?php

namespace app\components;

use yii\widgets\ActiveForm;
use Yii;
use app\modules\assetmanagement\models\TblAssetDetail;
use app\modules\assetmanagement\models\TblAssetDetailHistory;
use app\modules\assetmanagement\models\TblAssetTransactionHistory;
use app\modules\assetmanagement\models\TblAssetTransaction;
use app\modules\assetmanagement\models\TblAssetSet;
use app\modules\assetmanagement\models\TblAssetSetHistory;
use app\modules\import\ARImportStrategy;
use yii\base\UserException;

class AssetDetailImportStrategy extends ARImportStrategy {

    public function import(&$data) {
        $importedPks = [];
        $importedData = [];
        $errors = [];
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
                    $detailModel = new TblAssetDetail();
                    if (!empty($this->scenario)) {
                        $model->scenario = $this->scenario;
                        $detailModel->scenario = $this->scenario;
                    }
                    foreach ($this->configs as $config) {
                        $value = call_user_func($config['value'], $row);
                        if (isset($config['attribute']) && ($model->hasAttribute($config['attribute']) || $detailModel->hasAttribute($config['attribute']))) {
                            //Set value to the model
                            ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                            ($detailModel->hasAttribute($config['attribute'])) ? $detailModel->setAttribute($config['attribute'], $value) : '';
                        } else if (property_exists($model, $config['attribute'])) {
                            //Set value to the model of public attribute
                            $model->{$config['attribute']} = $value;
                        }
                    }
                    $error = ActiveForm::validate($model);
                    $add_mode = FALSE;
                    if (!empty($model->assetCode)) {
                        if ($model->assetCode->is_serial_number == 1) {
                            $detailModelNew = TblAssetDetail::find()->where(['asset_code' => $model->asset_code, 'serial_number' => $model->serial_number])->one();
                            $model->qty = 1;
                            $add_mode = TRUE;
                        } else {
                            if ($model->from_type == 'VEN' && $model->in_use != '1') {
                                $detailModelNew = TblAssetDetail::find()->where(['asset_code' => $detailModel->asset_code,
                                            'purchase_date' => $detailModel->purchase_date,
                                            'manufacturer_code' => $model->from_dest,
                                            'store_location_code' => $model->to_dest])->one();
                                $add_mode = TRUE;
                                if (!empty($detailModelNew)) {
                                    $trans->rollback();
                                    $message = 'Asset Inward has already been taken.';
                                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                                }
                            } else {
                                $out_qty = $model->qty;
                                $from_qty = $model->getQtySum('from');
                                $to_qty = $model->getQtySum('to');
                                $diffQty = $to_qty - $from_qty;
                                if ($diffQty < $out_qty) {
                                    $diffQty = ($diffQty < 0) ? 0 : $diffQty;
                                    $trans->rollback();
                                    $message = Yii::t('app', 'Quantity can not be greater than ') . $diffQty;
                                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                                } ELSE {
                                    $out_qty = $model->qty;
                                    $trn_model = TblAssetTransaction::find()->where(['tbl_asset_transaction.to_type' => $model->from_type, 'tbl_asset_transaction.to_dest' => $model->from_dest, 'tbl_asset_transaction.asset_code' => $model->asset_code])
                                            ->joinWith(['assetDetail'])
                                            ->andWhere(['!=', 'tbl_asset_transaction.remain_qty', 0])
                                            ->orderBy('tbl_asset_detail.purchase_date ASC')
                                            ->all();
                                    $cnt = 0;
                                    while ($out_qty != 0) {
                                        $historyModel = new TblAssetTransactionHistory();
                                        Yii::$app->operation->history($trn_model[$cnt], $historyModel, UPDATE);
                                        $modelList[] = $historyModel;
                                        $act_qty = $trn_model[$cnt]->remain_qty;
                                        $trn_model[$cnt]->remain_qty = ($act_qty >= $out_qty) ? $act_qty - $out_qty : 0;
                                        ($trn_model[$cnt]->remain_qty == 0) ? $trn_model[$cnt]->status = 1 : NULL;
                                        $trn_model[$cnt]->transaction_date = $model->transaction_date;
                                        $out_model = new TblAssetTransaction();
                                        $out_model->asset_detail_code = $trn_model[$cnt]->asset_detail_code;
                                        $out_model->asset_code = $trn_model[$cnt]->asset_code;
                                        $out_model->from_type = $trn_model[$cnt]->to_type;
                                        $out_model->from_dest = $trn_model[$cnt]->to_dest;
                                        $out_model->to_type = $model->to_type;
                                        $out_model->to_dest = $model->to_dest;
                                        $out_model->serial_number = $trn_model[$cnt]->serial_number;
                                        $out_model->transaction_date = $model->transaction_date;
                                        $out_model->put_to_use_date = $model->transaction_date;
                                        $out_model->qty = ($out_qty >= $act_qty) ? $act_qty : $out_qty;
                                        $out_model->remain_qty = $out_model->qty;
                                        if ($model->in_use == '1') {
                                            $out_model->status = 2;
                                        } else {
                                            $out_model->status = ($model->to_type == 3) ? 2 : (($model->from_type == 'VEN') ? 0 : '-1');
                                        }
                                        /* if ($out_model->status == 2) {
                                          $out_model->remain_qty = 0;
                                          } */
                                        $out_model->union_code = $trn_model[$cnt]->union_code;
                                        $out_model->sap_code = $model->sap_code;
                                        $modelList[] = $out_model;
                                        $modelList[] = $trn_model[$cnt];
                                        if ($act_qty >= $out_qty) {
                                            $out_qty = 0;
                                        } else {
                                            $out_qty-=$act_qty;
                                        }
                                        $cnt++;
                                    }
                                }
                            }
                        }
                      
                        if (!empty($detailModelNew)) {
                            $detailModel = $detailModelNew;
                        } else {
                            $detailModel->asset_detail_code = Yii::$app->general->getCodeAutoIncrement($detailModel);
                            $detailModel->store_location_code = $model->to_dest;
                            $detailModel->manufacturer_code = ($model->from_type == 'VEN') ? $model->from_dest : NULL;
                            $detailModel->put_to_use_date = $model->transaction_date;
                        }
                        $model->put_to_use_date = $model->transaction_date;
                        $model->remain_qty = $model->qty;
                        $model->asset_detail_code = $detailModel->asset_detail_code;
                    } else {
                        $message = Yii::t('app', 'Asset is invalid.');
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }
                    $error = ActiveForm::validate($model);
                    $detailerror = ActiveForm::validate($detailModel);
                    if (empty($model->getErrors()) && empty($detailModel->getErrors()) && $model->validate() && $detailModel->validate()) {
                        if ($model->assetCode->is_serial_number == 1) {
                            if ($model->from_type == 'VEN' && $model->getVENEntry() > 0) {
                                $trans->rollback();
                                $message = 'Asset Inward from VEN already taken.';
                                return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                            }
                            $outward = $model->getPreviousEntry();
                          
                            if (!empty($outward) && in_array($outward->status, [-1, 0, 2])) {
                                $historyModel = new TblAssetTransactionHistory();
                                \Yii::$app->operation->history($outward, $historyModel, 'UPDATE');
                                $modelList[] = $historyModel;
                                $outward->status = 1;
                                $modelList[] = $outward;
                            } else if ($model->from_type != 'VEN') {
                                $trans->rollback();
                                $message = 'Asset Not available In-Store/In-Transit.';
                                return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                            }
                        } else {
                            /*   if ($model->status == 2) {
                              $model->remain_qty = 0;
                              } */
                        }
                        if ($add_mode) {
                            $modelList[] = $model;
                            $modelList[] = $detailModel;
                        }
                        if (!empty($model->sap_code)) {
                            if ($model->to_type == 3 || $model->in_use) {
                                $check_data = TblAssetSet::find()
                                        ->where(['store_location_type' => $model->to_type, 'store_location_code' => $model->to_dest, 'status' => [2]])
                                        ->andWhere(['!=', 'sap_code', $model->sap_code])
                                        ->count();
                                $check_data += TblAssetTransaction::find()
                                        ->where(['to_type' => $model->to_type, 'to_dest' => $model->to_dest, 'status' => [2]])
                                        ->andWhere(['!=', 'sap_code', $model->sap_code])
                                        ->count();
                                if ($check_data > 0) {
                                    $trans->rollback();
                                    $message = Yii::t('app', 'Other Asset Set already availabe on destination.');
                                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                                }
                            }
                            $trn = TblAssetTransaction::find()->where(['to_dest' => $model->from_dest, 'to_type' => $model->from_type, 'status' => [-1, 0, 2], 'sap_code' => $model->sap_code])
                                    ->count();
                            $check_cnt = $trn - 1;
                            if ($check_cnt <= 0) {
                                $asset_set = TblAssetSet::find()->where(['sap_code' => $model->sap_code, 'status' => [-1, 0, 2]])->all();
                                $addset = TRUE;
                                if (!empty($asset_set)) {
                                    foreach ($asset_set as $set) {
                                        $historyModel = new TblAssetSetHistory();
                                        Yii::$app->operation->history($set, $historyModel, UPDATE);
                                        $modelList[] = $historyModel;
                                        if ($set->store_location_code == $model->to_dest) {
                                            $addset = FALSE;
                                        }
                                        $set->status = ($set->store_location_code == $model->to_dest) ? $model->status : 1;
                                        $set->is_active = ($set->status == 1) ? 0 : 1;
                                        $modelList[] = $set;
                                    }
                                }
                                if ($addset) {
                                    $set = new TblAssetSet();
                                    $set->store_location_code = $model->to_dest;
                                    $set->attributes = $set->storeLocCode->attributes;
                                    $set->sap_code = $model->sap_code;
                                    $set->status = $model->status;
                                    $modelList[] = $set;
                                }
                            }
                        }
                        foreach ($modelList as $modelRow) {
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
                                $message.=$value[0] . '<br/>';
                            }
                            foreach ($detailModel->getErrors() as $errorkey => $value) {
                                $message.=$value[0] . '<br/>';
                            }
                            return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                        }
                    } else {
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $message.=$value[0] . '<br/>';
                        }
                        foreach ($detailModel->getErrors() as $errorkey => $value) {
                            $message.=$value[0] . '<br/>';
                        }
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }
                } catch (UserException $e) {
                    $trans->rollback();
                    return ['total' => 0, 'status' => 'error', 'msg' => $e->getMessage(), 'pk' => 0];
                }
            }
        }
        if ($count == count($data) || $count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)];
        }
    }

}
