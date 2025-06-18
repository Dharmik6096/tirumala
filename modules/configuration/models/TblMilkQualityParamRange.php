<?php

namespace app\modules\configuration\models;

use Yii;

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
                [['union_code', 'process_name', 'org_code', 'org_type', 'min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr', 'animal_type_code', 'created_by', 'updated_by', 'created_at', 'updated_at', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_quality_param_range_code' => Yii::t('app', 'Milk Quality Param Range Code'),
            'process_name' => Yii::t('app', 'Process Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'org_code' => Yii::t('app', 'Org Code'),
            'org_type' => Yii::t('app', 'Org Type'),
            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
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

}
