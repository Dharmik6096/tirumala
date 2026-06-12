<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblShift;
use app\modules\collection\models\TblMilkCollection;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblDcsMilkDispatchTxn;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\syncutility\models\TblSentbox;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_collection_data_alias".
 *
 * @property integer $collection_data_alias_code
 * @property string $table_name
 * @property string $action_perform
 * @property string $member_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property integer $bmc_silos_info_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $name
 * @property string $mobile_no
 * @property integer $sample_no
 * @property string $type_of_data_receive
 * @property string $purchase_rate_code
 * @property integer $qty_mode
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $no_of_can
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property string $route_code
 * @property string $converted_qty
 * @property string $remarks
 * @property string $sync_status
 * @property integer $converted_qty_mode
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $incentive
 * @property string $deduction
 * @property string $total_amount
 * @property integer $send_status
 * @property string $transporter_code
 * @property integer $collection_type
 * @property string $date_time_of_testing
 * @property string $converted_can
 * @property integer $doc_no
 * @property string $vehicle_no
 * @property string $route_arrival_time
 * @property string $old_qty
 * @property string $old_fat
 * @property string $old_snf
 * @property string $old_rtpl
 * @property string $old_clr
 * @property string $old_amount
 * @property string $old_milk_type_code
 * @property string $old_milk_quality_type_code
 * @property integer $old_no_of_can
 * @property integer $old_purchase_rate_code
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
 */
class TblCollectionDataAlias extends \app\models\ChildModel {

    public $from_date, $to_date, $from_shift, $to_shift, $operation, $process_approval_code;
    public $qty_auto_sum, $qty_manual_sum, $bmc_collection_amount;
    public $amount_auto_sum, $amount_manual_sum;
    public $is_sentbox = False;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_data_alias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['date_time_of_collection', 'date_time_of_recieve', 'qlty_time', 'qty_time', 'date_time_of_testing', 'route_arrival_time', 'created_at', 'updated_at', 'old_milk_quality_type_code', 'old_milk_type_code', 'shift_code', 'own_bmc_code', 'antibiotic_sms_sent', 'antibiotic', 'is_sms_sent', 'old_customer_code', 'can_no', 'old_route_code', 'old_antibiotic', 'converted_amount', 'process_approval_code', 'approved_at', 'approved_by', 'approval_status', 'vehicle_code', 'is_sentbox', 'fat', 'snf', 'clr', 'water', 'qty', 'rtpl', 'amount', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'converted_can', 'old_qty', 'old_fat', 'old_snf', 'old_rtpl', 'old_clr', 'old_amount', 'bmc_silos_info_code', 'milk_type_code', 'milk_quality_type_code', 'sample_no', 'qty_mode', 'no_of_can', 'converted_qty_mode', 'send_status', 'collection_type', 'doc_no', 'old_no_of_can', 'old_purchase_rate_code', 'originating_type', 'table_name', 'action_perform', 'member_code', 'dcs_code', 'customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'name', 'mobile_no', 'type_of_data_receive', 'purchase_rate_code', 'route_code', 'remarks', 'sync_status', 'transporter_code', 'vehicle_no', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'qlty_auto', 'qty_auto'], 'safe'],
                [['dcs_code'], 'validateMilkCollection', 'on' => ['MilkCollection']],
                [['dcs_code'], 'validateCollectionAlias', 'on' => ['MilkCollection']],
                [['customer_code'], 'validateBmcCollection', 'on' => ['BmcCollection']],
                [['dcs_code'], 'validateMilkDispatch', 'on' => ['MilkDispatch']],
                [['dcs_code'], 'required', 'on' => ['androidsync']],
                [['error_desc'], 'string', 'on' => ['approve']],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        $flag = ['data_lock_bmc', 'billing_lock_bmc'];
                        $type = 'DCS';
                        if ($this->table_name == 'tbl_milk_collection') {
                            $flag = ['data_lock_member', 'billing_lock_member'];
                            $type = 'DCS';
                        }
                        if ($this->table_name == 'tbl_bmc_collection') {
                            $type = $this->customer_type;
                        }
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', $type, $flag);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['MilkCollection', 'BmcCollection', 'MilkDispatch']],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->shiftLock($this, 'date_time_of_collection', 'mcc_plant_code', '', 'member_lock');
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['MilkCollection']],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->shiftLock($this, 'date_time_of_collection', 'mcc_plant_code', '', 'bmc_lock');
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['BmcCollection']],
                [['bmc_code'], 'setNoOfCan'],
                [['is_antibiotic', 'scheme_rate', 'scheme_rate_code', 'actual_rate'], 'safe'],
                [['bmc_code'], 'convertedAmount'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'collection_data_alias_code' => Yii::t('app', 'Collection Data Alias Code'),
            'table_name' => Yii::t('app', 'Table Name'),
            'action_perform' => Yii::t('app', 'Action Perform'),
            'member_code' => Yii::t('app', 'Member'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'customer_type' => Yii::t('app', 'Type'),
            'customer_code' => Yii::t('app', 'Name'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'clr' => Yii::t('app', 'CLR'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'amount' => Yii::t('app', 'Amount'),
            'shift_code' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'route_code' => Yii::t('app', 'Route Code'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'remarks' => Yii::t('app', 'Remarks'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'incentive' => Yii::t('app', 'Incentive'),
            'deduction' => Yii::t('app', 'Deduction'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'send_status' => Yii::t('app', 'Send Status'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'collection_type' => Yii::t('app', 'Collection Type'),
            'date_time_of_testing' => Yii::t('app', 'Date Time Of Testing'),
            'converted_can' => Yii::t('app', 'Converted Can'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'route_arrival_time' => Yii::t('app', 'Route Arrival Time'),
            'old_qty' => Yii::t('app', 'Old Qty'),
            'old_fat' => Yii::t('app', 'Old FAT'),
            'old_snf' => Yii::t('app', 'Old SNF'),
            'old_rtpl' => Yii::t('app', 'Old RTPL'),
            'old_clr' => Yii::t('app', 'Old CLR'),
            'old_amount' => Yii::t('app', 'Old Amount'),
            'old_milk_type_code' => Yii::t('app', 'Old Milk Type'),
            'old_milk_quality_type_code' => Yii::t('app', 'Old Milk Quality Type'),
            'old_no_of_can' => Yii::t('app', 'Old No Of Can'),
            'old_purchase_rate_code' => Yii::t('app', 'Old Rate Code'),
            'tag_1' => Yii::t('app', 'Tag 1'),
            'tag_2' => Yii::t('app', 'Tag 2'),
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
            'old_customer_code' => Yii::t('app', 'Old Code'),
            'old_route_code' => Yii::t('app', 'Old Route Code'),
        ];
    }

    public function setOldAttributesValues(&$model) {
        $model->old_qty = $model->qty;
        $model->old_fat = $model->fat;
        $model->old_snf = $model->snf;
        $model->old_rtpl = $model->rtpl;
        $model->old_amount = $model->amount;
        $model->old_milk_type_code = $model->milk_type_code;
        $model->old_milk_quality_type_code = $model->milk_quality_type_code;
        $model->old_purchase_rate_code = $model->purchase_rate_code;
        $model->old_clr = $model->clr;
        $model->old_antibiotic = $model->antibiotic;
        if ($model->table_name == 'tbl_bmc_collection') {
            $model->old_no_of_can = $model->no_of_can;
        }
    }

    public function setModelAttributes($model, &$saveModel) {
        $saveModel->qty = $model->dispatch_qty;
        $saveModel->fat = $model->avg_fat;
        $saveModel->snf = $model->avg_snf;
        $saveModel->amount = $model->total_amount;
        $saveModel->clr = $model->avg_clr;
        $saveModel->no_of_can = $model->nos_of_can;

        $saveModel->old_qty = $saveModel->qty;
        $saveModel->old_fat = $saveModel->fat;
        $saveModel->old_snf = $saveModel->snf;
        $saveModel->old_rtpl = $model->rtpl;
        $saveModel->old_amount = $saveModel->amount;
        $saveModel->old_milk_type_code = $model->milk_type_code;
        $saveModel->old_milk_quality_type_code = $model->milk_quality_type_code;
        $saveModel->old_purchase_rate_code = $model->purchase_rate_code;
        $saveModel->old_clr = $model->avg_clr;
        $saveModel->old_no_of_can = $model->nos_of_can;
    }

    public function getMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getOldMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'old_milk_quality_type_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getOldMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'old_milk_type_code']);
    }

    public function validateMilkCollection($attribute, $params) {
        $MainModel = new TblMilkCollection();
        if (($this->fat != $this->old_fat || $this->snf != $this->old_snf || $this->qty != $this->old_qty || $this->milk_type_code != $this->old_milk_type_code || $this->antibiotic != $this->old_antibiotic)) {
            $MainModel->milkTypeWiseUnique($MainModel, $this, FALSE, FALSE, TRUE);
        } else {
            $MainModel->milkTypeWiseUnique($MainModel, $this);
        }
    }

    public function validateCollectionAlias($attribute, $params) {
        if (empty($this->getErrors($attribute))) {
            $bmcCollection = TblBmcCollection::find()
                ->where([
                    'dcs_code' => $this->dcs_code,
                    'shift_code' => $this->shift_code,
                ])
                ->andWhere(['CAST(date_time_of_collection AS DATE)' => Yii::$app->formatter->asDate($this->date_time_of_collection, 'php:Y-m-d')])
                ->sum('amount');
            
            $bmcCollectionQty = (float)$bmcCollection;

            $farmerCollection = TblMilkCollection::find()
                ->where([
                    'dcs_code' => $this->dcs_code,
                    'shift_code' => $this->shift_code,
                ])
                ->andWhere(['CAST(date_time_of_collection AS DATE)' => Yii::$app->formatter->asDate($this->date_time_of_collection, 'php:Y-m-d')])
                ->sum('amount');
                
            $farmerCollectionQty = (float)$farmerCollection;
            
            $selection = Yii::$app->request->post('selection');
            $isBatchApprove = (!empty($selection) && is_array($selection));

            if ($isBatchApprove) {
                $aliasCodes = [];
                foreach ($selection as $val) {
                    $codes = explode('###', $val);
                    $aliasCodes[] = $codes[0];
                }
                
                // Get all selected pending models for this DCS and Shift
                $selectedModels = TblCollectionDataAlias::find()
                    ->where(['collection_data_alias_code' => $aliasCodes])
                    ->andWhere(['dcs_code' => $this->dcs_code, 'shift_code' => $this->shift_code])
                    ->andWhere(['CAST(date_time_of_collection AS DATE)' => Yii::$app->formatter->asDate($this->date_time_of_collection, 'php:Y-m-d')])
                    ->andWhere(['approval_status' => ['Pending', 'Inprogress']])
                    ->all();
                
                $batchAmountDelta = 0;
                foreach ($selectedModels as $model) {
                    if ($model->action_perform == 'UPDATE') {
                        $batchAmountDelta += ((float)$model->amount - (float)$model->old_amount);
                    } else if ($model->action_perform == 'DELETE') {
                        $batchAmountDelta -= (float)$model->old_amount;
                    } else {
                        $batchAmountDelta += (float)$model->amount;
                    }
                }
                $totalFarmerQty = $farmerCollectionQty + $batchAmountDelta;
            } else {
                if ($this->action_perform == 'UPDATE') {
                    $farmerCollectionQty -= (float)$this->old_amount;
                } else if ($this->action_perform == 'DELETE') {
                    $farmerCollectionQty -= (float)$this->old_amount;
                    $this->amount = 0;
                }
                $totalFarmerQty = $farmerCollectionQty + (float)$this->amount;
            }

            if ($totalFarmerQty > $bmcCollectionQty) {
                $this->addError($attribute, Yii::t('app', 'FAMER collection not greater than BMC collection of respective MPP for date and shift'));
            }
        }
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function validateBmcCollection($attribute, $params) {
        $MainModel = new TblBmcCollection();
        if (($this->customer_code != $this->old_customer_code || $this->fat != $this->old_fat || $this->snf != $this->old_snf || $this->qty != $this->old_qty || $this->milk_type_code != $this->old_milk_type_code || $this->milk_quality_type_code != $this->old_milk_quality_type_code)) {
            $MainModel->milkTypeWiseUnique($MainModel, $this, FALSE, FALSE, TRUE);
        } else if ($this->action_perform == 'UPDATE' && ($this->antibiotic != $this->old_antibiotic || $this->route_code != $this->old_route_code)) {
            
        } else {
            $MainModel->milkTypeWiseUnique($MainModel, $this);
        }
    }

    public function validateMilkDispatch($attribute, $params) {
        $MainModel = new TblDcsMilkDispatch();
        $txModel = new TblDcsMilkDispatchTxn();
        $mainTableData = $MainModel->find()->where(['bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code, 'date_time_of_dispatch' => $this->date_time_of_collection])->one();

        if (($this->fat != $this->old_fat || $this->snf != $this->old_snf || $this->qty != $this->old_qty || $this->milk_type_code != $this->old_milk_type_code || $this->milk_quality_type_code != $this->old_milk_quality_type_code)) {
            if (!empty($mainTableData)) {
                $query = $txModel->find()->where(['dcs_code' => $this->dcs_code, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code, 'dispatch_qty' => $this->qty, 'avg_fat' => $this->fat, 'avg_snf' => $this->snf]);
                if ($this->milk_type_code == $this->old_milk_type_code && $this->milk_quality_type_code == $this->old_milk_quality_type_code) {
                    $query->andWhere(['!=', 'milk_type_code', $this->old_milk_type_code]);
                }
                $txTableData = $query->one();
            }
        } else {
            if (!empty($mainTableData)) {
                $txTableData = $txModel->find()->where(['dcs_code' => $this->dcs_code, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code, 'dispatch_qty' => $this->qty, 'avg_fat' => $this->fat, 'avg_snf' => $this->snf])
                        ->one();
            }
        }
        if (!empty($txTableData)) {
            $this->addError($attribute, "Record is Already Exist");
        }
    }

    public function getExistApproval() {
        return $this->find()->where(['dcs_code' => $this->dcs_code, 'member_code' => $this->member_code, 'date_time_of_collection' => $this->date_time_of_collection, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code, 'qty' => $this->qty, 'fat' => $this->fat, 'snf' => $this->snf, 'table_name' => 'tbl_milk_collection', 'action_perform' => 'DELETE'])
                        ->one();
    }

    public function setNoOfCan() {
        if (empty($this->getErrors())) {
            $configCanParLtr = Yii::$app->general->getUnionConfiguration($this->union_code, 'can_per_ltr', 'BMC');
            if (!empty($configCanParLtr)) {
                $this->no_of_can = ceil($this->qty / $configCanParLtr);
            }
        }
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getOldRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'old_route_code']);
    }

    public function convertedAmount($attribute, $params) {
        if (!empty($this->converted_qty) && !empty($this->rtpl)) {
            $this->converted_amount = $this->converted_qty * $this->rtpl;
        }
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function afterSave($insert, $changedAttributes) {
        if ($this->is_sentbox == TRUE) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
            if (in_array($this->originating_org_type, ['VLC', 'BMC']) && in_array($this->originating_type, ['23', '24']) && $flag == 'INSERT') {
                $sentbox = $this->sentboxModel($this->originating_org_code, $this->originating_org_type, $this->union_code);
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        if ($this->is_sentbox == TRUE) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : 'DELETE';
            if (in_array($this->originating_org_type, ['VLC', 'BMC']) && in_array($this->originating_type, ['23', '24'])) {
                $sentbox = $this->sentboxModel($this->originating_org_code, $this->originating_org_type, $this->union_code);
                if (!$sentbox->setSentbox($this, $flag)) {
                    throw new UserException("SentBox entry is not created, so transaction is rolled back!");
                }
            }
        }
    }

    private function sentboxModel($code, $type, $union) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $union;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
