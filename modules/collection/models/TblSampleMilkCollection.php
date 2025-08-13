<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\dcsoperation\models\TblShift;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblBmcMilkType;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\dcsoperation\models\TblDcsPurchaseRateBased;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;

/**
 * This is the model class for table "tbl_sample_milk_collection".
 *
 * @property integer $sample_milk_collection_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $route_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $qty
 * @property integer $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $rtpl
 * @property string $amount
 * @property integer $purchase_rate_code
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property integer $milk_analyser_type_code
 * @property integer $ws_code
 * @property string $source_of_milk
 * @property string $remarks
 * @property string $version_no
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property string $device_lat
 * @property string $device_long
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblSampleMilkCollection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sample_milk_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'own_bmc_code', 'own_mcc_plant_code', 'device_lat', 'device_long', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'qty', 'converted_qty', 'rtpl', 'amount', 'shift_code', 'milk_type_code', 'milk_quality_type_code', 'qty_mode', 'converted_qty_mode', 'purchase_rate_code', 'qlty_auto', 'qty_auto', 'milk_analyser_type_code', 'ws_code', 'originating_type', 'date_time_of_collection', 'qlty_time', 'qty_time', 'created_at', 'updated_at', 'version_no', 'source_of_milk', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['union_code'], 'required', 'on' => ['androidsync']],
            [['union_code', 'plant_code', 'mcc_plant_code', 'fat', 'snf', 'bmc_code', 'milk_type_code', 'shift_code', 'dcs_code', 'qty', 'milk_quality_type_code', 'amount', 'rtpl'], 'required'],
            [['dcs_code'], 'pastDateValidate', 'on' => ['create']],
            [['dcs_code'], 'unique', 'targetAttribute' => ['date_time_of_collection', 'shift_code', 'dcs_code', 'milk_type_code']],
            [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', 'DCS', ['data_lock_member', 'billing_lock_member']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create']],
            [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->shiftLock($this, 'date_time_of_collection', 'mcc_plant_code', 'qty', 'member_lock');
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sample_milk_collection_code' => Yii::t('app', 'Sample Milk Collection Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'route_code' => Yii::t('app', 'Route'),
            'date_time_of_collection' => Yii::t('app', 'Collection Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'qty' => Yii::t('app', 'Qty'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'source_of_milk' => Yii::t('app', 'Source Of Milk'),
            'remarks' => Yii::t('app', 'Remarks'),
            'version_no' => Yii::t('app', 'Version No'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'device_lat' => Yii::t('app', 'Device Lat'),
            'device_long' => Yii::t('app', 'Device Long'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function pastDateValidate($attribute, $params) {
        $date_time_of_collection = !empty($this->date_time_of_collection) ? date('Y-m-d', strtotime($this->date_time_of_collection)) : NULL;
        if (!empty($date_time_of_collection) && ($date_time_of_collection > date('Y-m-d'))) {
            $this->addError('date_time_of_collection', Yii::t('app/validation', $this->getAttributeLabel('date_time_of_collection') . ' Must be smaller than ' . date('d.m.Y')));
        }
    }

    public function calculateData($flag, $union = '', $bmcCode = '', $fat = '', $snf = '', $milk_type = '', $data = []) {
        $response = [];
        $flagArray = [];
        if (!is_array($flag)) {
            $flagArray[] = $flag;
        } else {
            $flagArray = $flag;
        }
//calculate clr
        if (in_array('calculate_clr', $flagArray)) {
            $lr1 = Yii::$app->general->getCheckBmcConfiguration($union, 'clr_constant1', $bmcCode, 'BMC', 'MEMBER_COLLECTION');
            $lr2 = Yii::$app->general->getCheckBmcConfiguration($union, 'clr_constant2', $bmcCode, 'BMC', 'MEMBER_COLLECTION');

            if ($lr1 == '' or $lr2 == '') {
                $lr1 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant1', 'VLC');
                $lr2 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant2', 'VLC');
            }
            (float) $lr1 = empty($lr1) ? 1 : $lr1;
            (float) $lr2 = empty($lr2) ? 0 : $lr2;
            $clr = ($snf - ($fat * $lr1) - $lr2) * 4;
            $response['clr'] = $clr;
        }

        if (in_array('check_fat_range', $flagArray)) {
//calculate fat range
            $range = Yii::$app->general->getUnionConfiguration($union, 'buf_min_fat_range_member', 'PORTAL');
            $mapping = new TblBmcMilkType();
            $mapped = $mapping->find()->where(['bmc_code' => $bmcCode, 'is_active' => 1])->all();

            if (!empty($range) && !empty($mapped) && count($mapped) == 2) {
                $type = [];
                foreach ($mapped as $map) {
                    $type[] = $map->milk_type_code;
                }
//Cow and Buffalo
                if (in_array(1, $type) && in_array(2, $type)) {
                    if ($range < $fat && $milk_type != 2) {
                        $response['status'] = 'success';
                        $response['data'] = 2;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Buffalo');
                    } elseif ($range >= $fat && $milk_type != 1) {
                        $response['status'] = 'success';
                        $response['data'] = 1;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Cow');
                    }
                }
//Cow and Mix
                if (in_array(1, $type) && in_array(3, $type)) {
                    if ($range < $fat && $milk_type != 3) {
                        $response['status'] = 'success';
                        $response['data'] = 3;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Mix');
                    } elseif ($range >= $fat && $milk_type != 1) {
                        $response['status'] = 'success';
                        $response['data'] = 1;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Cow');
                    }
                }
//Buffalo and Mix
                if (in_array(2, $type) && in_array(3, $type)) {
                    if ($range < $fat && $milk_type != 3) {
                        $response['status'] = 'success';
                        $response['data'] = 3;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Mix');
                    } elseif ($range >= $fat && $milk_type != 2) {
                        $response['status'] = 'success';
                        $response['data'] = 2;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Buffalo');
                    }
                }
            }
        }

        if (in_array('rtpl_calculate', $flagArray)) {
            //calculate rtpl 
            $model = new TblDcsPurchaseRateApplicabitity();
            $model->wef_date = $data['dt_date'];
            $data['milk_type'] = $data['milk_type'];
            $data['milk_quality_type_code'] = $data['milk_quality_type'];
            $data['appl_for'] = !empty($data['customer_type']) ? $data['customer_type'] : 'DCS';
            $data['appl_code'] = $data['dcs_code'];
            $model_data = $model->getDcsPurchaseRateApplicableData($data);

            if (!empty($model_data)) {
                if ($model_data->rate_gen_method_code == '4') {
                    $model->purchase_rate_code = $model_data->purchase_rate_code;
                    $purchase_rate_data = $model->getDcsPurchaseRateData($data);
                    if (!empty($purchase_rate_data)) {
                        $purchase_model = new TblDcsPurchaseRateBased();
                        $purchase_model->rate_type = $purchase_rate_data->rate_app_code;
                        $rate_type = !empty($purchase_model->rateTypeCode) ? $purchase_model->rateTypeCode->rate_type : '';
                        $purchase_data = $purchase_model->getDcsPurchaseRateData($model_data, $rate_type);
                        $kgfatRate = 0.00;
                        $kgsnfRate = 0.00;
                        $kgclrRate = 0.00;
                        $kgtsRate = 0.00;
                        $qty = $data['qty'];
                        $fat = $data['fat'];
                        $snf = $data['snf'];
                        $clr = $data['clr'];
                        $formula = '';
                        foreach ($purchase_data as $value) {
                            if ($value['param'] == 'FAT') {
                                $kgfatRate = $value['kg_rate'];
                            } else if ($value['param'] == 'SNF') {
                                $kgsnfRate = $value['kg_rate'];
                            } else if ($value['param'] == 'CLR') {
                                $kgclrRate = $value['kg_rate'];
                            } else if ($value['param'] == 'TS') {
                                $kgtsRate = $value['kg_rate'];
                            }
                            $formula = $value['formula'];
                        }
                        $formula = str_replace('kgFATRate', number_format($kgfatRate, 2, '.', ''), $formula);
                        $formula = str_replace('kgSNFRate', number_format($kgsnfRate, 2, '.', ''), $formula);
                        $formula = str_replace('kgCLRRate', number_format($kgclrRate, 2, '.', ''), $formula);
                        $formula = str_replace('kgTSRate', number_format($kgtsRate, 2, '.', ''), $formula);
                        $formula = str_replace('qty', number_format($qty, 2, '.', ''), $formula);
                        $formula = str_replace('fat', number_format($fat, 2, '.', ''), $formula);
                        $formula = str_replace('snf', number_format($snf, 2, '.', ''), $formula);
                        $formula = str_replace('clr', number_format((float) $clr, 2, '.', ''), $formula);
                        $command = \Yii::$app->db->createCommand("SELECT $formula as rtpl");
                        $result = $command->queryAll();
                        if (!empty($result)) {
                            $result[0]['rtpl'] = round(bcdiv($result[0]['rtpl'], $qty, 3), 2);
                            $response['status'] = 'success';
                            $rtpl_data['list'] = $result[0];
                            $response['data'] = $rtpl_data;
                        }
                    }
                } else {
                    $detail_model = new TblDcsPurchaseRateDetails();
                    $detail_model->rate_type_code = $model_data->rate_app_code;
                    $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
                    $rate_type = !empty($detail_model->rateTypeCode) ? $detail_model->rateTypeCode->rate_type : '';
                    $detail_data = $detail_model->getDcsPurchasseRateDetailData($data, $rate_type);
                    if (!empty($detail_data)) {
                        $response['status'] = 'success';
                        $rtpl_data['list'] = $detail_data;
                        $response['data'] = $rtpl_data;
                    }
                }
            }
        }
        return $response;
    }

    public function setCollectionData(&$model, $flag) {
        $datetime = date('Y-m-d H:i:s');
        $model->date_time_of_collection = empty($model->date_time_of_collection) ? NULL : Yii::$app->controls->view_date($model->date_time_of_collection, 'php:Y-m-d') . ' ' . Yii::$app->general->getshift($model->shift_code);
        $model->qlty_time = $datetime;
        $model->qty_time = $datetime;
        $model->qty_mode = 0;
        $model->qlty_auto = 0;
        $model->qty_auto = 0;
        $model->route_code = Yii::$app->general->getforeignkey($model->dcsCode, 'route_code');
        $model->own_mcc_plant_code = $model->mcc_plant_code;
        $model->own_bmc_code = $model->bmc_code;
        $model->scenario = 'create';
        $model->qty_mode = Yii::$app->general->getUnionConfiguration($model->union_code, 'collection_qty_mode', 'VLC');
        $conversion_const = Yii::$app->general->getUnionConfiguration($model->union_code, 'ltr_to_kg_constant', 'VLC');
        $model->converted_qty_mode = $model->qty_mode == 1 ? 0 : 1;
        $conversion_const = empty($conversion_const) ? 1 : $conversion_const;
        $model->converted_qty = $model->qty_mode == 1 ? $model->qty / $conversion_const : $model->qty * $conversion_const;
    }

}
