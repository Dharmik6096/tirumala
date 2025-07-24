<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblUnions;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_milk_quality_param_range".
 *
 * @property integer $milk_quality_param_range_code
 * @property string $process_name
 * @property string $union_code
 * @property string $org_code
 * @property string $org_type
 * @property integer $animal_type_code
 * @property double $min_fat
 * @property double $max_fat
 * @property double $min_snf
 * @property double $max_snf
 * @property double $min_clr
 * @property double $max_clr
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
class TblMilkQualityParamRange extends \app\models\ChildModel {

    public $org_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_quality_param_range';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'process_name', 'org_code', 'org_type', 'min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr', 'animal_type_code', 'created_by', 'updated_by', 'created_at', 'updated_at', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'org_name'], 'safe'],
            [['union_code', 'process_name', 'org_code', 'org_type', 'animal_type_code'], 'required'],
            [['min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr'], 'validateQualityParams'],
            [['process_name', 'org_code', 'org_type', 'animal_type_code'], 'unique', 'targetAttribute' => ['process_name', 'org_code', 'org_type', 'animal_type_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_quality_param_range_code' => Yii::t('app', 'Milk Quality Param Range Code'),
            'process_name' => Yii::t('app', 'Process Name'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'org_code' => Yii::t('app', 'Org Code'),
            'org_type' => Yii::t('app', 'Org Type'),
            'animal_type_code' => Yii::t('app', 'Animal Type'),
            'min_fat' => Yii::t('app', 'Min Fat'),
            'max_fat' => Yii::t('app', 'Max Fat'),
            'min_snf' => Yii::t('app', 'Min Snf'),
            'max_snf' => Yii::t('app', 'Max Snf'),
            'min_clr' => Yii::t('app', 'Min Clr'),
            'max_clr' => Yii::t('app', 'Max Clr'),
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

    public function getMilkQualityData() {
        return $this->find()->where(['animal_type_code' => $this->animal_type_code, 'milk_quality_param_range_code' => $this->milk_quality_param_range_code])->one();
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

    public function getAnimalTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'animal_type_code']);
    }

    public function validateQualityParams($attribute, $params) {
        $fields = [
            'min_fat' => $this->min_fat,
            'max_fat' => $this->max_fat,
            'min_snf' => $this->min_snf,
            'max_snf' => $this->max_snf,
            'min_clr' => $this->min_clr,
            'max_clr' => $this->max_clr,
        ];

        $filled = 0;
        foreach ($fields as $field => $value) {
            if (strlen(trim((string) $value)) > 0) {
                $filled++;
            }
        }
        if ($filled > 0 && $filled < count($fields)) {
            $this->addError($attribute, 'If any quality parameter is filled, all must be filled.');
        }
        $animal_name = $this->animalTypeCode->animal_type_name;
        if ((!empty($this->min_fat) || !empty($this->max_fat)) && $this->max_fat <= $this->min_fat) {
            $this->addError('max_fat', $animal_name . ' - Max Fat must be greater than Min Fat.');
        }

        if ((!empty($this->min_snf) || !empty($this->max_snf)) && $this->max_snf <= $this->min_snf) {
            $this->addError('max_snf', $animal_name . ' - Max SNF must be greater than Min SNF.');
        }

        if ((!empty($this->min_clr) || !empty($this->max_clr)) && $this->max_clr <= $this->min_clr) {
            $this->addError('max_clr', $animal_name . ' - Max CLR must be greater than Min CLR.');
        }
    }

    public function getQualityRange() {
        $fields = ['min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr'];
        $criteria = ['union_code' => $this->union_code, 'process_name' => $this->process_name, 'org_type' => $this->org_type, 'org_code' => $this->org_code, 'animal_type_code' => $this->animal_type_code];
        return $this->find()->select($fields)->where($criteria)->one() ?: TblUnionRatechartRange::find()->select($fields)->where(['union_code' => $this->union_code, 'animal_type_code' => $this->animal_type_code, 'config_for' => $this->org_type])->one();
    }

    public function getminMaxQualityRange() {
        $fields = ['MIN(min_fat) as min_fat', 'MAX(max_fat) as max_fat', 'MIN(min_snf) as min_snf', 'MAX(max_snf) as max_snf', 'MIN(min_clr) as min_clr', 'MAX(max_clr) as max_clr'];
        $criteria = ['union_code' => $this->union_code, 'process_name' => $this->process_name, 'org_type' => $this->org_type, 'org_code' => $this->org_code];
        $result = $this->find()->select($fields)->where($criteria)->groupBy(['union_code', 'process_name', 'org_type', 'org_code'])->one();
        return !empty($result) ? $result : TblUnionRatechartRange::find()->select($fields)->where(['union_code' => $this->union_code, 'config_for' => $this->org_type])->groupBy(['union_code', 'config_for'])->one();
    }

}
