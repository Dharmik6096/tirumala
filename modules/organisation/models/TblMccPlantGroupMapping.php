<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_plant_group_mapping".
 *
 * @property integer $mcc_plant_mapping_code
 * @property string $mcc_plant_code
 * @property string $p_mcc_plant_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMccPlantGroupMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_plant_group_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_plant_code', 'p_mcc_plant_code', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'default', 'value' => '1'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mcc_plant_mapping_code' => Yii::t('app', 'Mcc Plant Mapping Code'),
            'mcc_plant_code' => Yii::t('app', 'MCC Name'),
            'p_mcc_plant_code' => Yii::t('app', 'MCC Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'p_mcc_plant_code']);
    }

    public function getMainMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getTblBmcCode() {
        return $this->hasMany(TblDcsBmc::className(), ['mcc_plant_code' => 'p_mcc_plant_code']);
    }

    public function getTblDcsCode() {
        return $this->hasMany(TblDcs::className(), ['mcc_plant_code' => 'p_mcc_plant_code']);
    }

}
