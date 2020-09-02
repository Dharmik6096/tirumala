<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_fuel_rate_master".
 *
 * @property integer $fuel_rate_code
 * @property string $rate
 * @property string $wef_date
 * @property string $union_code
 * @property integer $fuel_type_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblFuelRateMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_fuel_rate_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate'], 'number'],
            [['fuel_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'fuel_type_code');
                }, 'on' => 'importCsv'],
            [['wef_date', 'bmc_code', 'fuel_type_code', 'rate', 'bmc_code'], 'required'],
            [['union_code', 'plant_code', 'mcc_plant_code'], 'required', 'except' => ['importCsv']],
            [['wef_date', 'created_at', 'updated_at', 'bmc_code'], 'safe'],
            [['union_code', 'created_by', 'updated_by'], 'string'],
            [['fuel_type_code'], 'integer'],
            [['rate'], 'number', 'min' => 1],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => 'importCsv'],
            [['fuel_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblFuelTypeMaster::className(), 'targetAttribute' => ['fuel_type_code' => 'fuel_type_code'], 'on' => 'importCsv'],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date'], 'convertDate', 'on' => ['importCsv']],
            [['fuel_type_code'], 'wefValidate', 'skipOnError' => true, 'on' => ['create', 'importCsv']],
            [['bmc_code'], 'setImport', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'fuel_rate_code' => Yii::t('app', 'Fuel Rate Code'),
            'rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'union_code' => Yii::t('app', 'Union'),
            'fuel_type_code' => Yii::t('app', 'Fuel Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'plant_code' => Yii::t('app', 'PLANT'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getFuelType() {
        return $this->hasOne(TblFuelTypeMaster::className(), ['fuel_type_code' => 'fuel_type_code']);
    }

    public function wefValidate($attribute, $params) {
        if (empty($this->getErrors())) {
            $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
            $data = $this->find()
                    ->where(['bmc_code' => $this->bmc_code, 'fuel_type_code' => $this->fuel_type_code])
                    ->andWhere(['>=', 'wef_date', $wef_date])
                    ->orderBy('wef_date desc')
                    ->one();
            if (!empty($data)) {
                $this->addError('wef_date', "Please select Wef Date greater than '" . Yii::$app->controls->view_date($data->wef_date) . "'");
            }
        }
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function setImport($attribute, $params) {
        $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
        $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
        }
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

}
