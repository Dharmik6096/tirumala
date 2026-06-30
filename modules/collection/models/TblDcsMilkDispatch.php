<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\collection\models\TblDcsMilkDispatchTxn;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\collection\models\TblMilkCollection;
use app\modules\sms\models\TblApiMaster;

/**
 * This is the model class for table "tbl_dcs_milk_dispatch".
 *
 * @property integer $dcs_milk_dispatch_code
 * @property string $challan_no
 * @property string $date_time_of_dispatch
 * @property integer $shift_code
 * @property integer $dispatch_type
 * @property integer $destination_type
 * @property string $destination_code
 * @property string $vehicle_no
 * @property string $vehicle_in_time
 * @property string $vehicle_out_time
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $route_code
 * @property string $remarks
 */
class TblDcsMilkDispatch extends \app\models\ChildModel {

    public $from_date, $to_date, $from_shift, $to_shift, $name, $dcs, $status;
    public $saveChildRecords = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_milk_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['date_time_of_dispatch', 'created_at', 'updated_at', 'dcs_milk_dispatch_code'], 'safe', 'on' => ['androidsync']],
                [['challan_no', 'destination_code', 'vehicle_no', 'vehicle_in_time', 'vehicle_out_time', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'route_code', 'remarks', 'antibiotic'], 'safe'],
                [['date_time_of_dispatch', 'created_at', 'updated_at', 'dcs'], 'safe'],
                [['shift_code', 'dispatch_type', 'destination_type', 'originating_type', 'dcs_milk_dispatch_code', 'received_timestamp'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'on' => ['create', 'update']],
                [['date_time_of_dispatch'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_dispatch', 'bmc_code', 'BMC', 'DCS', ['data_lock_bmc', 'billing_lock_bmc']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_milk_dispatch_code' => Yii::t('app', 'Dcs Milk Dispatch Code'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'date_time_of_dispatch' => Yii::t('app', 'Dispatch Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'dispatch_type' => Yii::t('app', 'Dispatch Type'),
            'destination_type' => Yii::t('app', 'Dest. Type'),
            'destination_code' => Yii::t('app', 'Destination'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'vehicle_in_time' => Yii::t('app', 'In Time'),
            'vehicle_out_time' => Yii::t('app', 'Out Time'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'route_code' => Yii::t('app', 'Route Code'),
            'remarks' => Yii::t('app', 'Remarks'),
            'antibiotic' => Yii::t('app', 'Antibiotic Test'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'destination_code']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'destination_code']);
    }

    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'destination_code']);
    }

    public function getDcsMilkDispatch() {
        return $this->hasOne(TblDcsMilkDispatchTxn::className(), ['dcs_milk_dispatch_code' => 'dcs_milk_dispatch_code']);
    }

    public function getExistingDispatch($data) {
        return $this->find()->where(['dcs_code' => $data->dcs_code, 'date_time_of_dispatch' => $data->date_time_of_collection, 'shift_code' => $data->shift_code])->one();
    }

    public function validateUnique(&$model, $txModel) {
        $flag = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_approval', 'PORTAL');

        $mainTable = $this->find()->where(['bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code, 'date_time_of_dispatch' => $this->date_time_of_dispatch, 'shift_code' => $this->shift_code])->one();

        if (!empty($mainTable)) {
            $txnModel = new TblDcsMilkDispatchTxn();
            $mainTableData = $txnModel->find()->where(['dcs_milk_dispatch_code' => $mainTable->dcs_milk_dispatch_code, 'dcs_code' => $this->dcs_code, 'milk_type_code' => $txModel->milk_type_code, 'milk_quality_type_code' => $txModel->milk_quality_type_code])->one();
        }
        $ApprovalModel = new TblCollectionDataAlias();
        $approvalTableData = $ApprovalModel->find()->where(['bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code, 'date_time_of_collection' => $this->date_time_of_dispatch, 'shift_code' => $this->shift_code, 'milk_type_code' => $txModel->milk_type_code, 'milk_quality_type_code' => $txModel->milk_quality_type_code, 'table_name' => 'tbl_dcs_milk_dispatch'])->one();
        if (($flag == 1 && !empty($approvalTableData)) || !empty($mainTableData)) {
            $model->addError('date_time_of_dispatch', "Milk Dispatch Already Exists.");
        }
    }

    public function getExistingData($data) {
        return $this->find()->where(['dcs_code' => $data->dcs_code, 'date_time_of_dispatch' => $data->date_time_of_dispatch, 'shift_code' => $data->shift_code])->one();
    }

    public function setTransactionDataModel($model) {
        $union = $model->union_code;
        $apiData = TblApiMaster::find()->where(['union_code' => $union, 'receiver_type' => 'SMS', 'is_active' => 1])->one();
        $unionData = TblUnions::find()->where(['union_code' => $union, 'is_active' => 1])->one();
        $dcsData = TblDcs::find()->where(['union_code' => $union, 'dcs_code' => $model->dcs_code])->one();
        $eiplCode = !empty($unionData->eipl_code) ? ($unionData->eipl_code) : '';
        $antibioticCheck = !empty($dcsData->antibiotic_check) ? ($dcsData->antibiotic_check) : '';
        if ((Yii::$app->session->get('eiplCode') == 'PRABHAT' || $eiplCode == 'PRABHAT') && $antibioticCheck == 1) {
            $antibioticTest = $model->antibiotic;
            $pconfig = Yii::$app->general->getUnionConfiguration($union, 'antibiotic_positive', 'PORTAL');
            $nconfig = Yii::$app->general->getUnionConfiguration($union, 'antibiotic_negative', 'PORTAL');
            $p_config = !empty($pconfig) ? (float) $pconfig : 0;
            $n_config = !empty($nconfig) ? (float) $nconfig : 0;
            if (!empty($antibioticTest)) {
                $milkCollection = new TblMilkCollection();
                $collectionData = $milkCollection->getCollectionData($model);

                $sms_data = [];
                $templateModel = new TblAlertTemplate();
                $module = strtolower($antibioticTest) == 'not tested' ? 'antibiotic_not_test' : (strtolower($antibioticTest) == 'ab+' ? 'antibiotic_positive' : 'antibiotic_negative');
                $templateData = $templateModel->getTemplateData($module, 'SMS', $union);
                if (!empty($collectionData) && !empty($templateData)) {
                    foreach ($collectionData as $collection) {
                        $memberName = Yii::$app->general->getforeignkey($collection->memberCode, 'member_name');
                        $mobile = Yii::$app->general->getforeignkey($collection->memberCode, 'mobile_no');
                        $farmerCode = Yii::$app->general->getforeignkey($collection->memberCode, 'farmer_code');
                        $excode = Yii::$app->general->getforeignkey($collection->memberCode, 'ex_member_code');
                        $date = date("d M Y", strtotime($collection->date_time_of_collection));
                        $shift = $collection->shift_code == 1 ? 'M' : ($collection->shift_code == 2 ? 'E' : $collection->shift_code);
                        $milk_type = strtoupper(Yii::$app->general->getforeignkey($collection->milkTypeCode, 'short_name'));
                        (float) $qty = $collection->qty;
                        (float) $fat = $collection->fat;
                        (float) $snf = $collection->snf;
                        (float) $rtpl = $collection->rtpl;
                        (float) $amount = $collection->amount;
                        $arrFrom = '';
                        $arrTo = '';
                        $ex_code = !empty($farmerCode) ? $farmerCode : $excode;

                        if (!empty($mobile)) {
                            if (strtolower($antibioticTest) == 'not tested') {
                                $arrFrom = array("{member_name}", "{member_code_ex}", "{date}", "{shift}", "{milk_type}", "{qty}", "{fat}", "{snf}", "{rate}", "{amount}");
                                $arrTo = array($memberName, $ex_code, $date, $shift, $milk_type, $qty, $fat, $snf, $rtpl, bcdiv($amount, 1, 2));
                            } else if (strtoupper($antibioticTest) == 'AB+') {
                                $flagVal = $p_config * $qty;
                                $mainAmount = $amount + $flagVal;
                                $arrFrom = array("{member_name}", "{member_code_ex}", "{date}", "{shift}", "{milk_type}", "{qty}", "{fat}", "{snf}", "{rate}", "{amount}", "{flag}", "{cal_val}");
                                $arrTo = array($memberName, $ex_code, $date, $shift, $milk_type, $qty, $fat, $snf, $rtpl, bcdiv($mainAmount, 1, 2), $p_config, bcdiv($flagVal, 1, 2));
                            } else if (strtoupper($antibioticTest) == 'AB-') {
                                $flagVal = (float) $n_config * (float) $qty;
                                $mainAmount = $amount + $flagVal;
                                $arrFrom = array("{member_name}", "{member_code_ex}", "{date}", "{shift}", "{milk_type}", "{qty}", "{fat}", "{snf}", "{rate}", "{amount}", "{flag}", "{cal_val}");
                                $arrTo = array($memberName, $ex_code, $date, $shift, $milk_type, $qty, $fat, $snf, $rtpl, bcdiv($mainAmount, 1, 2), $n_config, bcdiv($flagVal, 1, 2));
                            }
                            $word = $templateData->message;
                            $message = str_replace($arrFrom, $arrTo, $word);
                            $sms_data['refecence_code'] = (string) $collection->milk_collection_code;
                            $sms_data['module_type'] = $module;
                            $content_id = '';
                            if(!empty($templateData->api_master_id)){
                                $content_id = $templateData->api_master_id;
                            } else if(!empty($apiData->api_master_id)){
                                $content_id = $apiData->api_master_id;
                            }
//                            if (YII_ENV_DEV) {
//                                
//                            } else {
                            Yii::$app->general->saveAlertNotification($mobile, $message, $sms_data, TRUE, $templateData->header_info, $content_id);
                            $collection->updateAll(['antibiotic_sms_sent' => 1], ['milk_collection_code' => $collection->milk_collection_code]);
//                            }
                        }
                    }
                }
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
//        if ($flag == 'INSERT') {
        $this->setTransactionDataModel($this);
//        }
    }

    public function setTransactionData(&$model, $json, &$childModel) {
        $modelData = $model->find()->where(['dcs_milk_dispatch_code' => $model->dcs_milk_dispatch_code])->one();

        if (!empty($modelData)) {
            if ($model->x_col1 == $modelData->x_col1) {
                $model = $modelData;
            } else {
                $model->x_col2 = $model->dcs_milk_dispatch_code;
                $model->dcs_milk_dispatch_code = Yii::$app->general->getUuid();
            }
        }
    }

}
