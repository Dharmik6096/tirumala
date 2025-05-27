<?php

namespace app\modules\payment\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblBmcChillerInfo;
use Yii;

/**
 * This is the model class for table "tbl_mcc_rechilling_detail".
 *
 * @property integer $mcc_rechilling_detail_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property integer $chiller_info_code
 * @property string $chilling_date
 * @property integer $shift_code
 * @property string $qty
 * @property string $rate
 * @property string $amount
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMccRechillingDetail extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_rechilling_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'chiller_info_code', 'chilling_date', 'shift_code', 'qty', 'rate', 'amount', 'originating_org_code', 'originating_org_type', 'originating_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'amount'], 'required', 'except' => ['importCsv']],
            [['chiller_info_code', 'chilling_date', 'shift_code', 'qty', 'rate'], 'required'],
            [['chiller_info_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBmcChillerInfo::className(), 'targetAttribute' => ['chiller_info_code' => 'chiller_info_code'], 'filter' => ['is_active' => 1]],
            [['shift_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
            [['chiller_info_code', 'shift_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
            [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id'], 'on' => 'importCsv'],
            ['shift_code', 'in', 'range' => [1, 2], 'on' => ['importCsv'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', '{attribute} is invalid')],
            [['qty', 'rate', 'amount'], 'number'],
            [['chilling_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['chilling_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['chilling_date'], 'convertDate', 'on' => ['importCsv']],
            [['chiller_info_code'], 'assignAutoData', 'skipOnError' => true, 'on' => 'importCsv'],
            [['chiller_info_code'], 'unique',
                'targetAttribute' => ['chiller_info_code', 'chilling_date', 'shift_code'],
                'message' => 'The combination of Chiller Info, Chilling Date and Shift has already been taken.'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mcc_rechilling_detail_code' => Yii::t('app', 'Mcc Rechilling Detail Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'chiller_info_code' => Yii::t('app', 'Chiller Info'),
            'chilling_date' => Yii::t('app', 'Chilling Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'qty' => Yii::t('app', 'Qty'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getBmcChillerInfo() {
        return $this->hasOne(TblBmcChillerInfo::className(), ['chiller_info_code' => 'chiller_info_code']);
    }

    public function convertDateDot() {
        try {
            $this->chilling_date = Yii::$app->controls->view_date($this->chilling_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->chilling_date = '-';
        }
    }

    public function convertDate($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->chilling_date = !empty($this->chilling_date) ? Yii::$app->controls->view_date($this->chilling_date, 'php:Y-m-d') : NULL;
            $now = strtotime(date('Y-m-d'));            
            if (strtotime($this->chilling_date) > $now) {
                $this->addError($attribute, Yii::t('app/validation', 'Chilling Date cannot be a future date.'));
            } else {
                $this->chilling_date = $this->chilling_date . ' ' . \Yii::$app->general->getshift($this->shift_code);            
            }
        }
    }

    public function assignAutoData($attribute, $params) {
        if (empty($this->getErrors())) {
            $bmcChillerInfoData = TblBmcChillerInfo::find()->where(['chiller_info_code' => $this->chiller_info_code])->one();
            if (!empty($bmcChillerInfoData)) {
                $this->union_code = $bmcChillerInfoData->union_code;
                $this->plant_code = $bmcChillerInfoData->plant_code;
                $this->mcc_plant_code = $bmcChillerInfoData->mcc_plant_code;
                $this->bmc_code = $bmcChillerInfoData->bmc_code;
                $this->amount = $this->qty * $this->rate;
            } else {
                $this->addError($attribute, Yii::t('app/validation', 'Chiller Info Code is invalid.'));
            }
        }
    }

}
