<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\organisation\models\TblDcs;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\configuration\models\TblUnionRatechartRange;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;

/**
 * This is the model class for table "tbl_dcs_milk_dispatch_txn".
 *
 * @property integer $dcs_milk_dispatch_txn_code
 * @property integer $dcs_milk_dispatch_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $nos_of_can
 * @property string $dispatch_qty
 * @property string $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $avg_clr
 * @property string $water
 * @property string $temperature
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $total_amount
 */
class TblDcsMilkDispatchTxn extends \app\models\ChildModel {

    public $from_date, $to_date, $from_shift, $to_shift, $status, $date_time_of_dispatch, $shift_code, $bmc_code, $union_code, $dispatch_type;
    public $plant_code, $mcc_plant_code, $ref_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_milk_dispatch_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
            [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'milk_type_code');
                }, 'on' => 'importCsv'],
            [['milk_quality_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'milk_quality_type_code');
                }, 'on' => 'importCsv'],
            [['milk_type_code', 'shift_code', 'milk_quality_type_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
            [['date_time_of_dispatch'], 'convertDateDot', 'on' => ['importCsv']],
            [['date_time_of_dispatch'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['date_time_of_dispatch'], 'convertDate', 'on' => ['importCsv']],
            [['dcs_milk_dispatch_code', 'dcs_milk_dispatch_txn_code'], 'safe', 'on' => ['androidsync']],
            [['dcs_milk_dispatch_code', 'milk_quality_type_code', 'milk_type_code', 'nos_of_can', 'converted_qty_mode'], 'safe'],
            [['dispatch_qty', 'qty_mode', 'converted_qty', 'avg_fat', 'avg_snf', 'avg_clr', 'water', 'temperature', 'total_amount', 'purchase_rate_code', 'rtpl'], 'safe'],
            [['dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_at', 'updated_at', 'dcs_milk_dispatch_txn_code'], 'safe'],
            [['dcs_code', 'milk_type_code', 'milk_quality_type_code', 'dispatch_qty', 'avg_fat', 'avg_snf'], 'required', 'on' => ['create', 'update', 'importCsv']],
            [['rtpl'], 'required', 'on' => ['create', 'update']],
            [['water', 'avg_clr', 'nos_of_can'], 'default', 'value' => 0],
            [['nos_of_can'], 'integer', 'min' => 0, 'on' => ['create', 'update']],
            [['avg_clr'], 'double', 'min' => 0, 'on' => ['create', 'update']],
            [['milk_type_code'], 'validateUpdate', 'on' => ['update']],
            [['dispatch_qty'], 'double', 'min' => 0.01, 'message' => Yii::t('app/validation', '{attribute} must be greater than 0'), 'on' => ['create', 'update']],
            [['bmc_code', 'shift_code', 'date_time_of_dispatch', 'union_code'], 'safe'],
            [['bmc_code', 'shift_code', 'date_time_of_dispatch'], 'required', 'on' => ['importCsv']],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
            [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id'], 'on' => ['importCsv']],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'on' => ['importCsv']],
            [['milk_quality_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type_code' => 'milk_quality_type_code'], 'on' => ['importCsv']],
            [['dcs_code'], 'importSet', 'on' => ['importCsv']],
            [['date_time_of_dispatch'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_dispatch', 'bmc_code', 'BMC', 'DCS', ['data_lock_bmc', 'billing_lock_bmc']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['importCsv']],
            [['dcs_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->validateRateRange($this, 'avg_fat', 'avg_snf');
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_milk_dispatch_txn_code' => Yii::t('app', 'Dcs Milk Dispatch Txn Code'),
            'dcs_milk_dispatch_code' => Yii::t('app', 'Dcs Milk Dispatch Code'),
            'milk_quality_type_code' => Yii::t('app', 'Quality Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'nos_of_can' => Yii::t('app', 'Can'),
            'dispatch_qty' => Yii::t('app', 'Qty'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'avg_fat' => Yii::t('app', 'FAT'),
            'avg_snf' => Yii::t('app', 'SNF'),
            'avg_clr' => Yii::t('app', 'CLR'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'water' => Yii::t('app', 'Water'),
            'temperature' => Yii::t('app', 'Temperature'),
            'dcs_code' => Yii::t('app', 'Code'),
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
            'total_amount' => Yii::t('app', 'Total Amount'),
        ];
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getDcsMilkDispatch() {
        return $this->hasOne(TblDcsMilkDispatch::className(), ['dcs_milk_dispatch_code' => 'dcs_milk_dispatch_code']);
    }

    public function getApprovalData() {
        return $this->hasOne(TblCollectionDataAlias::className(), ['dcs_code' => 'dcs_code', 'old_milk_type_code' => 'milk_type_code', 'old_milk_quality_type_code' => 'milk_quality_type_code'])->andOnCondition(['tbl_collection_data_alias.table_name' => 'tbl_dcs_milk_dispatch', 'action_perform' => 'DELETE']);
    }

    public function setModelData($model, &$saveModel) {
        $saveModel->dispatch_qty = $model->qty;
        $saveModel->avg_fat = $model->fat;
        $saveModel->avg_snf = $model->snf;
        $saveModel->total_amount = $model->amount;
        $saveModel->avg_clr = $model->clr;
        $saveModel->nos_of_can = $model->no_of_can;
    }

    public function getTxnExistingCollection($data) {
        return $this->find()->where(['dcs_milk_dispatch_code' => $this->dcs_milk_dispatch_code, 'dcs_code' => $data->dcs_code, 'milk_type_code' => $data->old_milk_type_code, 'milk_quality_type_code' => $data->old_milk_quality_type_code])->one();
    }

    public function validateUpdate($attribute, $params) {
        $union = Yii::$app->general->getforeignkey($this->dcsMilkDispatch, 'union_code');
        $bmc = Yii::$app->general->getforeignkey($this->dcsMilkDispatch, 'bmc_code');
        $shift = Yii::$app->general->getforeignkey($this->dcsMilkDispatch, 'shift_code');
        $date = Yii::$app->general->getforeignkey($this->dcsMilkDispatch, 'date_time_of_dispatch');
        $flag = Yii::$app->general->getUnionConfiguration($union, 'collection_approval', 'PORTAL');

        $ApprovalModel = new TblCollectionDataAlias();
        $oldMilktype = $this->oldAttributes['milk_type_code'];
        $oldMilkqlttype = $this->oldAttributes['milk_quality_type_code'];
        if (!empty($this->oldAttributes) && ($this->avg_fat != $this->oldAttributes['avg_fat'] || $this->avg_snf != $this->oldAttributes['avg_snf'] || $this->dispatch_qty != $this->oldAttributes['dispatch_qty'] || $this->milk_type_code != $this->oldAttributes['milk_type_code'] || $this->milk_quality_type_code != $this->oldAttributes['milk_quality_type_code'] || $this->nos_of_can != $this->oldAttributes['nos_of_can'])) {

            $existTableData = $ApprovalModel->find()->where(['bmc_code' => $bmc, 'dcs_code' => $this->dcs_code, 'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($date)), 'shift_code' => $shift, 'old_milk_type_code' => $this->oldAttributes['milk_type_code'], 'old_milk_quality_type_code' => $this->oldAttributes['milk_quality_type_code'], 'table_name' => 'tbl_dcs_milk_dispatch'])->one();
            if ($flag == 1 && !empty($existTableData)) {
                $this->addError($attribute, "Record is Already Exist For Approval");
            }
            $approvalTableData = $ApprovalModel->find()->where(['bmc_code' => $bmc, 'dcs_code' => $this->dcs_code, 'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($date)), 'milk_quality_type_code' => $this->milk_quality_type_code, 'milk_type_code' => $this->milk_type_code, 'shift_code' => $shift, 'table_name' => 'tbl_dcs_milk_dispatch'])->one();


            $query = $this->find()->where(['dcs_milk_dispatch_code' => $this->dcs_milk_dispatch_code, 'dcs_code' => $this->dcs_code, 'milk_quality_type_code' => $this->milk_quality_type_code, 'milk_type_code' => $this->milk_type_code]);
            if ($this->milk_type_code == $oldMilktype && $this->milk_quality_type_code == $oldMilkqlttype) {
                $query->andWhere(['!=', 'milk_type_code', $oldMilktype]);
            }
            $mainTableData = $query->one();
            if (($flag == 1 && !empty($approvalTableData)) || !empty($mainTableData)) {
                $this->addError($attribute, "Record is Already Exist");
            }
        }
    }

    public function convertDateDot() {
        try {
            $this->date_time_of_dispatch = Yii::$app->controls->view_date($this->date_time_of_dispatch, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->date_time_of_dispatch = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->date_time_of_dispatch = !empty($this->date_time_of_dispatch) ? Yii::$app->controls->view_date($this->date_time_of_dispatch, 'php:Y-m-d') : NULL;
            $this->date_time_of_dispatch = $this->date_time_of_dispatch . ' ' . \Yii::$app->general->getshift($this->shift_code);
        }
    }

    public function setChildTable($model, &$modelSave) {
        $mainmodel = new TblDcsMilkDispatch();
        $mainmodel->attributes = $model->attributes;
        $mainmodel->bmc_code = $model->bmc_code;
        $mainmodel->shift_code = $model->shift_code;
        $mainmodel->date_time_of_dispatch = $model->date_time_of_dispatch;
        $existMainData = $mainmodel->getExistingData($mainmodel);
        if (empty($existMainData)) {
            $mainmodel->dcs_milk_dispatch_code = Yii::$app->general->getPrimaryCode($mainmodel);
            $mainmodel->union_code = Yii::$app->general->getforeignkey($mainmodel->bmcCode, 'union_code');
            $mainmodel->plant_code = Yii::$app->general->getforeignkey($mainmodel->bmcCode, 'plant_code');
            $mainmodel->mcc_plant_code = Yii::$app->general->getforeignkey($mainmodel->bmcCode, 'mcc_plant_code');
            array_push($modelSave, $mainmodel);
            $union = $mainmodel->union_code;
        } else {
            $model->dcs_milk_dispatch_code = $existMainData->dcs_milk_dispatch_code;
            $txData = $model->getTxnExistingData($model);
            if (!empty($txData)) {
                $this->addError('dcs_code', Yii::t('app/validation', 'Milk Dispatch Is Already Exist'));
            }
            $union = $existMainData->union_code;
        }
        $model->union_code = $union;
        Yii::$app->general->validateRateRange($model, 'avg_fat', 'avg_snf');
        // set clr
        (float) $fat = $this->avg_fat;
        (float) $snf = $this->avg_snf;
        (float) $lr1 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant1', 'BMC');
        (float) $lr2 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant2', 'BMC');
        $this->avg_clr = ($snf - ($fat * $lr1) - $lr2) * 4;
        $model->dcs_milk_dispatch_code = !empty($existMainData) ? $existMainData->dcs_milk_dispatch_code : $mainmodel->dcs_milk_dispatch_code;
        $model->dcs_milk_dispatch_txn_code = Yii::$app->general->getTransactionCode($model, $model->dcs_milk_dispatch_code);
        array_push($modelSave, $model);
    }

    public function importSet($attribute, $params) {
        $model = new TblDcs();
        $code = $model->validDcs($this->dcs_code, $this->bmc_code);
        if (empty($code)) {
            $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
        } else {
            $this->dcs_code = $code;
            Yii::$app->general->validateDeactivateDcs($this, $this->date_time_of_dispatch);
        }
        $this->date_time_of_dispatch = !empty($this->date_time_of_dispatch) ? date('Y-m-d', strtotime($this->date_time_of_dispatch)) : '';
        $this->date_time_of_dispatch = $this->date_time_of_dispatch . ' ' . \Yii::$app->general->getshift($this->shift_code);

        if (empty($this->getErrors()) && $this->total_amount === '' && $this->rtpl === '') {
            $data['milk_type'] = $this->milk_type_code;
            $data['milk_quality_type'] = $this->milk_quality_type_code;
            $data['fat'] = $this->avg_fat;
            $data['snf'] = $this->avg_snf;
            $data['shift'] = $this->shift_code;
            $model = new TblPurchaseRateApplicability();
            $model->dcs_code = $this->dcs_code;
            $model->wef_date = $this->date_time_of_dispatch;
            $data['rate_class'] = '(0, 1)';
            $model_data = $model->getdispatchPurchaseRateApplicableData($data);

            if (!empty($model_data)) {
                $detail_model = new TblPurchaseRateDetails();
                $detail_model->rate_type_code = $model_data->rate_app_code;
                $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
                $rate_type = $detail_model->rateTypeCode->rate_type;
                $detail_data = $detail_model->getDispatchPurchasseRateDetailData($data, $rate_type);
                if (!empty($detail_data)) {
                    $this->purchase_rate_code = (string) $detail_data->purchase_rate_code;
                    $this->rtpl = $detail_data->rtpl;
                    $this->total_amount = $detail_data->rtpl * $this->dispatch_qty;
                } else {
                    $this->addError('rtpl', Yii::t('app/validation', $this->getAttributeLabel('rtpl') . ' not available'));
                }
            } else {
                $this->addError('rtpl', Yii::t('app/validation', $this->getAttributeLabel('rtpl') . ' not available'));
            }
        } else if (empty(floatval($this->rtpl)) && !empty(floatval($this->total_amount))) {
            $this->rtpl = $this->total_amount / $this->dispatch_qty;
        } else if (!empty(floatval($this->rtpl)) && empty(floatval($this->total_amount))) {
            $this->total_amount = $this->rtpl * $this->dispatch_qty;
        } else if (empty(floatval($this->rtpl)) || empty(floatval($this->total_amount))) {
            $this->total_amount = 0;
            $this->rtpl = 0;
        }
    }

    public function getTxnExistingData($data) {
        return $this->find()->where(['dcs_milk_dispatch_code' => $this->dcs_milk_dispatch_code, 'dcs_code' => $data->dcs_code, 'milk_type_code' => $data->milk_type_code, 'milk_quality_type_code' => $data->milk_quality_type_code])->one();
    }

    public function getRateRange() {
        return $this->hasOne(TblUnionRatechartRange::className(), ['union_code' => 'union_code', 'animal_type_code' => 'milk_type_code']);
    }

}
