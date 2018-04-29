<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_types".
 *
 * @property integer $id
 * @property string $user_type
 */
class TblUserTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_type' => Yii::t('app', 'User Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblUserTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUserTypesQuery(get_called_class());
    }
}
