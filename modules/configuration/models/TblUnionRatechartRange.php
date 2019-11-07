<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_union_ratechart_range".
 *
 * @property integer $ratechart_range_code
 * @property integer $animal_type_code
 * @property double $min_fat
 * @property double $max_fat
 * @property double $min_snf
 * @property double $max_snf
 * @property double $min_clr
 * @property double $max_clr
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblUnionRatechartRange extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_union_ratechart_range';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['animal_type_code'], 'integer'],
            [['min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr'], 'number'],
            [['union_code', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'config_for'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ratechart_range_code' => Yii::t('app', 'Ratechart Range Code'),
            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
            'min_fat' => Yii::t('app', 'Min Fat'),
            'max_fat' => Yii::t('app', 'Max Fat'),
            'min_snf' => Yii::t('app', 'Min Snf'),
            'max_snf' => Yii::t('app', 'Max Snf'),
            'min_clr' => Yii::t('app', 'Min Clr'),
            'max_clr' => Yii::t('app', 'Max Clr'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblUnionRatechartRangeQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblUnionRatechartRangeQuery(get_called_class());
    }

}
