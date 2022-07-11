<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_milkcost_param".
 *
 * @property integer $milkcost_param_code
 * @property string $chilling_rate
 * @property string $primary_tpt_cost
 * @property string $commission_percentage
 * @property string $labour_charge
 * @property string $service_charge
 * @property string $wef_date
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkcostParam extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milkcost_param';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['wef_date', 'chilling_rate', 'primary_tpt_cost', 'commission_percentage', 'labour_charge', 'service_charge', 'mcc_plant_code'], 'required'],
                [['union_code', 'plant_code'], 'required', 'except' => ['importCsv']],
                [['chilling_rate', 'primary_tpt_cost', 'commission_percentage', 'labour_charge', 'service_charge'], 'number'],
                [['wef_date', 'created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'safe'],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
                [['mcc_plant_code'], function ($attribute, $params) {
                    Yii::$app->general->validateMCC($this, $attribute, 'mcc_plant_code');
                }, 'on' => ['importCsv']],
                [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
                [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['wef_date'], 'convertDate', 'on' => ['importCsv']],
                [['wef_date'], 'unique', 'targetAttribute' => ['wef_date', 'mcc_plant_code']],
                [['mcc_plant_code'], 'setImport', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milkcost_param_code' => Yii::t('app', 'Milkcost Param Code'),
            'chilling_rate' => Yii::t('app', 'Chilling Rate(Rs./Ltr)'),
            'primary_tpt_cost' => Yii::t('app', 'Primary TPT Cost(Rs./Ltr)'),
            'commission_percentage' => Yii::t('app', 'Commission Percentage(Per./Ltr)'),
            'labour_charge' => Yii::t('app', 'Labour Charge(Rs./Ltr)'),
            'service_charge' => Yii::t('app', 'Service Charge(Rs./Ltr)'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
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

    public function setImport($attribute, $params) {
        $mccPlant = $this->mccPlantCode;
        if (!empty($mccPlant)) {
            $this->union_code = $mccPlant->union_code;
            $this->plant_code = $mccPlant->plant_code;
        }
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

}
