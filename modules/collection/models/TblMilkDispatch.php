<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\geo\models\TblVillages;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_milk_dispatch".
 *
 * @property integer $milk_dispatch_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $shift
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $type_of_data_receive
 *
 * @property TblAnimalType $milkTypeCode
 * @property TblDcsSubcenterBmcInfo $bmcCode
 * @property TblDcs $dcsCode
 * @property TblVillages $villageCode
 */
class TblMilkDispatch extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'mcc_code', 'date_time_of_collection', 'shift', 'fat', 'snf', 'qty', 'no_of_can'], 'required', 'on' => ['importCsv']],
            [['date_time_of_collection'], 'convertDateDot', 'except' => 'convertDate', 'on' => ['importCsv']],
            [['date_time_of_collection'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['date_time_of_collection'], 'convertDate', 'on' => ['importCsv']],
            [['date_time_of_collection', 'shift'], 'backendData', 'when' => function($model) {
            return empty($model->getErrors());
        }, 'on' => ['importCsv']],
            [['date_time_of_collection'], 'dateValidate', 'on' => ['importCsv']],
            [['fat', 'snf', 'qty', 'water'], 'default', 'value' => 0, 'on' => ['importCsv']],
            [['qty'], 'double', 'min' => 0, 'max' => 99999, 'on' => ['importCsv']],
            [['fat', 'snf'], 'double', 'max' => 99, 'on' => ['importCsv']],
            [['no_of_can'], 'integer', 'max' => 99, 'on' => ['importCsv']],
            [['dcs_code', 'bmc_code', 'shift', 'village_code', 'type_of_data_receive'], 'string'],
            [['milk_type_code', 'sample_no'], 'integer'],
            [['fat', 'snf', 'water', 'qty'], 'number'],
            [['date_time_of_collection', 'date_time_of_recieve', 'dcs_code', 'mcc_code', 'return_cob', 'remarks'], 'safe'],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code'], 'except' => ['importCsv']],
            [['mcc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_code' => 'mcc_plant_code'], 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_dispatch_code' => Yii::t('app', 'Milk Dispatch Code'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'shift' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Name'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @inheritdoc
     * @return TblMilkDispatchQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMilkDispatchQuery(get_called_class());
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }

    public function convertDateDot() {
        try {
            $this->date_time_of_collection = Yii::$app->controls->view_date($this->date_time_of_collection, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->date_time_of_collection = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->date_time_of_collection = !empty($this->date_time_of_collection) ? Yii::$app->controls->view_date($this->date_time_of_collection, 'php:Y-m-d') : NULL;
        }
    }

    public function backendData() {
        $this->date_time_of_collection = Yii::$app->controls->view_date($this->date_time_of_collection, 'php:Y-m-d') . ' ' . \Yii::$app->general->getshift($this->shift);
        $datetime = date('Y-m-d H:i:s');
        $this->date_time_of_recieve = $datetime;
        $this->village_code = !empty($this->dcs_code) ? substr($this->dcs_code, 0, 6) : null;
        $this->type_of_data_receive = 'Import';
        $dcsData = $this->dcsCode;
        $this->model->route_code = !empty($dcsData->route_code) && $dcsData->route_code != 'N/A' ? $dcsData->route_code : null;
        $this->model->bmc_code = !empty($dcsData->bmc_code) && $dcsData->bmc_code != 'N/A' ? $dcsData->bmc_code : null;
    }

    public function getExistRecord() {
        $date = date('Y-m-d', strtotime($this->date_time_of_collection));
        return $this->find()
                        ->where(['dcs_code' => $this->dcs_code, 'cast(date_time_of_collection as date)' => $date, 'shift' => $this->shift])
                        ->one();
    }

    public function dateValidate($attribute, $params) {
        if (empty($this->getErrors())) {
            $collection_date = Yii::$app->controls->view_date($this->date_time_of_collection, 'php:Y-m-d');
            $today = date('Y-m-d');
            if ($collection_date > $today) {
                $this->addError($attribute, Yii::t('app', 'Collection Date can not be Future Date.'));
            }
        }
    }

}
