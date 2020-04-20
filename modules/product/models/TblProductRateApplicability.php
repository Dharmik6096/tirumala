<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\payment\models\TblProductSaleDetails;

/**
 * This is the model class for table "tbl_product_rate_applicability".
 *
 * @property integer $product_rate_applicability_code
 * @property string $wef_date
 * @property string $product_rate_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $product_code
 * @property double $rate
 * @property double $rate_two
 * @property string $mcc_plant_code
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblProductRateApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicable_code', 'wef_date'], 'required'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['product_rate_code', 'dcs_code', 'union_code', 'created_by', 'updated_by', 'mcc_plant_code', 'applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['product_code', 'originating_type', 'is_member_rate'], 'safe'],
            [['rate', 'rate_two'], 'safe'],
            [['applicable_code'], 'validateProductRate', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_rate_applicability_code' => Yii::t('app', 'Product Rate Applicability Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'product_rate_code' => Yii::t('app', 'Product Rate Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'product_code' => Yii::t('app', 'Product Code'),
            'rate' => Yii::t('app', 'Rate'),
            'rate_two' => Yii::t('app', 'Rate Two'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getProductRateCode() {
        return $this->hasOne(TblProductRate::className(), ['product_rate_code' => 'product_rate_code']);
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

    public function validateProductRate($attribute, $params) {
        return $this->validateData();
    }

    public function validateData($returnCodes = false) {
        $this->wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $data = $this->find()
                ->where(['applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for, 'product_code' => $this->product_code, 'is_member_rate' => $this->is_member_rate])
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

    public function getProductSaleDetails() {
        return $this->hasOne(TblProductSaleDetails::className(), ['rate_app_code' => 'product_rate_applicability_code']);
    }

    public function allowDelete() {
        if (!empty($this->productSaleDetails)) {
            return false;
        } else {
            return true;
        }
    }

}
