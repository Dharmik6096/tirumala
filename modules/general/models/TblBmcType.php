<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_type".
 *
 * @property integer $bmc_type_code
 * @property string $bmc_type_name
 * @property integer $is_active
 */
class TblBmcType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bmc_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bmc_type_code'], 'required'],
            [['bmc_type_code', 'is_active'], 'integer'],
            [['bmc_type_name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bmc_type_code' => Yii::t('app', 'Bmc Type Code'),
            'bmc_type_name' => Yii::t('app', 'Bmc Type Name'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblBmcTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblBmcTypeQuery(get_called_class());
    }
}
