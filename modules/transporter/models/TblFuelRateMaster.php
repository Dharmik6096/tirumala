<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

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
            [['mcc_plant_code'], 'setImport', 'on' => ['importCsv']],
            [['union_code', 'wef_date', 'plant_code', 'mcc_plant_code', 'fuel_type_code', 'rate'], 'required'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['union_code', 'created_by', 'updated_by'], 'string'],
            [['fuel_type_code'], 'integer'],
            [['rate'], 'number', 'min' => 1],
            [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
            [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
            [['fuel_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblFuelTypeMaster::className(), 'targetAttribute' => ['fuel_type_code' => 'fuel_type_code'], 'on' => 'importCsv'],
            [['fuel_type_code'], 'wefValidate', 'skipOnError' => true, 'on' => ['create', 'importCsv']],
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
        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $data = $this->find()
                ->where(['mcc_plant_code' => $this->mcc_plant_code, 'fuel_type_code' => $this->fuel_type_code])
                ->andWhere(['>=', 'wef_date', $wef_date])
                ->orderBy('wef_date desc')
                ->one();
        if (!empty($data)) {
            $this->addError('wef_date', "Please select Wef Date greater than '" . Yii::$app->controls->view_date($data->wef_date) . "'");
        }
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function setImport($attribute, $params) {
        $plant = Yii::$app->general->getforeignkey($this->mccCode, 'plant_code');
        $union = Yii::$app->general->getforeignkey($this->mccCode, 'union_code');
        $this->plant_code = $plant;
        $this->union_code = $union;
    }

}
