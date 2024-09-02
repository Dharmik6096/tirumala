<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblMilkClass;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_local_milk_rate".
 *
 * @property string $local_sale_rate_code
 * @property string $created_at
 * @property double $rate
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $updated_by
 */
class TblLocalMilkRate extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_local_milk_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_quality_type_code', 'milk_type_code', 'milk_class', 'rate', 'union_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'local_milk_rate_code' => Yii::t('app', 'Local Milk Rate Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'milk_class' => Yii::t('app', 'Milk Class'),
            'rate' => Yii::t('app', 'Rate'),
            'union_code' => Yii::t('app', 'Union'),
            'wef_date' => Yii::t('app', 'Effective Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Organization Code'),
            'originating_org_type' => Yii::t('app', 'Originating Organization Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'Additional Column 1'),
            'x_col2' => Yii::t('app', 'Additional Column 2'),
            'x_col3' => Yii::t('app', 'Additional Column 3'),
            'x_col4' => Yii::t('app', 'Additional Column 4'),
            'x_col5' => Yii::t('app', 'Additional Column 5'),
        ];
    }

    public function getMilkClass() {
        return $this->hasOne(TblMilkClass::className(), ['id' => 'milk_class']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public static function find() {
        return new TblLocalMilkRateQuery(get_called_class());
    }

}
