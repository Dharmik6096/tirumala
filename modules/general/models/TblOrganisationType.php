<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_organisation_type".
 *
 * @property integer $organisation_type_code
 * @property string $organisation_type
 * @property integer $is_active
 */
class TblOrganisationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_organisation_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organisation_type_code'], 'required'],
            [['organisation_type_code', 'is_active'], 'integer'],
            [['organisation_type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'organisation_type_code' => Yii::t('app', 'Organisation Type Code'),
            'organisation_type' => Yii::t('app', 'Organisation Type'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblOrganisationTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblOrganisationTypeQuery(get_called_class());
    }
}
