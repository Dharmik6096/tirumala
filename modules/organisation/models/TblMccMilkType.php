<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\configuration\models\TblUnionRatechartRange;
use app\modules\organisation\models\TblMccPlant;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_mcc_milk_type".
 *
 * @property string $mcc_plant_code
 * @property integer $milk_type_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMccMilkType extends \app\models\ChildModel {

    public $app_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_milk_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_plant_code', 'milk_type_code'], 'required'],
            [['mcc_plant_code', 'created_by', 'updated_by'], 'string'],
            [['milk_type_code', 'is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getRateChartRange() {
        return $this->hasOne(TblUnionRatechartRange::className(), ['animal_type_code' => 'milk_type_code'])->where(['union_code' => $this->mccCode->union_code, 'config_for' => $this->app_type]);
    }

}
