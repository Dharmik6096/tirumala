<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\globalmaster\models\TblUnits;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\syncutility\models\TblSentbox;
use app\modules\dcsaccounting\models\TblTax;
use app\modules\product\models\TblProductPurchaseRate;
use yii\helpers\ArrayHelper;
use app\modules\product\models\TblDispatchCenter;
use app\modules\usermanagement\models\TblUserDispatchCenterMapping;
use webvimark\modules\UserManagement\models\User;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsaccounting\models\TblLedgers;

/**
 * This is the model class for table "tbl_product".
 *
 * @property integer $product_code
 * @property integer $product_group_code
 * @property string $product_name
 * @property string $product_desc
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 *
 * @property TblProductGroup $productGroupCode
 */
class TblProduct extends \app\models\ChildModel {

    public $is_sentbox = TRUE;
    public $import_union_code, $import_eipl_code, $import_key_pattern;
    public $product_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['product_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'product_type');
                }, 'on' => 'importCsv'],
                [['product_group_code', 'product_name', 'union_code', 'unit_code', 'tax_code', 'x_col3'], 'required', 'except' => ['androidsync', 'importCsv']],
                [['product_group_code', 'product_name', 'tax_code', 'product_type'], 'required', 'on' => ['importCsv']],
                [['product_group_code', 'is_active'], 'integer', 'except' => ['androidsync']],
                [['product_name', 'product_desc', 'created_by', 'updated_by', 'local_name'], 'string', 'except' => ['androidsync']],
                ['product_name', 'unique', 'when' => function($model) {
                    $data = $this->find()->where(['union_code' => $model->union_code, 'product_name' => $model->product_name])->andWhere(['<>', 'product_code', $model->product_code])->one();
                    return ($data) ? true : false;
                }, 'except' => ['androidsync']],
                [['product_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['androidsync']],
                [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['androidsync']],
                [['created_at', 'updated_at', 'product_code', 'is_inhouse', 'is_inclusive_tax', 'is_saleable', 'is_indent', 'ref_code', 'tax_code', 'product_category_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'product_market_name', 'product_variant', 'product_sku', 'product_pack_type', 'brand_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_dpu_product', 'dpu_product_code', 'product_type', 'item_code', 'min_stock', 'is_milk', 'milk_type', 'purchase_ledger', 'sale_ledger', 'stock_ledger', 'local_sale_ledger', 'other_state_tax_code', 'coupon_ledger'], 'safe'],
                [['product_group_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProductGroup::className(), 'targetAttribute' => ['product_group_code' => 'product_group_code'], 'except' => ['androidsync']],
                [['tax_code'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->productGroupCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'tax_code', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
                [['tax_code'], 'exist', 'skipOnEmpty' => true, 'targetClass' => TblTax::className(), 'targetAttribute' => ['tax_code' => 'tax_code'], 'on' => ['importCsv']],
                [['dpu_product_code'], 'string', 'min' => 4, 'max' => 4, 'except' => ['androidsync']],
                [['dpu_product_code'], 'integer', 'min' => 0, 'except' => ['androidsync']],
                [['dpu_product_code'], 'required', 'when' => function ($model) {
                    return $model->is_dpu_product == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproduct-is_dpu_product').is(':checked'); 
          }", 'except' => ['androidsync']],
                [['is_dpu_product', 'is_inhouse', 'is_inclusive_tax', 'is_saleable', 'is_indent'], 'in', 'range' => ['0', '1'], 'on' => ['importCsv']],
                [['product_group_code'], 'setImport', 'on' => ['importCsv']],
                [['ref_code'], 'unique', 'targetAttribute' => ['union_code', 'ref_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['androidsync']],
                [['dpu_product_code'], 'unique', 'targetAttribute' => ['union_code', 'dpu_product_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['dpu_product_code'], 'validateDpuProduct', 'except' => ['androidsync']],
                [['is_active'], 'default', 'value' => 1, 'on' => ['importCsv']],
                [['product_type'], 'setProductType', 'on' => ['importCsv']],
                [['x_col3'], 'default', 'value' => 2],
                [['min_stock'], 'double', 'min' => 0],
                [['min_stock'], 'default', 'value' => 0],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblProduct', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_code' => Yii::t('app', 'Product Code'),
            'product_group_code' => Yii::t('app', 'Product Group'),
            'product_name' => Yii::t('app', 'Product Name'),
            'product_desc' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
            'union_code' => Yii::t('app', 'Union'),
            'unit_code' => Yii::t('app', 'Unit'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'ref_code' => Yii::t('app', 'Reference Code'),
            'tax_code' => Yii::t('app', 'Tax'),
            'is_inhouse' => Yii::t('app', 'Is Inhouse'),
            'is_inclusive_tax' => Yii::t('app', 'Is Inclusive Tax'),
            'is_saleable' => Yii::t('app', 'Is Saleable'),
            'is_indent' => Yii::t('app', 'Is Indent'),
            'is_dpu_product' => Yii::t('app', 'Is DPU Product'),
            'dpu_product_code' => Yii::t('app', 'DPU Product Code'),
            'x_col3' => Yii::t('app', 'Product Type'),
            'other_state_tax_code' => Yii::t('app', 'Other State Tax'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductGroupCode() {
        return $this->hasOne(TblProductGroup::className(), ['product_group_code' => 'product_group_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getUnitCode() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
    }

    /**
     * @inheritdoc
     * @return TblProductQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProductQuery(get_called_class());
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

    public function getTaxCode() {
        return $this->hasOne(TblTax::className(), ['tax_code' => 'tax_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getProduct($code, $date) {

        $records = $this->getRecord($code);
        $array = ['status' => 'error'];
        if (!empty($records)) {
            $date = date('Y-m-d', strtotime($date));
            $purchaseRate = TblProductPurchaseRate::find()->select('purchase_rate')
                            ->where(['product_code' => $code, 'cast(wef_date as date)' => $date])
//                    ->where('product_code="' . $code . '" and cast(wef_date as date)<= "' . $date . '" and is_active=1')
                            ->orderBy(['cast(wef_date as date)' => SORT_DESC])->one();
            if ($purchaseRate)
                $array = ['status' => 'success', 'name' => $records->product_name, 'rate' => ($purchaseRate) ? $purchaseRate->purchase_rate : 0, 'unit' => Yii::$app->general->getforeignkey($records->primaryUom, 'unit_name'), 'tax_code' => $records->tax_code];
        }
        return $array;
    }

    public function getPrimaryUom() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
    }

    public function getRecord($productCode) {

        $records = $this->find()->select('product_name,unit_code,tax_code')->where(['product_code' => $productCode])->one();
        return $records;
    }

    public function validateDpuProduct($attribute, $params) {
        if (!empty($this->is_dpu_product)) {
            $validateDpuCode = Yii::$app->general->getUnionConfiguration($this->union_code, 'validate_dpu_product_code', 'PORTAL');
            if (!empty($validateDpuCode)) {
                (int) $minVal = Yii::$app->general->getUnionConfiguration($this->union_code, 'dpu_product_code_min_value', 'PORTAL');
                (int) $maxVal = Yii::$app->general->getUnionConfiguration($this->union_code, 'dpu_product_code_max_value', 'PORTAL');
                if (!empty($minVal) && is_numeric($minVal) && $this->dpu_product_code < $minVal) {
                    $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " must be greater then or equal to " . $minVal));
                } else if (!empty($maxVal) && is_numeric($maxVal) && $this->dpu_product_code > $maxVal) {
                    $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " must be less than or equal to " . $maxVal));
                }
            }
        }
    }

    public function checkAllowDelete() {
        return Yii::$app->general->allowUpdateDelete($this);
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->unit_code = Yii::$app->general->getforeignkey($this->productGroupCode, 'unit_code');
            if (empty($this->is_milk) || $this->is_milk == 0) {
                $this->milk_type = null;
                $this->local_sale_ledger = null;
                $this->coupon_ledger = null;
            }
        }
    }

    public function setProductType($attribute, $params) {
        $this->x_col3 = $this->product_type;
    }

    public function getProductList($unionCode, $type) {
        $inventory_with_dispatch_center = Yii::$app->general->getUnionConfiguration($unionCode, 'inventory_with_dispatch_center', 'PORTAL') == 1 ? true : false;
        if ($inventory_with_dispatch_center) {
            $pgCode = NULL;
            $user = Yii::$app->session->get('UserCode');
            $userModel = new TblUserDispatchCenterMapping();
            $userData = $userModel->find()->select('dispatch_center_code')->where(['user_code' => $user])->all();
            if (!empty($userData)) {
                $dispatchCenterCode = array_column($userData, 'dispatch_center_code');
                $dispatchModel = new TblDispatchCenter();
                $dispatchCentData = $dispatchModel->find()->where(['dispatch_center_code' => $dispatchCenterCode])->one();
                $pgCode = !empty($dispatchCentData) ? $dispatchCentData->dispatch_center_type_code : NULL;
            }
            $pgCode = !empty($pgCode) ? explode(',', $pgCode) : '';
            $query = $this->find()
                    ->select(['product_code', 'product_name'])
                    ->where(['union_code' => $unionCode, 'is_active' => 1, 'product_group_code' => $pgCode])
                    ->all();
            $value = ArrayHelper::map($query, 'product_code', 'product_name');
            return $value;
        } else {
            $query = $this->find()->select(['product_code', 'product_name'])->where(['union_code' => $unionCode, 'is_active' => 1]);
            if (!empty($type)) {
                $query->andWhere(['x_col3' => $type]);
            }
            $value = $query->all();

            $data = ArrayHelper::map($value, 'product_code', 'product_name');
            return $data;
        }
    }

    public function validateRefCode($attribute, $params) {

        if (!empty($this->product_code) && !empty($this->ref_code)) {

            if (strlen($this->product_code) < strlen($this->ref_code)) {
                $this->addError($attribute, Yii::t('app/validation', 'SAP Code Length Must be Less Than ' . strlen($this->getAttributeLabel('product_code'))));
                return false;
            }
        }
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type']);
    }

    public function getPurchaseLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'purchase_ledger']);
    }

    public function getSaleLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'sale_ledger']);
    }

    public function getStockLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'stock_ledger']);
    }

    public function getLocalSaleLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'local_sale_ledger']);
    }

    public function getStateTaxCode() {
        return $this->hasOne(TblTax::className(), ['tax_code' => 'other_state_tax_code']);
    }

    public function getCouponLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'coupon_ledger']);
    }

}
