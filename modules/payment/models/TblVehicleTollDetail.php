<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
//use app\modules\materialmanagement\models\TblCustomerMaster;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\transporter\models\TblVehicleMaster;

/**
 * This is the model class for table "tbl_vehicle_toll_detail".
 *
 * @property integer $toll_detail_code
 * @property string $dispatch_date
 * @property string $vehicle_code
 * @property string $parsing_no
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $toll_amount
 * @property string $fastag_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblVehicleTollDetail extends \app\models\ChildModel {

    public $transporter_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_toll_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['weighing_cost'], 'default', 'value' => 0],
            [['dispatch_date', 'created_at', 'updated_at', 'union_code', 'transporter_code', 'weighing_cost'], 'safe'],
            [['vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'string'],
            [['vehicle_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'union_code', 'dispatch_date'], 'required', 'except' => 'importCsv'],
            [['parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest', 'union_code', 'dispatch_date'], 'required', 'on' => 'importCsv'],
            [['toll_amount', 'fastag_amount', 'weighing_cost'], 'number', 'min' => 0],
            [['from_dest'], 'reverseValidate'],
            [['dispatch_date'], 'validatePreDate'],
            ['dispatch_date', 'unique', 'targetAttribute' => ['dispatch_date', 'from_type', 'from_dest', 'to_type', 'to_dest', 'vehicle_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['parsing_no'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleMaster::className(), 'targetAttribute' => ['parsing_no' => 'parsing_no'], 'on' => 'importCsv'],
            [['dispatch_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
            [['from_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['from_dest' => 'plant_code'], 'when' => function ($model) {
            return strtolower($model->from_type) == 'plant';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblvehicletolldetail-from_type').val() == 'plant'; 
          }", 'on' => ['importCsv']],
            [['from_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['from_dest' => 'mcc_plant_code'], 'when' => function ($model) {
            return strtolower($model->from_type) == 'mcc';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblvehicletolldetail-from_type').val() == 'mcc'; 
          }", 'on' => ['importCsv']],
            [['to_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['to_dest' => 'mcc_plant_code'], 'when' => function ($model) {
            return strtolower($model->to_type) == 'mcc';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblvehicletolldetail-to_type').val() == 'mcc'; 
          }", 'on' => ['importCsv']],
            [['to_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['to_dest' => 'plant_code'], 'when' => function ($model) {
            return strtolower($model->to_type) == 'plant';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblvehicletolldetail-to_type').val() == 'plant'; 
          }", 'on' => ['importCsv']],
            [['to_dest'], 'checkUnique'],
            [['from_dest'], 'setField', 'except' => 'importCsv'],
            [['from_dest'], 'setFieldImport', 'on' => 'importCsv'],
            [['toll_amount'], 'required', 'when' => function ($model) {
            return trim($model->fastag_amount) == '';
        }, 'whenClient' => "function (attribute, value) { 
              return $.trim($('#tblvehicletolldetail-fastag_amount').val()) == ''; 
          }"],
            [['fastag_amount'], 'required', 'when' => function ($model) {
            return trim($model->toll_amount) == '';
        }, 'whenClient' => "function (attribute, value) { 
              return $.trim($('#tblvehicletolldetail-toll_amount').val()) == ''; 
          }"],
            [['from_type'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalStatic($this, $attribute, 'place_type');
        }, 'on' => 'importCsv'],
            [['to_type'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalStatic($this, $attribute, 'place_type');
        }, 'on' => 'importCsv'],
            [['from_dest'], 'typeFromValidate', 'on' => 'importCsv'],
            [['to_dest'], 'typeToValidate', 'on' => 'importCsv'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'toll_detail_code' => Yii::t('app', 'Toll Detail Code'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'parsing_no' => Yii::t('app', 'Vehicle No'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Place'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Place'),
            'toll_amount' => Yii::t('app', 'Toll Amount'),
            'fastag_amount' => Yii::t('app', 'FASTag Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'union_code' => Yii::t('app', 'Union'),
            'weighing_cost' => Yii::t('app', 'Weighing Cost'),
        ];
    }

    public function getMccPlantCodeSource() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'from_dest']);
    }

    public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'to_dest']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCodeSource() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'from_dest']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'to_dest']);
    }

    public function getCustomerCodeSource() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'from_dest']);
    }

    public function getCustomerCodeDest() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'to_dest']);
    }

    public function getVehicleCode() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function validatePreDate($attribute, $params) {
        $count = $this->find()
                ->where(['from_dest' => $this->to_dest, 'from_type' => $this->to_type, 'to_dest' => $this->from_dest, 'to_type' => $this->from_type, 'vehicle_code' => $this->vehicle_code])
                ->andWhere(['>=', 'dispatch_date', $this->dispatch_date])
                ->andFilterWhere(['!=', 'toll_detail_code', $this->toll_detail_code])
                ->count();
        $count += $this->find()
                ->where(['from_dest' => $this->from_dest, 'from_type' => $this->from_type, 'to_dest' => $this->to_dest, 'to_type' => $this->to_type, 'vehicle_code' => $this->vehicle_code])
                ->andWhere(['>=', 'dispatch_date', $this->dispatch_date])
                ->andFilterWhere(['!=', 'toll_detail_code', $this->toll_detail_code])
                ->count();
        if ($count > 0) {
            $this->addError($attribute, Yii::t('app', 'Wef Date must be greater than last Wef Date.'));
        }
    }

    public function reverseValidate($attribute, $params) {
        $count = $this->find()
                ->where(['from_dest' => $this->to_dest, 'from_type' => $this->to_type, 'to_dest' => $this->from_dest, 'to_type' => $this->from_type, 'dispatch_date' => $this->dispatch_date, 'vehicle_code' => $this->vehicle_code])
                ->andFilterWhere(['!=', 'toll_detail_code', $this->toll_detail_code])
                ->count();
        if ($count > 0) {
            $this->addError($attribute, Yii::t('app', 'Reverse is not Allow on Same Wef Date'));
        }
    }

    public function checkUnique($attribute, $params) {
        if ($this->from_dest == $this->to_dest && $this->from_type == $this->to_type) {
            $this->addError($attribute, Yii::t('app', 'From Dest. And To Dest. is not Same'));
        }
    }

    public function setField($attribute, $params) {
        if (!empty($this->vehicle_code)) {
            $this->parsing_no = Yii::$app->general->getforeignkey($this->vehicleCode, 'parsing_no');
        }
    }

    public function getParsingNo() {
        return $this->hasOne(TblVehicleMaster::className(), ['parsing_no' => 'parsing_no']);
    }

    public function setFieldImport($attribute, $params) {
        if (!empty($this->parsing_no)) {
            $this->vehicle_code = Yii::$app->general->getforeignkey($this->parsingNo, 'vehicle_code');
        }
    }

    public function typeFromValidate($attribute, $params) {
        if (strtolower($this->from_type) == 'vendor') {
            $cu_type_sr = Yii::$app->general->getforeignkey($this->customerCodeSource, 'customer_type');
            if (strtolower($cu_type_sr) != 'vendor') {
                $this->addError($attribute, Yii::t('app', $this->getAttributeLabel($attribute) . ' is Invalid'));
            }
        }
    }

    public function typeToValidate($attribute, $params) {
        if (strtolower($this->to_type) == 'vendor') {
            $cu_type_dest = Yii::$app->general->getforeignkey($this->customerCodeDest, 'customer_type');
            if (vendor($cu_type_dest) != 'VENDOR') {
                $this->addError($attribute, Yii::t('app', $this->getAttributeLabel($attribute) . ' is Invalid'));
            }
        }
    }

}
