<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_national".
 *
 * @property string $national_code
 * @property string $national_name
 * @property string $state_code
 */
class TblNational extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $name;
    public static function tableName()
    {
        return 'tbl_national';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['national_code', 'national_name', 'state_code'], 'required'],
            [['name','code'], 'safe'],
            [['national_code', 'national_name', 'state_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'national_code' => Yii::t('app', 'National Code'),
            'national_name' => Yii::t('app', 'National Name'),
            'state_code' => Yii::t('app', 'State Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblNationalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblNationalQuery(get_called_class());
    }
    public function getNatioanl(){
        return $this->find()->select('national_code,national_name ')->all();
    }
}
