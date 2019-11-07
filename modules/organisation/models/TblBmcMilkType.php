<?php

namespace app\modules\organisation\models;

use Yii;

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
class TblBmcMilkType extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bmc_milk_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bmc_code', 'milk_type_code'], 'required'],
            [['bmc_code', 'created_by', 'updated_by'], 'string'],
            [['milk_type_code', 'is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
}
