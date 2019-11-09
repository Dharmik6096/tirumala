<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\configuration\models\TblUnionRatechartRange;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_bmc_milk_type".
 *
 * @property string $bmc_code
 * @property integer $milk_type_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBmcMilkType extends \app\models\ChildModel {

    public $app_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_milk_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_code', 'milk_type_code'], 'required'],
            [['bmc_code', 'created_by', 'updated_by'], 'string'],
            [['milk_type_code', 'is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            ['milk_type_code', 'unique', 'targetAttribute' => ['milk_type_code', 'bmc_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateMilkType($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code']],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_code' => Yii::t('app', 'Bmc Code'),
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

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getRateChartRange() {
        return $this->hasOne(TblUnionRatechartRange::className(), ['animal_type_code' => 'milk_type_code'])->where(['union_code' => $this->bmcCode->union_code, 'config_for' => $this->app_type]);
    }

}
