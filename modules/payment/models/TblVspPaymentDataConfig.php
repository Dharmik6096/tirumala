<?php

namespace app\modules\payment\models;

use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblShift;
use Yii;

/**
 * This is the model class for table "tbl_vsp_payment_data_config".
 *
 * @property integer $vsp_payment_data_config_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
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
class TblVspPaymentDataConfig extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_payment_data_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['shift_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
                [['shift_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
                [['date_time_of_collection', 'created_at', 'updated_at', 'dcs_code', 'shift_code', 'dcs_name'], 'safe'],
                [['shift_code', 'originating_type'], 'integer'],
                [['dcs_code', 'bmc_code'], 'string', 'max' => 12],
                [['mcc_plant_code', 'plant_code'], 'string', 'max' => 6],
                [['union_code'], 'string', 'max' => 3],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
                [['union_code', 'mcc_plant_code', 'plant_code', 'bmc_code'], 'required', 'except' => ['importCsv']],
                [['date_time_of_collection', 'shift_code', 'dcs_code'], 'required'],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['date_time_of_collection'], 'validDcs', 'on' => ['importCsv']],
                [['date_time_of_collection'], 'convertDateDot', 'on' => ['importCsv']],
                [['date_time_of_collection'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['date_time_of_collection'], 'convertDate', 'on' => ['importCsv']],
                ['shift_code', 'unique', 'targetAttribute' => ['shift_code', 'dcs_code', 'date_time_of_collection'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                    return empty($this->getErrors());
                },],
                [['shift_code'], 'ImportfieldSet', 'skipOnError' => true, 'on' => 'importCsv'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vsp_payment_data_config_code' => Yii::t('app', 'Vsp Payment Data Config Code'),
            'date_time_of_collection' => Yii::t('app', 'Collection Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
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
            $this->date_time_of_collection = $this->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->shift_code);
        }
    }

    public function validDcs() {
        $data = TblDcs::find()->select('dcs_code')->where(['or', ['dcs_code' => $this->dcs_code], ['dcs_code_ex' => $this->dcs_code], ['ref_code' => $this->dcs_code]])->andWhere(['is_active' => 1, 'bmc_code' => $this->bmc_code])->all();
        $dcs = !empty($data) && count($data) == 1 ? $data[0]->dcs_code : '';

        if (empty($dcs)) {
            $this->addError('customer_code', Yii::t('app/validation', Yii::t('app', 'DCS Code') . ' is invalid'));
        }
    }

    public function ImportfieldSet($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'plant_code');
            $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');

//            if (empty($this->dcs_code)) {
//                $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
//            } else {
//                $bmc = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
//                if (!empty($this->bmc_code) && ($this->bmc_code != $bmc)) {
//                    $this->addError('bmc_code', Yii::t('app/validation', $this->getAttributeLabel('bmc_code') . ' is invalid'));
//                }
//                $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
//            }
        }
    }

}
