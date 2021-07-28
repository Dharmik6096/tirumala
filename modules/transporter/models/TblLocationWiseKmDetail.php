<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\materialmanagement\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_location_wise_km_detail".
 *
 * @property integer $km_detail_code
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $wef_date
 * @property string $total_kms
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblLocationWiseKmDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_location_wise_km_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_type', 'from_dest', 'to_type', 'to_dest', 'union_code', 'created_by', 'updated_by'], 'string'],
            [['from_type', 'from_dest', 'to_type', 'to_dest', 'union_code', 'wef_date', 'total_kms'], 'required'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['total_kms'], 'number', 'min' => 0],
            [['is_active'], 'integer'],
            [['is_active'], 'default', 'value' => 1],
            [['from_dest'], 'reverseValidate'],
            [['wef_date'], 'validatePreDate'],
            ['wef_date', 'unique', 'targetAttribute' => ['wef_date', 'from_type', 'from_dest', 'to_type', 'to_dest'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
            [['from_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['from_dest' => 'plant_code'], 'when' => function ($model) {
            return strtolower($model->from_type) == 'plant';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tbllocationwisekmdetail-from_type').val() == 'plant'; 
          }", 'on' => ['importCsv']],
            [['from_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['from_dest' => 'mcc_plant_code'], 'when' => function ($model) {
            return strtolower($model->from_type) == 'mcc';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tbllocationwisekmdetail-from_type').val() == 'mcc'; 
          }", 'on' => ['importCsv']],
            [['to_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['to_dest' => 'mcc_plant_code'], 'when' => function ($model) {
            return strtolower($model->to_type) == 'mcc';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tbllocationwisekmdetail-to_type').val() == 'mcc'; 
          }", 'on' => ['importCsv']],
            [['to_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['to_dest' => 'plant_code'], 'when' => function ($model) {
            return strtolower($model->to_type) == 'plant';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tbllocationwisekmdetail-to_type').val() == 'plant'; 
          }", 'on' => ['importCsv']],
            [['to_dest'], 'checkUnique'],
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
            'km_detail_code' => Yii::t('app', 'Km Detail Code'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Place'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Place'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'total_kms' => Yii::t('app', 'Distance(Km)'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function validatePreDate($attribute, $params) {
        $count = $this->find()
                ->where(['from_dest' => $this->to_dest, 'from_type' => $this->to_type, 'to_dest' => $this->from_dest, 'to_type' => $this->from_type])
                ->andWhere(['=', 'wef_date', $this->wef_date])
                ->andFilterWhere(['!=', 'km_detail_code', $this->km_detail_code])
                ->count();
        $count += $this->find()
                ->where(['from_dest' => $this->from_dest, 'from_type' => $this->from_type, 'to_dest' => $this->to_dest, 'to_type' => $this->to_type])
                ->andWhere(['=', 'wef_date', $this->wef_date])
                ->andFilterWhere(['!=', 'km_detail_code', $this->km_detail_code])
                ->count();
        if ($count > 0) {
            $this->addError($attribute, Yii::t('app', 'Wef Date must be greater than last Wef Date.'));
        }
    }

    public function reverseValidate($attribute, $params) {
        $count = $this->find()
                ->where(['from_dest' => $this->to_dest, 'from_type' => $this->to_type, 'to_dest' => $this->from_dest, 'to_type' => $this->from_type, 'wef_date' => $this->wef_date])
                ->andFilterWhere(['!=', 'km_detail_code', $this->km_detail_code])
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
            if (strtolower($cu_type_dest) != 'vendor') {
                $this->addError($attribute, Yii::t('app', $this->getAttributeLabel($attribute) . ' is Invalid'));
            }
        }
    }

}
