<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\product\models\TblProduct;
use webvimark\modules\UserManagement\models\User;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\product\models\TblProductStock;
use app\modules\assetmanagement\models\TblStoreLocation;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\product\models\TblProductSaleRateApplicability;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "tbl_indent_master".
 *
 * @property string $indent_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $member_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $indent_date
 * @property string $product_code
 * @property string $qty
 * @property string $status
 * @property string $status_date
 * @property string $status_by
 * @property string $status_remarks
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
class TblIndentMaster extends \app\models\ChildModel {

    public $member, $route_code, $from_date, $to_date, $code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_indent_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['indent_code'], 'required', 'except' => ['importCsv', 'importCsvOther']],
            [['union_code', 'mcc_plant_code', 'plant_code', 'dcs_code', 'bmc_code', 'customer_type', 'customer_code', 'member_code', 'product_code', 'status', 'indent_date', 'qty', 'status_remarks', 'route_code', 'approve_qty', 'rejected_qty', 'approve_remarks', 'received_qty', 'dispatch_qty', 'is_close', 'from_date', 'to_date'], 'safe'],
            [['indent_type', 'warehouse_code', 'rate', 'amount', 'code'], 'safe'],
            [['product_code', 'indent_date', 'qty'], 'required', 'on' => ['create', 'createOther', 'importCsv', 'importCsvOther']],
            [['dcs_code'], 'required', 'on' => ['create', 'createOther', 'importCsv']],
            [['indent_type', 'rate', 'amount'], 'required', 'on' => ['createOther']],
            [['warehouse_code'], 'required', 'when' => function ($model) {
                    return $model->indent_type == 2;
                }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblindentmaster-indent_type').val() != ''; 
                        }"],
            [['union_code', 'mcc_plant_code', 'plant_code', 'bmc_code', 'member_code'], 'required', 'on' => ['create']],
            [['union_code', 'mcc_plant_code', 'plant_code', 'bmc_code'], 'required', 'on' => ['createOther']],
            [['member'], 'required', 'on' => ['importCsv']],
            [['status_date', 'created_at', 'updated_at', 'created_by', 'updated_by', 'status_by'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['qty'], 'number'],
            [['indent_date'], 'convertDateDot', 'on' => ['importCsv', 'importCsvOther']],
            [['indent_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv', 'importCsvOther']],
            [['indent_date'], 'convertDate', 'on' => ['importCsv', 'importCsvOther']],
            [['indent_date'], 'statusSet', 'skipOnError' => true],
            [['customer_type', 'customer_code'], 'required', 'on' => ['importCsvOther']],
            [['customer_type'], 'in', 'range' => ['MEMBER', 'DCS'], 'on' => ['importCsvOther']],
            [['indent_date'], 'importFieldSet', 'skipOnError' => true, 'on' => ['importCsv']],
            [['indent_date'], 'importFieldSetOther', 'skipOnError' => true, 'on' => ['importCsvOther']],
            [['indent_code'], 'validateCancel', 'skipOnError' => true],
            [['product_code'], 'unique', 'targetAttribute' => ['product_code', 'member_code', 'dcs_code', 'indent_date'], 'message' => Yii::t('app/validation', 'Record is Already Exist.'), 'skipOnEmpty' => TRUE, 'when' => function($model) {
                    return empty($this->getErrors());
                }, 'on' => ['create', 'importCsv']],
            [['product_code'], 'unique', 'targetAttribute' => ['product_code', 'indent_type', 'warehouse_code', 'dcs_code', 'indent_date', 'member_code'], 'message' => Yii::t('app/validation', 'Record is Already Exist.'), 'skipOnEmpty' => TRUE, 'when' => function($model) {
                    return empty($this->getErrors());
                }, 'on' => ['createOther', 'importCsvOther']],
            [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code'], 'on' => ['importCsv', 'importCsvOther']],
            [['warehouse_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStoreLocation::className(), 'targetAttribute' => ['warehouse_code' => 'store_location_code'], 'on' => ['importCsvOther']],
            [['approve_qty'], 'approveQty', 'on' => ['approve']],
            [['is_close'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'indent_code' => Yii::t('app', 'Indent Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'member_code' => Yii::t('app', 'Member'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'union_code' => Yii::t('app', 'Union'),
            'indent_date' => Yii::t('app', 'Indent Date'),
            'product_code' => Yii::t('app', 'Product'),
            'qty' => Yii::t('app', 'Qty'),
            'status' => Yii::t('app', 'Status'),
            'status_date' => Yii::t('app', 'Status Date'),
            'status_by' => Yii::t('app', 'Status By'),
            'status_remarks' => Yii::t('app', 'Status Remarks'),
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
            'route_code' => Yii::t('app', 'Route'),
            'warehouse_code' => Yii::t('app', 'Warehouse'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
            'received_qty' => Yii::t('app', 'Acknowledgement Qty'),
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

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getStatusBy() {
        return $this->hasOne(User::className(), ['id' => 'status_by']);
    }

    public function getWarehouseCode() {
        return $this->hasOne(TblStoreLocation::className(), ['store_location_code' => 'warehouse_code']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function convertDateDot() {
        try {
            $this->indent_date = Yii::$app->controls->view_date($this->indent_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->indent_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->indent_date = !empty($this->indent_date) ? Yii::$app->controls->view_date($this->indent_date, 'php:Y-m-d') . ' 00:00:00.000000' : NULL;
        }
    }

    public function statusSet($attribute, $params) {
        $datetime = date('Y-m-d H:i:s');
        $this->status = empty($this->status) ? '0' : $this->status;
        $this->status_date = empty($this->status_date) ? $datetime : $this->status_date;
        $this->status_by = empty($this->status_by) && !empty(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : $this->status_by;
    }

    public function validateMember() {
        return TblMember::find()->where(['is_active' => 1])->andWhere(['or', ['member_code' => $this->customer_code], ['ex_member_code' => $this->customer_code], ['ref_code' => $this->customer_code], ['sap_farmer_code' => $this->customer_code], ['vendor_code' => $this->customer_code]])->one();
    }

    public function importFieldSet($attribute, $params) {
        $dcs = new TblDcs();
        $this->dcs_code = $dcs->getValidDcs($this->dcs_code);
        if (empty($this->dcs_code)) {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
        } else {
            $dcsCodeData = $this->dcsCode;
            if (!empty($dcsCodeData)) {
                $this->bmc_code = $dcsCodeData->bmc_code;
                $this->mcc_plant_code = $dcsCodeData->mcc_plant_code;
                $this->plant_code = $dcsCodeData->plant_code;
                $this->union_code = $dcsCodeData->union_code;
            }
            $member = $this->dcs_code . $this->member;
            $this->member_code = $member;
            $this->customer_code = $member;
            $this->customer_type = 'Member';
            if (empty($this->memberCode)) {
                $this->addError('member_code', Yii::t('app/validation', $this->getAttributeLabel('member_code') . ' is invalid'));
            }
        }
    }

    public function importFieldSetOther($attribute, $params) {
        if (empty($this->getErrors())) {
            $DcsCode = $this->customer_code;
            if ($this->customer_type == 'MEMBER') {
                $memberCodeData = $this->validateMember();
                $DcsCode = '';
                if (!empty($memberCodeData)) {
                    $DcsCode = $memberCodeData->dcs_code;
                    $this->member_code = $memberCodeData->member_code;
                }
            }
            $dcs = new TblDcs();
            $this->dcs_code = $dcs->getValidDcs($DcsCode);
            if (empty($this->dcs_code)) {
                $this->addError('customer_code', Yii::t('app/validation', Yii::t('app', 'Customer Code') . ' is invalid'));
            } else {
                $dcsCodeData = $this->dcsCode;
                if (!empty($dcsCodeData)) {
                    $this->bmc_code = $dcsCodeData->bmc_code;
                    $this->mcc_plant_code = $dcsCodeData->mcc_plant_code;
                    $this->plant_code = $dcsCodeData->plant_code;
                    $this->union_code = $dcsCodeData->union_code;
                }
                $this->indent_type = !empty($this->warehouse_code) ? 'warehouse' : 'mcc';

                $product = new TblIndentProduct();
                $indentProduct = $product->getIndentProductList($this->union_code, $this->indent_type, $this->product_code);
                if (empty($indentProduct)) {
                    $this->addError('rate', Yii::t('app/validation', ' Product Is not Applicable For Indent'));
                }
                $applicable_code = $this->dcs_code;
                $applicable_type = 'DCS';
                $memberRate = $this->customer_type == 'MEMBER' ? 1 : 0;
                $appQuery = TblProductSaleRateApplicability::find()->innerJoinWith(['productRateCode', 'productCode'])
                        ->select(['product_sale_rate_applicability_code', 'tbl_product.unit_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date as dt'])->groupBy(['product_sale_rate_applicability_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date', 'tbl_product.unit_code'])
                        ->having(['<=', '[tbl_product_sale_rate_applicability].[wef_date]', $this->indent_date])
                        ->where(['tbl_product_sale_rate.product_code' => $this->product_code, 'tbl_product_sale_rate_applicability.applicable_for' => $applicable_type, 'tbl_product_sale_rate_applicability.is_member_rate' => (int) $memberRate, 'tbl_product_sale_rate_applicability.applicable_code' => $applicable_code]);
                $app = $appQuery->orderBy(['tbl_product_sale_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();
                if (empty($app)) {
                    $this->addError('rate', Yii::t('app/validation', ' Product Sale Rate not Applicable'));
                } else {
                    $this->rate = $app['sale_rate'];
                    $this->amount = $this->qty * $this->rate;
                }
            }
        }
    }

    public function setChildTable(&$model, &$saveModel, &$errors) {
        if (!empty($model)) {
            $modelStages = new TblApprovalStagesDetail();
            $modelStages->setApprovalData($model->union_code, 'indent_master', $model->indent_code, $saveModel, $approval_stages);
            $model->status = empty($approval_stages) ? 2 : 0;
        }
    }

    public function setChildTableOther(&$model, $transaction_data, &$childModel) {//this function is for HO APP Indent Save
        $model->status = !empty($model->status) ? $model->status : '0';
        if (empty($model->indent_code)) {
            $model->indent_code = Yii::$app->general->getCodeAutoIncrement($model);
            $model->customer_type = 'Member'; // Yii::$app->general->getCodeAutoIncrement($model);
            $model->customer_code = $model->member_code;
            $dcsCodeData = $model->dcsCode;
            if (!empty($dcsCodeData) && !empty($dcsCodeData->union_code)) {
                $model->bmc_code = $dcsCodeData->bmc_code;
                $model->mcc_plant_code = $dcsCodeData->mcc_plant_code;
                $model->plant_code = $dcsCodeData->plant_code;
                $model->union_code = $dcsCodeData->union_code;
            }
            if (!empty($model)) {
                $modelStages = new TblApprovalStagesDetail();
                $modelStages->setApprovalData($model->union_code, 'indent_master', $model->indent_code, $childModel, $approval_stages);
                $model->status = empty($approval_stages) ? '2' : '0';
            }
        } else {
            $stage_model = new TblProcessApproval();
            $stage_model->process_code = $model->indent_code;
            $stage_model->process_name = 'indent_master';
            $stage_modelData = $stage_model->getData();
            foreach ($stage_modelData as $stage_modelD) {
                $historyModel = new TblProcessApprovalHistory();
                Yii::$app->operation->history($stage_modelD, $historyModel, 'UPDATE');
                $childModel[] = $historyModel;
                $stage_modelD->status = '4';
                $childModel[] = $stage_modelD;
            }
        }
    }

    public function getExistingStock($model) {
        if (!empty($model)) {
            $modelStages = new TblProductStock();
            $modelStages->union_code = $model['union_code'];
            $modelStages->mcc_plant_code = $model['mcc_plant_code'];
            $modelStages->product_code = $model['product_code'];
            $modelStages->bmc_code = $model['bmc_code'];
            $availableStockData = $modelStages->getAvailableStock('BMC');
            $availableStockQty = 0;
            foreach ($availableStockData as $stock) {
                $availableStockQty = $availableStockQty + $stock->stock;
            }
            return $availableStockQty;
        }
    }

    public function checkStatus() {
        return (trim(($this->status) == 5)) ? false : true;
    }

    public function validateCancel($attribute, $params) {
        if (!empty($this->indent_code) && $this->status == '4') {
            $stage_model = new TblProcessApproval();
            $stage_model->process_code = $this->indent_code;
            $stage_model->process_name = 'indent_master';
            $stage_modelData = $stage_model->getData();
            $indentUnderApprove = false;
            foreach ($stage_modelData as $s) {
                if (!empty($s->status)) {
                    $indentUnderApprove = true;
                }
            }
            if ($indentUnderApprove) {
                $this->addError($attribute, Yii::t('app/validation', 'Indent Already under Approval'));
            }
        }
    }

    public function approveQty($attribute) {

        if (!empty($this->approve_qty) && !empty($this->qty)) {

            $total_qty = $this->approve_qty + $this->rejected_qty;
            if (!empty($total_qty > $this->qty)) {
                $this->addError($attribute, Yii::t('app/validation', 'Approve Qty Must be Less than Requested Qty.'));
                return false;
            }
        }
    }

    public function getApprovalLevel($process_code) {
        $stage_model = new TblProcessApproval();
        $level = $stage_model->find()
                ->where(['process_code' => $process_code, 'level' => 1])
                ->andWhere(['!=', 'status', 0])
                ->one();
        return $level;
    }

    public function indentDetail($model) {

        $query = $this->find()
                ->andWhere(['dcs_code' => $model->dcs_code, 'member_code' => $model->member_code, 'product_code' => $model->product_code, 'status' => '2', 'is_close' => '0'])
                ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'sort' => [
                'defaultOrder' => ['indent_code' => SORT_ASC],
                'attributes' => [
                    'indent_code',
                    'customer_type',
                    'customer_code',
                    'member_code',
                    'dcs_code',
                    'bmc_code',
                    'mcc_plant_code',
                    'plant_code',
                    'union_code',
                    'indent_date',
                    'product_code',
                    'qty',
                    'rate',
                    'amount',
                    'status',
                    'status_date',
                    'status_by',
                    'status_remarks',
                    'indent_type',
                    'warehouse_code',
                    'approve_qty',
                    'rejected_qty',
                    'approve_remarks',
                    'received_qty',
                    'dispatch_qty',
                    'is_close',
                ],
            ],
        ]);
        return $dataProvider;
    }

}
