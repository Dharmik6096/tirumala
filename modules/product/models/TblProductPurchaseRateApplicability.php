<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_product_purchase_rate_applicability".
 *
 * @property string $product_purchase_rate_applicability_code
 * @property string $wef_date
 * @property string $product_purchase_rate_code
 * @property string $product_code
 * @property string $purchase_rate
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $union_code
 * @property string $mcc_plant_code
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
 */
class TblProductPurchaseRateApplicability extends \app\models\ChildModel {

    public $plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_purchase_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicable_code', 'wef_date'], 'required'],
            [['product_purchase_rate_applicability_code', 'product_purchase_rate_code', 'product_code', 'applicable_code', 'applicable_for', 'applicable_type', 'union_code', 'mcc_plant_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['purchase_rate'], 'safe'],
            [['originating_type'], 'safe'],
//                [['applicable_code', 'applicable_for', 'product_code', 'wef_date'], 'unique', 'targetAttribute' => ['applicable_code', 'applicable_for', 'product_code', 'wef_date'], 'message' => 'The combination of Wef Date, Product Code, Applicable Code and Applicable For has already been taken.'],
//            [['applicable_code'], 'validateProductRate', 'skipOnEmpty' => false],
            [['product_purchase_rate_code'], 'validateProductPurchaseRate', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_purchase_rate_applicability_code' => Yii::t('app', 'Product Purchase Rate Applicability Code'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'product_purchase_rate_code' => Yii::t('app', 'Product Purchase Rate Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'purchase_rate' => Yii::t('app', 'Purchase Rate'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'union_code' => Yii::t('app', 'Union'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
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
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getProductRateCode() {
        return $this->hasOne(TblProductSaleRate::className(), ['product_rate_code' => 'product_rate_code']);
    }

    public function getProductRate() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getDispDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_type', 'union_code' => 'union_code']);
    }

    public function getCustomerTypeFor() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function getName($applicableFor) {
        if ($applicableFor == 'PLANT') {
            return Yii::$app->general->getforeignkey($this->plantCode, 'name');
        } else if ($applicableFor == 'MCC') {
            return Yii::$app->general->getforeignkey($this->mccPlantCode, 'name');
        } else if ($applicableFor == 'BMC') {
            return Yii::$app->general->getforeignkey($this->bmcCode, 'bmc_name');
        } else if ($applicableFor == 'DCS') {
            return Yii::$app->general->getforeignkey($this->dispDcsCode, 'dcs_name');
        } else {
            return Yii::$app->general->getforeignkey($this->mainCustomerCode, 'customer_name');
        }
    }

    public function validateProductPurchaseRate($attribute, $params) {
        $this->product_purchase_rate_applicability_code = Yii::$app->general->getTransactionCode($this, $this->product_purchase_rate_code);
    }

    public function validateProductRate($attribute, $params) {
        return $this->validateData();
    }

    public function validateData($returnCodes = false) {
        $this->wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $data = $this->find()
                ->where(['applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for, 'product_code' => $this->product_code])
                ->andWhere(['wef_date' => $this->wef_date])
                ->all();
        $applicable_code = [];
        $message = [];
        for ($i = 0; $i < count($data); $i++) {
            $mesageVal = $data[$i]->getName($data[$i]->applicable_for) . ' - ' . Yii::$app->general->getforeignkey($data[$i]->customerType, 'customer_desc') . '(' . Yii::$app->general->getforeignkey($data[$i]->productRate, 'product_name') . ': ' . Yii::$app->controls->view_date($data[$i]->wef_date) . ')';
            $message[$mesageVal] = $mesageVal;
            $applicable_code[] = $data[$i]->applicable_code;
        }
        if (count($message) > 0) {
            $messagestring = 'Following are the current applicabilities.<br/>' . implode('<br/>', $message);
            $this->addError('applicable_code', $messagestring);
            if ($returnCodes) {
                return $applicable_code;
            }
            return false;
        }
        if ($returnCodes) {
            return $applicable_code;
        }
        return true;
    }

    public function allowDelete() {
        return true;
    }

    public function getProductPurchaseRateCode() {
        return $this->hasOne(TblProductPurchaseRate::className(), ['product_purchase_rate_code' => 'product_purchase_rate_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

}
