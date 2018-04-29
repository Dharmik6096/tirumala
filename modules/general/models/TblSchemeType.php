<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_type".
 *
 * @property integer $scheme_type_code
 * @property string $scheme_type
 * @property integer $is_active
 */
class TblSchemeType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_scheme_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scheme_type_code'], 'required'],
            [['scheme_type_code', 'is_active'], 'integer'],
            [['scheme_type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scheme_type_code' => Yii::t('app', 'Scheme Type Code'),
            'scheme_type' => Yii::t('app', 'Scheme Type'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblSchemeTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblSchemeTypeQuery(get_called_class());
    }
}
