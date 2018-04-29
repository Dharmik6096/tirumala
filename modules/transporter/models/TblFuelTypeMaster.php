<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_fuel_type".
 *
 * @property integer $fuel_type_code
 * @property string $fuel_type
 */
class TblFuelTypeMaster extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_fuel_type_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fuel_type'], 'string'],
            [['is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fuel_type_code' => Yii::t('app', 'Fuel Type Code'),
            'fuel_type' => Yii::t('app', 'Fuel Type'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
