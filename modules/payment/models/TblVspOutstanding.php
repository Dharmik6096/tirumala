<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_vsp_outstanding".
 *
 * @property integer $vsp_outstanding_code
 * @property string $union_code
 * @property integer $payment_cycle_code
 * @property string $hold_amount
 * @property string $due_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblVspOutstanding extends \app\models\ChildModel {

    public $customer_name, $ex_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_outstanding';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'created_by', 'updated_by'], 'string'],
            [['payment_cycle_code'], 'integer'],
            [['hold_amount', 'due_amount'], 'number'],
            [['created_at', 'updated_at', 'is_active', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'customer_type', 'bmc_code'], 'required', 'on' => ['createPortal']],
            [['bmc_code', 'customer_code'], 'required', 'on' => ['importCsv']],
            [['hold_amount', 'due_amount'], 'default', 'value' => '0'],
            [['hold_amount', 'due_amount'], 'double', 'min' => 0.01, 'message' => Yii::t('app/validation', '{attribute} must be greater than 0'), 'on' => ['createPortal', 'importCsv']],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
            [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
            [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'on' => ['importCsv']],
            [['customer_type', 'customer_code', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['bmc_code'], 'importDataSet', 'on' => ['importCsv']],
            [['customer_code'], 'unique', 'targetAttribute' => ['customer_code', 'customer_type'], 'message' => Yii::t('app/validation', 'Record is Already Exist.')],
            //For show validation message Name instead of Code
            [['customer_code'], 'required', 'message' => Yii::t('app/validation', 'Name Cannot be blank'), 'on' => ['createPortal']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vsp_outstanding_code' => Yii::t('app', 'Vsp Outstanding Code'),
            'union_code' => Yii::t('app', 'Union'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'hold_amount' => Yii::t('app', 'Previous Hold'),
            'due_amount' => Yii::t('app', 'Previous Due'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'customer_code' => Yii::t('app', 'Code'),
            'customer_type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVspOutstandingQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblVspOutstandingQuery(get_called_class());
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function importDataSet($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            Yii::$app->general->validateCustomer($this);
        }
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->ex_code]);
    }

}
